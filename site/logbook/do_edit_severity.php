<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$accion = $_GET["accion"] ?? $_POST["accion"] ?? '';
	$severidad = $_GET["severidad"] ?? $_POST["severidad"] ?? '';
	$sev_inicial = $_GET["sev_inicial"] ?? $_POST["sev_inicial"] ?? '';
	$mala = $_GET["mala"] ?? $_POST["mala"] ?? 0;
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	$usr=$cCfn->getUser();
	
	$sql=$cCfn->getQuery("severidad_bit", NULL);
	$result = $cCfn->exeQuery($sql,$db);
	$sev_final=null;
	foreach( $result as $sev ){
		if( $sev['ID'] == $severidad ){
			$sev_final = $sev['NOMBRE'];
		}
	}
	
	if( $sev_inicial != $sev_final ){
		$texto=null;
		if( $mala == 1 ){
			$texto="$usr cambia severidad de $sev_inicial a $sev_final. Severidad mal tipificada. ";
		}
		else{
			$texto="$usr cambia severidad de $sev_inicial a $sev_final. ";
		}
		
		$params = [
			":id" => $id,
			":severidad" => $severidad,
			":mala" => $mala
		];
		$sql=$cCfn->getQuery("update_severity", $params);
		$result = $cCfn->exeQuery($sql,$db);
		
		$params = [
			":id" => $id,
			":usr" => $usr,
			":comentario" => $texto
		];
		$sql=$cCfn->getQuery("nueva_bit_comment", $params);
		$result = $cCfn->exeQuery($sql,$db);
		
	} ?>
	<!DOCTYPE html>
	<html >
		<head>
			<title>Editar Severidad</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h2 align='center'>Bitacora <?php echo "$id"; ?></h2>
			<h3 align='center' >Severidad modificada.</h3>
		</body>
	</html>
