<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$bid = $_GET["bid"] ?? $_POST["bid"] ?? '';
	
	### combo con los reportes 
	$params = [
		":bid" => $bid
	];
	$sql=$cCfn->getQuery("desc_bit", $params);
	$result = $cCfn->exeQuery($sql,$db);
	$tabla = null;
	$cont=0;
	for( $r=0; $r<sizeof($result); $r++ ){
	//$data = mysqli_fetch_array($result2,MYSQLI_NUM)){
		$data = $result;
		//$cont++;
		$fila_id= "fila_".$r;
		$chk_id = "chk_".$r;
		$tabla .= "<tr id='$fila_id' onmouseover='filaSelect(this.id)' onmouseout='filaNormal(this.id)'>";
		$tabla .= 	"<td>".$data[$r]['usr']."</td>";
		$tabla .= 	"<td align='center'>".$data[$r]['INGRESO']."</td>";
		$tabla .= 	"<td>".$data[$r]['DESCRIPCION']."</td>";
		$tabla .= 	"<td align='center'><input id='$chk_id' name='$chk_id' type='checkbox' value='".$data[$r]['ID']."'></td>";
		$tabla .= "</tr>";
	}
	
	$sql=$cCfn->getQuery("doc_bit_tipo", $params);
	$result = $cCfn->exeQuery($sql,$db);
	$combo_tarea  ="<select name='sel_tarea' id='sel_tarea' onChange='CambiaFoco();'>";
	$combo_tarea .="<option value='0' selected>Seleccionar Accion</option>	";
	foreach( $result as $row ){
		$combo_tarea .="<option value='".$row['TIPO_ID']."' >".$row['TIPO_ID']." - ".$row['TIPO_NOMBRE']."</option>	";
	}
	$combo_tarea .="</select>"; ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title > Documentar Acci&oacute;n </title>
			<?php include("../../protected/style.php") ?>
			<script type="text/javascript" src="../lib/jquery/jquery-3.5.1.js"></script>
			<script type="text/javascript" src="./js/javascript.js"></script>
		</head>
		<body>
			<h1 align="center"> Documentar Acci&oacute;n </h1>
			<input type="hidden" name="id_bitacora" id="id_bitacora" value="<?php echo $bid; ?>" />
			<input type="hidden" name="registros" id="registros" value="<?php echo $cont; ?>" />
			<table border="1" class="sample" width="400" align="center">
				<tr>
					<th colspan="4" align="CENTER"> &nbsp; BITACORA: <?php echo $bid; ?> </th>
				</tr>
				<tr>
					<th align="LEFT"> &nbsp; TIPO: </th>
					<td colspan="3" align="left"> <?php echo $combo_tarea; ?> </td>
				</tr>
				<tr>
					<th align="LEFT"> &nbsp; ID: </th>
					<td colspan="3" align="left"> <input type="text" name="txt_tarea" id="txt_tarea" onBlur="return ValidarTarea();" /> </td>
				</tr>
				<tr>
					<th colspan="4" align="center">
						<input id="enviar" name="enviar" type="button" value="Guardar" onClick="GuardarDatosNew();" />&nbsp;
						<input id="enviar" name="volver" type="button" value="Volver" onClick="Volver();">&nbsp;
						<input id="enviar" name="volver" type="button" value="Limpiar" onClick="Limpiar();" /> 
					</th>
				</tr>
			</table>
			
			<table align="center">
				<tr>
					<td>
						<table border="1" class="sample" width="400" align="center">
							<tr>
								<th colspan="2" align="CENTER"> ASOCIACIONES </th>
							</tr>
							<tr>
								<th width="35%" > &nbsp; ID &nbsp;</th>
								<th width="65%"> &nbsp;TIPO &nbsp;</th>
							</tr>    
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div id="capa_datos"></div>
					</td>
				</tr>
			</table>
			
		</body>
	</html>
	
	<script type="text/javascript">TraerAsociaciones(<?php echo $bid; ?> );</script>
