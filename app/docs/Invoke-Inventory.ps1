<#
 .SYNOPSIS
  
 
 .DESCRIPTION
 
 .EXAMPLE
.\Invoke-Inventory.ps1 -ComputerName rzh01-pc83.rezhcable.ru -apiUrl "http://192.168.0.235:8000" -Verbose

 .LINK
  https://webnote.satin-pl.com
 
 .NOTES
  Version:        0.01
  Author:         Pavel Satin
  Email:          plsatin@yandex.ru
  Creation Date:  21.06.2020
  Purpose/Change: Initial script development
 
#>
Param(
    [Parameter(Mandatory = $true)]
    [string]$ComputerName,
    [Parameter(Mandatory = $false)]
    [string]$apiUrl = "http://192.168.0.235:8000"

)


function Get-UserApiKey {
    Param(
        [Parameter(Mandatory = $false)]
        [string]$UserName = "tech@rezhcable.ru",
        [Parameter(Mandatory = $false)]
        [string]$UserPassword = "Z123456z",
        [Parameter(Mandatory = $false)]
        [string]$apiUrl = "http://192.168.0.235:8000"
    
    )

    $headers = @{ "Content-Type" = "application/x-www-form-urlencoded" }    
    $postParams = @{"email"="$UserName";"password"="$UserPassword"}
    $UserAPiKey  = Invoke-RestMethod -Method POST -Body $postParams -Headers $headers -Uri "$apiUrl/api/login"

    return $UserAPiKey
}


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







$api_key = (Get-UserApiKey).token

$result = Test-Connection -ComputerName $ComputerName -Count 2 -Quiet

if ($result) {
    ## Замер времени исполнения скрипта
    $watch = [System.Diagnostics.Stopwatch]::StartNew()
    $watch.Start() #Запуск таймера

    $ComputerUUID = Get-ComputerUUID -ComputerName $ComputerName
    
    Write-Host "[OK] Host $ComputerName is available"
    Write-Host "Computer UUID: $ComputerUUID "

    ## Получам ИД компьютера из БД, иначе создаем новую запись о компьютере
    $headers = @{"Authorization" = "Bearer $api_key"}
    $ComputerTarget  = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/computers?name=$ComputerName&computertargetid=$ComputerUUID"

    if ($ComputerTarget.id) {
        $ComputerTargetId = $ComputerTarget.id
    } else {
        $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
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
    $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
    $postParams = @{"last_inventory_start" = "$($(Get-Date).ToString("yyyy-MM-dd HH:MM:s"))"; "last_inventory_end" = "NULL"; "last_inventory_index" = "$ComputerInventoryIndex"}

    $ComputerTarget  = Invoke-RestMethod -Method PUT -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId" -Body $postParams




    ## Получаем списко классов для инвентаризации
    $headers = @{"Authorization" = "Bearer $api_key"}
    $wmiClasses = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/classes"
    # $wmiClasses

    $recordCount = 0

    foreach ($class in $wmiClasses) {
        $wmiClassId = $class.id
        $Win32Namespace = $class.namespace
        $Win32ClassName = $class.name

        Write-Verbose "Removing a class: $Win32ClassName"
        $headers = @{"Authorization" = "Bearer $api_key"}
        $wmiClassDelete = Invoke-RestMethod -Method DELETE -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId/classes/$wmiClassId"
        Write-Verbose $($wmiClassDelete.data.Message)

        $headers = @{"Authorization" = "Bearer $api_key"}
        $wmiProperties = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/classes/$wmiClassId/properties"
        # $wmiProperties

        if ($class.enabled -eq 1) {
            Write-Host "Processed class: $Win32ClassName"

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
                default {
                    $computerClassI = Get-WMIObject -Namespace $Win32Namespace -Class $Win32ClassName -ComputerName $ComputerName -ErrorAction stop
                    break
                }
            }

            


            $InstanceId = 0

            if ($computerClassI) {
                foreach ($computerClass in $computerClassI) {
                    $InstanceId = $InstanceId + 1

                    foreach ($rowProp in $wmiProperties) {
                        $PropertyId = $rowProp.id
                        $PropertyName = $rowProp.name

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

                            $headers = @{"Content-Type" = "application/x-www-form-urlencoded"; "Authorization" = "Bearer $api_key"}
                            $postParams = @{"value" = "$Value"; "instance_id" = "$InstanceId"}
                            $ComputerTarget  = Invoke-RestMethod -Method POST -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId/properties/$wmiClassId/$PropertyId" -Body $postParams

                            $recordCount ++
                    }
                }
            }


        } ## if $class.enabled

    }



    $watch.Stop() ## Остановка таймера
    Write-Host $watch.Elapsed ## Время выполнения
    Write-Host "Total properties pushed: $recordCount"


} else {
    Write-Host "[UNKNOWN] Host $ComputerName is not available."
}
