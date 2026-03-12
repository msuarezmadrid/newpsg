<?php
	include_once("../../protected/config.php");
	include_once("../../protected/control.php");
	include_once("../../protected/user.php");
	include_once("../lib/FS_2/JSClass/FusionCharts.php");

	$user = get_user();
	$link = mysafe_read($intraserver, $intrauser, $intrapass) or my_die("Could not connect");
	mysql_select_db($intradb);

	$buscar='';		
	$usuario= '';
	$fecha_desde= '';	
	$fecha_hasta='';	

	if( ($_POST['txt_fecha']) || ($_POST['txt_fecha_hasta']) || ($_POST['modulo']) || ($_POST['usuario']) || ($_POST['titulo']) ){
	
		$fecha_desde= $_POST['txt_fecha'];
		$fecha_hasta= $_POST['txt_fecha_hasta'];
	
		$wherenow = " WHERE NOMBRE_MODULO NOT LIKE 'Salir%' ";
		
		if( ($fecha_desde!='') || ($fecha_hasta!='') || ($_POST['modulo']) || ($_POST['usuario']) || ($_POST['titulo']) ){
			$where=' AND ';
		}
		if ($_POST['titulo']!=''){
			$buscar_tit= $_POST['titulo'];
			$t_titulo= " TITULO LIKE '%$buscar_tit%'";
		}
		if (($_POST['titulo']!='')&&($_POST['modulo']!='')){
			$and =" AND ";
		}
		if ($_POST['modulo']!=''){
			$buscar= $_POST['modulo'];
			$t_modulo= " NOMBRE_MODULO LIKE '%$buscar%'";
		}
		if (($_POST['usuario']!='')&&($_POST['modulo']!='')){
			$and2 =" AND ";
		}
		if ($_POST['usuario']!=''){
			$buscar_u= $_POST['usuario'];
			$t_usuario= " $and2 NOMBRE_USER LIKE '%$buscar_u%'";
		}
		if (($_POST['usuario']!='')||($_POST['modulo']!='')){
			$and3 = " AND ";
		}
		if ($fecha_desde!=''){
			$f_ini= " $and3 DATE_FORMAT(FECHA,'%Y-%m-%d') >= '$fecha_desde' ";
		}
		if (($_POST['usuario']!='')||($_POST['modulo']!='')||($fecha_desde!='')){
			$and4 = " AND ";
		}
		if ($fecha_hasta!=''){
			$f_fin= " $and4 DATE_FORMAT(FECHA,'%Y-%m-%d') <= '$fecha_hasta' ";
		}
		$sql_psg = "SELECT ID, NOMBRE_USER, ID_MODULO, TITULO, NOMBRE_MODULO, FECHA   
					FROM PSG_USERS_SUBMENU  
					$wherenow $where $t_titulo $and $t_modulo 
					$t_usuario 
					$f_ini 
					$f_fin					
					ORDER BY FECHA DESC";	
		
		$r_psg = mysql_query($sql_psg) or my_die("Query failed");
		
	}
	else{
		$sql_psg = "SELECT ID, NOMBRE_USER, ID_MODULO, TITULO, NOMBRE_MODULO, FECHA    
					FROM PSG_USERS_SUBMENU 
					WHERE NOMBRE_MODULO NOT LIKE 'Salir%' AND FECHA > DATE_ADD(CURDATE(),INTERVAL -1 DAY) 
					ORDER BY FECHA DESC";
		
		$r_psg = mysql_query($sql_psg) or my_die("Query failed");
	}
	$sql_psg_excel = $sql_psg;
	
	## SI EL BOTON BUSCAR FUE PRESIONADO, ENTRA AQUI..
	if( isset($_POST['busca']) ){
		if (mysql_num_rows($r_psg)==0){
			$texto_busqueda='Sin Resultados';
		}
		else{
			$texto_busqueda='Resultados para "'.$buscar.'"';
		}
	}
?>

<html>
	<head>
		<title>Usuarios en M&oacute;dulos PSG</title>
		<?php include_once("../../protected/style.php") ?>
		
		<script type="text/javascript" src="../lib/ajax/ajax_objeto.js"></script>
		<!-- BEGIN - Calendar -->
			<link rel="stylesheet" type="text/css" media="all" href="../lib/calendar/calendar-blue.css" title="win2k-cold-1" />
			<script type="text/javascript" src="../lib/calendar/calendar.js"></script>
			<script type="text/javascript" src="../lib/calendar/lang/calendar-en.js"></script>
			<script type="text/javascript" src="../lib/calendar/calendar-setup.js"></script>
		<!--  END  - Calendar -->
		<script src="../lib/jquery/jquery-1.11.1.js"></script>
		
		<style type="text/css">
			.listado td{
				background-color: #C0C0C0;
				padding:5px !important;
			}
			.estilo_link {
				color: blue;
				text-decoration: underline;
				cursor: pointer;
			}
			.centro {
				text-align:center;
			} 
		</style>
	</head>
	
	<body>
		<h1 align="CENTER">USUARIOS SUBMENU PSG</h1>
		
		<form name='test' action="#" method="post">
			<table border="1" align="center" class="sample">
				<tr>
					<th style="width:290px;height:80px;" colspan="6">T&iacute;tulo<br>
						<input name="titulo" type="text" id="titulo" value="<?php echo $buscar_tit; ?>" maxlength="50" style="width:175px;">
					</th>
					<th style="width:290px;height:80px;" colspan="6">M&oacute;dulo<br>
						<input name="modulo" type="text" id="modulo" value="<?php echo $buscar; ?>" maxlength="50" style="width:175px;">
					</th>
					<th style="width:250px;height:80px;" colspan="6">Usuario <br>
						<input name="usuario" type="text" id="usuario" value="<?php echo $buscar_u; ?>" maxlength="50" style="width:130px;">
					</th>
					<th style="width:180px;">Fecha Desde<br>
						<INPUT name="ano" type="hidden" id="ano" value="<?php echo $ano; ?>" maxlength="8">
						<INPUT name="txt_fecha" type="text" id="txt_fecha" style="width:100px" value="<?php echo $fecha_desde; ?>" readonly maxlength="8">
					</th>
					<th style="width:180px;">Fecha Hasta<br>
						<INPUT name="txt_fecha_hasta" type="text" id="txt_fecha_hasta" style="width:100px" value="<?php echo $fecha_hasta; ?>" readonly maxlength="8">
					</th>
					<th style="width:200px;">
						<input type="submit" id="busca" name="busca" value="Buscar">
					</th>
				</tr>
			</table >
			<br>
			
			<!-- GRAFICO -->
			<table align="center" >
				<tr>
					<td  ><?php echo graficoColumna(); ?></td>
				</tr>
			</table>	
			<!-- FIN GRAFICO -->
			<br>
			
			<table  border="1" align="center" class="sample2" id="tablaresult"style="width:50%">
				<tr>
					<th style="width:7%" id="head_modulo">ID</th>
					<th style="width:30%" id="head_modulo">T&iacute;tulo</th>
					<th style="width:30%" id="head_modulo">M&oacute;dulo</th>
					<th style="width:15%" id="head_usuario">Usuario</th>
					<th style="width:10%" id="head_usuario">Area Origen</th>
					<th style="width:18%" id="head_fecha">Fecha</th>
				</tr>
				<?php 
					if (mysql_num_rows($r_psg)!=0){
						while ($row = mysql_fetch_array($r_psg)){
							$idnow 		   = $row['ID_MODULO'];
							$nombre_modulo = $row['NOMBRE_MODULO'];
							$titulo = $row['TITULO'];
							$nombre_user = $row['NOMBRE_USER'];
							$fecha = $row['FECHA'];
							
							echo '<tr class="listado">';
							echo '<td><a href="../reports/print_report.php?id='.$idnow.'" target="_blank">'.$idnow.'</a></td>';
							echo '<td><a href="../reports/print_report.php?id='.$idnow.'" target="_blank">'.$titulo.'</a></td>';
							echo '<td>'.utf8_decode($nombre_modulo).'</td>';
							echo '<td style="text-align: center;">'.$nombre_user.'</td>';
							
							$sql_area="SELECT ORIGEN FROM TP_AREA_ORIGEN WHERE USUARIO = '".$row['NOMBRE_USER']."' LIMIT 1";
							$r_area = mysql_query($sql_area) or my_die("Query failed");
							if (mysql_num_rows($r_area)!=0){
								while ($row2 = mysql_fetch_array($r_area)){
									$area_origen =$row2[0];
								}
							}					
							echo '<td style="text-align: center;">'.$area_origen.'</td>';
							echo '<td style="text-align: center;">'.$fecha.'</td>';
						}
					}
					else{
						echo '<tr><td colspan="6">No hay registros</td></tr>';
					}
				?>	
				<tr>
					<td width="50%" align="center" valign="top" colspan="6"><div id="capa_datos"></div></td>
				</tr>		 
			</table>
		</form>
		<form name='test' action="psg_usuarios_excel_submenu.php" method="post" id="Form2">
			<div>
				<? /*echo $sql_psg_excel;*/ ?>
				<input type="hidden" id="result" name="result" value="<? echo $sql_psg_excel;?>">
				<input type="submit" name="action" value="Exportar Excel">
			</div>
		</form>
		<?
			function graficoColumna(){ 
				$xml2 = "<graph caption='Grafico Gestion de Reportes' xAxisName='Modulos' yAxisName='Cantidad' decimalPrecision='0' formatNumberScale='0' rotateNames='1'>";
				$sql_psg = "SELECT COUNT(1) AS CANT, TITULO, ID_MODULO   
							FROM PSG_USERS_SUBMENU WHERE NOMBRE_MODULO NOT LIKE 'Salir%' 
							GROUP BY TITULO 
							ORDER BY CANT DESC";
				$r_modulos = mysql_query($sql_psg) or my_die("Query failed");
				if (mysql_num_rows($r_modulos)!=0){	
					while ($row2 = mysql_fetch_array($r_modulos)){
						$xml2 .="<set name='".$row2[2]."' value='$row2[0]' color='AFD8F8'/>";
					}
				}
				$xml2 .= "</graph>";
				$retorno = renderChartHTML("../lib/FS_2/Column2D.swf","",urlencode($xml2),"Grafico1Id",1100,500,"","","");
				return "$retorno";
			}
		?>
		<script>
			function catcalc(cal) {
				var date = cal.date;
				var time = date.getTime()
			}
			Calendar.setup({
				inputField      :    "txt_fecha",   // id of the input field
				ifFormat        :    "%Y-%m-%d",       // format of the input field
				showsTime       :    false,
				timeFormat      :    "24"//,
				//onUpdate        :    catcalc
			});
			Calendar.setup({
				inputField      :    "txt_fecha_hasta",   // id of the input field
				ifFormat        :    "%Y-%m-%d",       // format of the input field
				showsTime       :    false,
				timeFormat      :    "24"//,
				//onUpdate        :    catcalc
			});
		</script>
		<?php 
			mysql_free_result($r_psg);
			mysql_close();
		?>
		
	</body>
</html>
