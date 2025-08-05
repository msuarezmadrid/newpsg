<?php
    session_start();
    if( isset($_SESSION['user']) ){
        header("Location: /site/home");
        exit;
    }
    require "autoloader.php";
    use App\Componentes\DependencyContainer;
    $container = new DependencyContainer();
	$cCfg = $container->getFunciones();
    
	header("Location: login.php");