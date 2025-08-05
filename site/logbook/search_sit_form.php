<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	$date = new DateTime();
	
	$sitio = $_GET["sitio"] ?? $_POST["sitio"] ?? ''; ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Subgerencia Operaciones y Mantenimiento de Red</title>
			<?php include("../../protected/style.php") ?>
		</head>
		<body>
			<h1 align="CENTER">Busqueda de Sitios en TP/PID/TAREA/SC</h1>
			<form id="frm" name="frm" action="search_sit_form.php" method="post">
				<table border="1">
					<tr>
						<th>Seleccione el Sitio: </th>
						<td><select id="sitio" name="sitio" >
							<?php
							$sql=$cCfn->getQuery("sel_sitio_bit", NULL);
							$result = $cCfn->exeQuery($sql,$db);
							foreach( $result as $row ) {
								echo "<option value='".$row['SITE_ID']."' ";
								if($sitio == $row['SITE_ID']) echo " selected ";
								echo ">".$row['SITIO']." - ".$row['NOMBRE']."</option> ";
							} ?>
						</select></td>
					</tr>
					<tr>
						<td colspan="2" align="RIGHT"><input type="submit" id="consultar" name="consultar" value="Consultar" /></td>
					</tr>
				</table>
			</form>
			
			<?php
				if( !empty($sitio) ) {
					$params = [
						":sitio" => $sitio
					];
					$sql=$cCfn->getQuery("sel_sitios_bit", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if( !empty($result_tipo) ) {
						echo "<table border='1'>
							<tr><th colspan='2'>
								Resultados</th></tr>
							<tr><th>
								Tipo</th>
								<th>Numero</th></tr> ";
						foreach( $result_tipo as $row_tipo ){
							echo "<tr><td>".$row_tipo['TIPO_NOMBRE']."</td><td><a href='";
							if($row_tipo['TIPO_ID'] == 1) echo "../tp/ver_planned.php?id=".$row_tipo['ASOC_ID']."";
							if($row_tipo['TIPO_ID'] == 2) echo "../pid/ver_pid.php?id=".$row_tipo['ASOC_ID']."";
							if($row_tipo['TIPO_ID'] == 3) echo "../tasks/ver_tarea.php?id=".$row_tipo['ASOC_ID']."";
							if($row_tipo['TIPO_ID'] == 4) echo "../sc/ver_sc.php?id=".$row_tipo['ASOC_ID']."";
							echo "' target='_blank'>".$row_tipo['ASOC_ID']."</a></td></tr>";
						}
						echo "</table>";
					} 
					else echo "<p><table border='1'><tr><th colspan='2'>Resultados</th></tr><tr><td>No existe TP/PID/TAREA/SC asociados al Sitio seleccionado</td></tr></table></p>";
				} ?>
		</body>
	</html>