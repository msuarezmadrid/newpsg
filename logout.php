<?php
    session_start();
<<<<<<< Updated upstream
    require "autoloader.php"; 
    use App\Config\classConfig;
    $cCfg = new classConfig();
    $cCfg->logOut();
=======
    require "autoloader.php";
    use App\Componentes\DependencyContainer;
    $container = new DependencyContainer();
	$cCfg = $container->getFunciones();
    $cCfg->logOut(); 
>>>>>>> Stashed changes
