# Записки этого проекта



## Отчет об установленном ПО на компьютере

```sql
SELECT cp.computer_id, 
    c.name,
    MAX(CASE cp.wmiproperty_id WHEN '901' THEN Value ELSE NULL END) ProductName,
    MAX(CASE cp.wmiproperty_id WHEN '902' THEN Value ELSE NULL END) Version,
    MAX(CASE cp.wmiproperty_id WHEN '903' THEN Value ELSE NULL END) Vendor,
    MAX(CASE cp.wmiproperty_id WHEN '904' THEN Value ELSE NULL END) InstallDate
FROM computer_properties AS cp
 JOIN computers AS c ON (c.id = cp.computer_id)
    WHERE c.name = 'rzh01-pc83.rezhcable.ru'
        GROUP BY cp.instance_id;
 
```


## Отчет об установленной программе на всех компьютерах

```sql
SELECT c.name,
    cp.value
FROM computer_properties AS cp
    JOIN computers AS c ON (c.id = cp.computer_id)
        WHERE cp.wmiproperty_id = '901' AND cp.value LIKE '%Kaspersky%' 

```


## Отчет о моделях принтеров

```sql
SELECT c.name,  cp.value
FROM computer_properties AS cp
JOIN computers AS c ON (c.id = cp.computer_id)
WHERE cp.wmiproperty_id = '95' AND  cp.value NOT LIKE '%Microsoft%' AND cp.value NOT LIKE '%PDF%' AND cp.value NOT LIKE '%FAX%' AND cp.value NOT LIKE '%OneNote%' AND cp.value NOT LIKE '%AnyDesk%'  GROUP BY cp.value

```
