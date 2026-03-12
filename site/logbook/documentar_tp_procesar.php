<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	
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
					$params = [ $txt_tarea ];
					$result = $cCfn->exeQuery("asoc_tp",$params,'i',$cCfn->getLocal(),null,$modulo);
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
					$params = [ $txt_tarea ];
					$result = $cCfn->exeQuery("asoc_pid",$params,'i',$cCfn->getLocal(),null,$modulo);
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
					$params = [ $txt_tarea ];
					$result = $cCfn->exeQuery("asoc_tar",$params,'i',$cCfn->getLocal(),null,$modulo);
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
					$params = [ $txt_tarea ];
					$result = $cCfn->exeQuery("asoc_sc",$params,'i',$cCfn->getLocal(),null,$modulo);
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
					$params = [ $id_bitacora, $txt_tarea, $id_tipo ];
					$result = $cCfn->exeQuery("doc_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
					if ( empty($row) ){
						# ************** INSERTAR EN LOG ****************
						$result = $cCfn->exeQuery("ins_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
						# ***********************************************
						# ********** RESCATAR BITACORA SI EL TP TUBIERA ***********************************************
						$bitacora = null;
						$params = [ $txt_tarea ];
						$result = $cCfn->exeQuery("asoc_bit_tp",$params,'i',$cCfn->getLocal(),null,$modulo);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
							
						# *********** INSERTAR EN CONSOLIDADO ***********
						$params = [ 0, $txt_tarea, 0, $bitacora, 0, $id_bitacora ];
						$result = $cCfn->exeQuery("ins_conso_bit_asoc",$params,'iiiiii',$cCfn->getLocal(),null,$modulo);
						# ***********************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
				case 2:{# PROBLEMA
					$params = [ $id_bitacora, $txt_tarea, $id_tipo ];
					$result = $cCfn->exeQuery("doc_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
					if ( empty($row) ){
						# ********************* INSERTAR EN LOG *************************
						$result = $cCfn->exeQuery("ins_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
						# ***************************************************************
				
						# ********** RESCATAR BITACORA SI EL TP TUVIERA ***********************************************
						$bitacora = null;
						$params = [ $txt_tarea ];
						$result = $cCfn->exeQuery("asoc_bit_pid",$params,'i',$cCfn->getLocal(),null,$modulo);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
						# ****************** INSERTAR EN CONSOLIDADO ********************
						$params = [ $txt_tarea, 0, 0, $bitacora, 0, $id_bitacora ];
						$result = $cCfn->exeQuery("ins_conso_bit_asoc",$params,'iiiiii',$cCfn->getLocal(),null,$modulo);
						# ***************************************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
				case 3:{# TAREA
					$params = [ $id_bitacora, $txt_tarea, $id_tipo ];
					$result = $cCfn->exeQuery("doc_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
					if ( empty($row) ){
						# ********************* INSERTAR EN LOG *************************
						$result = $cCfn->exeQuery("ins_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
						# ***************************************************************
						# ********************* RESCATAR BITACORA SI EL TP TUVIERA ************************************
						$bitacora = null;
						$params = [ $txt_tarea ];
						$result = $cCfn->exeQuery("asoc_bit_tar",$params,'i',$cCfn->getLocal(),null,$modulo);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
						# ****************** INSERTAR EN CONSOLIDADO ********************
						$params = [ 0, 0, $txt_tarea, $bitacora, 0, $id_bitacora ];
						$result = $cCfn->exeQuery("ins_conso_bit_asoc",$params,'iiiiii',$cCfn->getLocal(),null,$modulo);
						# ***************************************************************
						$res="SI";
					}else{
						$res="NO";
					}
					# *************************************************************************************************
					break;
				}
				case 4:{//SC
					$params = [ $id_bitacora, $txt_tarea, $id_tipo ];
					$result = $cCfn->exeQuery("doc_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
					if ( empty($row) ){
						# ****************** INSERTAR EN LOG ****************************
						$result = $cCfn->exeQuery("ins_bit_asoc",$params,'iii',$cCfn->getLocal(),null,$modulo);
						# ***************************************************************
						# ****************** RESCATAR BITACORA SI EL TP TUVIERA ***************************************
						$bitacora = null;
						$params = [ $txt_tarea ];
						$result = $cCfn->exeQuery("asoc_bit_sc",$params,'i',$cCfn->getLocal(),null,$modulo);
						if ( !empty($row) ){
							$bitacora = $row[0]['ID'];
						}else{
							$bitacora = 0;
						}
						# *********************************************************************************************
						# ******************* INSERTAR EN CONSOLIDADO *******************
						$params = [ 0, 0, 0, $bitacora, $txt_tarea, $id_bitacora ];
						$result = $cCfn->exeQuery("ins_conso_bit_asoc",$params,'iiiiii',$cCfn->getLocal(),null,$modulo);
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
			$params = [ $id_bitacora ];
			$result = $cCfn->exeQuery("doc_bit_asoc_tip",$params,'i',$cCfn->getLocal(),null,$modulo);
			$tabla = "<table border='1' class='sample' width='400' align='center'>";
			while( $row=$result->fetch_assoc() ){
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