<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb"; 
	
	$tabla = $_GET["tabla"] ?? $_POST["tabla"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if( $tabla == 1){
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
		":id" => $id
	];
	$sql=$cCfn->getQuery("sel_bit_adm", $params);
	$result = $cCfn->exeQuery($sql,$db);
	$nombre=$result[0]['NOMBRE']; ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h1 align="CENTER">Editar</h1>
			<form name='test' action="insert_edit_table.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="tabla" value="<?php echo $tabla; ?>" />
				<p>Nombre:</p>
				<p><textarea NAME="nuevo"><?php echo $nombre?></textarea></p>
				<p><input type="submit" value="Ingresar" /></p>
			</form>
		</body>
	</html>