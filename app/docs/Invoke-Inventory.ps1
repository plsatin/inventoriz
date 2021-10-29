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
            #Запрос не выполнен завершаем!
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

        ## Нужно однозначно определится с UUID
        # if ($ComputerUUID -eq "FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF") {
        # } elseif ($ComputerUUID -eq "00000000-0000-0000-0000-000000000000") {
        # } elseif ($ComputerUUID -eq "03000200-0400-0500-0006-000700080009") {
        #     if ($OSSerial -eq "00425-00000-00002-AA147") {
        #         $ComputerUUID = $(Get-ItemProperty -Path HKLM:\SOFTWARE\Microsoft\Cryptography -Name MachineGuid).MachineGuid
        #         $ComputerUUID = $ComputerUUID.ToUpper()
        #     }
        # } elseif ( $Null -eq $ComputerUUID ) {
        #     $ComputerUUID = $(Get-ItemProperty -Path HKLM:\SOFTWARE\Microsoft\Cryptography -Name MachineGuid).MachineGuid
        #     $ComputerUUID = $ComputerUUID.ToUpper()
        # } else {
        # }

        $ComputerUUID = $ComputerUUID + "-" + $OSSerial
        Write-Verbose "ComputerUUID: $ComputerUUID"

        return $ComputerUUID
    } catch [System.Exception] {
        $errorstr = $_.Exception.toString()
        Write-Verbose $errorstr
    }
} # Конец функции Get-ComputerUUID






$result = Test-Connection -ComputerName $ComputerName -Count 2 -Quiet

if ($result) {
    #Замер времени исполнения скрипта
    $watch = [System.Diagnostics.Stopwatch]::StartNew()
    $watch.Start() #Запуск таймера

    $ComputerUUID = Get-ComputerUUID -ComputerName $ComputerName
    
    Write-Host "[OK] Host $ComputerName is available"
    Write-Host "Computer UUID: $ComputerUUID "

    # Получам ИД компьютера из БД, иначе создаем новую запись о компьютере
    $ComputerTarget  = Invoke-RestMethod -Method GET -ContentType "application/json" -Uri "$apiUrl/api/v1/computers?name=$ComputerName&computertargetid=$ComputerUUID"

    if ($ComputerTarget.id) {
        $ComputerTargetId = $ComputerTarget.id
    } else {
        $headers = @{"Content-Type" = "application/x-www-form-urlencoded"}
        $postParams = @{"name" = "$ComputerName"; "computertargetid" = "$ComputerUUID"}
        $ComputerTarget  = Invoke-RestMethod -Method POST -Headers $headers -Uri "$apiUrl/api/v1/computers" -Body $postParams
        $ComputerTargetId = $ComputerTarget.id
    }



    $wmiClasses = Invoke-RestMethod -Method GET -ContentType "application/json" -Uri "$apiUrl/api/v1/classes"
    # $wmiClasses

    $recordCount = 0

    foreach ($class in $wmiClasses) {
        $wmiClassId = $class.id
        $Win32Namespace = $class.namespace
        $Win32ClassName = $class.name

        Write-Verbose "Removing a class: $Win32ClassName"
        $wmiDelClass = Invoke-RestMethod -Method DELETE -ContentType "application/json" -Uri "$apiUrl/api/v1/computers/$ComputerTargetId/classes/$wmiClassId"
        # $wmiDelClass.data.Message

        $wmiProperties = Invoke-RestMethod -Method GET -ContentType "application/json" -Uri "$apiUrl/api/v1/classes/$wmiClassId/properties"
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

                            $headers = @{"Content-Type" = "application/x-www-form-urlencoded"}
                            $postParams = @{"value" = "$Value"; "instance_id" = "$InstanceId"}
                            $ComputerTarget  = Invoke-RestMethod -Method POST -Headers $headers -Uri "$apiUrl/api/v1/computers/$ComputerTargetId/properties/$wmiClassId/$PropertyId" -Body $postParams

                            $recordCount ++
                    }
                }
            }


        } ## if $class.enabled

    }



    $watch.Stop() #Остановка таймера
    Write-Host $watch.Elapsed #Время выполнения
    Write-Host "Total properties pushed: $recordCount"


} else {
    Write-Host "[UNKNOWN] Host $ComputerName is not available."
}
