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
	
	$severidad = $_GET["severidad"] ?? $_POST["severidad"] ?? '';
	$modo_bitacora = $_GET["modo_bitacora"] ?? $_POST["modo_bitacora"] ?? '';
	
	include_once("ver_bitacora.php");
	
	$logged_usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	if( empty($severidad) ) $severidad=3;
	
	$sel_opc=null;
	$result = $cCfn->exeQuery("severidad_bit",null,null,$cCfn->getLocal(),null,$modulo);
	$sel_opc="<option value='' >Seleccionar</option>";
	while ($s = $result->fetch_assoc()) {
		if( $s['ID'] == $severidad ){
			$sel_opc.="<option value='".$s['ID']."' selected>".$s['NOMBRE']."</option>";
			$sev_final = $s['NOMBRE'];
		}
		else{
			$sel_opc.="<option value='".$s['ID']."' >".$s['NOMBRE']."</option>";
		}
	}
	?>
	<h3 align="CENTER">	Bit&aacute;cora con Severidad mayor o igual que <?php echo $sev_final; ?></h3>
	<form action="<?php echo $base_path; ?>/bitacora_sev.php" method="POST">
		<p>
			<select name="severidad" >
				<?php echo $sel_opc; ?>
			</select>
		</p>
		<p>
			<input type="submit" value="Refresh" />
			<input type="radio" name="modo" <?php if ( empty($modo_bitacora) ){ echo "checked"; } ?> value="0" />Expanded
			<input type="radio" name="modo" <?php if ( $modo_bitacora==1 ){ echo "checked"; } ?> value="1" />Compressed
		</p>
	</form>
	<?php
		$params = [ $severidad ];
		$sql="sel_bit_sev";
		ver_bitacora($sql,$logged_usr,$modo_bitacora); ?>
