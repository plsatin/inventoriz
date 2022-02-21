<div class="modal modal-center" id="about-dialog" tabindex="-1" role="dialog" aria-labelledby="about-dialog-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="glyph glyph-cancel" aria-hidden="true"></i>
                </button>
                <h4 class="modal-title" id="about-dialog-label">О проекте</h4>
            </div>
            <div class="modal-body">

                <h2 id="user-content-о-проекте">О проекте</h2>
                <p>Проект предназначен для сбора информации об аппаратном и программном обеспечении компьютеров в сети. На данный момент сбор информации производится с помощью Powershell&nbsp;<a href="http://rzh01-mon-01.rezhcable.ru:3030/it4/inventorydb/src/branch/master/app/docs/Invoke-Inventory.ps1" rel="nofollow">скрипта</a>. Так же будет разработан плагин для Icinga2 для сбора информации. Интерфейс предполагается в двух вариантах, как самостоятельное веб-приложение (на PHP, Laravel или HTML+Javascript) и как модуль к Icingaweb2.</p>
                <p>На самом деле, можно сказать это вторая версия предыдущего проекта по инвентаризации компьютеров.</p>
                <h2 id="user-content-сбор-информации">Сбор информации</h2>
                <p>Пример запуска скрипта:</p>
                <pre>
                <code>.\Invoke-Inventory.ps1 -ComputerName rzh01-pc83.rezhcable.ru -apiUrl &quot;http://192.168.0.235:8400&quot; -Verbose</code></pre>

            </div>
        </div>
    </div>
</div>