<nav class="navbar navbar-default">
    {{-- <div class="navbar-global theme-default">
        <div class="container">
            <div class="navbar-header">
                <a href="http://www.microsoft.com" class="navbar-brand">
                    <img src="https://assets.onestore.ms/cdnfiles/onestorerolling-1511-11008/shell/v3/images/logo/microsoft.png" alt="Microsoft" height="23" />
                </a>
            </div>
        </div>
    </div> --}}
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
                            <li><a href="/tree">Все узлы</a></li>
                            <li class="divider"></li>
                            <li><a href="/tree?computer=rzh01-pc83.rezhcable.ru">rzh01-pc83.rezhcable.ru</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="color-type-secondary" href="/api/documentation" >Rest API</a>
                    </li>

                    <li>
                        <a class="color-type-secondary" href="#aboutdialog" data-toggle="modal">О проекте</a>
                    </li>

                </ul>

            </div>

        </div>
    </div>
</nav>
