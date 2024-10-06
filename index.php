<?php
    session_start();
    if( isset($_SESSION['user']) ){
        header("Location: ./site/home.php");
        exit;
    } 
    require "autoloader.php";
    use App\Config\classConfig;
    $cCfg = new classConfig();
    $cCfg->login(false);
