<#
 .SYNOPSIS
  Скрипт инвентаризации компьютера и отправки данных в Inventory API v1
 
 .DESCRIPTION
 
 .EXAMPLE
.\Invoke-ADInventory.ps1 -SearchBase "OU=PC,OU=RZH,DC=rezhcable,DC=ru" -SearchOnlyNew $false


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
    [Parameter(Mandatory = $false)]
    [string]$SearchBase = "OU=PC,OU=RZH,DC=rezhcable,DC=ru",
    [Parameter(Mandatory = $false)]
    [bool]$SearchOnlyNew = $true,
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






## Замер времени исполнения скрипта
$watch = [System.Diagnostics.Stopwatch]::StartNew()
$watch.Start() #Запуск таймера


foreach ($ComputerName in (Get-ADComputer -filter * -SearchBase $SearchBase | Sort-Object Name | ForEach-Object {$_.name} )) {

    $ComputerName = $ComputerName.ToLower() + "." + "rezhcable.ru"
    $msgSkip = "[UNKNOWN] Host $ComputerName is not available."


    if ($SearchOnlyNew) {

        ## Получаем токен для доступа к API
        $api_key = (Get-UserApiKey -UserName $UserName -UserPassword $UserPassword -apiUrl $apiUrl).token

        ## Получаем ИД компьютера из БД, иначе создаем новую запись о компьютере
        $headers = @{}
        $headers = @{"Authorization" = "Bearer $api_key"}
        $ComputerTarget  = Invoke-RestMethod -Method GET -ContentType "application/json" -Headers $headers -Uri "$apiUrl/api/v1/computers?name=$ComputerName"

        if ($ComputerTarget.id) {
            $ComputerTargetId = $ComputerTarget.id

            $msgSkip =  "Компьютер ($ComputerName) уже проинвентаризирован, пропускаем"
            $result = $false
        } else {
            $result = Test-Connection -ComputerName $ComputerName -Count 2 -Quiet
        }

    } else {
        $result = Test-Connection -ComputerName $ComputerName -Count 2 -Quiet
    }


    if ($result) {
    
        Write-Verbose "Computer is being inventoried: $ComputerName"
        
        .\Invoke-Inventory.ps1 -ComputerName $ComputerName
 
    
    } else {
        Write-Host $msgSkip
    }

}

$watch.Stop() ## Остановка таймера
Write-Host "The script ran in: $($watch.Elapsed)" ## Время выполнения
Write-Host "[OK] All computers are inventoried"




