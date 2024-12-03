<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
    $cCfn->checkSession();
?>
<div class="mainPanel">
    <h3 style="text-align:center;">Trabajos programados</h3>
    <p><a href="./tp/nuevo_tp.php" >Crear nuevo trabajo programado</a>: Creaci&oacute;n de trabajos programados (TP).</p>
</div>
