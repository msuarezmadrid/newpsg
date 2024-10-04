<?php
    session_start();
    require "autoloader.php";
    use App\Config\classConfig;
    $cCfg = new classConfig();
    $cCfg->logOut();
