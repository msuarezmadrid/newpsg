<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$usr=$cCfn->getUser();
	$db="intradb";
	
	$date = new DateTime();
	$ahora=$date->format('Y-m-d H:i:s');
	
	## VARIABLES 
	$opc		= $_GET["opc"] ?? $_REQUEST["opc"] ?? '';
	$sitio		= $_GET["sitio"] ?? $_REQUEST["sitio"] ?? '';
	
	$severidad 	= $_GET["severidad"] ?? $_REQUEST["severidad"] ?? '';
	$titulo		= $_GET["titulo"] ?? $_REQUEST["titulo"] ?? '';
	
	$sel_sitio 	= array();
	$nodo 		= array();
	$servicio 	= array();
	
	$sel_sitio	= $_GET["sel_sitio"] ?? $_REQUEST["sel_sitio"] ?? '';
	$sel_sitio	= explode(",",$sel_sitio);
	$nodo		= $_GET["nodo"] ?? $_REQUEST["nodo"] ?? '';
	$nodo		= explode(",",$nodo);
	$servicio	= $_GET["servicio"] ?? $_REQUEST["servicio"] ?? '';
	$servicio	= explode(",",$servicio);
	
	$iyear 		= $_GET["iano"] ?? $_REQUEST["iano"] ?? '';
	$imonth 	= $_GET["imes"] ?? $_REQUEST["imes"] ?? '';
	$iday 		= $_GET["idia"] ?? $_REQUEST["idia"] ?? '';
	$ihour 		= $_GET["ihora"] ?? $_REQUEST["ihora"] ?? '';
	$iminute 	= $_GET["iminuto"] ?? $_REQUEST["iminuto"] ?? '';
	
	$fyear 		= $_GET["fano"] ?? $_REQUEST["fano"] ?? '';
	$fmonth 	= $_GET["fmes"] ?? $_REQUEST["fmes"] ?? '';
	$fday 		= $_GET["fdia"] ?? $_REQUEST["fdia"] ?? '';
	$fhour 		= $_GET["fhora"] ?? $_REQUEST["fhora"] ?? '';
	$fminute 	= $_GET["fminuto"] ?? $_REQUEST["fminuto"] ?? '';
	
	$tipo		= $_GET["tipo"] ?? $_REQUEST["tipo"] ?? '';
	$opt_cerrar	= $_GET["opt"] ?? $_REQUEST["opt"] ?? '';
	
	switch($opc){
		case 1:{
			$sitioso=NULL;
			if( $sitio == "todos" ){
				$sql=$cCfn->getQuery("nueva_bit_SitNodServ1", NULL);
			}
			$result = $cCfn->exeQuery($sql,$db);
			foreach( $result as $row ){
				$sitioso.= "<option value='".$row['SITIO']." ".$row['NOMBRE']."'>".$row['SITIO']." ".$row['NOMBRE']."</option>";
			}
			
			$sql=$cCfn->getQuery("nueva_bit_ne", NULL);
			$result = $cCfn->exeQuery($sql,$db);
			$nodo=null;
			foreach( $result as $row ){
				$nodo.="<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
			}
			
			$sql=$cCfn->getQuery("nueva_bit_serv", NULL);
			$result = $cCfn->exeQuery($sql,$db);
			$servicio=null;
			foreach( $result as $row ){
				$servicio.="<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
			}
			
			echo $sitioso."|".$severidad."|".$titulo."|".$nodo."|".$servicio."|".$usr."|".$tipo."|".$opt_cerrar;
			
			break;
		}
		case 2:{
				if( empty($sel_sitio[0]) ){
					$res.= "Debe completar los campos obligatorios 1.Sitio ";
				}
				else{
					$params = [
						":titulo" => $titulo,
						":usr" => $usr,
						":tipo" => $tipo,
						":severidad" => $severidad,
						":lista" => NULL,
						":bit_class" => $titulo,
						":usr" => $usr 
					];
					$sql=$cCfn->getQuery("nueva_bit_opersis", $params);
					$result = $cCfn->exeQuery($sql,$db);
					$id=$result["insert_id"];
					//$id=mysqli_insert_id();
					
					## debug 
					$archivo = './debug_bit.txt';
					$fp = fopen($archivo, "a");
					
					$string = "\nID: $id ,nueva_bitacora.php\n";
					$write = fputs($fp, $string);
					
					$string = "Datos: $sistema $plataforma  $servicio $elemento\n";
					$write = fputs($fp, $string);

					$string = "Insert: $sql\n";
					$write = fputs($fp, $string);
					fclose($fp);
					#################
					
					if ($opt_cerrar=="C"){
						$params = [
							":id" => $id 
						];
						$sql=$cCfn->getQuery("update_bit", $params);
						$result = $cCfn->exeQuery($sql,$db);
					}
					
					$lista="";
					if( !empty($sel_sitio) ){
						foreach($sel_sitio as $key => $value){
							$lista.=$value."/";
							
							$params = [
								":id" => $id, 
								":value" => addslashes($value)
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
							
							$log="Insertando desde .../logbook/proceso_ajax_bitacora.php -- $sql ";
							// my_log($log);
							$cCfn->my_log($log);
							
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
							$cCfn->exeQuery($sql,$db);
						}
					}
					
					$params = [
						":id" => $id,
						":lista" => $lista
					];
					$sql=$cCfn->getQuery("update_bit5", $params);
					$cCfn->exeQuery($sql,$db);
					
					$params = [
						":id" => $id,
						":time" => $ahora
					];
					$sql=$cCfn->getQuery("update_bit2", $params);
					$cCfn->exeQuery($sql,$db);
					
					$res.="Bitacora creada con Nº : ".$id;
				}
				echo $res;
			break;
		}
		case 4:{
			echo("crear_bitacora.php");
			exit;
			break;
		}
	}

