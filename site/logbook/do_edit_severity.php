<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	
	$accion = $_GET["accion"] ?? $_POST["accion"] ?? '';
	$severidad = $_GET["severidad"] ?? $_POST["severidad"] ?? '';
	$sev_inicial = $_GET["sev_inicial"] ?? $_POST["sev_inicial"] ?? '';
	$mala = $_GET["mala"] ?? $_POST["mala"] ?? 0;
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	$usr=$cCfn->getUser();
	
	$result = $cCfn->exeQuery("severidad_bit",null,null,$cCfn->getLocal(),null,$modulo);
	$sev_final=null;
	foreach( $sev = $result->fetch_assoc() ){
		if( $sev['ID'] == $severidad ){
			$sev_final = $sev['NOMBRE'];
		}
	}
	
	if( $sev_inicial != $sev_final ){
		$texto=null;
		if( $mala == 1 ){
			$texto="$usr cambia severidad de $sev_inicial a $sev_final. Severidad mal tipificada. ";
		}
		else{
			$texto="$usr cambia severidad de $sev_inicial a $sev_final. ";
		}
		
		$params = [ $id, $severidad, $mala ];
		$types="iii";
		$result = $cCfn->exeQuery("update_severity",$params,$types,$cCfn->getLocal(),null,$modulo);
		
		$params = [ $usr, $texto, $id ];
		$types="ssi";
		$result = $cCfn->exeQuery("nueva_bit_comment",$params,$types,$cCfn->getLocal(),null,$modulo);
		
	} ?>
	<title>Editar Severidad</title>
	
	<h2 align='center'>Bitacora <?php echo "$id"; ?></h2>
	<h3 align='center' >Severidad modificada.</h3>
	