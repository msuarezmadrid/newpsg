<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$iduser=$cCfn->getUser();
	$db="intradb";
	
	$date = new DateTime();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$ayear=$date->format('Y');
	$amonth=$date->format('m');
	$aday=$date->format('d');
	
	$id_falla = $_GET["opc"] ?? $_REQUEST["opc"] ?? '';
	$titulo = $_GET["titulo"] ?? $_REQUEST["titulo"] ?? '';
	$comentario = $_GET["comentario"] ?? $_REQUEST["comentario"] ?? '';
	$cerrar = $_GET["cerrar"] ?? $_REQUEST["cerrar"] ?? '';
	$tipo = $_GET["tipo"] ?? $_REQUEST["tipo"] ?? '';
	
	if ($id_falla == 1){
		$titulo = " CORE Telefonia Fija ";
	}else if ($id_falla == 2 ){
		$titulo = " NOC-IP ";
	}else if ($id_falla == 3 ){
		$titulo = " NOC Transporte";
	}else {
		$id_falla =-1;
	}
	$colspan='8';
	
	$datayear = null;
	for( $i=2002; $i<=date('Y'); $i++ ){
		$datayear .= "<option value='$i'>$i</option>";
	}
	
	$datamonth = null;
	for ($i=1; $i<=12; $i++){
		$datamonth .= "<option value='$i'>$i</option>";
	}
	
	$dataday = null;
	for ($i=1; $i<=31; $i++){
		$dataday .= "<option value='$i'>$i</option>";
	}
	
	$datahour = null;
	for ($i=0; $i<24; $i++){
		$datahour .= "<option value='$i'>$i</option>";
	}
	
	$dataminute = null;
	for ($i=0; $i<60; $i++){
		$dataminute .= "<option value='$i'>$i</option>";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Crear bitacora</title>
		<h1 align="center">Crear Bitacora&nbsp;<?php echo $titulo?></h1>	
		<script src="js/jquery-1.10.2.js"></script>
		<script src="js/bitacora.js"></script>		
		<?php include_once("../../protected/style.php"); ?>
		<style type="text/css" >
			.bittitulo {
				width:2%;	
			}

			.bitcolumn {
				width:23%;
			}

			.bitbox{
				width:95%;
			}			
		</style>
	</head>
	<body>
		<form id="frm_bitacora"  action="#">
			<div id="div_datos">
				<table cellpadding='0' cellspacing='10px' border='0' width='100%'>
					<tr>	
						<td colspan='<?php echo $colspan; ?>' >
						Severidad: (obligatorio)
						</td>	
					</tr>	
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							<div id="div_severidad"></div>		
						</td>
					</tr>
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							Titulo: (parte fija,obligatorio)
						</td>	
					</tr>	
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							<div id="div_falla"></div>
						</td>
					</tr>
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							Fallas:(Obligatorio)
						</td>
					</tr>
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							   <div id="div_falla_core"></div>
						</td>
					</tr>

					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							Titulo (parte variable,opcional):	
						</td>					
					</tr>	
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							<textarea id="titulo" name="titulo" rows='3' cols='49' wrap='SOFT'></textarea>
						</td>
					</tr>

					<tr>
						<td colspan='<?php echo $colspan; ?>'>
						Sitio/Nodo/Servicio: 
						</td>
					</tr>
					<tr>
						<td colspan='<?php echo $colspan; ?>'>
							<div id="div_servicios"></div>
						</td>
					</tr>
					<tr>
						<td class='bittitulo'>Pais</td>
						<td class='bitcolumn'><div id='div_paises'></div></td>
						<td class='bittitulo'>Regiones</td>
						<td class='bitcolumn'><div id='div_regiones'></div></td>
						<td class='bittitulo'>Zonas</td>
						<td class='bitcolumn'><div id='div_zonas'></div></td>
						<td class='bittitulo'>Lugar</td>
						<td class='bitcolumn'><div id='div_lugares'></div></td>
					<tr>
					<tr>
						<td colspan='<?php echo $colspan?>'>Comentario inicial (opcional, obligatorio cuando se usa Crear y Cerrar)</td>
					</tr>
					<tr>
						<td colspan='<?php echo $colspan?>'>
							<textarea id="comentario" name="comentario" rows='5' cols='49' wrap='SOFT'><?php echo "$comentario" ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan='4'>
						<div id='id_calendario'>
							<table cellpadding='0' cellspacing='1px' border='1' width='100%'>
							<tr>
								<td  width='20%' >Inicio de evento:</td>
								<td  width='80%'>
									<select  style ='width:18%' id="iyear"><?php echo $datayear; ?></select>
									&nbsp;
									<select  style ='width:18%' id="imonth"><?php echo $datamonth; ?></select>		
									&nbsp;
									<select  style ='width:18%' id="iday"><?php echo $dataday; ?></select>
									&nbsp;
									<select  style ='width:18%' id="ihour"><?php echo $datahour; ?></select>
									&nbsp;
									<select  style ='width:18%' id="iminute"><?php echo $dataminute; ?></select>
								</td>
							</tr>
							<tr>
								<td  width='20%' >Fin de evento:</td>
								<td  width='80%'>
									<select  style ='width:18%' id="fyear"><?php echo $datayear; ?></select>
									&nbsp;
									<select  style ='width:18%' id="fmonth"><?php echo $datamonth; ?></select>
									&nbsp;
									<select  style ='width:18%' id="fday"><?php echo $dataday; ?></select>
									&nbsp;
									<select  style ='width:18%' id="fhour"><?php echo $datahour; ?></select>
									&nbsp;
									<select  style ='width:18%' id="fminute"><?php echo $dataminute; ?></select>
								</td>
							</tr>		
							</table>
						</div>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<table cellpadding='0' cellspacing='1px' border='1' width='100%'>
							<tr><td style='width:50%'>	
								<INPUT type="radio" name="cerrar" <?php if (($cerrar=="") or ($cerrar=="A")) echo "checked"; ?> value="A">Abrir
							</td><td style='width:50%'>
								<INPUT type="radio" name="cerrar" <?php if ($cerrar=="C") echo "checked"; ?> value="C">Crear y Cerrar
							</td></tr>
							<tr>
								<td>
								<INPUT type="radio" name="tipo" <?php if (($tipo=="") or ($tipo==0)) echo "checked"; ?> value="0">P&uacute;blico
								</td><td>
								<INPUT type="radio" name="tipo" <?php if ($tipo==1) echo "checked"; ?> value="1">Privado
								</td>
							</tr>
							<tr>
								<td  colspan='2'>
								<input type="hidden" id="id_falla" name="id_falla" value="<?php echo $id_falla; ?>" >	
								</td>
							</tr>
							<tr>
								<td colspan='3'><input id='id_accion' name="id_accion" type="button" value="Ingresar">&nbsp;&nbsp;
								<input type="reset" value="Borrar"></td>
							</tr>
						</td>
					</tr>
				</table>	
			</div>
		</form>		
	</body>
</html>
