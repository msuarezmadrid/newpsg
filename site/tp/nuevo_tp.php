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
	$functions=$cCfn->functions();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
	
	$basePath=$cCfn->basePath();
	
	$tp_aux=$cCfn->getTPaux();
	$user=$cCfn->getUser();
	$areaOrigen=$_SESSION['origen'];
	$sAreaOrigen = ( $_SESSION['perfil_adc'] === "NO" ) ? "disabled" : "" ;
	
	$arrTpTipo = $cCfn->getTPtipo();
	$arrServicios = $cCfn->getServicios($areaOrigen);
	$arrRegion = $cCfn->getRegiones();
	
?>
<!DOCTYPE html>
<html lang='es'>
	<head>
		<title>Nuevo TP - Entel</title>
		<?php include($header); ?> 
	</head>
	<body>
		
		<div class='container-fluid' >
			<div class='row'>
				<div class='col-sm-12 col-md-12 col-lg-12' ><h3 style='text-align:center;'>Creaci&oacute;n de TP</h3></div>
			</div>
			<input type='hidden' id='planned_aux' name='planned_aux' value='<?php echo $tp_aux; ?>'>
			<input type='hidden' id='path' value='<?php echo $basePath; ?>'>
			<div id='dvAreaaOrigen' class='row' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Area de Origen:<strong></div>
				<div class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' >
					<select id='s_areaorigen' name='s_areaorigen' <?php echo $sAreaOrigen; ?> >
						<option value='' selected>(Seleccionar &aacute;rea)</option>
						<?php 
							for( $r=0; $r<sizeof($arrTpTipo); $r++ ){
								if( $arrTpTipo[$r]['ORIGEN'] === $areaOrigen ){
									echo "<option selected value='".$arrTpTipo[$r]['ID']."' >".$arrTpTipo[$r]['ORIGEN']."</option>";
								}else{
									echo "<option value='".$arrTpTipo[$r]['ID']."' >".$arrTpTipo[$r]['ORIGEN']."</option>";
								}
							}
						?>
					</select>
				</div>
			</div>
			<div id='dvServicio' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Servicio:<strong></div>
				<div class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' >
					<select id='s_servicios' name='s_servicios' onChange='update_elemento();' >
						<option value='' selected>(Seleccionar)</option>
						<?php 
							for( $r=0; $r<sizeof($arrServicios); $r++ ){
								echo "<option value='".$arrServicios[$r]['ELEMENTOS']."' >".$arrServicios[$r]['ELEMENTOS']."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div id='dvElementos' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Elemento:<strong></div>
				<div id='s_elementos' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvTipoTrabajo' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Tipo de Trabajo:<strong></div>
				<div id='s_tipotrabajos' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvModalidad' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Modalidad de Trabajo:<strong></div>
				<div id='s_modalidadtrabajos' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvTitulo' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>T&iacute;tulo:<strong></div>
				<div id='titulo' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvRegion' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Regi&oacute;n:<strong></div>
				<div id='s_region' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' >
					<select id='s_regiones' name='s_regiones' onChange='update_comunas();' >
						<option value='any' selected>(Sin Region)</option>
						<?php 
							for( $r=0; $r<sizeof($arrRegion); $r++ ){
								echo "<option value='".$arrRegion[$r]['ID_REGION']."' >".$arrRegion[$r]['REGION']."-".$arrRegion[$r]['REGION_NOMBRE']."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div id='dvComuna' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Comuna:<strong></div>
				<div id='s_comuna' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvLugar' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Lugar:<strong></div>
				<div id='s_lugars' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvNomLugar' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Nombre Lugar:<strong></div>
				<div id='s_nomlugar' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			<div id='dvUbicacion' class='row' style='display:none; margin-top: 10px;' >
				<div class='col-sm-2 col-md-2 col-lg-2' style='text-align:left; font-size:small;' ><strong>Ubicaci&oacute;n:<strong></div>
				<div id='s_ubica' class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; font-size:small;' ></div>
			</div>
			
			
			
			<div id='dvConfirmar' class='row' style='display:none; margin-top: 30px;' >
				<div id='dvbtnConfirmar' class='col-sm-12 col-md-12 col-lg-12' style='text-align:left; font-size:small;' >
					<button type='button' class='btn btn-xs btn-link' id='btnConfirmar' onClick="ingresarTramo();" >Confirmar</button>
				</div>
			</div>
			<div id='dvTramos' class='row table-responsive' style='display:none; margin-top: 30px; ' >
				<div id='dvTablaTramos' class='col-sm-6 col-md-6 col-lg-6' style='font-size:small;' >
					<table id='tbTramos' class='table table-condensed table-bordered' >
						<thead style='font-size:small;'><tr ><th>ID</th><th>Regi&oacute;n</th><th>Comuna</th><th>Lugar</th><th>Nombre Lugar</th><th>Ubicaci&oacute;n</th><th colspan='2'>Acci&oacute;n</th></tr></thead>
						<tbody style='font-size:small;'></tbody>
					</table>
				</div>
			</div>
			<div id='dvTituloFull' class='row' style='display:none; margin-top: 30px;' >
				<div class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; ' >
					<label id='lblTitulo' ></label>
				</div>
			</div>
			<div id='dvBotones' class='row' style=' margin-top: 10px;' >
				<div class='col-sm-4 col-md-4 col-lg-4' style='text-align:left; ' >
					<button type='button' class='btn btn-xs btn-default' id='btnLimpiar' onClick='limpiar();' >Limpiar</button>
					<button type='button' class='btn btn-xs btn-default' id='btnAbandonar' >Abandonar</button>
					<button type='button' class='btn btn-xs btn-default' id='btnIngresar' onClick='ingresaTP()' disabled>Ingresar</button>
				</div>
			</div>
			<br/>
		</div>
		
		<!-- MODAL -->
		<div id='modalTramo' class='modal fade' role='dialog'>
			<div class='modal-dialog ' id='modalDialogTramo' role='document'>
				<div class='modal-content' id='modalContentTramo' ></div>
			</div>
		</div>
        <!-- FIN MODAL -->
		
	</body>
</html>