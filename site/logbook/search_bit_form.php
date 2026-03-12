<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	
	$cCfn->checkSession();
	$modulo="logbook";
	$date = new DateTime(); # clase DateTime global de PHP 
	
	$usr = $_GET["usr"] ?? $_POST["usr"] ?? $cCfn->getUser();
	
	$sel_area=null;
	$result = $cCfn->exeQuery("lista_area",null,null,$cCfn->getLocal(),null,$modulo);
	$sel_area="<option value='' selected>Seleccionar</option>";
	while( $row = $result->fetch_assoc() ){
		if( $usr == $row['AREA'] ){
			$sel_area.= "<option value='".$row['AREA']."' selected>".$row['AREA']."</option>";
		}
		else{
			$sel_area.= "<option value='".$row['AREA']."'>".$row['AREA']."</option>";
		}
	}
	
	$sel_sev=null;
	$result = $cCfn->exeQuery("severidad_bit",null,null,$cCfn->getLocal(),null,$modulo);
	$sel_sev="<option value='' selected>Seleccionar</option>";
	while( $s = $result->fetch_assoc() ){
		$sel_sev.="<option value='".$s['ID']."' >".$s['NOMBRE']."</option>";
	}
	?>
	
	<script src="../lib/jquery/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="../lib/jquery/jquery-ui.css">
	<script src="../lib/jquery/jquery-ui.min.js"></script>
	<script src="../lib/jquery/jquery-ui-i18n.min.js"></script>
	<script src="../lib/javascript/calendar_datepicker.js"></script>
	
	<script>
		$.datepicker.setDefaults($.datepicker.regional['es']);
	</script>
	
	<h1 align="center">Buscar en bitacora</h1>
	<p>
		<form name='test' action="search_bit_query.php" method="post"> 
			<table>
				<tr>
					<td><label for="numero">N&uacute;mero: </label></td>
					<td><input type="text" id="numero" name="numero"></td>
				</tr>
				<tr>
					<td><label for="descripcion">T&iacute;tulo: </label></td>
					<td><input type="text" id="descripcion" name="descripcion"></td>
				</tr>
					<td><label for="nodo">Sitio/Nodo/Servicio: </label></td>
					<td><input type="text" id="nodo" name="nodo"></td>
				<tr>
					<td><label for="creador">Creador: </label></td>
					<td><input type="text" id="creador" name="creador"></td>
				</tr>
				<tr>
					<td><label for="area">&Aacute;rea: </label></td>
					<td><select id="area" name="area"><?php echo $sel_area; ?></select></td>
				</tr>
				<tr>
					<td>Severidad : </td>
					<td>
						<select name='comp' >
							<option value='=' selected>=</option>
							<option value='>' >Mayor que</option>
							<option value='<' >Menor que</option>
						</select>&nbsp;que&nbsp;
						<select name='severidad'>
							<?php echo $sel_sev; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><input type="radio" name="abiertas" checked value="0">Todas</td>
					<td><input type="radio" name="abiertas" value="1">No cerradas solamente</td>
				</tr>
				<tr>
					<td><label for="fecha1">Al o Desde: </label></td>
					<td><input type="text" id="fecha1" name="fecha1"></td>
				</tr>
				<tr>
					<td><label for="fecha2">Hasta: </label></td>
					<td><input type="text" id="fecha2" name="fecha2"></td>
				</tr>
				<tr>
					<td><input type="radio" name="modo" checked value="0">Expanded</td>
					<td><input type="radio" name="modo" value="1">Compressed</td>
				</tr>
				<tr>
					<td colspan='2'><input type="submit" value="Buscar" />&nbsp;<input type="reset" value="Borrar" /></td>
					<!--td></td-->
				</tr>
			</table>
		</form>
	