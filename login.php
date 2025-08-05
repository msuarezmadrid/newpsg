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
<<<<<<< Updated upstream
	$politicas=null;
    if( isset($_POST["ingresarLogin"]) && $_POST["ingresarLogin"] == "si" ){
		$msje = $cCfn->login( true, true );
		echo "<script>sessionStorage.removeItem('modalShown');</script>"; # PARA LIMPIAR LA VAR DE SESSION DE JAVASCRIPT 
    }
	if( $msje === "Empty" ) {
		$class="alert alert-danger";
		$msje="ERROR! Datos incorrectos o inexistentes! ";
	}
	$modalInfo=$cCfn->getInfoModalLogin(); # POLITICAS DE SEGURIDAD 
?>
<!DOCTYPE html>
<html > 
    <head>
        <title><?php echo $title; ?> - Entel</title>
		<link rel="icon" href="<?php echo $favicon; ?>" type="image/png">
        <?php include("./lib/header.php"); ?>
		<script>sessionStorage.clear();</script>
    </head>
    <body >
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12"><h2 style="text-align:center;"><?php echo $title; ?></h2></div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 col-md-4 col-lg-4">
					<div class="<?php echo $class; ?>" ><?php echo $msje; ?></div>
                    <form action="<?php echo basename(__FILE__); ?>" method="POST" class="">
						<div class="form-group">
							<div class="input-group">
								Login
							</div>
						</div>
                        <div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="txtUsuario" type="text" class="form-control input-sm col-xs-3" name="txtUsuario" placeholder="Ingrese usuario">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="txtPwd" type="password" class="form-control input-sm col-xs-3" name="txtPwd" placeholder="Ingrese clave">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<button type="submit" name="ingresarLogin" id="ingresarLogin" value="si" class="btn btn-primary">Ingresar</button>
							</div>
						</div>
                    </form>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4">
                    <p style="text-align:center;"><img src="./img/entel_logo.gif" class="img-rounded" alt="Entel"></p>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4"></div>
            </div>
        </div>
        <br/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8 col-md-8 col-lg-8">
				
					<div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#collapse1">Se olvid&oacute; de su clave?</a>
								</h4>
							</div>
							<div id="collapse1" class="panel-collapse collapse">
								<div class="panel-body">
									Recupere su clave en su m&oacute;vil. (por SMS)<br/>
									Ingrese su nombre de usuario. (solo usuarios registrados recibir&aacute;n una clave temporal que caducar&aacute; en 24 horas)<br/>
									Recuerde que al generar la nueva clave, esta debe cumplir con las <a href="#" data-toggle="modal" data-target="#modalPoliticas" >pol&iacute;ticas de seguridad</a>.<br/>
								</div>
								<div class="panel-footer">
									<form action="#" method="POST" class="form-inline">
										<div class="form-group">
											<label for="rUser">Usuario:</label>
											<input type="text" class="form-control input-sm" id="rUser" name="rUser">
										</div>
										<button type="submit" class="btn btn-default">Enviar</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					
                </div>
				<div class="col-sm-8 col-md-8 col-lg-8"></div>
            </div>
        </div>

        <!-- MODAL POLITICAS -->
		<div id='modalPoliticas' class='modal fade' role='dialog'>
			<div class='modal-dialog ' id='modalDialog' role='document'>
				<div class='modal-content' id='modalContent' ><?php echo $modalInfo; ?></div>
			</div>
		</div>
        <!-- FIN MODAL -->
    </body>
    
</html>
=======
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
					<div class="col-sm-12 col-md-12 col-lg-12"><h2 style="text-align:center;"><?php echo $title; ?></h2></div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-4 col-md-4 col-lg-4">
						<div class="<?php echo $class; ?>" ><?php echo $msje; ?></div>
						<form action="<?php echo basename(__FILE__); ?>" method="POST" class="">
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
						</form>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<p style="text-align:center;"><img src="./img/entel_logo.gif" class="img-rounded" alt="Entel"></p>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4"></div>
				</div>
			</div>
			<br/>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<strong>Se olvid&oacute; de su clave?</strong>
						<ul>
							<li>Recupere su clave en su m&oacute;vil. (por SMS)</li>
							<li>Ingrese su nombre de usuario. (solo usuarios registrados recibir&aacute;n una clave temporal que caducar&aacute; en 24 horas)</li>
							<li>Recuerde que al generar la nueva clave, esta debe cumplir con las <a href='#' data-toggle='modal' data-target='#modalPoliticas'>pol&iacute;ticas de seguridad</a>.</li>
						</ul>
						<p>
							<form action="#" method="POST" class="form-inline">
								<div class="form-group">
									<label for="rUser">Usuario:</label>
									<input type="text" class="form-control" id="rUser" name="rUser">
								</div>
								<button type="submit" class="btn btn-default">Enviar</button>
							</form>
						</p>
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
>>>>>>> Stashed changes
