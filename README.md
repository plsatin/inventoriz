# API инвентаризации компьютеров

## О проекте

Проект предназначен для сбора информации об аппаратном и программном обеспечении компьютеров в сети. На данный момент сбор информации производится с помощью Powershell [скрипта](app/docs/Invoke-Inventory.ps1). Так же будет разработан плагин для Icinga2 для сбора информации. Интерфейс предполагается в двух вариантах, как самостоятельное веб-приложение (на PHP, Laravel или HTML+Javascript) и как модуль к Icingaweb2.

На самом деле, можно сказать это вторая версия предыдущего проекта по инвентаризации компьютеров.


## Сбор информации

Пример запуска скрипта:

```powershell
.\Invoke-Inventory.ps1 -ComputerName rzh01-pc83.rezhcable.ru -apiUrl "http://192.168.0.235:8400" -Verbose

```



## Apace2 config

```conf
Listen 8400


<VirtualHost *:8400>
    DocumentRoot "/var/www/html/inventory/public"
    ServerName itdesk.rezhcable.ru

    <Directory "/var/www/html/inventory/public">
        # Ignore the .htaccess file in this directory
        AllowOverride None

        # Make pretty URLs
        <IfModule mod_rewrite.c>
            <IfModule mod_negotiation.c>
                Options -MultiViews -Indexes
            </IfModule>

            RewriteEngine On


            RewriteCond %{HTTP:Authorization} ^(.*)
            RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
            # Redirect Trailing Slashes...
            RewriteRule ^(.*)/$ /$1 [L,R=301]

            # Handle Front Controller...
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^ index.php [L]

        </IfModule>
    </Directory>
    ErrorLog /var/log/apache2/inventory_api_error.log
    CustomLog /var/log/apache2/inventory_api_access.log combined
</VirtualHost>

```






## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
