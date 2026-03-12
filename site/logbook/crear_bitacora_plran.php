<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$usr=$cCfn->getUser();
	
	$date = new DateTime();
	$ahora=$date->format('Y-m-d H:i:s');
	$ayear=$date->format('Y');
	$amonth=$date->format('m');
	$aday=$date->format('d');
	
	$sitioso = $_GET["sitioso"] ?? $_POST["sitioso"] ?? '';
	$nodo = $_GET["nodo"] ?? $_POST["nodo"] ?? '';
?>

	<script type="text/javascript" src="lib/jquery/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="logbook/js/javascript.js"></script>
	
	<body onLoad="ObtenerIDs('todos');" >
		<h1 align="CENTER">Crear bit&aacute;cora primera l&iacute;nea RAN</h1>
		
		<form onsubmit="Guardar_datos();" >
			<table align="center" border="1" class="sample">
				<tr>
					<td> 
						<input type="radio"  name="sitios" value="todos"   checked="checked" onClick="ObtenerIDs(this.value)"> Lista de TODOS los sitios
					</td>
				</tr>
				<tr>
					<td>Severidad: BAJA</td>
				</tr>
				<tr>
					<td>T&iacute;tulo: FALLA</td>
				</tr>
				<tr>
					<td><!--Sitio/Nodo (obligatorio): -->
						Selecci&oacute;n m&uacute;ltiple. Para seleccionar m&aacute;s de uno de la lista, se debe presionar CTRL al mismo tiempo.<br/>
						<select id='selec_sitio' name='sitio[]' size='5' multiple >
							<?php echo $sitioso; ?>
						</select>
						<select id='selec_nodo' name='nodo[]' size='5' multiple >
							<?php echo $nodo; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="id_opt" name="cerrar"  value="A" checked="checked">Abrir
						<input type="radio" id="id_opt" name="cerrar" value="C">Crear y Cerrar
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="id_tipo" name="tipo"  value="0"  checked="checked">P&uacute;blico
						<input type="radio" id="id_tipo" name="tipo" value="1" >Privado
					</td>
				</tr>
				<tr>
					<td align="center">
						<input type="button" onClick="Guardar_datos()" value="Ingresar" > 
						<input type="reset"  value="Borrar" >
						<input type="button" value="Volver" onClick="Volver_menu()">
					</td>
				</tr>
			</table>
		</form>
	</body>
