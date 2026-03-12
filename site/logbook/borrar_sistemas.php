<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$tabla = $_GET["tabla"] ?? $_POST["tabla"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if ($tabla == 1){
		$table="ELEMENTO";
	}
	else if ($tabla == 2){
		$table="SISTEMA";
	}
	else{
		$table="SUBSISTEMA";
	}
	
	$params = [ $table,$id ];
	$result = $cCfn->exeQuery("del_bit_adm",$params,'ii',$cCfn->getLocal(),null,$modulo);
	if( !empty($result['affected']) ) $msje="<h3 align='center' >Registro Borrado.</h3>"; 
	else $msje="<h3 align='center' >No se pudo eliminar registro.</h3>"; ?>
	