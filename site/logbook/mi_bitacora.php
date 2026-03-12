<?php
	session_start();
	require "../../autoloader.php";
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	$date = new DateTime(); # clase DateTime global de PHP 
	
	include_once("ver_bitacora.php");
	
	$usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$filtro = $_GET["filtro"] ?? $_POST["filtro"] ?? 'on';
	$modo_bitacora = $_GET["modo"] ?? $_POST["modo"] ?? ''; ?>
	
	<?php
	if( $filtro == "on" ) {
		echo "<meta http-equiv='Refresh' content='60'>";
	}
	?>
	<title>Bitacora de <?php echo $usr; ?></title>
	<h3 align="CENTER">
		Bitacora de <?php echo "$usr al $ahora" ?> <a href="<?php echo $base_path; ?>/crear_bitacora.php?tipo=1">(Crear nueva)</a>
	</h3>
	<form action="mi_bitacora.php?<?php echo SID;?>" method="post">
		<p>
			<input type="submit" value="Refresh">
			<input type="radio" name="filtro" onclick="this.form.submit()" value="on" <?php if ($filtro == "on") echo "checked"?>>Automatico
			<input type="radio" name="filtro" onclick="this.form.submit()" value="off" <?php if ($filtro == "off") echo "checked"?>>Manual
			<input type="radio" name="modo" onclick="this.form.submit()" <?php if (($modo_bitacora=="") or ($modo_bitacora==0)) echo "checked" ?> value="0">Expanded
			<input type="radio" name="modo" onclick="this.form.submit()" <?php if ($modo_bitacora==1) echo "checked" ?> value="1">Compressed
		</p>
	</form>
	<?php
	$params = [ $usr ];
	$types="s";
	$sql="sel_mi_bit";
	ver_bitacora($sql,$usr,$modo_bitacora); ?>
		