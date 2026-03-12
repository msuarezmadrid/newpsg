<?php
	include_once("../../protected/classFunciones.php");
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$clasificacion = $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? '';
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
				<p>Bitacora <?php echo "$id" ?></p>
				<input type="hidden" name="id" value="<?php echo $id; ?>" /> 
				<?php
					echo "<p>Tipo de problema: (obligatorio)</p>"; ?>
				<p>
					<select name="clasificacion">
						<?php
							if( empty($clasificacion) ){
								echo "<option value='' selected>Seleccionar</option>";
							}
							$sql = $cCfn->getQuery("edit_bit_op", NULL);
							$result = $cCfn->exeQuery($sql,$db);
							foreach( $result as $row ){
								if( $clasificacion==$row['NOMBRE'] ){
									echo "<option value='".$row['NOMBRE']."' selected>".$row['NOMBRE']."</option>";
								}
								else{
									echo "<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
								}
							} ?>
					</select>
				</p>
				<?php echo "<input type='hidden' name='titulo? value=''>"; ?>
				<p>
					<input name="accion" type="submit" value="Ingresar" /> <input name="accion" type="submit" value="Volver" />
				</p>
			</form>
		</body>
	</html>