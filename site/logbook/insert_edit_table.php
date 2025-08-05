<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb"; 
	
	if( empty($nuevo) ){
		include_once("editar_tabla.php");
		exit;
	}
	if ($tabla == 1){
		$table="ELEMENTO";
	}
	else if ($tabla == 2){
		$table="SISTEMA";
	}
	else{
		$table="SUBSISTEMA";
	}
	
	$params = [
		":table" => $table,
		":nuevo" => $nuevo,
		":id" => $id
	];
	$sql=$cCfn->getQuery("upd_bit_adm", $params);
	$result = $cCfn->exeQuery($sql,$db); ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar </title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h3 align='center' >Registro editado.</h3>
		</body>
	</html>