<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	header('Content-Type: text/html; charset=UTF-8');
	
	$opc = $_GET["opc"] ?? $_POST["opc"] ?? '';
	$id_tipo = $_GET["id_tipo"] ?? $_POST["id_tipo"] ?? '';
	$txt_tarea = $_GET["txt_tarea"] ?? $_POST["txt_tarea"] ?? '';
	$id_bitacora = $_GET["id_bitacora"] ?? $_POST["id_bitacora"] ?? '';
	
	switch($opc){
		case 1:{ # ************************************ VALIDA TAREA *********************************************
			$res = 'SI';
			switch ($id_tipo){
				case 1:{# PLANNED
					# ********* RESCATAR BITACORA SI EL TP TUVIERA ***********************************************
					$params = [
						":assoc_id" => $txt_tarea
					];
					$sql=$cCfn->getQuery("asoc_tp", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( !empty($row) ){
						$res = 'SI';
					}else{
						$res = 'NO';
					}
					# ********************************************************************************************
					break;
				}
				case 2:{# PROBLEMA
					# ********* RESCATAR BITACORA SI EL TP TUBIERA ***********************************************
					$params = [
						":assoc_id" => $txt_tarea
					];
					$sql=$cCfn->getQuery("asoc_pid", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( !empty($row) ){
						$res = 'SI';
					}else{
						$res = 'NO';
					}
					# ********************************************************************************************
					break;
				}
				case 3:{# TAREA
					# ********* RESCATAR BITACORA SI EL TP TUBIERA ***********************************************
					$params = [
						":assoc_id" => $txt_tarea
					];
					$sql=$cCfn->getQuery("asoc_tar", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( !empty($row) ){
						$res = 'SI';
					}else{
						$res = 'NO';
					}
					# ********************************************************************************************
					break;
				}
				case 4:{//SC
					# ********* RESCATAR BITACORA SI EL TP TUBIERA ***********************************************
					$params = [
						":assoc_id" => $txt_tarea
					];
					$sql=$cCfn->getQuery("asoc_sc", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( !empty($row) ){
						$res = 'SI';
					}else{
						$res = 'NO';
					}
					# ********************************************************************************************
					break;
				}
			}
			
			break; 
		}
		case 2:{ # ******************* GUARDA LOS COMENTARIOS PARA LA TAREA **************************************
			$res="NO";
			switch ($id_tipo){
				case 1:{# PLANNED
					$params = [
						":id_bitacora" => $id_bitacora,
						":txt_tarea" => $txt_tarea,
						":id_tipo" => $id_tipo
					];
					$sql=$cCfn->getQuery("doc_bit_asoc", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( empty($row) ){
						# ************** INSERTAR EN LOG ****************
						$sql=$cCfn->getQuery("ins_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***********************************************
						# ********** RESCATAR BITACORA SI EL TP TUBIERA ***********************************************
						$bitacora = null;
						$params = [
							":assoc_id" => $txt_tarea
						];
						$sql=$cCfn->getQuery("asoc_bit_tp", $params);
						$result = $cCfn->exeQuery($sql,$db);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
							
						# *********** INSERTAR EN CONSOLIDADO ***********
						$params = [
							":pid" => 0,
							":txt_tarea" => $txt_tarea,
							":tar" => 0,
							":bitacora" => $bitacora,
							":sc" => 0,
							":id_bitacora" => $id_bitacora
						];
						$sql=$cCfn->getQuery("ins_conso_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***********************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
				case 2:{# PROBLEMA
					$params = [
						":id_bitacora" => $id_bitacora,
						":txt_tarea" => $txt_tarea,
						":id_tipo" => $id_tipo
					];
					$sql=$cCfn->getQuery("doc_bit_asoc", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( empty($row) ){
						# ********************* INSERTAR EN LOG *************************
						$sql=$cCfn->getQuery("ins_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***************************************************************
				
						# ********** RESCATAR BITACORA SI EL TP TUVIERA ***********************************************
						$bitacora = null;
						$params = [
							":assoc_id" => $txt_tarea
						];
						$sql=$cCfn->getQuery("asoc_bit_pid", $params);
						$row = $cCfn->exeQuery($sql,$db);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
						# ****************** INSERTAR EN CONSOLIDADO ********************
						$params = [
							":pid" => $txt_tarea,
							":txt_tarea" => 0,
							":tar" => 0,
							":bitacora" => $bitacora,
							":sc" => 0,
							":id_bitacora" => $id_bitacora
						];
						$sql=$cCfn->getQuery("ins_conso_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***************************************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
				case 3:{# TAREA
					$params = [
						":id_bitacora" => $id_bitacora,
						":txt_tarea" => $txt_tarea,
						":id_tipo" => $id_tipo
					];
					$sql=$cCfn->getQuery("doc_bit_asoc", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( empty($row) ){
						# ********************* INSERTAR EN LOG *************************
						$sql=$cCfn->getQuery("ins_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***************************************************************
						# ********************* RESCATAR BITACORA SI EL TP TUVIERA ************************************
						$bitacora = null;
						$params = [
							":assoc_id" => $txt_tarea
						];
						$sql=$cCfn->getQuery("asoc_bit_tar", $params);
						$row = $cCfn->exeQuery($sql,$db);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
						# ****************** INSERTAR EN CONSOLIDADO ********************
						$params = [
							":pid" => 0,
							":txt_tarea" => 0,
							":tar" => $txt_tarea,
							":bitacora" => $bitacora,
							":sc" => 0,
							":id_bitacora" => $id_bitacora
						];
						$sql=$cCfn->getQuery("ins_conso_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***************************************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
				case 4:{//SC
					$params = [
						":id_bitacora" => $id_bitacora,
						":txt_tarea" => $txt_tarea,
						":id_tipo" => $id_tipo
					];
					$sql=$cCfn->getQuery("doc_bit_asoc", $params);
					$row = $cCfn->exeQuery($sql,$db);
					if ( empty($row) ){
						# ****************** INSERTAR EN LOG ****************************
						$sql=$cCfn->getQuery("ins_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***************************************************************
						# ****************** RESCATAR BITACORA SI EL TP TUVIERA ***************************************
						$bitacora = null;
						$params = [
							":assoc_id" => $txt_tarea
						];
						$sql=$cCfn->getQuery("asoc_bit_sc", $params);
						$row = $cCfn->exeQuery($sql,$db);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
						# ******************* INSERTAR EN CONSOLIDADO *******************
						$params = [
							":pid" => 0,
							":txt_tarea" => 0,
							":tar" => 0,
							":bitacora" => $bitacora,
							":sc" => $txt_tarea,
							":id_bitacora" => $id_bitacora
						];
						$sql=$cCfn->getQuery("ins_conso_bit_asoc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						# ***************************************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
			}
			
			break;
		}
		case 3:{# TAREA
			$tabla=null;
			$params = [
				":id_bitacora" => $id_bitacora
			];
			$sql=$cCfn->getQuery("doc_bit_asoc_tip", $params);
			$result = $cCfn->exeQuery($sql,$db);
			$tabla = "<table border='1' class='sample' width='400' align='center'>";
			foreach( $result as $row ){
				$tabla .= "<tr>";
				$tabla .= 		"<td width='35%' > &nbsp; ".$row['ASOC_ID']." &nbsp;</td>";
				$tabla .= 		"<td width='65%'> &nbsp;  ".$row['TIPO_NOMBRE']." &nbsp;</td>";
				$tabla .= "</tr>";
			}
			$tabla.=" </table>";
			echo $tabla;
			# *********************************************************************************************************
			break;
		}
	}#  Fin switch case 
?>