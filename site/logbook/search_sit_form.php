<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	
	$cCfn->checkSession();
	$modulo="logbook";
	$date = new DateTime();
	
	$sitio = $_GET["sitio"] ?? $_POST["sitio"] ?? ''; ?>
	
	<h1 align="CENTER">Busqueda de Sitios en TP/PID/TAREA/SC</h1>
	<form id="frm" name="frm" action="search_sit_form.php" method="post">
		<table border="1">
			<tr>
				<th>Seleccione el Sitio: </th>
				<td><select id="sitio" name="sitio" >
					<?php
					$result = $cCfn->exeQuery("sel_sitio_bit",null,null,$cCfn->getLocal(),null,$modulo);
					while( $row = $result->fetch_assoc() ) {
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
			$params = [ $sitio ];
			$types="s";
			$result_tipo = $cCfn->exeQuery("sel_sitios_bit",$params,$types,$cCfn->getLocal(),null,$modulo);
			if( !empty($result_tipo) ) {
				echo "<table border='1'>
					<tr><th colspan='2'>
						Resultados</th></tr>
					<tr><th>
						Tipo</th>
						<th>Numero</th></tr> ";
				while( $row_tipo = $result_tipo->fetch_assoc() ){
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
	