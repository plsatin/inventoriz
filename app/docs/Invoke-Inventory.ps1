<#
 .SYNOPSIS
  Скрипт инвентаризации компьютера и отправки данных в Inventory API v1
 
 .DESCRIPTION
 
 .EXAMPLE
.\Invoke-Inventory.ps1 -ComputerName rzh01-pc83.rezhcable.ru -apiUrl "http://192.168.0.235:8000" -Verbose

.\Invoke-Inventory.ps1 -ComputerName rzh01-pc88.rezhcable.ru -Verbose

 .LINK
  https://webnote.satin-pl.com
 
 .NOTES
  Version:        0.1.1
  Author:         Pavel Satin
  Email:          plsatin@yandex.ru
  Creation Date:  25.10.2021
  Purpose/Change: Initial script development
  Update Date:    03.11.2021
  Purpose/Change: Добавлено: время начала и окончания инвентаризации, а также порядковый номер инвентаризации
 
#>
Param(
    [Parameter(Mandatory = $true)]
    [string]$ComputerName,
    [Parameter(Mandatory = $false)]
    [string]$apiUrl = "http://itdesk.rezhcable.ru:8400",
    [Parameter(Mandatory = $false)]
    [string]$UserName = "tech@rezhcable.ru",
    [Parameter(Mandatory = $false)]
    [string]$UserPassword = "Z123456z"
)


function Get-UserApiKey {
    Param(
        [Parameter(Mandatory = $true)]
        [string]$UserName,
        [Parameter(Mandatory = $true)]
        [string]$UserPassword,
        [Parameter(Mandatory = $true)]
        [string]$apiUrl
    )

    $headers = @{}
    $headers = @{ "Content-Type" = "application/x-www-form-urlencoded" }
    $postParams = @{}
    $postParams = @{"email"="$UserName";"password"="$UserPassword"}
    $UserAPiKey  = Invoke-RestMethod -Method POST -Body $postParams -Headers $headers -Uri "$apiUrl/api/login"

    return $UserAPiKey
} ## Конец функции Get-UserApiKey


function Get-ComputerUUID {
    Param(
        [Parameter(
          Mandatory = $true,
          ParameterSetName = '',
          ValueFromPipeline = $true)]
        [string]$ComputerName
    )

    try {
        $ComputerName = $ComputerName.ToLower()
        $computerSystem = Get-WmiObject Win32_ComputerSystem -computer $ComputerName -ErrorAction SilentlyContinue -Errorvariable err

        if (!$computerSystem) {
            ## Запрос не выполнен завершаем!
            Write-Host "[CRITICAL] Host $ComputerName is not accepting requests!"
            Write-Host $err.Message
            $returnState = $returnStateUnknown
            exit
            # [System.Environment]::Exit($returnState)
        } else {
            $returnState = $returnStateOK
        }

        $ComputerUUID = get-wmiobject Win32_ComputerSystemProduct -computername $ComputerName | Select-Object -ExpandProperty UUID -ErrorAction SilentlyContinue
        $OSSerial = get-wmiobject Win32_OperatingSystem -computername $ComputerName | Select-Object -ExpandProperty SerialNumber -ErrorAction SilentlyContinue

        Write-Verbose "UUID from WMI: $ComputerUUID"
        Write-Verbose "OS serial from WMI: $OSSerial"


        $ComputerUUID = $ComputerUUID + "-" + $OSSerial
        Write-Verbose "ComputerUUID: $ComputerUUID"

        return $ComputerUUID
    } catch [System.Exception] {
        $errorstr = $_.Exception.toString()
        Write-Verbose $errorstr
    }
} ## Конец функции Get-ComputerUUID








$result = Test-Connection -ComputerName $ComputerName -Count 2 -Quiet

if ($result) {
    ## Замер времени исполнения скрипта
    $watch = [System.Diagnostics.Stopwatch]::StartNew()
    $watch.Start() #Запуск таймера

    $ComputerUUID = Get-ComputerUUID -ComputerName $ComputerName
    
    Write-Host "Host $ComputerName is available"
    Write-Host "Computer UUID: $ComputerUUID "

    ## Получаем токен для доступа к API
    $api_key = (Get-UserApiKey -UserName $UserName -UserPassword $UserPassword -apiUrl $apiUrl).token

    ## Получаем ИД компьютера из БД, иначе создаем новую запись о компьютере
    $headers = @{}
    $headers = @{"Authorization" = "Bearer $api_key"}
    $ComputerTarget  = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/computers?name=$ComputerName&computertargetid=$ComputerUUID"

    if ($ComputerTarget.id) {
        $ComputerTargetId = $ComputerTarget.id
    } else {
        $headers = @{}
        $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
        $postParams = @{}
        $postParams = @{"name" = "$ComputerName"; "computertargetid" = "$ComputerUUID"}
        $ComputerTarget  = Invoke-RestMethod -Method POST -Headers $headers -Uri "$apiUrl/api/v1/computers" -Body $postParams
        $ComputerTargetId = $ComputerTarget.id
    }

    if ($ComputerTarget.last_inventory_index) {
        $ComputerInventoryIndex = $ComputerTarget.last_inventory_index + 1
    } else {
        $ComputerInventoryIndex = 1
    }


    ## Сохраняем время начала инвентаризации и ее порядковый номер
    $inventorySart = $(Get-Date).ToString('yyyy-MM-dd HH:mm:ss')
    $headers = @{}
    $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
    $postParams = @{}
    $postParams = @{"last_inventory_start" = "$inventorySart"; "last_inventory_end" = ""; "last_inventory_index" = "$ComputerInventoryIndex"}

    $ComputerTarget  = Invoke-RestMethod -Method PUT -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId" -Body $postParams

    Write-Verbose "Computer inventory ($ComputerInventoryIndex) start time: $inventorySart"


    ## Получаем список классов для инвентаризации
    $headers = @{}
    $headers = @{"Authorization" = "Bearer $api_key"}
    $wmiClasses = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/classes"
    Write-Verbose ($wmiClasses | Select-Object id, name, namespace, title | Format-Table | Out-String)
    
    $classCount = $wmiClasses.Count
    $recordCount = 0
    $classDone = 1
    $isQuickFix = $false

    foreach ($class in $wmiClasses) {
        $wmiClassId = $class.id
        $Win32Namespace = $class.namespace
        $Win32ClassName = $class.name

        ## Показываем прогресс выполнения
        $percentDone =  [math]::Round(($classDone / $classCount) * 100)
        Write-Progress -Activity "Processed class: $Win32ClassName on computer $ComputerName ..." -Status "Done: $percentDone%" -PercentComplete $percentDone


        Write-Verbose "Removing a class: $Win32ClassName"
        $headers = @{}
        $headers = @{"Authorization" = "Bearer $api_key"}
        $wmiClassDelete = Invoke-RestMethod -Method DELETE -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId/classes/$wmiClassId"
        Write-Verbose $($wmiClassDelete.data.Message)

        $wmiProperties = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/classes/$wmiClassId/properties"
        Write-Verbose ($wmiProperties | Select-Object id, wmiclass_id, name | Format-Table | Out-String)

        if ($class.enabled -eq 1) {
            Write-Verbose "Processed class: $Win32ClassName"

            switch ($Win32ClassName) {
                "SoftwareLicensingProduct" {
                    $computerClassI = Get-WmiObject -Class SoftwareLicensingProduct -ComputerName $ComputerName -ErrorAction Stop | Where-Object PartialProductKey | Select-Object Name, ApplicationId, LicenseStatus, ProductKeyChannel
                    break
                }
                "Win32_Product" {
                    $computerClassI = Get-WMIObject -Class Win32_Product -ComputerName $ComputerName | Sort-Object InstallDate –Descending
                    break
                }
                "Win32_PNPEntity" {
                    $computerClassI = Get-WmiObject -Class Win32_PNPEntity -ComputerName $ComputerName -ErrorAction Stop | 
                        Where-Object{$_.ConfigManagerErrorCode -ne 0} | 
                        Select-Object Name, DeviceID, ConfigManagerErrorCode
                    break
                }
                "Win32_QuickFixEngineering" {
                    $computerClassI = Get-WMIObject -Namespace $Win32Namespace -Class $Win32ClassName -ComputerName $ComputerName -ErrorAction stop
                    $isQuickFix = $true
                    break
                }
                default {
                    $computerClassI = Get-WMIObject -Namespace $Win32Namespace -Class $Win32ClassName -ComputerName $ComputerName -ErrorAction stop
                    break
                }
            }


            $InstanceId = 0

            if ($computerClassI) {
                foreach ($computerClass in $computerClassI) {
                    $InstanceId ++

                    foreach ($rowProp in $wmiProperties) {
                        $PropertyId = $rowProp.id
                        if ($isQuickFix) {
                            $PropertyName = $rowProp.name
                            if ($PropertyName -eq "HotFixID") {
                                $HotFixID = $computerClass.$PropertyName | Out-String
                            }
                            if ($PropertyName -eq "Name") {
                                $computerClass.$PropertyName = $HotFixID
                            }
                        } else {
                            $PropertyName = $rowProp.name

                        }

                            $Value = $computerClass.$PropertyName | Out-String
                            try {
                                if (!($Null -eq $Value) -And ($Value.ToString() -like '*\*')) {
                                    Write-Verbose $Value.ToString()
                                    $Value = ($Value.ToString()).Replace("\", "\\")
                                } else {

                                }
                            } catch [Exception] {
                                Write-Verbose $_.Exception.ToString()
                            }

                            $headers = @{}
                            $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
                            $postParams = @{}
                            $postParams = @{"value" = "$Value"; "instance_id" = "$InstanceId"}
                            $ComputerTarget  = Invoke-RestMethod -Method POST -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId/properties/$wmiClassId/$PropertyId" -Body $postParams

                            $recordCount ++
                    }
                }
            }

            $isQuickFix = $false


        } ## if $class.enabled

        $classDone ++

    }


    ## Сохраняем время окончания инвентаризации
    $inventoryEnd = $(Get-Date).ToString('yyyy-MM-dd HH:mm:ss')
    $headers = @{}
    $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
    $postParams = @{}
    $postParams = @{"last_inventory_end" = "$inventoryEnd"; "last_inventory_start" = "$inventorySart"; "last_inventory_index" = "$ComputerInventoryIndex"}

    $ComputerTarget  = Invoke-RestMethod -Method PUT -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId" -Body $postParams



    $watch.Stop() ## Остановка таймера
    Write-Host "The script ran in: $($watch.Elapsed)" ## Время выполнения
    Write-Host "Total properties pushed: $recordCount"
    Write-Host "[OK] Сomputer has been successfully inventoried"


} else {
    Write-Host "[UNKNOWN] Host $ComputerName is not available."
}
