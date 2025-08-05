<?php
    session_start();
    require "autoloader.php";
    use App\Componentes\DependencyContainer;
    $container = new DependencyContainer();
	$cCfg = $container->getFunciones();
    $cCfg->logOut(); 