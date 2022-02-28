# Записки проекта


## Интеллектуальная инвентаризация

Автоматизированная система (АС) должна сама знать, что нужно инвентаризировать клиенту на данный момент


## Ссылки

 - [Swagger UI](http://itdesk.rezhcable.ru:8400/api/documentation)
 - [Тестовый интерфейс](http://itdesk.rezhcable.ru:8400/tree)
 - [Список компьютеров (Json для дерева)](http://itdesk.rezhcable.ru:8400/api/v1/computers-list)





## Права и роли

### Генерация таблицы прав

```bash
php artisan db:seed --class=PermissionTableSeeder

```


## Генерация API документации

```bash
php artisan swagger-lume:generate


```





### Сводная таблица прав и ролей

```sql
SELECT p.id, p.controller, p.action, p.method,
    MAX(CASE r.role_id WHEN '1' THEN r.role_id ELSE NULL END) Admin,
    MAX(CASE r.role_id WHEN '2' THEN r.role_id ELSE NULL END) User
 FROM permissions AS p
 JOIN permission_role AS r ON (p.id = r.permission_id)
        GROUP BY r.permission_id;

```


## Сбор информации

### Обновления Windows

```powershell

Session = New-Object -ComObject "Microsoft.Update.Session"
$Searcher = $Session.CreateUpdateSearcher()
$historyCount = $Searcher.GetTotalHistoryCount()
$Searcher.QueryHistory(0, $historyCount) | Select-Object Date,@{name="Operation"; expression={switch($_.operation){1 {"Installation"}; 2 {"Uninstallation"}; 3 {"Other"}}}}, @{name="Status"; expression={switch($_.resultcode){1 {"In Progress"}; 2 {"Succeeded"}; 3 {"Succeeded With Errors"};4 {"Failed"}; 5 {"Aborted"} }}}, Title, Description


```

### Программное обеспечение

```powershell

$ComputerName = "rzh01-pc84"
$list = @()
$InstalledSoftwareKey = "SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Uninstall"
$InstalledSoftware = [microsoft.win32.registrykey]::OpenRemoteBaseKey('LocalMachine', $ComputerName)
$RegistryKey = $InstalledSoftware.OpenSubKey($InstalledSoftwareKey)
$SubKeys = $RegistryKey.GetSubKeyNames()

Foreach ($key in $SubKeys){
    $thisKey = $InstalledSoftwareKey + "\\" + $key
    $thisSubKey = $InstalledSoftware.OpenSubKey($thisKey)
    $obj = New-Object PSObject
    # $obj | Add-Member -MemberType NoteProperty -Name "ComputerName" -Value $ComputerName
    $obj | Add-Member -MemberType NoteProperty -Name "DisplayName" -Value $($thisSubKey.GetValue("DisplayName"))
    $obj | Add-Member -MemberType NoteProperty -Name "DisplayVersion" -Value $($thisSubKey.GetValue("DisplayVersion"))
    $obj | Add-Member -MemberType NoteProperty -Name "Publisher" -Value $($thisSubKey.GetValue("Publisher"))
    $obj | Add-Member -MemberType NoteProperty -Name "InstallDate" -Value $($thisSubKey.GetValue("InstallDate"))
    $obj | Add-Member -MemberType NoteProperty -Name "UninstallString" -Value $($thisSubKey.GetValue("UninstallString"))
    $list += $obj
}
$list | Out-GridView



```





## Пользовательский интерфейс

 - [Winstrap - примеры компонентов](http://itdesk.rezhcable.ru:8400/winstrap/index.html)


### Дашбоард. Графики

 - [https://sawtech.ru/tehno-blog/rabota-s-grafikami-i-diagrammami-google-chart/](Работа с графиками и диаграммами google chart)


Ссылка на компьютер в Windows Admin Center:

[https://192.168.0.231:4434/computerManagement/connections/computer/rzh01-pc83.rezhcable.ru](Windows Admin Center - rzh01-pc83.rezhcable.ru)

Для серверов:

[https://192.168.0.231:4434/servermanager/connections/server/rzh01-rds-01.rezhcable.ru](Windows Admin Center - rzh01-rds-01.rezhcable.ru)








## Отчеты

 + [Laravel Report Generators (PDF, CSV & Excel)](https://github.com/Jimmy-JS/laravel-report-generator)


### Отчет об установленном ПО на компьютере

```sql
SELECT cp.computer_id, 
    c.name,
    MAX(CASE cp.wmiproperty_id WHEN '901' THEN Value ELSE NULL END) ProductName,
    MAX(CASE cp.wmiproperty_id WHEN '902' THEN Value ELSE NULL END) Version,
    MAX(CASE cp.wmiproperty_id WHEN '903' THEN Value ELSE NULL END) Vendor,
    MAX(CASE cp.wmiproperty_id WHEN '904' THEN Value ELSE NULL END) InstallDate,
    MAX(CASE cp.wmiproperty_id WHEN '905' THEN Value ELSE NULL END) IdentifyingNumber
FROM computer_properties AS cp
 JOIN computers AS c ON (c.id = cp.computer_id)
    WHERE c.name = 'rzh01-pc83.rezhcable.ru'
        GROUP BY cp.instance_id;
 
```


### Отчет об установленной программе на всех компьютерах

```sql
SELECT c.name,
    cp.value
FROM computer_properties AS cp
    JOIN computers AS c ON (c.id = cp.computer_id)
        WHERE cp.wmiproperty_id = '901' AND cp.value LIKE '%Kaspersky%' 

```


### Отчет о моделях принтеров

```sql
SELECT c.name,  cp.value
FROM computer_properties AS cp
JOIN computers AS c ON (c.id = cp.computer_id)
WHERE cp.wmiproperty_id = '95' AND  cp.value NOT LIKE '%Microsoft%' AND cp.value NOT LIKE '%PDF%' AND cp.value NOT LIKE '%FAX%' AND cp.value NOT LIKE '%OneNote%' AND cp.value NOT LIKE '%AnyDesk%'  GROUP BY cp.value

```

 