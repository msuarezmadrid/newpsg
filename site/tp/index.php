<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
    $cCfn->checkSession();
?>
<div class="mainPanel">
    <h3 style="text-align:center;">Trabajos programados</h3>
    <p><a href="#" target="_blank">Crear nuevo Flujo</a></p>
</div>
