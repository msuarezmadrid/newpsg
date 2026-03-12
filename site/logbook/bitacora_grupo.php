<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	$date = new DateTime(); # clase DateTime global de PHP 
	
	include_once("ver_bitacora.php");
	
	$logged_usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$modo_bitacora = $_GET["modo"] ?? $_POST["modo"] ?? '';
	$usr = $_GET["usr"] ?? $_POST["usr"] ?? $logged_usr; ?>
	
	
	<h3 align="CENTER">	Bit&aacute;cora de <?php echo "$usr al $ahora"; ?> </h3>
	<form action="<?php echo $base_path; ?>/bitacora_grupo.php" method="post">
		<p>
			<select name="usr" >
			<?php
				if( empty($usr) ){
					echo "<option value='' selected>Seleccionar</option>";
				}
				$result = $cCfn->exeQuery("lista_area",null,null,$cCfn->getLocal(),null,$modulo);
				while ($row = $result->fetch_assoc()) {
					if( $usr == $row['AREA'] ){
						echo "<option value='".$row['AREA']."' selected>".$row['AREA']."</option>";
					}
					else{
						echo "<option value='".$row['AREA']."'>".$row['AREA']."</option>";
					}
				} ?>
			</select>
		</p>
		<p>
			<input type="submit" value="Refresh" />
			<input type="radio" name="modo" <?php if ( empty($modo_bitacora) ) echo "checked"; ?> value="0" />Expanded
			<input type="radio" name="modo" <?php if ( $modo_bitacora==1 ) echo "checked"; ?> value="1" />Compressed
		</p>
	</form>
	<?php
		$params = [ $usr ];
		$types="s";
		$sql="sel_bit_grupo";
		ver_bitacora($sql,$logged_usr,$modo_bitacora); ?>
		