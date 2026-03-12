<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$sistema = $_GET["sistema"] ?? $_POST["sistema"] ?? '';
	$plataforma = $_GET["plataforma"] ?? $_POST["plataforma"] ?? '';
	$servicio = $_GET["servicio"] ?? $_POST["servicio"] ?? '';
	$elemento = $_GET["elemento"] ?? $_POST["elemento"] ?? '';
?>
	<h1 align='center'>Editar Sistema/Plataforma/Servicio/Elemento a Bit&aacute;cora <?php echo "$id"; ?></h1>
	<form name='test' action="insert_node_opersis.php" method="post" target='_blank'>
		<input type="hidden" name="id" value="<?php echo $id; ?>"> 
		<p>
			Sistema:
			<select name='sistema' onchange='this.form.submit()'>
				<?php
					$result = $cCfn->exeQuery("sistema_bit",null,null,$cCfn->getLocal(),null,$modulo);
					if( empty($sistema) ) echo "<option value='' selected>Seleccionar</option>";
					
					while( $row = $result->fetch_assoc() ){
						if( $sistema == $row['SISTEMA'] ){
							echo "<option value='".$row['SISTEMA']."' selected>".$row['SISTEMA']."</option>";
						}
						else{
							echo "<option value='".$row['SISTEMA']."'>".$row['SISTEMA']."</option>";
						}
					} ?>
			</select>
		</p>
		<?php 
		if( !empty($sistema) ){ ?>
			<p>
				Plataforma:
				<select name='plataforma' onchange='this.form.submit()'>
				<?php 
					$params = [ $sistema ];
					$result = $cCfn->exeQuery("plataform_bit",$params,'s',$cCfn->getLocal(),null,$modulo);
					$hit=0;
					if( empty($plataforma) ) echo "<option value='' selected>Seleccionar</option>";
					while ($row = $result->fetch_assoc()) {
						if ($plataforma == $row['PLATAFORMA'] ){
							echo "<option value='".$row['PLATAFORMA']."' selected>".$row['PLATAFORMA']."</option>\n";
							$hit=1;
						}
						else{
							echo "<option value='".$row['PLATAFORMA']."'>".$row['PLATAFORMA']."</option>\n";
						}
					}
					if( empty($hit) && !empty($plataforma) ){
						echo "<option value='' selected>Seleccionar</option>\n";
						$plataforma="";
						$servicio="";
						$elemento="";
					} ?>
				</select>
			</p>
			<?php 
		}
		if( !empty($plataforma) ){ ?>
			<p>
				Servicio:
				<select name='servicio' onchange='this.form.submit()'>
				<?php 
					$params = [ $sistema, $plataforma ];
					$result = $cCfn->exeQuery("servicio_bit",$params,'ss',$cCfn->getLocal(),null,$modulo);
					$hit=0;
					if( empty($servicio) ) echo "<option value='' selected>Seleccionar</option>";
					while ($row = $result->fetch_assoc()) {
						if( $servicio == $row['SERVICIO'] ){
							echo "<option value='".$row['SERVICIO']."' selected>".$row['SERVICIO']."</option>";
							$hit=1;
						}
						else{
							echo "<option value='".$row['SERVICIO']."'>".$row['SERVICIO']."</option>";
						}
					}
					if( empty($hit) && !empty($servicio) ){
						echo "<option value='' selected>Seleccionar</option>";
						$servicio="";
						$elemento="";
					} ?>
					</select>
					</p>
					<?php 
		}
		if( !empty($servicio) ){ ?>
			<p>
				Elemento:
				<select name='elemento' onchange='this.form.submit()'>
				<?php 
					$params = [ $sistema, $plataforma, $servicio ];
					$result = $cCfn->exeQuery("elemento_bit",$params,'sss',$cCfn->getLocal(),null,$modulo);
					$hit=0;
					
					if( empty($elemento) ) echo "<option value='' selected>Seleccionar</option>";
					
					while ($row = $result->fetch_assoc()) {
						if ($elemento == $row['ELEMENTO'] ){
							echo "<option value='".$row['ELEMENTO']."' selected>".$row['ELEMENTO']."</option>";
							$hit=1;
						}
						else{
							echo "<option value='".$row['ELEMENTO']."'>".$row['ELEMENTO']."</option>";
						}
					}
					if( empty($hit) && !empty($elemento) ){
						echo "<option value='' selected>Seleccionar</option>\n";
						$elemento="";
					} ?>
				</select>
			</p>
			<?php
		} ?>
		<p>
			<input name="accion" type="submit" value="Cambiar">&nbsp;&nbsp;<input name="accion" type="submit" value="Volver">
		</p>
	</form>
			