<<<<<<< Updated upstream
<?php
	require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
	
	$file=basename(__FILE__);
	# VARIABLE PARA SWITCH 
	$ops = $_REQUEST['opcion'];
	
	# VARIABLES PARA LOS CASOS, PRIMEROS DATOS 
	$s_ao = ( isset($_REQUEST['s_areaorigen']) ) ? $_REQUEST['s_areaorigen'] : "" ;
	$s_srv = ( isset($_REQUEST['servicio']) ) ? $_REQUEST['servicio'] : "" ;
	$elem = ( isset($_REQUEST['elemento']) ) ? $_REQUEST['elemento'] : "" ;
	$tipoTrabajo = ( isset($_REQUEST['tipotrabajo']) ) ? $_REQUEST['tipotrabajo'] : "" ;
	$tipoingreso = ( isset($_REQUEST['tipoingreso']) ) ? $_REQUEST['tipoingreso'] : "" ;
	$region = ( isset($_REQUEST['region']) ) ? $_REQUEST['region'] : "" ;
	$comuna = ( isset($_REQUEST['comuna']) ) ? $_REQUEST['comuna'] : "" ;
	$lugar = ( isset($_REQUEST['lugar']) ) ? $_REQUEST['lugar'] : "" ;
	$elemento = ( isset($_REQUEST['elemento']) ) ? $_REQUEST['elemento'] : "" ;
	
	# DATOS PARA TRAMOS 
	$ubicacion = ( isset($_REQUEST['sala']) ) ? $_REQUEST['sala'] : "" ;
	$planned_aux = ( isset($_REQUEST['planned_aux']) ) ? $_REQUEST['planned_aux'] : "" ;
	$modalidad = ( isset($_REQUEST['modalidad']) ) ? $_REQUEST['modalidad'] : "" ;
	
	# INGRESAR TP 
	$fullTitulo = ( isset($_REQUEST['fullTitulo']) ) ? addslashes($_REQUEST['fullTitulo']) : "" ;
	
	$user = $cCfn->getUser();
	
	$id_registro = ( isset($_REQUEST['id_reg']) ) ? $_REQUEST['id_reg'] : "" ;

	$planned = ( isset($_REQUEST['planned']) ) ? $_REQUEST['planned'] : "" ;
	$date = ( isset($_REQUEST['date']) ) ? $_REQUEST['date'] : "" ;
	$time = ( isset($_REQUEST['time']) ) ? $_REQUEST['time'] : "" ;


	
	$cCfn->my_log("[". $file ."] INICIA [Opcion $ops]" );
	
	
	switch( $ops ){
		case 1:{
			$select=null;
			$elementos=$cCfn->getElementos($s_ao,$s_srv);
			$select = "<select id='s_elemento' name='s_elemento' onChange='update_tipoTrabajo()' >";
			$select.= "<option value='any' selected >(Sin elemento)</option>";
			for( $e=0; $e<sizeof($elementos); $e++ ){
				$select.="<option value='".$elementos[$e]['SUBELEMENTOS']."' >".$elementos[$e]['SUBELEMENTOS']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 2:{
			$select=null;
			$params = [
				":area" => $s_ao
			];
			$sql = $cCfn->getQuery("qry_tipotrabajo", $params);
			$servicio=( $s_srv !== "any" ) ? "AND ELEMENTOS='".$s_srv."' " : "" ;
			$elemento=( $elem !== "any" ) ? "AND SUBELEMENTOS='".$elem."' " : "" ;
			$sql.=$servicio.$elemento."AND FLAG=0 AND HABILITADO='SI' GROUP BY TIPO,ID ORDER BY TIPO ASC ";
			
			# EJECUTAMOS LA QUERY ARMADA 
			$res = $cCfn->exeQuery($sql, $cCfn->getConnBD());
			
			$select = "<select id='s_tipoTrabajo' name='s_tipoTrabajo' onChange='update_tipoTarea()' >";
			$select.= "<option value='any' selected >(Sin tipo de trabajo)</option>";
			for( $e=0; $e<sizeof($res); $e++ ){
				$select.="<option value='".$res[$e]['ID']."' >".$res[$e]['TIPO']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 3:{
			$select=null;
			$tipoIngreso=$cCfn->getTipoIngreso();
			$select = "<select id='s_tipoIngreso' name='s_tipoIngreso' onChange='validaTitulo()' >";
			$select.= "<option value='' selected >(Sin Tipo de ingreso)</option>";
			for( $e=0; $e<sizeof($tipoIngreso); $e++ ){
				$select.="<option value='".$tipoIngreso[$e]['ID']."' >".$tipoIngreso[$e]['TIPO_INGRESO']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 4:{
			$zona=$cCfn->comprobarZona($s_ao,$tipoTrabajo);
			$params = [
				":area" => $s_ao
			];
			$sql = $cCfn->getQuery("qry_valTitulo", $params);
			$res = $cCfn->exeQuery($sql, $cCfn->getConnBD());
			$clasifTp = $res[0]['CLASIFICACION'];
			$origenTp = $res[0]['ORIGEN'];
			$areaTp = substr($clasifTp,0,3);
			$pre_titulo="WO.$clasifTp.$origenTp";
			if( $s_srv === "Energia y Clima" ) $zona="NO";
			
			echo "<input type='text' id='txtTitulo' name='txtTitulo' onChange='update_region();' />\n
				<input type='hidden' id='pre_titulo' name='pre_titulo' value='$pre_titulo' />\n
				<input type='hidden' id='zona' name='zona' value='$zona' />\n
				<input type='hidden' id='areaorigen' name='areaorigen' value='$s_ao' />\n";
			
			break;
		}
		case 5:{ # COMUNAS 
			$select=null;
			$comunas=$cCfn->getComunas($region);
			$select = "<select id='s_comunas' name='s_comunas' onChange='update_lugar();' >";
			$select.= "<option value='any' selected >(Sin Comuna)</option>";
			for( $e=0; $e<sizeof($comunas); $e++ ){
				$select.="<option value='".$comunas[$e]['ID']."' >".$comunas[$e]['COMUNA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 55:{ # COMUNAS EDITAR
			$select=null;
			$comunas=$cCfn->getComunas($region);
			$select = "<select id='edits_comunas' name='edits_comunas' onChange='update_lugar_edit();' >";
			$select.= "<option value='any' selected >(Sin Comuna)</option>";
			for( $e=0; $e<sizeof($comunas); $e++ ){
				$select.="<option value='".$comunas[$e]['ID']."' >".$comunas[$e]['COMUNA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 6:{ # LUGAR 
			$select=null;
			$arr=array();
			$select = "<select id='s_lugar' name='s_lugar' onChange='update_nomLugar();' >";
			if( !empty($comuna) ){
				$select.= "<option value='' selected >(Sin lugar)</option>";
				$lugar=$cCfn->getLugares($region,$comuna);
				for( $e=0; $e<sizeof($lugar); $e++ ){
					$select.="<option value='".$lugar[$e]['LUGAR']."' >".$lugar[$e]['LUGAR']."</option>";
					array_push($arr,$lugar[$e]['LUGAR']);
				}
				if( !in_array("Sitios",$arr) ){
					$sites = $cCfn->getSites($region,$comuna);
					if( !empty($sites) ){
						$select.="<option value='Sitios' >Sitios</option>";
					}
				}
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 66:{ # LUGAR EDITAR 
			$select=null;
			$arr=array();
			$select = "<select id='edits_lugar' name='edits_lugar' onChange='update_nomLugar_edit();' >";
			if( !empty($comuna) ){
				$select.= "<option value='' selected >(Sin lugar)</option>";
				$lugar=$cCfn->getLugares($region,$comuna);
				for( $e=0; $e<sizeof($lugar); $e++ ){
					$select.="<option value='".$lugar[$e]['LUGAR']."' >".$lugar[$e]['LUGAR']."</option>";
					array_push($arr,$lugar[$e]['LUGAR']);
				}
				if( !in_array("Sitios",$arr) ){
					$sites = $cCfn->getSites($region,$comuna);
					if( !empty($sites) ){
						$select.="<option value='Sitios' >Sitios</option>";
					}
				}
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 7:{
			$input=null;
			if( $lugar == "Sitios" ){
				$nomSite = $cCfn->getNomsites($region,$comuna);
				$input="<div class='contenedorSeleccionmultiple'>";
				for( $e=0; $e<sizeof($nomSite); $e++ ){
					$input.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick='detectarSitios(true)'  value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
				}
				$input.="</div>";
			}
			else{
				$input="<select id='s_nombreelemento' name='s_nombreelemento' onChange='update_ubicacion()' >";
				$input.="<option value=''>Seleccione Nombre</option>";
				$nombres = $cCfn->getNoms($region,$comuna,$lugar);
				for( $e=0; $e<sizeof($nombres); $e++ ){
					$input.="<option value='".$nombres[$e]['NOMBRE']."' >".$nombres[$e]['NOMBRE']."</option>";
				}
				$input.= "</select>";
			}
			echo $input;
			break;
		}
		case 77:{ # NOM LUGAR EDITAR 
			$input=null;
			if( $lugar == "Sitios" ){
				$nomSite = $cCfn->getNomsites($region,$comuna);
				$input="<div class='contenedorSeleccionmultiple'>";
				for( $e=0; $e<sizeof($nomSite); $e++ ){
					$input.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick=''  value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
				}
				$input.="</div>";
			}
			else{
				$input="<select id='edits_nombreelemento' name='edits_nombreelemento' onChange='update_ubicacion_edit()' >";
				$input.="<option value=''>Seleccione Nombre</option>";
				$nombres = $cCfn->getNoms($region,$comuna,$lugar);
				for( $e=0; $e<sizeof($nombres); $e++ ){
					$input.="<option value='".$nombres[$e]['NOMBRE']."' >".$nombres[$e]['NOMBRE']."</option>";
				}
				$input.= "</select>";
			}
			echo $input;
			break;
		}
		case 8:{ # SALA 
			$select=null;
			$select = "<select id='s_sala' name='s_sala' onChange='confirmarBoton();' >";
			$select.= "<option value='any' selected >(Sin sala)</option>";
			$ubi = $cCfn->getUbicacion($region,$comuna,$lugar,$elemento);
			for( $e=0; $e<sizeof($ubi); $e++ ){
				$select.="<option value='".$ubi[$e]['SALA']."' >".$ubi[$e]['SALA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 88:{ # SALA 
			$select=null;
			$select = "<select id='edits_sala' name='edits_sala' onChange='' >";
			$select.= "<option value='any' selected >(Sin sala)</option>";
			$ubi = $cCfn->getUbicacion($region,$comuna,$lugar,$elemento);
			for( $e=0; $e<sizeof($ubi); $e++ ){
				$select.="<option value='".$ubi[$e]['SALA']."' >".$ubi[$e]['SALA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 9:{ # INGRESAR TRAMO 
			$table=null;
			if( !empty($ubicacion) ){
				$id=$cCfn->getSala($region,$comuna,$lugar,$elemento,$ubicacion);
				$id_elemento=$id[0]['ID_TP_ELEMENTO'];
			}else{
				$id_elemento="NULL";
			}
			$params = [
				":planned_aux" => $planned_aux, 
				":region" => $region, 
				":comuna" => $comuna, 
				":lugar" => $lugar, 
				":elemento" => $elemento, 
				":sala" => $ubicacion, 
				":id_elemento" => $id_elemento, 
				":modalidadTrabajo" => $modalidad
			];
			# INSERTAMOS DATOS 
			$sql=$cCfn->getQuery("insert_tmp_tp_data", $params);
			$affected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			$res=$cCfn->getNewTramo($planned_aux);
			for( $r=0; $r<sizeof($res); $r++ ){
				$table.="<tr>";
				$table.="<td>".$res[$r]['id']."</td>";
				$table.="<td>".$res[$r]['REGION_NOMBRE']."</td>";
				$table.="<td>".$res[$r]['COMUNA']."</td>";
				$table.="<td>".$res[$r]['lugar']."</td>";
				$table.="<td>".$res[$r]['nombre_lugar']."</td>";
				$table.="<td>".$res[$r]['sala']."</td>";
				$table.="<td><button type='button' class='btn btn-xs btn-link' onClick='eliminaTramo(".$res[$r]['id'].", ".$planned_aux.")'>Eliminar</button></td><td><button type='button' class='btn btn-xs btn-link' onClick='editaTramo(".$res[$r]['id'].",".$planned_aux.")'>Editar</button></td>";
				$table.="</tr>";
			}
			
			echo $table;
			break;
		}
		case 10:{ # ELIMINAR TRAMO 
			$table=null;
			$params = [
				":tp_aux" => $planned_aux
			];
			$sql=$cCfn->getQuery("qry_tpaux", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			if( !empty($row[0]['N']) ){
				$params = [
					":id" => $id_registro,
					":planned_aux" => $planned_aux
				];
				$sql=$cCfn->getQuery("delete_tmp_tp_data", $params);
				$delAffected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
				if( $delAffected['affected'] === 1 ){
					# LIMPIAMOS LA TABLA 
					$res=$cCfn->getNewTramo($planned_aux);
					if( empty($res) ){
						echo 1;
					}
					else{
						for( $r=0; $r<sizeof($res); $r++ ){
							$table.="<tr>";
							$table.="<td>".$res[$r]['id']."</td>";
							$table.="<td>".$res[$r]['REGION_NOMBRE']."</td>";
							$table.="<td>".$res[$r]['COMUNA']."</td>";
							$table.="<td>".$res[$r]['lugar']."</td>";
							$table.="<td>".$res[$r]['nombre_lugar']."</td>";
							$table.="<td>".$res[$r]['sala']."</td>";
							$table.="<td><button type='button' class='btn btn-xs btn-link' onClick='eliminaTramo(".$res[$r]['id'].", ".$planned_aux.")'>Eliminar</button></td><td><button type='button' class='btn btn-xs btn-link' onClick='editaTramo(".$res[$r]['id'].",".$planned_aux.")'>Editar</button></td>";
							$table.="</tr>";
						}
						echo $table;
					}
				}
			}else{
				echo "No existe ningun registro con el id:".$id_registro;
			}
			
			break;
		}
		case 11:{ # EDITAR TRAMO 
			$modal=null;
			
			$params = [
				":id_reg" => $id_registro
			];
			# REGISTRO A EDITAR 
			$sql=$cCfn->getQuery("qry_editaTramo", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			$arrRegion = $cCfn->getRegiones();
			$arrComunas= $cCfn->getComunas($row[0]['region']);
			$arrLugar=$cCfn->getLugares($row[0]['region'],$row[0]['comuna']);
			
			# MODAL HEADER 
			$modal.="<div class='modal-header'>
				<h4 class='modal-title'>Editar registro ".$id_registro."</h4>
				<input type='hidden' id='id_registro' name='id_registro' value='".$id_registro."'>
				<input type='hidden' id='planned_aux' name='planned_aux' value='".$planned_aux."'>
			</div>";
			
			# MODAL BODY 
			## REGIONES 
			$modal.="<div class='modal-body'>
						<div class='row'>
							<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Regi&oacute;n</div>
								<div class='col-sm-4 col-md-4 col-lg-4' id='edit_regiones' style='text-align:left; font-size:small;' >
									<select id='edits_regiones' name='edits_regiones' onChange='update_comunas_edit();' >
										<option value='any' >(Sin Region)</option> ";
			for( $r=0; $r<sizeof($arrRegion); $r++ ){
				if( $row[0]['region'] === $arrRegion[$r]['ID_REGION'] ){
					$modal.= "<option value='".$arrRegion[$r]['ID_REGION']."' selected>".$arrRegion[$r]['REGION']."-".$arrRegion[$r]['REGION_NOMBRE']."</option>";
				}else{
					$modal.= "<option value='".$arrRegion[$r]['ID_REGION']."' >".$arrRegion[$r]['REGION']."-".$arrRegion[$r]['REGION_NOMBRE']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			## COMUNAS 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Comuna</div>
							<div class='col-sm-4 col-md-4 col-lg-4' id='edit_comunas' style='text-align:left; font-size:small;' >
								<select id='edits_comunas' name='edits_comunas' onChange='update_lugar_edit();' >
									<option value='any' >(Sin Comuna)</option> ";
			for( $r=0; $r<sizeof($arrComunas); $r++ ){
				if( $row[0]['comuna'] === $arrComunas[$r]['ID'] ){
					$modal.= "<option value='".$arrComunas[$r]['ID']."' selected>".$arrComunas[$r]['COMUNA']."</option>";
				}else{
					$modal.= "<option value='".$arrComunas[$r]['ID']."' >".$arrComunas[$r]['COMUNA']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			## LUGAR 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Lugar</div>
							<div class='col-sm-4 col-md-4 col-lg-4' id='edit_lugar' style='text-align:left; font-size:small;' >
								<select id='edits_lugar' name='edits_lugar' onChange='update_nomLugar_edit();' >
									<option value='any' >(Sin Comuna)</option> ";
			for( $r=0; $r<sizeof($arrLugar); $r++ ){
				if( $row[0]['lugar'] === $arrLugar[$r]['LUGAR'] ){
					$modal.= "<option value='".$arrLugar[$r]['LUGAR']."' selected>".$arrLugar[$r]['LUGAR']."</option>";
				}else{
					$modal.= "<option value='".$arrLugar[$r]['LUGAR']."' >".$arrLugar[$r]['LUGAR']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			## NOM LUGAR 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Nombre Lugar</div>
							<div class='col-sm-8 col-md-8 col-lg-8' id='edit_nomlugar' style='text-align:left; font-size:small;' > ";
			if( $row[0]['lugar'] === "Sitios" ){
				$nomSite = $cCfn->getNomsites($row[0]['region'],$row[0]['comuna']);
				$modal.="<div class='contenedorSeleccionmultiple'>";
				$site=explode('|',$row[0]['nombre_lugar']);
				for( $e=0; $e<sizeof($nomSite); $e++ ){
					if( !empty($site[$e]) && $nomSite[$e]['SITIO'] === $site[$e] ){
						$modal.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick='' checked value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
					}else{
						$modal.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick=''  value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
					}
				}
				$modal.="</div></div>";
			}
			else{
				$modal.="<select id='edits_nombreelemento' name='edits_nombreelemento' onChange='update_ubicacion_edit()' >";
				$modal.="<option value=''>Seleccione Nombre</option>";
				$nombres = $cCfn->getNoms($row[0]['region'],$row[0]['comuna'],$row[0]['lugar']);
				for( $e=0; $e<sizeof($nombres); $e++ ){
					if( $row[0]['nombre_lugar'] === $nombres[$e]['NOMBRE'] ){
						$modal.="<option value='".$nombres[$e]['NOMBRE']."' selected>".$nombres[$e]['NOMBRE']."</option>";
					}else{
						$modal.="<option value='".$nombres[$e]['NOMBRE']."' >".$nombres[$e]['NOMBRE']."</option>";
					}
				}
				//$modal.= "</select>";
				$modal.="</select></div>";
			}
			$modal.="</div>"; # FIN ROW NOM LUGAR 
			
			## UBICACION 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Ubicaci&oacute;n</div>
							<div class='col-sm-4 col-md-4 col-lg-4' id='edit_ubicacion' style='text-align:left; font-size:small;' >
								<select id='edits_sala' name='edits_sala' onChange='' >
									<option value='any' >(Sin Sala)</option> ";
			$ubi = $cCfn->getUbicacion($row[0]['region'],$row[0]['comuna'],$row[0]['lugar'],$row[0]['nombre_lugar']);
			for( $e=0; $e<sizeof($ubi); $e++ ){
				if( $ubi[$e]['SALA'] === $row[0]['sala'] ){
					$modal.="<option value='".$ubi[$e]['SALA']."' selected>".$ubi[$e]['SALA']."</option>";
				}else{
					$modal.="<option value='".$ubi[$e]['SALA']."' >".$ubi[$e]['SALA']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			$modal.="</div>"; # FINAL DIV MODAL-BODY 
			
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cancelar</button>
				<button type='button' class='btn btn-default btn-sm' onClick='guardarTramo()' >Guardar</button>
			</div>";
			
			echo $modal;
			break;
		}
		case 12:{ # CREAR TP 
			$correlativo=null;
			if( $elemento === "Energia y Clima" ){
				$correlativo=1;
			}
			
			$data = [
				"correlativo" => $correlativo,
				"fullTitulo" => $fullTitulo,
				"planned_aux" => $planned_aux,
				"tipoTrabajo" => $tipoTrabajo,
				"tipoingreso" => $tipoingreso
			];
			
			$id_tp = $cCfn->crearTP($data); # RECUPERO EL ID DEL TP y SI TIENE HIJOS 
			
			print_r($id_tp);
			
			break;
		}
		
		case 13:{ # GUARDAR TRAMO EDITADO 
			$table=null;
			$arrRes=array();
			
			if( !empty($ubicacion) ){
				$id=$cCfn->getSala($region,$comuna,$lugar,$elemento,$ubicacion);
				$id_elemento=$id[0]['ID_TP_ELEMENTO'];
			}else{
				$id_elemento="NULL";
			}
			
			$params = [
				":id_reg" => $id_registro,
			];
			# TRAEMOS LOS DATOS 
			$sql=$cCfn->getQuery("qry_editaTramo", $params);
			$objTramo = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			$paramsOrig = [
				":id_reg" => $id_registro,
				":region" => $objTramo[0]['region'], 
				":comuna" => $objTramo[0]['comuna'], 
				":lugar" => $objTramo[0]['lugar'], 
				":nom_lugar" => $objTramo[0]['nombre_lugar'], 
				":ubicacion" => $objTramo[0]['sala'],
				":id_elemento" => $id_elemento
			];
			
			$paramsUpdat = [
				":id_reg" => $id_registro,
				":region" => $region, 
				":comuna" => $comuna, 
				":lugar" => $lugar, 
				":nom_lugar" => $elemento, 
				":ubicacion" => $ubicacion,
				":id_elemento" => $id_elemento
			];
			
			$arrRes=array_diff($paramsUpdat,$paramsOrig);
			
			if( !empty($arrRes) ){
				# ACTUALIZAMOS REGISTRO 
				$sql=$cCfn->getQuery("update_tramo", $paramsUpdat);
				$updateAffected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			}
			
			# TRAEMOS LOS DATOS 
			$res=$cCfn->getNewTramo($planned_aux);
			if( empty($res) ){
				echo 1;
			}
			else{
				for( $r=0; $r<sizeof($res); $r++ ){
					$table.="<tr>";
					$table.="<td>".$res[$r]['id']."</td>";
					$table.="<td>".$res[$r]['REGION_NOMBRE']."</td>";
					$table.="<td>".$res[$r]['COMUNA']."</td>";
					$table.="<td>".$res[$r]['lugar']."</td>";
					$table.="<td>".$res[$r]['nombre_lugar']."</td>";
					$table.="<td>".$res[$r]['sala']."</td>";
					$table.="<td><button type='button' class='btn btn-xs btn-link' onClick='eliminaTramo(".$res[$r]['id'].", ".$planned_aux.")'>Eliminar</button></td><td><button type='button' class='btn btn-xs btn-link' onClick='editaTramo(".$res[$r]['id'].",".$planned_aux.")'>Editar</button></td>";
					$table.="</tr>";
				}
				echo $table;
			}
			break;
		}

		case 14:{ # 
			$modal=null;
			
			$params = [
				":tp" => $planned
			];
			# 
			$sql=$cCfn->getQuery("qry_detalleTp", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			if( empty($row[0]['TP_FECHA_SOLICITADA']) ){
				$title = "Ingresar";
				$prebody = "<h5>Seleccione los datos a continuaci&oacute;n</h5>";
			}
			else{
				$title = "Modificar";
				$prebody = "<h4>Fecha actual</h4>
					<h5 style='text-align:center;'>".$row[0]['TP_FECHA_SOLICITADA']."</h5>";
			}
			
			# MODAL HEADER 
			$modal.="<div class='modal-header'>
				<h4 class='modal-title'>".$title." fecha solicitada para ejecuci&oacute;n</h4>
				<h4 class='modal-title'>Para TP ".$planned." </h4>
				<input type='hidden' id='planned' name='planned' value='".$planned."'>
			</div>";

			# MODAL BODY 
			$modal.="<div class='modal-body'>
				<div class='row'>
					<div class='col-sm-6 col-md-6 col-lg-6' >
						<div class='form-group'>
							".$prebody."
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-4 col-md-4 col-lg-4' style='text-align:left;'  >
						<div class='form-group'>
							<label for='date'>Fecha</label>
							<input type='text' class='form-control' id='date'>
						</div>
						<div class='form-group'>
							<label for='time'>Hora</label>
							<input type='text' class='form-control' id='time'>
						</div>
					</div>
				</div>
			</div>";

			# MODAL FOOTER 
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cancelar</button>
				<button type='button' class='btn btn-default btn-sm' onClick='saveFechaSolicEjecTP()' >Guardar</button>
			</div>";

			echo $modal;
			break;
		}

		case 15:{
			// 
			$params = [
				":tp" => $planned
			];
			# TRAEMOS EL REGISTRO DE TP_DATA 
			$sql=$cCfn->getQuery("qry_tpdata", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());

			$flagF=null; $flagH=null;
			# Validamos fecha y hora 
			if( $cCfn->validarFecha($date) ){
				$flagF=true;
			}
			if( $cCfn->validarHora($time) ){ 
				$flagH=true;
			}

			$dateFinal=null;
			if( $flagF && $flagH ){
				$datetime = new \DateTime("$date $time");
				$dateFinal = $datetime->format('Y-m-d H:i');
			}

			$updateAffected=null;
			if( !empty($dateFinal) ){
				$params = [
					":tp" => $planned,
					":fechaFinal" => $dateFinal,
					":reg" => $row[0]["ID"]
				];
				$sql=$cCfn->getQuery("update_tpdata", $params);
				$updateAffected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			}
			echo $updateAffected['affected'];

			break;
		}
		
		default:{
			
		}
	}
	
	
=======
<?php
	require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
	
	$file=basename(__FILE__);
	# VARIABLE PARA SWITCH 
	$ops = $_REQUEST['opcion'];
	
	# VARIABLES PARA LOS CASOS, PRIMEROS DATOS 
	$s_ao = ( isset($_REQUEST['s_areaorigen']) ) ? $_REQUEST['s_areaorigen'] : "" ;
	$s_srv = ( isset($_REQUEST['servicio']) ) ? $_REQUEST['servicio'] : "" ;
	$elem = ( isset($_REQUEST['elemento']) ) ? $_REQUEST['elemento'] : "" ;
	$tipoTrabajo = ( isset($_REQUEST['tipotrabajo']) ) ? $_REQUEST['tipotrabajo'] : "" ;
	$tipoingreso = ( isset($_REQUEST['tipoingreso']) ) ? $_REQUEST['tipoingreso'] : "" ;
	$region = ( isset($_REQUEST['region']) ) ? $_REQUEST['region'] : "" ;
	$comuna = ( isset($_REQUEST['comuna']) ) ? $_REQUEST['comuna'] : "" ;
	$lugar = ( isset($_REQUEST['lugar']) ) ? $_REQUEST['lugar'] : "" ;
	$elemento = ( isset($_REQUEST['elemento']) ) ? $_REQUEST['elemento'] : "" ;
	
	# DATOS PARA TRAMOS 
	$ubicacion = ( isset($_REQUEST['sala']) ) ? $_REQUEST['sala'] : "" ;
	$planned_aux = ( isset($_REQUEST['planned_aux']) ) ? $_REQUEST['planned_aux'] : "" ;
	$modalidad = ( isset($_REQUEST['modalidad']) ) ? $_REQUEST['modalidad'] : "" ;
	
	# INGRESAR TP 
	$fullTitulo = ( isset($_REQUEST['fullTitulo']) ) ? addslashes($_REQUEST['fullTitulo']) : "" ;
	
	$user = $cCfn->getUser();
	
	$id_registro = ( isset($_REQUEST['id_reg']) ) ? $_REQUEST['id_reg'] : "" ;

	$planned = ( isset($_REQUEST['planned']) ) ? $_REQUEST['planned'] : "" ;
	$date = ( isset($_REQUEST['date']) ) ? $_REQUEST['date'] : "" ;
	$time = ( isset($_REQUEST['time']) ) ? $_REQUEST['time'] : "" ;


	
	$cCfn->my_log("[". $file ."] INICIA [Opcion $ops]" );
	
	
	switch( $ops ){
		case 1:{
			$select=null;
			$elementos=$cCfn->getElementos($s_ao,$s_srv);
			$select = "<select id='s_elemento' name='s_elemento' onChange='update_tipoTrabajo()' >";
			$select.= "<option value='any' selected >(Sin elemento)</option>";
			for( $e=0; $e<sizeof($elementos); $e++ ){
				$select.="<option value='".$elementos[$e]['SUBELEMENTOS']."' >".$elementos[$e]['SUBELEMENTOS']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 2:{
			$select=null;
			$params = [
				":area" => $s_ao
			];
			$sql = $cCfn->getQuery("qry_tipotrabajo", $params);
			$servicio=( $s_srv !== "any" ) ? "AND ELEMENTOS='".$s_srv."' " : "" ;
			$elemento=( $elem !== "any" ) ? "AND SUBELEMENTOS='".$elem."' " : "" ;
			$sql.=$servicio.$elemento."AND FLAG=0 AND HABILITADO='SI' GROUP BY TIPO,ID ORDER BY TIPO ASC ";
			
			# EJECUTAMOS LA QUERY ARMADA 
			$res = $cCfn->exeQuery($sql, $cCfn->getConnBD());
			
			$select = "<select id='s_tipoTrabajo' name='s_tipoTrabajo' onChange='update_tipoTarea()' >";
			$select.= "<option value='any' selected >(Sin tipo de trabajo)</option>";
			for( $e=0; $e<sizeof($res); $e++ ){
				$select.="<option value='".$res[$e]['ID']."' >".$res[$e]['TIPO']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 3:{
			$select=null;
			$tipoIngreso=$cCfn->getTipoIngreso();
			$select = "<select id='s_tipoIngreso' name='s_tipoIngreso' onChange='validaTitulo()' >";
			$select.= "<option value='' selected >(Sin Tipo de ingreso)</option>";
			for( $e=0; $e<sizeof($tipoIngreso); $e++ ){
				$select.="<option value='".$tipoIngreso[$e]['ID']."' >".$tipoIngreso[$e]['TIPO_INGRESO']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 4:{
			$zona=$cCfn->comprobarZona($s_ao,$tipoTrabajo);
			$params = [
				":area" => $s_ao
			];
			$sql = $cCfn->getQuery("qry_valTitulo", $params);
			$res = $cCfn->exeQuery($sql, $cCfn->getConnBD());
			$clasifTp = $res[0]['CLASIFICACION'];
			$origenTp = $res[0]['ORIGEN'];
			$areaTp = substr($clasifTp,0,3);
			$pre_titulo="WO.$clasifTp.$origenTp";
			if( $s_srv === "Energia y Clima" ) $zona="NO";
			
			echo "<input type='text' id='txtTitulo' name='txtTitulo' onChange='update_region();' />\n
				<input type='hidden' id='pre_titulo' name='pre_titulo' value='$pre_titulo' />\n
				<input type='hidden' id='zona' name='zona' value='$zona' />\n
				<input type='hidden' id='areaorigen' name='areaorigen' value='$s_ao' />\n";
			
			break;
		}
		case 5:{ # COMUNAS 
			$select=null;
			$comunas=$cCfn->getComunas($region);
			$select = "<select id='s_comunas' name='s_comunas' onChange='update_lugar();' >";
			$select.= "<option value='any' selected >(Sin Comuna)</option>";
			for( $e=0; $e<sizeof($comunas); $e++ ){
				$select.="<option value='".$comunas[$e]['ID']."' >".$comunas[$e]['COMUNA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 55:{ # COMUNAS EDITAR
			$select=null;
			$comunas=$cCfn->getComunas($region);
			$select = "<select id='edits_comunas' name='edits_comunas' onChange='update_lugar_edit();' >";
			$select.= "<option value='any' selected >(Sin Comuna)</option>";
			for( $e=0; $e<sizeof($comunas); $e++ ){
				$select.="<option value='".$comunas[$e]['ID']."' >".$comunas[$e]['COMUNA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 6:{ # LUGAR 
			$select=null;
			$arr=array();
			$select = "<select id='s_lugar' name='s_lugar' onChange='update_nomLugar();' >";
			if( !empty($comuna) ){
				$select.= "<option value='' selected >(Sin lugar)</option>";
				$lugar=$cCfn->getLugares($region,$comuna);
				for( $e=0; $e<sizeof($lugar); $e++ ){
					$select.="<option value='".$lugar[$e]['LUGAR']."' >".$lugar[$e]['LUGAR']."</option>";
					array_push($arr,$lugar[$e]['LUGAR']);
				}
				if( !in_array("Sitios",$arr) ){
					$sites = $cCfn->getSites($region,$comuna);
					if( !empty($sites) ){
						$select.="<option value='Sitios' >Sitios</option>";
					}
				}
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 66:{ # LUGAR EDITAR 
			$select=null;
			$arr=array();
			$select = "<select id='edits_lugar' name='edits_lugar' onChange='update_nomLugar_edit();' >";
			if( !empty($comuna) ){
				$select.= "<option value='' selected >(Sin lugar)</option>";
				$lugar=$cCfn->getLugares($region,$comuna);
				for( $e=0; $e<sizeof($lugar); $e++ ){
					$select.="<option value='".$lugar[$e]['LUGAR']."' >".$lugar[$e]['LUGAR']."</option>";
					array_push($arr,$lugar[$e]['LUGAR']);
				}
				if( !in_array("Sitios",$arr) ){
					$sites = $cCfn->getSites($region,$comuna);
					if( !empty($sites) ){
						$select.="<option value='Sitios' >Sitios</option>";
					}
				}
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 7:{
			$input=null;
			if( $lugar == "Sitios" ){
				$nomSite = $cCfn->getNomsites($region,$comuna);
				$input="<div class='contenedorSeleccionmultiple'>";
				for( $e=0; $e<sizeof($nomSite); $e++ ){
					$input.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick='detectarSitios(true)'  value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
				}
				$input.="</div>";
			}
			else{
				$input="<select id='s_nombreelemento' name='s_nombreelemento' onChange='update_ubicacion()' >";
				$input.="<option value=''>Seleccione Nombre</option>";
				$nombres = $cCfn->getNoms($region,$comuna,$lugar);
				for( $e=0; $e<sizeof($nombres); $e++ ){
					$input.="<option value='".$nombres[$e]['NOMBRE']."' >".$nombres[$e]['NOMBRE']."</option>";
				}
				$input.= "</select>";
			}
			echo $input;
			break;
		}
		case 77:{ # NOM LUGAR EDITAR 
			$input=null;
			if( $lugar == "Sitios" ){
				$nomSite = $cCfn->getNomsites($region,$comuna);
				$input="<div class='contenedorSeleccionmultiple'>";
				for( $e=0; $e<sizeof($nomSite); $e++ ){
					$input.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick=''  value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
				}
				$input.="</div>";
			}
			else{
				$input="<select id='edits_nombreelemento' name='edits_nombreelemento' onChange='update_ubicacion_edit()' >";
				$input.="<option value=''>Seleccione Nombre</option>";
				$nombres = $cCfn->getNoms($region,$comuna,$lugar);
				for( $e=0; $e<sizeof($nombres); $e++ ){
					$input.="<option value='".$nombres[$e]['NOMBRE']."' >".$nombres[$e]['NOMBRE']."</option>";
				}
				$input.= "</select>";
			}
			echo $input;
			break;
		}
		case 8:{ # SALA 
			$select=null;
			$select = "<select id='s_sala' name='s_sala' onChange='confirmarBoton();' >";
			$select.= "<option value='any' selected >(Sin sala)</option>";
			$ubi = $cCfn->getUbicacion($region,$comuna,$lugar,$elemento);
			for( $e=0; $e<sizeof($ubi); $e++ ){
				$select.="<option value='".$ubi[$e]['SALA']."' >".$ubi[$e]['SALA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 88:{ # SALA 
			$select=null;
			$select = "<select id='edits_sala' name='edits_sala' onChange='' >";
			$select.= "<option value='any' selected >(Sin sala)</option>";
			$ubi = $cCfn->getUbicacion($region,$comuna,$lugar,$elemento);
			for( $e=0; $e<sizeof($ubi); $e++ ){
				$select.="<option value='".$ubi[$e]['SALA']."' >".$ubi[$e]['SALA']."</option>";
			}
			$select.= "</select>";
			echo $select;
			break;
		}
		case 9:{ # INGRESAR TRAMO 
			$table=null;
			if( !empty($ubicacion) ){
				$id=$cCfn->getSala($region,$comuna,$lugar,$elemento,$ubicacion);
				$id_elemento=$id[0]['ID_TP_ELEMENTO'];
			}else{
				$id_elemento="NULL";
			}
			$params = [
				":planned_aux" => $planned_aux, 
				":region" => $region, 
				":comuna" => $comuna, 
				":lugar" => $lugar, 
				":elemento" => $elemento, 
				":sala" => $ubicacion, 
				":id_elemento" => $id_elemento, 
				":modalidadTrabajo" => $modalidad
			];
			# INSERTAMOS DATOS 
			$sql=$cCfn->getQuery("insert_tmp_tp_data", $params);
			$affected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			$res=$cCfn->getNewTramo($planned_aux);
			for( $r=0; $r<sizeof($res); $r++ ){
				$table.="<tr>";
				$table.="<td>".$res[$r]['id']."</td>";
				$table.="<td>".$res[$r]['REGION_NOMBRE']."</td>";
				$table.="<td>".$res[$r]['COMUNA']."</td>";
				$table.="<td>".$res[$r]['lugar']."</td>";
				$table.="<td>".$res[$r]['nombre_lugar']."</td>";
				$table.="<td>".$res[$r]['sala']."</td>";
				$table.="<td><button type='button' class='btn btn-xs btn-link' onClick='eliminaTramo(".$res[$r]['id'].", ".$planned_aux.")'>Eliminar</button></td><td><button type='button' class='btn btn-xs btn-link' onClick='editaTramo(".$res[$r]['id'].",".$planned_aux.")'>Editar</button></td>";
				$table.="</tr>";
			}
			
			echo $table;
			break;
		}
		case 10:{ # ELIMINAR TRAMO 
			$table=null;
			$params = [
				":tp_aux" => $planned_aux
			];
			$sql=$cCfn->getQuery("qry_tpaux", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			if( !empty($row[0]['N']) ){
				$params = [
					":id" => $id_registro,
					":planned_aux" => $planned_aux
				];
				$sql=$cCfn->getQuery("delete_tmp_tp_data", $params);
				$delAffected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
				if( $delAffected['affected'] === 1 ){
					# LIMPIAMOS LA TABLA 
					$res=$cCfn->getNewTramo($planned_aux);
					if( empty($res) ){
						echo 1;
					}
					else{
						for( $r=0; $r<sizeof($res); $r++ ){
							$table.="<tr>";
							$table.="<td>".$res[$r]['id']."</td>";
							$table.="<td>".$res[$r]['REGION_NOMBRE']."</td>";
							$table.="<td>".$res[$r]['COMUNA']."</td>";
							$table.="<td>".$res[$r]['lugar']."</td>";
							$table.="<td>".$res[$r]['nombre_lugar']."</td>";
							$table.="<td>".$res[$r]['sala']."</td>";
							$table.="<td><button type='button' class='btn btn-xs btn-link' onClick='eliminaTramo(".$res[$r]['id'].", ".$planned_aux.")'>Eliminar</button></td><td><button type='button' class='btn btn-xs btn-link' onClick='editaTramo(".$res[$r]['id'].",".$planned_aux.")'>Editar</button></td>";
							$table.="</tr>";
						}
						echo $table;
					}
				}
			}else{
				echo "No existe ningun registro con el id:".$id_registro;
			}
			
			break;
		}
		case 11:{ # EDITAR TRAMO 
			$modal=null;
			
			$params = [
				":id_reg" => $id_registro
			];
			# REGISTRO A EDITAR 
			$sql=$cCfn->getQuery("qry_editaTramo", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			$arrRegion = $cCfn->getRegiones();
			$arrComunas= $cCfn->getComunas($row[0]['region']);
			$arrLugar=$cCfn->getLugares($row[0]['region'],$row[0]['comuna']);
			
			# MODAL HEADER 
			$modal.="<div class='modal-header'>
				<h4 class='modal-title'>Editar registro ".$id_registro."</h4>
				<input type='hidden' id='id_registro' name='id_registro' value='".$id_registro."'>
				<input type='hidden' id='planned_aux' name='planned_aux' value='".$planned_aux."'>
			</div>";
			
			# MODAL BODY 
			## REGIONES 
			$modal.="<div class='modal-body'>
						<div class='row'>
							<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Regi&oacute;n</div>
								<div class='col-sm-4 col-md-4 col-lg-4' id='edit_regiones' style='text-align:left; font-size:small;' >
									<select id='edits_regiones' name='edits_regiones' onChange='update_comunas_edit();' >
										<option value='any' >(Sin Region)</option> ";
			for( $r=0; $r<sizeof($arrRegion); $r++ ){
				if( $row[0]['region'] === $arrRegion[$r]['ID_REGION'] ){
					$modal.= "<option value='".$arrRegion[$r]['ID_REGION']."' selected>".$arrRegion[$r]['REGION']."-".$arrRegion[$r]['REGION_NOMBRE']."</option>";
				}else{
					$modal.= "<option value='".$arrRegion[$r]['ID_REGION']."' >".$arrRegion[$r]['REGION']."-".$arrRegion[$r]['REGION_NOMBRE']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			## COMUNAS 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Comuna</div>
							<div class='col-sm-4 col-md-4 col-lg-4' id='edit_comunas' style='text-align:left; font-size:small;' >
								<select id='edits_comunas' name='edits_comunas' onChange='update_lugar_edit();' >
									<option value='any' >(Sin Comuna)</option> ";
			for( $r=0; $r<sizeof($arrComunas); $r++ ){
				if( $row[0]['comuna'] === $arrComunas[$r]['ID'] ){
					$modal.= "<option value='".$arrComunas[$r]['ID']."' selected>".$arrComunas[$r]['COMUNA']."</option>";
				}else{
					$modal.= "<option value='".$arrComunas[$r]['ID']."' >".$arrComunas[$r]['COMUNA']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			## LUGAR 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Lugar</div>
							<div class='col-sm-4 col-md-4 col-lg-4' id='edit_lugar' style='text-align:left; font-size:small;' >
								<select id='edits_lugar' name='edits_lugar' onChange='update_nomLugar_edit();' >
									<option value='any' >(Sin Comuna)</option> ";
			for( $r=0; $r<sizeof($arrLugar); $r++ ){
				if( $row[0]['lugar'] === $arrLugar[$r]['LUGAR'] ){
					$modal.= "<option value='".$arrLugar[$r]['LUGAR']."' selected>".$arrLugar[$r]['LUGAR']."</option>";
				}else{
					$modal.= "<option value='".$arrLugar[$r]['LUGAR']."' >".$arrLugar[$r]['LUGAR']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			## NOM LUGAR 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Nombre Lugar</div>
							<div class='col-sm-8 col-md-8 col-lg-8' id='edit_nomlugar' style='text-align:left; font-size:small;' > ";
			if( $row[0]['lugar'] === "Sitios" ){
				$nomSite = $cCfn->getNomsites($row[0]['region'],$row[0]['comuna']);
				$modal.="<div class='contenedorSeleccionmultiple'>";
				$site=explode('|',$row[0]['nombre_lugar']);
				for( $e=0; $e<sizeof($nomSite); $e++ ){
					if( !empty($site[$e]) && $nomSite[$e]['SITIO'] === $site[$e] ){
						$modal.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick='' checked value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
					}else{
						$modal.="<input type='checkbox' id='chkSitio' name='SITIO[]' onclick=''  value='".$nomSite[$e]['SITIO']."' /> ".$nomSite[$e]['SITIO']." - ".$nomSite[$e]['NOMBRE']." <br/> ";
					}
				}
				$modal.="</div></div>";
			}
			else{
				$modal.="<select id='edits_nombreelemento' name='edits_nombreelemento' onChange='update_ubicacion_edit()' >";
				$modal.="<option value=''>Seleccione Nombre</option>";
				$nombres = $cCfn->getNoms($row[0]['region'],$row[0]['comuna'],$row[0]['lugar']);
				for( $e=0; $e<sizeof($nombres); $e++ ){
					if( $row[0]['nombre_lugar'] === $nombres[$e]['NOMBRE'] ){
						$modal.="<option value='".$nombres[$e]['NOMBRE']."' selected>".$nombres[$e]['NOMBRE']."</option>";
					}else{
						$modal.="<option value='".$nombres[$e]['NOMBRE']."' >".$nombres[$e]['NOMBRE']."</option>";
					}
				}
				//$modal.= "</select>";
				$modal.="</select></div>";
			}
			$modal.="</div>"; # FIN ROW NOM LUGAR 
			
			## UBICACION 
			$modal.="<div class='row' style='margin-top: 10px;'>
						<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' >Ubicaci&oacute;n</div>
							<div class='col-sm-4 col-md-4 col-lg-4' id='edit_ubicacion' style='text-align:left; font-size:small;' >
								<select id='edits_sala' name='edits_sala' onChange='' >
									<option value='any' >(Sin Sala)</option> ";
			$ubi = $cCfn->getUbicacion($row[0]['region'],$row[0]['comuna'],$row[0]['lugar'],$row[0]['nombre_lugar']);
			for( $e=0; $e<sizeof($ubi); $e++ ){
				if( $ubi[$e]['SALA'] === $row[0]['sala'] ){
					$modal.="<option value='".$ubi[$e]['SALA']."' selected>".$ubi[$e]['SALA']."</option>";
				}else{
					$modal.="<option value='".$ubi[$e]['SALA']."' >".$ubi[$e]['SALA']."</option>";
				}
			}
			$modal.="</select></div>";
			$modal.="</div>"; # FIN ROW 
			
			$modal.="</div>"; # FINAL DIV MODAL-BODY 
			
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cancelar</button>
				<button type='button' class='btn btn-default btn-sm' onClick='guardarTramo()' >Guardar</button>
			</div>";
			
			echo $modal;
			break;
		}
		case 12:{ # CREAR TP 
			$correlativo=null;
			if( $elemento === "Energia y Clima" ){
				$correlativo=1;
			}
			
			$data = [
				"correlativo" => $correlativo,
				"fullTitulo" => $fullTitulo,
				"planned_aux" => $planned_aux,
				"tipoTrabajo" => $tipoTrabajo,
				"tipoingreso" => $tipoingreso
			];
			
			$id_tp = $cCfn->crearTP($data); # RECUPERO EL ID DEL TP y SI TIENE HIJOS 
			
			print_r($id_tp);
			
			break;
		}
		
		case 13:{ # GUARDAR TRAMO EDITADO 
			$table=null;
			$arrRes=array();
			
			if( !empty($ubicacion) ){
				$id=$cCfn->getSala($region,$comuna,$lugar,$elemento,$ubicacion);
				$id_elemento=$id[0]['ID_TP_ELEMENTO'];
			}else{
				$id_elemento="NULL";
			}
			
			$params = [
				":id_reg" => $id_registro,
			];
			# TRAEMOS LOS DATOS 
			$sql=$cCfn->getQuery("qry_editaTramo", $params);
			$objTramo = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			$paramsOrig = [
				":id_reg" => $id_registro,
				":region" => $objTramo[0]['region'], 
				":comuna" => $objTramo[0]['comuna'], 
				":lugar" => $objTramo[0]['lugar'], 
				":nom_lugar" => $objTramo[0]['nombre_lugar'], 
				":ubicacion" => $objTramo[0]['sala'],
				":id_elemento" => $id_elemento
			];
			
			$paramsUpdat = [
				":id_reg" => $id_registro,
				":region" => $region, 
				":comuna" => $comuna, 
				":lugar" => $lugar, 
				":nom_lugar" => $elemento, 
				":ubicacion" => $ubicacion,
				":id_elemento" => $id_elemento
			];
			
			$arrRes=array_diff($paramsUpdat,$paramsOrig);
			
			if( !empty($arrRes) ){
				# ACTUALIZAMOS REGISTRO 
				$sql=$cCfn->getQuery("update_tramo", $paramsUpdat);
				$updateAffected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			}
			
			# TRAEMOS LOS DATOS 
			$res=$cCfn->getNewTramo($planned_aux);
			if( empty($res) ){
				echo 1;
			}
			else{
				for( $r=0; $r<sizeof($res); $r++ ){
					$table.="<tr>";
					$table.="<td>".$res[$r]['id']."</td>";
					$table.="<td>".$res[$r]['REGION_NOMBRE']."</td>";
					$table.="<td>".$res[$r]['COMUNA']."</td>";
					$table.="<td>".$res[$r]['lugar']."</td>";
					$table.="<td>".$res[$r]['nombre_lugar']."</td>";
					$table.="<td>".$res[$r]['sala']."</td>";
					$table.="<td><button type='button' class='btn btn-xs btn-link' onClick='eliminaTramo(".$res[$r]['id'].", ".$planned_aux.")'>Eliminar</button></td><td><button type='button' class='btn btn-xs btn-link' onClick='editaTramo(".$res[$r]['id'].",".$planned_aux.")'>Editar</button></td>";
					$table.="</tr>";
				}
				echo $table;
			}
			break;
		}

		case 14:{ # 
			$modal=null;
			
			$params = [
				":tp" => $planned
			];
			# 
			$sql=$cCfn->getQuery("qry_detalleTp", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			
			if( empty($row[0]['TP_FECHA_SOLICITADA']) ){
				$title = "Ingresar";
				$prebody = "<h5>Seleccione los datos a continuaci&oacute;n</h5>";
			}
			else{
				$title = "Modificar";
				$prebody = "<h4>Fecha actual</h4>
					<h5 style='text-align:center;'>".$row[0]['TP_FECHA_SOLICITADA']."</h5>";
			}
			
			# MODAL HEADER 
			$modal.="<div class='modal-header'>
				<h4 class='modal-title'>".$title." fecha solicitada para ejecuci&oacute;n</h4>
				<h4 class='modal-title'>Para TP ".$planned." </h4>
				<input type='hidden' id='planned' name='planned' value='".$planned."'>
			</div>";

			# MODAL BODY 
			$modal.="<div class='modal-body'>
				<div class='row'>
					<div class='col-sm-6 col-md-6 col-lg-6' >
						<div class='form-group'>
							".$prebody."
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-4 col-md-4 col-lg-4' style='text-align:left;'  >
						<div class='form-group'>
							<label for='date'>Fecha</label>
							<input type='text' class='form-control' id='date'>
						</div>
						<div class='form-group'>
							<label for='time'>Hora</label>
							<input type='text' class='form-control' id='time'>
						</div>
					</div>
				</div>
			</div>";

			# MODAL FOOTER 
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cancelar</button>
				<button type='button' class='btn btn-default btn-sm' onClick='saveFechaSolicEjecTP()' >Guardar</button>
			</div>";

			echo $modal;
			break;
		}

		case 15:{
			// 
			$params = [
				":tp" => $planned
			];
			# TRAEMOS EL REGISTRO DE TP_DATA 
			$sql=$cCfn->getQuery("qry_tpdata", $params);
			$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());

			$flagF=null; $flagH=null;
			# Validamos fecha y hora 
			if( $cCfn->validarFecha($date) ){
				$flagF=true;
			}
			if( $cCfn->validarHora($time) ){ 
				$flagH=true;
			}

			$dateFinal=null;
			if( $flagF && $flagH ){
				$datetime = new \DateTime("$date $time");
				$dateFinal = $datetime->format('Y-m-d H:i');
			}

			$updateAffected=null;
			if( !empty($dateFinal) ){
				$params = [
					":tp" => $planned,
					":fechaFinal" => $dateFinal,
					":reg" => $row[0]["ID"]
				];
				$sql=$cCfn->getQuery("update_tpdata", $params);
				$updateAffected = $cCfn->exeQuery($sql,$cCfn->getConnBD());
			}
			echo $updateAffected['affected'];

			break;
		}
		
		default:{
			
		}
	}
	
	
>>>>>>> Stashed changes
?>