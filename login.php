<?php
	session_start();
    require "autoloader.php";
    use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	$file=basename(__FILE__);
	
    $favicon=$cCfn->favicon();
    $title=$cCfn->title();
	
    $politicas = $cCfnSeg->traePoliticas();
	
	$msje=null;
	$class=null;
    if( isset($_REQUEST["ingresarLogin"]) && $_REQUEST["ingresarLogin"] == "si" ){
        $msje = $cCfn->login($_REQUEST["txtUsuario"],$_REQUEST["txtPwd"]);
    }
?>
	
	<!DOCTYPE html>
	<html lang='es'>
		<head>
			<title><?php echo $title; ?> - Entel</title>
			<?php include($cCfn->getHeader()); ?>
		</head>
		<body >
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<h2 style="text-align:center;">&nbsp;</h2>
					</div>
				</div>
			</div>
			<div class="container" >
				<div class="row" >
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="<?php echo $class; ?>" ><?php echo $msje; ?></div>
						<form action="<?php echo $file; ?>" method="POST" class="">
							<div class="panel panel-primary centrar" >
								<div class="panel-heading"><h2 style="text-align:center;"><?php echo $title; ?></h2></div>
								<div class="panel-body" >
									<div class="form-group">
										<label for="txtUsuario">Usuario:</label>
										<input type="text" class="form-control" placeholder="Ingrese Usuario" id="txtUsuario" name="txtUsuario" required>
									</div>
									<div class="form-group">
										<label for="txtPwd">Clave:</label>
										<input type="password" class="form-control" placeholder="Ingrese la Clave" id="txtPwd" name="txtPwd" required>
									</div>
									<div class="form-group">
										<button type="submit" name="ingresarLogin" id="ingresarLogin" value="si" class="btn btn-primary">Ingresar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 " style='margin-top:10px' align='center' >
						<p class=''>
							<img src="./img/entel_logo.gif" class="img-rounded" alt="Entel">
						</p>
					</div>
					<!--div class="col-sm-4 col-md-4 col-lg-4"></div-->
				</div>
			</div>
			<br/>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3 col-md-3 col-lg-3"></div>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="panel-group">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" href="#olvido">Se olvid&oacute; de su clave?</a>
									</h4>
								</div>
								<div id="olvido" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
											<li>Recupere su clave en su m&oacute;vil. (por SMS)</li>
											<li>Ingrese su nombre de usuario. (solo usuarios registrados recibir&aacute;n una clave temporal que caducar&aacute; en 24 horas)</li>
											<li>Recuerde que al generar la nueva clave, esta debe cumplir con las <a href='#' data-toggle='modal' data-target='#modalPoliticas'>pol&iacute;ticas de seguridad</a>.</li>
										</ul>
										<form action="<?php echo BASE_URL . "/obtener_pass.php"; ?>" method="POST" class="form-inline">
											<div class="form-group">
												<label for="rUser">Usuario:</label>
												<input type="text" class="form-control" id="rUser" name="rUser" required>
											</div>
											<button type="submit" class="btn btn-default">Enviar</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-md-3 col-lg-3"></div>
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