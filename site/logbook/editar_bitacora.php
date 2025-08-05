<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	
	if ( $cCfn->checkProfile("Operacion Sistemas") ){
		include_once("editar_bitacora_opersis.php");
	}
	else{
		include_once("editar_bitacora_gsm.php");
	}
?>
