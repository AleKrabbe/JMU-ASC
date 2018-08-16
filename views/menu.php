<div class="row border-bottom white-bg">
    <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">

        <a href="#" class="navbar-brand">STM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-reorder"></i>
        </button>

        <!--</div>-->
        <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav mr-auto">
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">Consulta</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="consulta.php?data=conselho_permanente">Conselho Permanente</a></li>
                        <li><a href="consulta.php?data=conselho_especial">Conselho Especial</a></li>
                        <li><hr style="margin: 0 auto;width: 90%"></li>
                        <li><a href="consulta.php?data=militar">Militar</a></li>
                        <li><a href="consulta.php?data=om">Organização Militar</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">Novo Conselho</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="novo-conselho.php?type=permanente">Conselho Permanente</a></li>
                        <li><a href="novo-conselho.php?type=especial">Conselho Especial</a></li>
                    </ul>
                </li>
                <li>
                    <div class="vl"></div>
                </li>
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastro</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="cadastro-militar.php">Militar</a></li>
                        <li><a href="cadastro-om.php">Organização Militar</a></li>
                        <li><hr style="margin: 0 auto;width: 90%"></li>
                        <li><a href="" class="not-active">Posto</a></li>
                        <li><a href="" class="not-active">Força Armada</a></li>
                    </ul>
                </li>
                <li>
                    <a aria-expanded="false" role="button" href="#" class="not-active" >Relatórios</a>
                </li>
                <li>
                    <a aria-expanded="false" role="button" href="#" class="not-active" >Sorteio</a>
                </li>
            </ul>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Sair
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>