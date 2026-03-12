<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$db="intradb";

	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$descripcion = $_GET["descripcion"] ?? $_POST["descripcion"] ?? '';
?>
	<title>Documentar acci&oacute;n</title>
	<h1 align="CENTER">Documentar acci&oacute;n</h1>
	<form name='test' action="insert_accion.php" method="post" target='_blank'>
		<p>Bit&aacute;cora <?php echo "$id"; ?></p>
		<input type='hidden' id='id' name='id' value='<?php echo "$id"; ?>' /> 
		<p>Descripci&oacute;n (obligatorio):</p>
		<p><textarea id='descripcion' name="descripcion" rows='14' cols='49' wrap='soft'><?php echo "$descripcion"; ?></textarea></p>
		<p><input type="submit" id='cmd_enviar' name ='cmd_enviar' value="Ingresar" /> <input type="reset" value="Borrar" /></p>
	</form>
	