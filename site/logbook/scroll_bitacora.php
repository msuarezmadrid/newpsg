<?php
	require "../../autoloader.php";
	
	session_start();
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	
	$cCfn->checkSession();
	$modulo="logbook";
	
	$date = new DateTime(); # clase DateTime global de PHP 
	
	include_once("ver_bitacora.php");
	
	$usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$modo_bitacora = $_GET["modo"] ?? $_POST["modo"] ?? '';
	$offset = $_GET["offset"] ?? $_POST["offset"] ?? '0';
	$horiz = $_GET["horiz"] ?? $_POST["horiz"] ?? '';
	$vert = $_GET["vert"] ?? $_POST["vert"] ?? '';
	$nr_records = $_GET["nr_records"] ?? $_POST["nr_records"] ?? '';
		
	if( empty($nr_records) ) $nr_records=30;

	if ( $horiz == "<-" ){
		$nr_records=$nr_records-5;
		if( $nr_records < 5 ) $nr_records=5;
	}
	else if ( $horiz == "->" ){
		$nr_records=$nr_records+5;
	}
	
	if( $vert == "^" ){
		$offset=$offset-$nr_records;
		if( $offset < 0 ) $offset=0;
	}
	else if ($vert == "v"){
		$offset=$offset+$nr_records;
	} ?>
	
	<h3 align="CENTER">	Bitacora</h3>
	<form action="scroll_bitacora.php" method="post" align="CENTER">
		<input name="horiz" type="submit" value="<-" />Records: <?php echo $nr_records; ?><input name="horiz"  type="submit" value="->" />
		<input name="vert" type="submit" value="^">Offset: <?php echo $offset; ?><input name="vert"  type="submit" value="v" />
		<input type="radio" name="modo" <?php if( empty($modo_bitacora) ) echo "checked"; ?> value="0" />Expanded
		<input type="radio" name="modo" <?php if ($modo_bitacora==1) echo "checked"; ?> value="1" />Compressed
		<input type="submit" value="Refresh" />
		<input type="hidden" name="offset" value="<?php echo $offset; ?>" /> 
		<input type="hidden" name="nr_records" value="<?php echo $nr_records; ?>" /> 
	</form>
	<br/>
	<?php
		$params = [ $offset, $nr_records ];
		$types="ii";
		$sql="sel_bit_scroll";
		ver_bitacora($sql,$usr,$modo_bitacora); ?>
	