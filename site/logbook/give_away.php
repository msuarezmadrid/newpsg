<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$accion = $_GET["accion"] ?? $_POST["accion"] ?? '';
	$owner = $_GET["owner"] ?? $_POST["owner"] ?? '';
	$bid = $_GET["bid"] ?? $_POST["bid"] ?? '';
	
	if( empty($owner) ){
		include_once("transferir.php");
		exit;
	}
	$usr=$cCfn->getUser();
	$params = [
		":bid" => $bid,
		":owner" => $owner
	];
	$sql=$cCfn->getQuery("update_bit_usr", $params);
	$result = $cCfn->exeQuery($sql,$db);
	
	$texto="$usr traspasa bitacora $bid a $owner";
	$params = [
		":id" => $bid,
		":usr" => $usr,
		":comentario" => $texto
	];
	$sql=$cCfn->getQuery("nueva_bit_comment", $params);
	$result = $cCfn->exeQuery($sql,$db); ?>
	
	<!DOCTYPE html>
	<html >
		<head>
			<title>Transferir</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h2 align='center'>Bitacora <?php echo "$bid"; ?></h2>
			<h3 align='center' >Dueño modificado.</h3>
		</body>
	</html>