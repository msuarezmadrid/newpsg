<?php
    require "autoloader.php";
    
    use App\Funciones\classFunciones;

    $cCfn = new classFunciones();
    $favicon=$cCfn->favicon();
    $title=$cCfn->title();
    
	$msje=null;
	$class=null;
    if( isset($_POST["ingresarLogin"]) && $_POST["ingresarLogin"] == "si" ){
        $msje = $cCfn->login( true, true );
    }
	if( $msje === "Empty" ) {
		$class="alert alert-danger";
		$msje="ERROR! Datos incorrectos o inexistentes! ";
	}
?>
<!DOCTYPE html>
<html > 
    <head>
        <title><?php echo $title; ?> - Entel</title>
        <?php include("./lib/header.php"); ?>
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
                        <li>Recuerde que al generar la nueva clave, esta debe cumplir con las pol&iacute;ticas de seguridad.</li>
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
