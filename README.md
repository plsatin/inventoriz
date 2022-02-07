# API инвентаризации компьютеров

## О проекте

Проект предназначен для сбора информации об аппаратном и программном обеспечении компьютеров в сети. На данный момент сбор информации производится с помощью Powershell [скрипта](app/docs/Invoke-Inventory.ps1). Так же будет разработан плагин для Icinga2 для сбора информации. Интерфейс предполагается в двух вариантах, как самостоятельное веб-приложение (на PHP, Laravel или HTML+Javascript) и как модуль к Icingaweb2.

На самом деле, можно сказать это вторая версия предыдущего проекта по инвентаризации компьютеров.


## Сбор информации

Пример запуска скрипта:

```powershell
.\Invoke-Inventory.ps1 -ComputerName rzh01-pc83.rezhcable.ru -apiUrl "http://192.168.0.235:8400" -Verbose

```





## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
