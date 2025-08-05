<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$Opcion = $_GET["Opcion"] ?? $_POST["Opcion"] ?? '';
	$sitios = $_GET["sitios"] ?? $_POST["sitios"] ?? '';
	$bitacora = $_GET["bitacora"] ?? $_POST["bitacora"] ?? '';
	
	if( $Opcion == "Agregar" && !empty($sitios) ){
		$params = [ $bitacora, $sitios ];
		$result = $cCfn->exeQuery("ins_bit_sitios",$params,'ss',$cCfn->getLocal(),null,$modulo);
	}
	if( $Opcion == "Eliminar" ){
		$params = [ $bitacora, $sitios ];
		$result = $cCfn->exeQuery("del_bit_sitios",$params,'ss',$cCfn->getLocal(),null,$modulo);
		echo "<script type='text/javascript'>window.location='asociar_sitios.php?bitacora=$bitacora'</script>";
	} ?>
	
			<h2>Asociar Sitios a Bit&aacute;cora <?php echo $bitacora; ?></h2>
			<form name="frm" action="#" method="post">
				<input type="hidden" name="bitacora" value="<?php echo $bitacora; ?>" />
				<table border='1'>
					<tr>
						<th>Seleccione el sitio:</th>
						<td>
							<select id='sitios' name='sitios'>
							<?php
								$params = [ $bitacora ];
								$result = $cCfn->exeQuery("asoc_sitios_bit",$params,'s',$cCfn->getLocal(),null,$modulo); ?>
									<option value='' selected>Seleccionar</option>
							<?php 
								while( $row_sitios = $result->fetch_assoc() ){
									echo "<option value='".$row_sitios['SITE_ID']."'>".$row_sitios['SITIO']."</option>";
								} ?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan='2' align='center'>
							<input type='submit' id='Opcion' name='Opcion' value='Agregar' />&nbsp;
							<input type='submit' id='Opcion' name='Opcion' value='Volver' />
						</td>
					</tr>
				</table>
				<p>Los sitios asociados son los siguientes:</p>
				<table border="1">
					<tr>
						<th>Sitios</th>
						<th>Eliminar</th>
					</tr>
				<?php
				$params = [ $bitacora ];
				$result = $cCfn->exeQuery("asoc_bit_sitios",$params,'s',$cCfn->getLocal(),null,$modulo);
				while( $row_tabla = $result->fetch_assoc() ){ ?>
					<tr>
						<td><?php echo $row_tabla['SITIO']; ?></td>
						<td><a href='asociar_sitios.php?bitacora=<?php echo $bitacora; ?>&sitios=<?php echo $row_tabla['SITE_ID']; ?>&Opcion=Eliminar'>Eliminar</a></td>
					</tr>
				<?php 
				} ?>
				</table>
			</form>
			