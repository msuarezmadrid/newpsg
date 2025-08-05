<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
    $cCfn->checkSession();
	
	$favicon=$cCfn->favicon();
    $header=$cCfn->siteHeader();
	
	$bodyCss=$cCfn->bodyCss();
	$boostrapCss=$cCfn->boostrapCss();
	$flatpickrcss=$cCfn->flatpickrcss();
	$functions=$cCfn->functions();
	$flatpickr=$cCfn->flatpickr();
	$flatpickres=$cCfn->flatpickres();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
	
	$basePath=$cCfn->basePath();
	
	if( isset($_GET['tp']) ){
		$planned=$_GET['tp'];
	}
	else{
		echo "<script type='text/javascript' language='javascript'>alert('Debe ingresar un TP'); window.history.back(); </script>";
	}
	$userADC=$_SESSION['perfil_adc'];
	$usr=$cCfn->getUser();
	
	$params = [
		":tp" => $planned
	];
	$sql=$cCfn->getQuery("qry_tpdata", $params);
	$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
	if( empty($row[0]['ID_COMUNA']) ){
		$comuna = "NO";
	}else $comuna = "SI";
	
	$boolOwner=null;
?>
<!DOCTYPE html>
<html lang='es'>
	<head>
		<title>TP <?php echo $planned; ?> - Entel</title>
		<?php include($header); ?> 
	</head>
	<body>
		
		<div class='container-fluid' >
			<div class='row'>
				<div class='col-sm-12 col-md-12 col-lg-12' ><h3 style='text-align:center;'><strong>Trabajo Programado <?php echo $planned; ?></strong></h3></div>
				<input type='hidden' id='planned_id' name='planned_id' value='<?php echo $planned; ?>'>
				<input type='hidden' id='path' value='<?php echo $basePath; ?>'>
			</div>
			<div class='row'><br/></div>
			<!-- TITULO -->
			<div class='row'>
				<div class='col-sm-12 col-md-12 col-lg-12' >
					<table class='table tabla table-condensed ' >
						<thead class='tablathead' >
							<tr>
								<th>T&iacute;tulo</th>
								<th>Descripci&oacute;n</th>
								<th>RPN</th>
								<th>Estado</th>
								<th>Planificaci&oacute;n inicio</th>
								<th>Planificaci&oacute;n t&eacute;rmino</th>
								<th>Ejecuci&oacute;n</th>
								<th>Cierre</th>
								<th>Creado por</th>
							</tr>
						</thead>
						<?php 
							$params = [
								":id" => $planned
							];
							$sql=$cCfn->getQuery("qry_tp", $params);
							$objTp = $cCfn->exeQuery($sql,$cCfn->getConnBD());
							$desc = ( empty($objTp[0]['DESCRIPCION']) ) ? $objTp[0]['DESCRIPCION'] : "<pre>".$objTp[0]['DESCRIPCION']."</pre>" ;
							$tp_ver=$objTp[0]['TP_VER'];
							$owner=$objTp[0]['OWNER'];
							$tp_state_id=$objTp[0]['ESTADOS_ID'];
							$boolOwner = ( $usr === $owner ) ? true : false ;
						?>
						<tbody class='tablatbody' >
							<tr>
								<td><?php echo htmlspecialchars($objTp[0]['TITULO']); ?></td>
								<td><?php echo $desc; ?></td>
								<td><?php echo $objTp[0]['RPN']; ?></td>
								<td><?php echo $objTp[0]['NOMBRE_EDO']; ?></td>
								<td><?php echo $objTp[0]['TP_PLANNED']; ?></td>
								<td><?php echo $objTp[0]['FECHA_PLANIF_FIN']; ?></td>
								<td><?php echo $objTp[0]['TP_FECHA_EXEC']; ?></td>
								<td><?php echo $objTp[0]['FECHA_FIN']; ?></td>
								<td><?php echo $objTp[0]['OWNER']; ?></td>
							</tr>
						</tbody>
					</table >
				</div>
			</div>
			<!-- ################################################################################################### -->
			<?php 
				$tp_opred=null;
				if( $tp_ver !== 0 ){
					$params = [
						":id" => $planned
					];
					$sql=$cCfn->getQuery("qry_terreno", $params);
					$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
					$terreno=null;
					if( !empty($row) ){
						$tp_opred=1;
						$terreno=$row[0]['TERRENO'];
					}
			?>
					<div class='row'>
						<div class='col-sm-12 col-md-12 col-lg-12' >
							<table class='table tabla table-condensed ' >
								<thead class='tablathead' >
									<tr>
										<th>Solicitante</th>
										<th>Fecha Creaci&oacute;n</th>
										<th>Area de origen</th>
										<th>Servicios</th>
								<?php 
									if( $comuna === "NO" ){
										echo "<th>Zona</th>";
									}
								?>
										<th>Elementos</th>
										<th>Tipo de trabajo</th>
								<?php 
									if( $comuna === "SI" ){
										echo "<th>Modalidad de Trabajo</th>";
									}
								?>
										<th>Clasificaci&oacute;n</th>
								<?php 
									if( $comuna === "NO" ){
										echo "<th>Correlativo</th>";
									}
								?>
										<th>Fecha solicitada para ejecuci&oacute;n</th>
								<?php 
									if( $tp_opred !== 0 ){
										echo "<th>Terreno</th>";
									}
									
									if( $comuna === "SI" ){
										echo "<th>Acci&oacute;n</th>";
									}
								?>
									</tr>
								</thead>
								<?php 
									$params = [
										":tp" => $planned
									];
									$sql=$cCfn->getQuery("qry_detalleTp", $params);
									$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
									$userIsOwner=$row[0]['TP_SOLIC'];
									$descripcion_region =$row[0]['REGION'];
									$fechaSolic = ( empty($row[0]['TP_FECHA_SOLICITADA']) ) ? "-" : $row[0]['TP_FECHA_SOLICITADA'] ;
									$boolOwner = ( $usr === $userIsOwner ) ? true : false ;
								?>
								<tbody class='tablatbody' >
									<tr>
										<td><?php echo $userIsOwner; ?></td>
										<td><?php echo $row[0]['TP_FECHA']; ?></td>
										<td><?php echo $row[0]['TP_AREA']; ?></td>
										<td><?php echo $row[0]['TP_ELEMENTOS']; ?></td>
									<?php 
										if( $comuna === "NO" ){
											echo "<td>".$row[0]['TP_ZONA']."</td>";
										}
									?>
										<td><?php echo $row[0]['TP_SUBELEMENTOS']; ?></td>
										<td><?php echo $row[0]['TP_TIPO']; ?></td>
									<?php 
										if( $comuna === "SI" ){
											echo "<td>".$row[0]['TIPO_INGRESO']."</td>";
										}
									?>
										<td><?php echo $row[0]['TP_CLASIF']; ?></td>
									<?php 
										if( $comuna === "NO" ){
											echo "<td>".$row[0]['TP_REF']."</td>";
										}
										
										$fec_title=null;
										if( $userADC === "SI" ){
											if( empty($row[0]['TP_FECHA_SOLICITADA']) ){
												$fec_title="(Ingresar ";
											}else $fec_title="(Modificar ";
											$link="<button type='button' class='btn btn-xs btn-link' id='btnFechaSolic' onClick='fechaSolicEjecTP(\"".$userADC."\",".$planned.");' >".$fec_title." fecha)</button>";
										}
										else{
											$edos=array(2,9,10,6);
											if( !empty($boolOwner) && in_array($tp_state_id,$edos) ){
												$link="<button type='button' class='btn btn-xs btn-link' id='btnFechaSolic' onClick='fechaSolicEjecTP(\"".$userADC."\",".$planned.");' >".$fec_title."</button>";
											}
											else{
												$link="";
											}
										}
									?>
										<td><?php echo $fechaSolic." ".$link; ?></td>
									<?php 
										if( $tp_opred !== 0 ){
											echo "<td>".$terreno."</td>";
										}
										$tp_solicitante=$row[0]['TP_SOLIC'];
										$tp_area=$row[0]['TP_AREA'];
										$tp_tipo=$row[0]['TP_TIPO'];
										$elementos=$row[0]['TP_ELEMENTOS'];
										$lugar=$row[0]['ELEMENTO'];
										$subelementos=$row[0]['TP_SUBELEMENTOS'];
										$aidi_tp_cla=$row[0]['ID'];
										$clasificacion=$row[0]['TP_CLASIF'];
										$sala=$row[0]['SALA'];
										$nombre_elemento=$row[0]['NOMBRE_ELEMENTO'];
										$comunatp=$row[0]['COMUNA'];
										$idcomunatp=$row[0]['ID_COMU'];
										$region=$row[0]['ID_REGION'];
										$zona=$row[0]['TP_ZONA'];
										$tipoIngreso=$row[0]['TIPO_INGRESO'];
										$idTpData=$row[0]['ID_TP_DATA'];
										
										if( $comuna === "SI" ){
											if( $userADC === "SI" ){
												$btn="<button type='button' class='btn btn-xs btn-link' id='btnEditarTrama' onClick='EditarTrama(".$idTpData.",".$planned.",1);' >Editar</button>";
											}
											else{
												$btn="";
											}
										}
										echo "<td>".$btn."</td>";
									?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
			<?php 
				
				} # FIN if( $tp_ver !== 0 )
				
				if( $comuna === "SI" ){ ?>
				<div class='row'>
					<div class='col-sm-12 col-md-12 col-lg-12' >
						<table class='table tabla table-condensed ' >
							<thead class='tablathead' >
								<tr>
									<th>Regi&oacute;n</th>
									<th>Comuna</th>
									<th>Lugar</th>
									<th>Nombre Lugar</th>
									<th>Ubicaci&oacute;n</th>
									<th>Acci&oacute;n</th>
								</tr>
							</thead>
							<tbody class='tablatbody'>
								<tr>
									<td><?php echo $descripcion_region; ?></td>
									<td><?php echo $comunatp; ?></td>
									<td><?php echo $lugar; ?></td>
									<td><?php echo $nombre_elemento; ?></td>
									<td><?php echo ( !empty($sala) ? $sala : "Sala/Exterior" ); ?></td>
									<td><?php echo ( $userADC === "SI" ? "<button type='button' class='btn btn-xs btn-link' onClick='EditarTrama(".$idTpData.",".$planned.",1);' >Editar</button> | <button type='button' class='btn btn-xs btn-link' onClick='EliminarTrama(".$idTpData.",".$planned.");' >Eliminar</button>" : "" ); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			<?php
				}

				if ( $userADC === "SI" ){
					$params = [
						":tp" => $planned
					];
					$sql=$cCfn->getQuery("qry_comuna_tpdata", $params);
					$row = $cCfn->exeQuery($sql,$cCfn->getConnBD());
					$row[0]['ID_COMUNA']=""; // prueba
					if( empty($row[0]['ID_COMUNA']) ){ ?>
						<button type='button' class='btn btn-xs btn-link' onClick='mostrarVistaADC();' >Modificar par&aacute;metros de trabajo</button>
						
						<div id='vistaADC' style='display:none;'>
							<table >
								<tr >
									<th>Area de origen</th>
								</tr>
							</table>
						</div>
				<?php 
					} // fin if( empty($row[0]['ID_COMUNA']) ){ 
				}
			?>
			
			
		</div>
		<!-- MODAL -->
		<div id='modaltp' class='modal fade' role='dialog'>
			<div class='modal-dialog ' id='modalDialogTP' role='document'>
				<div class='modal-content' id='modalContentTP' ></div>
			</div>
		</div>
        <!-- FIN MODAL -->
	</body>
</html>