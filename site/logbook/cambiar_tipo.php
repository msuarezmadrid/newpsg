<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$tipo = $_GET["tipo"] ?? $_POST["tipo"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if( $tipo==0 ){
		$tipo=1;
	}
	else{
		$tipo=0;
	}
	
	$params = [ $id, $tipo ];
	$result = $cCfn->exeQuery("update_bit_tipo",$params,'ii',$cCfn->getLocal(),null,$modulo);
	if( !empty($result['affected']) ) $msje="<h3 align='center' >Tipo modificado.</h3>"; 
	else $msje="<h3 align='center' >No se pudo modificar tipo.</h3>";
	?>
	
	<h2 align='center'>Bit&aacute;cora <?php echo "$id"; ?></h2>
	<h3 align='center' ><?php echo $msje; ?>.</h3>