<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";	?>
	
	<h1 align="CENTER">Administraci&oacute;n tablas de Sistemas</h1>
	<form name='test' action="<?php echo $base_path;?>/edit_sist_tables.php" method="post">
		<select name="tabla">
			<option value="1" selected>ELEMENTO</option>
			<option value="2">SISTEMA</option>
			<option value="3">SUBSISTEMA</option>
		</select>
		<p>
			<input type="submit" name="eleccion" value="Editar">&nbsp;
			<input type="submit" name="eleccion" value="Agregar">&nbsp;
			<input name="eleccion" type="reset" value="Borrar">&nbsp;
		</p>
	</form>
	