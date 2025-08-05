<?php
	include_once("../../protected/classFunciones.php");
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$clasificacion = $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? '';
	$titulo = $_GET["titulo"] ?? $_POST["titulo"] ?? '';
?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar bitacora</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h1 align="center">Editar</h1>
			<form name='test' action='insert_edit.php' method='post' target='_blank'>
				<p>Bitacora <?php echo "$id"; ?></p>
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<?php echo "<p>T&iacute;tulo: (parte fija, obligatorio)</p>"; ?>
				<p>
					<select name="clasificacion">
						<?php
							if( $clasificacion=="" ){
								echo "<option value='' selected>Seleccionar</option>";
							}
							$sql = $cCfn->getQuery("edit_bit_gsm", NULL);
							$result = $cCfn->exeQuery($sql,$db);
							foreach( $result as $row ){
								if ($clasificacion==$row['OPCION']){
									echo "<option value='".$row['OPCION']."' selected>".$row['OPCION']."</option>";
								}
								else{
									echo "<option value='".$row['OPCION']."'>".$row['OPCION']."</option>";
								}
							} ?>
					</select>
				</p>
				<?php
					echo "<p>T&iacute;tulo (parte variable, opcional): </p>";
					echo "<p><textarea name='titulo' rows='3' cols='49' wrap='SOFT'>$titulo</textarea></p>"; ?>
				<p>
					<input name="accion" type="submit" value="Ingresar"> <input name= "accion" type="submit" value="Volver">
				</p>
			</form>
		</body>
	</html>