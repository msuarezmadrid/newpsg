<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb"; 
	$usr=$cCfn->getUser();
	
	$tabla = $_GET["tabla"] ?? $_POST["tabla"] ?? '';
	$eleccion = $_GET["eleccion"] ?? $_POST["eleccion"] ?? '';
	
	if ($tabla == 1){
		$table="intradb.ELEMENTO";
	}
	else if ($tabla == 2){
		$table="intradb.SISTEMA";
	}
	else{
		$table="intradb.SUBSISTEMA";
	} ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Subgerencia Operaciones y Mantenimiento de Red</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h3 align="CENTER">	<?php echo $table ?> </h3>
			<table border='1' align="CENTER">
				<tr>
					<th>Acci&oacute;n</th>
					<th>Acci&oacute;n</th>
					<th>Nombre</th>
				</tr>
				<?php
				$params = [
					":table" => $table
				];
				if ($eleccion == "Agregar"){
					$sql=$cCfn->getQuery("ins_tbl_adm", $params);
					$result = $cCfn->exeQuery($sql,$db);
				}
				$sql=$cCfn->getQuery("sel_tbl_adm", $params);
				$result = $cCfn->exeQuery($sql,$db);
				
				foreach( $result as $row ){
					echo "<tr>";
					echo "	<td><a href='borrar_sistemas.php?id=".$row['ID']."&tabla=".$tabla."' target='_blank'>Borrar</a></td>";
					echo "	<td><a href='editar_tabla.php?id=".$row['ID']."&tabla=".$tabla."' target='_blank'>Editar</a></td>";
					echo "	<td>".$row['NOMBRE']."</td>";
					echo "</tr>";
				} ?>
			</table>
		</body>
	</html>