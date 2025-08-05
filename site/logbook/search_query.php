<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb"; 
	
	include_once("ver_log.php"); ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Subgerencia Operaciones y Mantenimiento de Red</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h2 align="CENTER">	Resultado de b&uacute;squeda </h2>
			<?php
				$arr_registros= [$descripcion,$user,$fecha1,$fecha2];
				if( $cCfn->hayElementosVacios($arr_registros) ){
					echo "<h3>No se encontraron datos que mostrar</h3></body></html>";
					exit;
				}
				
				$clauses=null;
				$aux=" WHERE ";
				
				if( !empty($descripcion) ){
					$clauses .=$aux . " DESCRIPCION LIKE '%$descripcion%' ";
					$aux=" AND ";
				}
				
				if( !empty($user) ){
					$clauses .=$aux . " usr='$user' ";
					$aux=" AND ";
				}
				
				if( !empty($fecha1) ){
					if( empty($fecha2) ){
						$clauses .= $aux . "( INGRESO LIKE '%$fecha1%' ) ";
					}
					else{
						$clauses .= $aux . " ( INGRESO >= '$fecha1' )";
					}
					$aux=" AND ";
				}
				if( !empty($fecha2) ){
					$clauses .= $aux . "( INGRESO <= '$fecha2' )";
				}
				
				$sql = "SELECT usr,INGRESO,DESCRIPCION,PROBLEMA_ID,PLANNED_ID,TAREA_ID,BITACORA_ID,SC_ID FROM CONSOLIDADO $clauses ORDER BY INGRESO DESC ";
				
				$params = [
					":clauses" => $clauses
				];
				$sql=$cCfn->getQuery("sel_conso_search", $params);
				ver_log(null,$sql); ?>
			
		</body>
	</html>