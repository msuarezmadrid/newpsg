<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	
	$cCfn->checkSession();
	$modulo="logbook";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	$bit_sev_id=null;
	$params = [ $id	];
	$result = $cCfn->exeQuery("edit_severity",$params,'i',$cCfn->getLocal(),null,$modulo);
	$row = $result->fetch_assoc();
	$bit_sev_id=$row['BIT_SEVERITY'];
	
	$result = $cCfn->exeQuery("severidad_bit",null,null,$cCfn->getLocal(),null,$modulo);
	$sel_opc=null;
	while ($s = $result->fetch_assoc()) {
		if( $s['ID'] == $bit_sev_id ){
			$sel_opc.="<option value='".$s['ID']."' selected>".$s['NOMBRE']."</option>";
			$severity = $s['ID'];
		}
		else{
			$sel_opc.="<option value='".$s['ID']."' >".$s['NOMBRE']."</option>";
		}
	}
?>
	<!--!DOCTYPE html>
	<html>
		<head>
			<title>Editar bitacora</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body-->
			<h1 align="center">Editar</h1>
			<form name='test' action="do_edit_severity.php" method="post">
				<p>Bit&aacute;cora <?php echo "$id"; ?></p>
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="sev_inicial" value="<?php echo $severity; ?>" />
				<p>
					<select name="severidad">
						<?php echo $sel_opc; ?>
					</select>
				</p>
				<?php 
					if( $cCfn->checkProfile("Adm Problemas") ){ ?>
						<p>
							Severidad mal tipificada:
							<input type="radio" name="mala" checked value="0" />No
							<input type="radio" name="mala"  value="1" />Si
						</p>
				<?php
					}
				?>
				<p>
					<input name="accion" type="submit" value="Ingresar" />&nbsp;&nbsp;<input name="accion" type="submit" value="Volver" />
				</p>
			</form>
		<!--/body>
	</html-->