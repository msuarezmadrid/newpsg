<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	$date = new DateTime();
	
	$descripcion = $_GET["descripcion"] ?? $_POST["descripcion"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if( empty($descripcion) ){
		include("crear_accion.php");
		exit;
	}
	
	$usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$params = [
		":id" => $id
	];
	$sql=$cCfn->getQuery("sel_bitacora", $params);
	$row = $cCfn->exeQuery($sql,$db);
	// echo "<br/>sql:".$sql."<br/>"; 
	
	$pid=$row[0]['PROBLEMA_ID'];
	$tp	=$row[0]['PLANNED_ID'];
	$tar=$row[0]['TAREA_ID'];
	$sc	=$row[0]['SC_ID'];
	
	$params = [
		":usr" => $usr,
		":descripcion" => addslashes($descripcion),
		":id" => $id,
		":pid" => $pid,
		":tp" => $tp,
		":tar" => $tar,
		":sc" => $sc 
	];
	$sql=$cCfn->getQuery("insert_bit_conso", $params);
	$result = $cCfn->exeQuery($sql,$db);
	// echo "<br/>sql:".$sql."<br/>"; 
	$insert_id=$result['insert_id']; ## ID DEL REGISTRO INSERTADO 
	
	$params = [
		":id" => $id
	];
	$sql=$cCfn->getQuery("sel_bitacora_asoc", $params);
	// echo "<br/>sql:".$sql."<br/>"; 
	$result = $cCfn->exeQuery($sql,$db);
	$params = [
		":usr" => $usr,
		":descripcion" => addslashes($descripcion)
	];
	foreach( $result as $row ) {
		$sql_insert = $cCfn->getQuery("insert_bit_conso2", $params);
		if($row['ASOC_ID'] == 1) $sql_insert .= "0,'".$row['ASOC_ID']."',0,0)";
		if($row['ASOC_ID'] == 2) $sql_insert .= "'".$row['ASOC_ID']."',0,0,0)";
		if($row['ASOC_ID'] == 3) $sql_insert .= "0,0,'".$row['ASOC_ID']."',0)";
		if($row['ASOC_ID'] == 4) $sql_insert .= "0,0,0,'".$row['ASOC_ID']."')";
		$result = $cCfn->exeQuery($sql_insert,$db);
		// echo "<br/>sql:".$sql_insert."<br/>"; 
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Documentar acci&oacute;n</title>
		<?php include_once("../../protected/style.php") ?>
	</head>
	<body>
		<h2 align='center'>Bitacora <?php echo "$id"; ?></h2>
		<h3 align='center' >Acci&oacute;n Ingresada.</h3>
	</body>
</html>
