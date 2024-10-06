<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
    $cCfn->checkSession();
    $favicon=$cCfn->favicon();
    $title=$cCfn->title();
?>
<!DOCTYPE html>
<html >
    <head>
        <title><?php echo $title; ?> - Entel</title>
        <?php include("./lib/header.php"); ?> 
    </head>
    <body >
        <div class="mainLog">
            <h3 style="text-align:center;">Log de Alarmas</h3>
            
        </div>
    </body>
</html>