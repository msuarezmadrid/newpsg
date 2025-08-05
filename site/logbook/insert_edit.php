<?php
	include_once("../../protected/classFunciones.php");
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	
	$accion = $_GET["accion"] ?? $_POST["accion"] ?? '';
	$clasificacion = $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? '';
	$titulo = $_GET["titulo"] ?? $_POST["titulo"] ?? '';
	$descripcion = $_GET["descripcion"] ?? $_POST["descripcion"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if( empty($clasificacion) ){
		include_once("editar_bitacora.php");
		exit;
	}
	$usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$descripcion=addslashes($clasificacion . " " . $titulo);
	
	$params = [
		":id" => $id,
		":clasificacion" => $clasificacion,
		":descripcion" => $descripcion
	];
	$sql=$cCfn->getQuery("update_bit_edit", $params);
	$result = $cCfn->exeQuery($sql,$db);
	$affected_bit=$row[0]['affected'];
	
	### ACTUALIZACION DE TABLAS DEL PANEL 
	## 2g
	$params = [
		":id" => $id,
		":descripcion" => $descripcion
	];
	$sql=$cCfn->getQuery("update_bit_edit2", $params);
	$result = $cCfn->exeQuery($sql,$db);
	$affected_2g=$row[0]['affected'];
	## 3g
	$sql=$cCfn->getQuery("update_bit_edit3", $params);
	$result = $cCfn->exeQuery($sql,$db);
	$affected_3g=$row[0]['affected'];
	############################################
?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar bitacora</title>
			<?php include_once("../../protected/style.php"); ?>
		</head>
		<body>
			<h2 align='center'>Bitacora <?php echo "$id"; ?></h2>
			<h3 align='center' >Registros editados.</h3>
		</body>
	</html>
