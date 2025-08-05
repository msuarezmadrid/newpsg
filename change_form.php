<?php
	session_start();
	require "autoloader.php";
    use App\Funciones\classFunciones;
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfnSeg = $container->getFuncionesSeguridad();
	
	$favicon=$cCfn->favicon();
	$encriptado = ( isset($encriptado) ) ? $_REQUEST["encriptado"] : NULL ;
	
	$user=base64_decode($_REQUEST['usr']);
	
	$politicas = $cCfnSeg->traePoliticas(); ?>
	
<!DOCTYPE html>
<html >
	<head>
		<?php include($cCfn->getHeader()); ?> 
	</head>
	<body >
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-md-12'>
					<h1 align='center'>PSG Redes</h1>
				</div>
			</div>
		</div>
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-md-12'>
					<h2>Debe cambiar su clave.</h2>
				</div>
			</div>
		</div>

		<div class='container-fluid'>
			<div class='row'>
				<div class='col-md-12'>

					<form name='aut' action='change_pass.php' method='post'>
						<input type='hidden' name='user' value='<?php echo $user; ?>'>

						<table class='table-condensed'>
							<tr>
								<td>Clave anterior/temporal:</td>
								<td><input size='30%' type='password' name='oldpassw' placeholder='Ingrese su actual o anterior clave' /></td>
							</tr>
							<tr>
								<td>Nueva clave:</td>
								<td><input size='30%' type='password' name='newpassw' placeholder='Ingrese una nueva clave' /></td>
							</tr>
							<tr>
								<td>Confirmar nueva clave:</td>
								<td><input size='30%' type='password' name='newpassw2' placeholder='Confirme su nueva clave' /></td>
							</tr>
							<tr>
								<td><input class='btn btn-default btn-xs' type='submit' value='Cambiar' /></td>
								<td><input class='btn btn-default btn-xs' type='reset' value='Limpiar' /></td>
							</tr>
							<tr>
								<td colspan='2'>&nbsp;</td>
							</tr>
							<tr>
								<td colspan='2'>
									Su nueva clave, debe cumplir con las <a href='#' data-toggle='modal' data-target='#modalPoliticas'>pol&iacute;ticas de seguridad</a> de Entel.
								</td>
							</tr>
						</table>

					</form>

				</div>
			</div>
		</div>

		<!-- MODAL POLITICAS -->
		<div id='modalPoliticas' class='modal fade' role='dialog'>
			<div class='modal-dialog '>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
						<h4 class='modal-title'>Pol&iacute;ticas de seguridad Entel</h4>
					</div>
					<div class='modal-body'>
						<?php echo $politicas; ?>
					</div>
					<div class='modal-footer'></div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL -->
	</body>
</html>