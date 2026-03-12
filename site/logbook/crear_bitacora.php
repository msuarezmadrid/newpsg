<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	$usr=$cCfn->getUser();
	
	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : null; ?>
	
	<?php 
	if ( $cCfn->checkProfile("ProfileOperadorRed") || $cCfn->checkProfile("om_test") ){ ?>
		
		<h1 align='center'>Creaci&oacute;n de Bit&aacute;coras</h1>
		<h3>Bienvenido, Operador <?php echo $usr; ?>. Por favor seleccione el tipo de bit&aacute;cora que desea crear:</h3>
		<p><a href='<?php echo $base_path; ?>/crear_bitacora_opersis.php'>Operadores VAS</a></p>
		<p><a href='<?php echo $base_path; ?>/crear_bitacora_gsm.php'>Operadores GSM/WCDMA</a></p>
		<p><a href='<?php echo $base_path; ?>/crear_bitacora_plran.php'>Primera l&iacute;nea RAN </a></p>
		<p><a href='<?php echo $base_path; ?>/crear_bitacora_fallas.php?op=1'>CORE Telefon&iacute;a Fija</a></p>
		<p><a href='<?php echo $base_path; ?>/crear_bitacora_fallas.php?op=2'>NOC IP</a></p>
		<p><a href='<?php echo $base_path; ?>/crear_bitacora_fallas.php?op=3'>NOC Transporte</a></p>
		
	<?php 
	}
	else{
		if( $cCfn->checkProfile("ProfileOperadorRed") ){
			include("crear_bitacora_opersis.php");
		}
		else{
			include("crear_bitacora_gsm.php");
		}
	} ?>
	