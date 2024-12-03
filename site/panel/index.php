<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
	
    $cCfn = new classFunciones();
    $cCfn->checkSession();
?>
<div class="mainPanel">
    <h3 style="text-align:center;">Panel de supervisi&oacute;n</h3>
    <p><a href="./panel/panel_alarmas.php" >Panel Alarmas de Celdas</a></p>
    <p><a href="./panel/log_alarmas.php" >Logs de sitios</a></p>
</div>
