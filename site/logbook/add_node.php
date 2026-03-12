<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	$params = [ $id	];
	$result = $cCfn->exeQuery("add_nodo_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
	$row = $result->fetch_assoc();
	
	$seleccionados=explode("/",$row['NODO']);
	
	if ( $cCfn->checkProfile("Operacion Sistemas") ){
		$sistema=isset($seleccionados[0]) ? $seleccionados[0] : null ;
		$plataforma=isset($seleccionados[1]) ? $seleccionados[1] : null ;
		$servicio=isset($seleccionados[2]) ? $seleccionados[2] : null ;
		$elemento=isset($seleccionados[3]) ? $seleccionados[3] : null ;
		include_once($base_path."/add_node_opersis.php");
	}
	else{
		include_once($base_path."/add_node_gsm.php");
	}
?>