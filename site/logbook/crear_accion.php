<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";

	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$descripcion = $_GET["descripcion"] ?? $_POST["descripcion"] ?? '';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Documentar acci&oacute;n</title>
		<?php include_once("../../protected/style.php") ?>
		<!--script src="js/jquery-1.10.2.js"></script-->
		<!--script src="js/bitacora_accion.js"></script-->
		<!--script type="text/javascript" src="js/jquery.zclip.js"></script-->
	</head>
	<body>
		<h1 align="CENTER">Documentar acci&oacute;n</h1>
		<form name='test' action="insert_accion.php" method="post" target='_blank'>
			<p>Bitacora <?php echo "$id"; ?></p>
			<input type='hidden' id='id' name='id' value='<?php echo "$id"; ?>' /> 
			<p>Descripci&oacute;n (obligatorio):</p>
			<p><textarea id='descripcion' name="descripcion" rows='14' cols='49' wrap='soft'><?php echo "$descripcion"; ?></textarea></p>
			<p><input type="submit" id='cmd_enviar' name ='cmd_enviar' value="Ingresar" /> <input type="reset" value="Borrar" /></p>
		</form>
	</body>
</html>