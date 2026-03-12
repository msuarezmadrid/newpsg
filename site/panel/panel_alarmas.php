<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
	
    $cCfn = new classFunciones();
    $cCfn->checkSession();
?>
	<div class="mainLog">
		<h3 style="text-align:center;">Panel de Alarmas</h3>
		
	</div>
