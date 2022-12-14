<nav class="navbar navbar-default">
    <div class="navbar-local color-accent theme-dark">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="glyph glyph-hamburger"></i>
                </button>

                <a href="/" class="navbar-brand">
                    <img src="/assets/images/inventoriz-logo-header.png" alt="Winstrap" height="29" />
                </a>

            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="/tree" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Инвентаризация<i class="glyph glyph-chevron-down-2"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/tree">Диспетчер устройств</a></li>
                            <li class="divider"></li>
                            <li><a href="/softwares">Программное обеспечение</a></li>
                            <li><a href="/computers">Компьютеры</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Rest API<i class="glyph glyph-chevron-down-2"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/routes">Маршруты</a></li>
                            <li><a class="color-type-secondary" href="/api/documentation" >Документация</a></li>
                        </ul>
                    </li>

                    <li class="dropdown" id="header-login">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Вход<i class="glyph glyph-chevron-down-2"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#login-form-dialog" data-toggle="modal">Вход</a></li>
                        </ul>
                    </li>

                </ul>

            </div>

        </div>
    </div>
</nav>

<div class="alert-stack" id="header-alert-stack"></div>