<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	$params = [
		":id" => $id
	];
	$sql=$cCfn->getQuery("del_node_nod", $params);
	$result = $cCfn->exeQuery($sql,$db);
	
	$sql=$cCfn->getQuery("del_node_serv", $params);
	$result = $cCfn->exeQuery($sql,$db);
	
	$sql=$cCfn->getQuery("del_node_sitio", $params);
	$result = $cCfn->exeQuery($sql,$db);
	
	$lista=null;
	
	if( !empty($sitio) ){
		foreach($sitio as $key => $value){
			$lista.=$value."/";
			$params = [
				":id" => $id,
				":value" => $value
			];
			$sql=$cCfn->getQuery("nueva_bit_bitSitio", $params);
			$result = $cCfn->exeQuery($sql,$db);
			##########################################################################
			$site=trim(substr($value,1,6));
			$params = [
				":id" => $id,
				":site" => $site
			];
			$sql=$cCfn->getQuery("update_bit4", $params);
			$result = $cCfn->exeQuery($sql,$db);
			##########################################################################
		}
	}
	
	if( !empty($nodo) ){
		foreach($nodo as $key => $value){
			$lista.=$value."/";
			$params = [
				":id" => $id,
				":servicio" => $value
			];
			$sql=$cCfn->getQuery("nueva_bit_opersis2", $params);
			$result = $cCfn->exeQuery($sql,$db);
		}
	}
	
	if ($servicio != ""){
		foreach($servicio as $key => $value){
			$lista.=$value."/";
			$params = [
				":id" => $id,
				":elemento" => $value
			];
			$sql=$cCfn->getQuery("nueva_bit_opersis3", $params);
			$result = $cCfn->exeQuery($sql,$db);
		}
	}
	
	$params = [
		":id" => $id,
		":lista" => $lista
	];
	$sql=$cCfn->getQuery("update_bit5", $params);
	$result = $cCfn->exeQuery($sql,$db);
	
?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Agregar Sitio/Nodo/Servicio</title>
			<?php include_once("../../protected/style.php") ?>
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
		</head>
		<body>
			<h2 align='center'>Bitacora <?php echo "$id"; ?></h2>
			<h3 align='center' >Registros ingresados.</h3>
		</body>
	</html>
