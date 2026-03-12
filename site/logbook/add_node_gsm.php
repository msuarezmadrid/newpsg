<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$todos = $_GET["todos"] ?? $_POST["todos"] ?? '';
	
	$params = [ $id	];
	$result = $cCfn->exeQuery("add_nodo_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
	$row = $result->fetch_assoc();
	$seleccionados=explode("/",$row['NODO']); ?>
		
		<h1 align='center'>Agregar Nodo/Sitio/Servicio a Bit&aacute;cora <?php echo $id; ?></h1>
		<?php
		if( $todos=="todos" ){
			echo "<form name='site' action='add_node_gsm.php?todos=no' method='post'>";
		}
		else{
			echo "<form name='site' action='add_node.php?todos=todos' method='post'>";
		} ?>
		<input type='hidden' name='id' value='<?php echo $id; ?>' />
		<p>
			<?php 
				if ($todos == "todos"){
					echo "<input name='f_accion' type='submit' value='Lista solo sitios alarmados'>\n";
				}
				else{
					echo "<input name='f_accion' type='submit' value='Lista todos los sitios'>\n";
				} ?>
		</p></form>
		<p>
			<form name='test' action="insert_node.php" method="post" target='_blank'>
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<p>
					Sitio/Nodo/Servicio: Selecci&oacute;n m&uacute;ltiple. Para seleccionar m&aacute;s de uno de la lista, se debe presionar CTRL al mismo tiempo.
				</p>
				<p>
					<select name="sitio[]" size='5' multiple>
						<?php
						if( $todos == "todos" ){
							$qry="nueva_bit_SitNodServ1";
						}
						else{
							$qry="nueva_bit_SitNodServ2";
						}
						$result = $cCfn->exeQuery($qry,null,null,$cCfn->getLocal(),null,$modulo);
						while ( $row = $result->fetch_assoc() ){
							$sitio=$row['SITIO'] . " " . $row['NOMBRE'];
							if( in_array($sitio,$seleccionados) ){
								echo "<option value='".$row['SITIO']." ".$row['NOMBRE']."' selected>".$row['NOMBRE']." ".$row['SITIO']."</option>";
							}
							else{
								echo "<option value='".$row['SITIO']." ".$row['NOMBRE']."'>".$row['NOMBRE']." ".$row['SITIO']."</option>";
							}
						} ?>
					</select>
				</p>
				<p>
					<select name="nodo[]" size='5' multiple>
						<?php
						$result = $cCfn->exeQuery("nueva_bit_ne",null,null,$cCfn->getLocal(),null,$modulo);
						while ( $row = $result->fetch_assoc() ){
							if( in_array($row['NOMBRE'],$seleccionados) ){
								echo "<option value='".$row['NOMBRE']."' selected>".$row['NOMBRE']."</option>";
							}
							else{
								echo "<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
							}
						} ?>
					</select>
				</p>
				<p>
					<select name="servicio[]" size='5' multiple>
						<?php
						$result = $cCfn->exeQuery("nueva_bit_serv",null,null,$cCfn->getLocal(),null,$modulo);
						while ( $row = $result->fetch_assoc() ){
							if( in_array($row['NOMBRE'],$seleccionados) ){
								echo "<option value='".$row['NOMBRE']."' selected>".$row['NOMBRE']."</option>";
							}
							else{
								echo "<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
							}
						} ?>
					</select>
				</p>
				<p>
					<input name="accion" type="submit" value="Enviar">&nbsp;&nbsp;<input name="accion" type="submit" value="Volver">
				</p>
			</form>
		</p>