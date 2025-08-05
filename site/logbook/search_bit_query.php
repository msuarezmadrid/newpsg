<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	$date = new DateTime();
	
	include_once("ver_bitacora.php");
	
	try
	{
		$usr=$cCfn->getUser();
		$ahora=$date->format('Y-m-d H:i:s');
		
		$numero       	= $_GET["numero"] ?? $_REQUEST["numero"] ?? '';
		$modo       	= $_GET["modo"] ?? $_REQUEST["modo"] ?? '';
		$descripcion	= $_GET["descripcion"] ?? $_REQUEST["descripcion"] ?? '';
		$nodo			= $_GET["nodo"] ?? $_REQUEST["nodo"] ?? '';
		$creador		= $_GET["creador"] ?? $_REQUEST["creador"] ?? '';
		$severidad      = $_GET["severidad"] ?? $_REQUEST["severidad"] ?? '';
		$area			= $_GET["area"] ?? $_REQUEST["area"] ?? '';
		$abiertas       = $_GET["abiertas"] ?? $_REQUEST["abiertas"] ?? '';
		$fecha1       	= $_GET["fecha1"] ?? $_REQUEST["fecha1"] ?? '';
		$fecha2       	= $_GET["fecha2"] ?? $_REQUEST["fecha2"] ?? '';
		
		$arr_registros= [$numero,$descripcion,$nodo,$creador,$area,$fecha1,$fecha2,$abiertas,$severidad];
		if( $cCfn->hayElementosVacios($arr_registros) ){
			echo "<!DOCTYPE html>
			<html>
				<head>
					<title>Bitacora</title>
					<?php include_once('../../protected/style.php') ?>
				</head>
				<body>
					<h3 align='center' >No hay data.</h3>
				</body>
			</html>";
			exit;
		}
		if ( empty($modo) ) $modo = 0;
		?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Bitacora</title>
			<?php
			if( !empty($numero) ){
				echo "<title>Bitacora $numero</title>";
			}
			else{
				echo "<title>Busqueda Bitacora</title>";
			}
			include_once("../../protected/style.php") ?>
			<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
			<script type="text/javascript" src="js/bitacora_accion.js"></script>
			<script type="text/javascript" src="js/jquery.zclip.js"></script>
		</head>
		<body>
			<h3 align="center">Resultado de b&uacute;squeda</h3>
			<?php
				$clauses="";
				$aux=" WHERE ";
				$from="";
				
				if ( !empty($numero) ){
					$clauses .=" WHERE BITACORA.ID='$numero' ";
					$params = [
						":from" => $from,
						":clauses" => $clauses
					];
					$sql=$cCfn->getQuery("search_bit", $params);
				}
				else{
					if ($descripcion != ""){
						$clauses .=$aux . " BITACORA.TITULO LIKE '%$descripcion%' ";
						$aux=" AND ";
					}
					if ($nodo != ""){
						$clauses .=$aux . " BITACORA.NODO LIKE '%$nodo%' ";
						$aux=" AND ";
					}
					if ($creador != ""){
						$clauses .=$aux . " BITACORA.usr LIKE '%$creador%' ";
						$aux=" AND ";
					}
					if ($severidad != ""){
						$clauses .=$aux . " BITACORA.BIT_SEVERITY $comp '$severidad'  ";
						$aux=" AND ";
					}
					if ($area != ""){
						$clauses .=$aux . " BITACORA.usr=users.usr AND users.personal_id=PERSONAL.ID AND PERSONAL.AREA='$area' ";
						$aux=" AND ";
						$from=",users,o_m.PERSONAL";
					}

					if ($abiertas == 1){
						$clauses .=$aux . " BITACORA.FIN IS NULL  ";
						$aux=" AND ";
					}
					if ($fecha1 != ""){
						if ($fecha2 == ""){
							$clauses .= $aux . "( BITACORA.INICIO LIKE '%$fecha1%' OR BITACORA.FIN LIKE '%$fecha1%' OR BITACORA.EVENT_TIME LIKE '%$fecha1%' OR BITACORA.CEASE_TIME LIKE '%$fecha1%' ) ";
						}
						else{
							$clauses .= $aux . " ( (BITACORA.INICIO >= '$fecha1' AND BITACORA.INICIO <= '$fecha2') OR (BITACORA.FIN >='$fecha1' AND BITACORA.FIN <='$fecha2') OR (BITACORA.EVENT_TIME >='$fecha1' AND BITACORA.EVENT_TIME<= '$fecha2') OR (BITACORA.CEASE_TIME >='$fecha1' AND BITACORA.CEASE_TIME<='$fecha2')  )";
						}
						$aux=" AND ";
					}
					if ( ($fecha2 != "") and ($fecha1 == "") ){
						$clauses .= $aux . "( BITACORA.INICIO <= '$fecha2' OR BITACORA.FIN <= '$fecha2' OR BITACORA.EVENT_TIME <='$fecha2' OR BITACORA.CEASE_TIME <='$fecha2' )";
					}
					
					$params = [
						":from" => $from,
						":clauses" => $clauses
					];
					$sql=$cCfn->getQuery("search_bit", $params);
				}
				
				ver_bitacora($sql,$usr,$modo,1);
				
	} catch (Exception $e) {
		$modal=" 
			<div class='modal-header'>
				<h4 class='modal-title'>Sitio  </h4>
			</div>
			<div class='modal-body'>
				<h4 align='left'>Error: ".$e->getMessage()."</h4>
				<h4 align='left'>Archivo: ".$e->getFile()."</h4>
				<h4 align='left'>Línea: ".$e->getLine()."</h4>
				<h4 align='left'>Trace:<br/> ".$e->getTraceAsString()."</h4>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-primary' data-dismiss='modal'>Cerrar</button>
			</div>";
		echo $modal;
	} ?>
	</body>
</html>