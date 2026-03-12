<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$base_path=BASE_URL . "/site/logbook";
	
	$cCfn->checkSession(); ?>
	<p><h1 align="CENTER">Bit&aacute;cora</h1></p>
	<h5>Nuevo: <a href="<?php echo $base_path; ?>/severidad_help.php" target="_blank">Severidad </a>: Clasificaci&oacute;n de eventos por severidad.</h5>
	<p>
		<a href="<?php echo $base_path; ?>/help.php" target="_blank">Ayuda: </a>
		Instrucciones (breves) de uso.</p>
	<p>
		<a href="<?php echo $base_path; ?>/bitacora.php" class='main-link auto-refresh' >Bit&aacute;cora:</a>
		Bit&aacute;cora actual, solo con eventos abiertos. Se actualiza autom&aacute;ticamente cada minuto.</p>
	<p>
		<a href="<?php echo $base_path; ?>/mi_bitacora.php" class='main-link auto-refresh'>Mi Bit&aacute;cora:</a>
		Bit&aacute;cora actual, solo con eventos abiertos por el usuario. Se actualiza autom&aacute;ticamente cada minuto.</p>
	<p>
		<a href="<?php echo $base_path; ?>/bitacora_de.php" target="_blank">Bit&aacute;cora de:</a>
		Bit&aacute;cora actual, solo con eventos abiertos por cierto usuario.</p>
	<p>
		<a href="<?php echo $base_path; ?>/bitacora_sev.php" class='main-link auto-refresh'>Bit&aacute;cora Severidad</a>
		Bit&aacute;cora actual, solo con eventos abiertos con severidad mayor o igual que. Se actualiza autom&aacute;ticamente cada minuto.</p>
	<p>
		<a href="<?php echo $base_path; ?>/bitacora_grupo.php" target="_blank">Bit&aacute;cora de &aacute;rea:</a>
		Bit&aacute;cora actual, solo con eventos abiertos por usuarios pertenecientes a cierta &aacute;rea.</p>
	<p>
		<a href="<?php echo $base_path; ?>/scroll_bitacora.php" target="_blank">Bit&aacute;cora:</a>
		Visor de eventos (con scroll).</p>
	<p>
		<a href="<?php echo $base_path; ?>/search_bit_form.php">Buscar:</a>
		B&uacute;squeda en bit&aacute;cora</p>
	<p>
		<a href="<?php echo $base_path; ?>/search_sit_form.php">Buscar:</a>
		B&uacute;squeda de Sitios en TP/PID/TAREA/SC</p>
	
	<!-- <p>
		<a href="asociar_masiva_bitacoras.php">Transferencia de turno:</a>
		Asociacion masiva de bitacoras
	</p> -->
	
	<h3>Log</h3>
	<p>
		Combina bit&aacute;cora, problemas, tareas, y trabajos programados</p>
	<p>
		<a href="<?php echo $base_path; ?>/log.php" class='main-link auto-refresh'>Log:</a>
		Ultimos eventos en la red. Se actualiza autom&aacute;ticamente cada minuto.</p>
	<p>
		<a href="<?php echo $base_path; ?>/scroll_log.php" target="_blank">Log:</a>
		Visor de eventos (con scroll).</p>
	<p>
		<a href="<?php echo $base_path; ?>/search_form.php">Buscar:</a>
		B&uacute;squeda en log de eventos.</p>
	<p>
		<a href="<?php echo $base_path; ?>/bcon_form.php" target="_blank">Glup:</a>
		B&uacute;squeda en base</p>
	  
	<?php
		$req="Adm Sistemas";
		if ( $cCfn->checkProfile("Adm Sistemas") ){ ?>
			<h3>Administraci&oacute;n</h3>
			<p>
				<a href="<?php echo $base_path; ?>/adm_sistemas.php" target="_blank">Administraci&oacute;n Sistemas:</a>
				Administraci&oacute;n de Elemento, Sistema y Subsistema.</p>
	<?php
		} ?>
	<div align="center"><img src="" alt="" /></div>
	