<#
 .SYNOPSIS
  
 
 .DESCRIPTION
 
 .EXAMPLE

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
    [string]$CEnabled = "1"

)



#Замер времени исполнения скрипта
$watch = [System.Diagnostics.Stopwatch]::StartNew()
$watch.Start() #Запуск таймера





















$watch.Stop() #Остановка таймера
Write-Host $watch.Elapsed #Время выполнения
