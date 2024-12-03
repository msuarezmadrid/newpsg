<?php
    require "../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
	
	$file=basename(__FILE__);
    $cCfn = new classFunciones();
    $cCfn->checkSession();
	
    $favicon=$cCfn->favicon();
    $header=$cCfn->siteHeader();
	
	$bodyCss=$cCfn->bodyCss();
	$boostrapCss=$cCfn->boostrapCss();
	$functions=$cCfn->functions();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
    
	$title=$cCfn->title();
	
	$gVacaciones = $cCfn->getVacaciones($_SESSION["user"]);
	$vacation = (!empty($gVacaciones[0]['VACACIONES'])) ? $gVacaciones[0]['VACACIONES'] : NULL;
	$turno = (!empty($gVacaciones[0]['TURNO'])) ? $gVacaciones[0]['TURNO'] : NULL;
	
	$arrPendientes=array();
	$arrPend=array();
	$gTrabajos = $cCfn->getTrabajos($_SESSION["user"]); 
	$arrPendientes['trabajos']=$gTrabajos[0]['N'];
	$gProblemas = $cCfn->getProblemas($_SESSION["user"]); 
	$arrPendientes['problemas']=$gProblemas[0]['N'];
	$gTareas = $cCfn->getTareas($_SESSION["user"]); 
	$arrPendientes['tareas']=$gTareas[0]['N'];
	$gBitacoras = $cCfn->getBitacoras($_SESSION["user"]); 
	$arrPendientes['bitacoras']=$gBitacoras[0]['N'];
	$gInventario = $cCfn->getInventario($_SESSION["user"]); 
	$arrPendientes['inventario']=$gInventario[0]['N'];
	$gMensajes = $cCfn->getMensajes($_SESSION["user"]); 
	$arrPendientes['mensajes']=$gMensajes[0]['N'];
	
	$modalInfo=$cCfn->getInfoModalInicio();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo $title; ?> - Entel</title>
        <?php include($header); ?> 
    </head>
    <body > 
        <!-- Navbar -->
        <nav class="navbar navbar-light " style="background-color: #e6e6e6;" >
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="btn btn-primary navbar-btn" onclick="toggleSidebar()">
                        <span class="glyphicon glyphicon-menu-hamburger"></span>
                    </button>
                    <h6 class="navbar-brand" >Hola <a href='home.php'><?php echo $_SESSION["user"]; ?></a></h6>
                </div>
                <ul class="nav navbar-nav"></ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../logout.php" class="nav-link"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                </ul>
            </div>
        </nav>

        <?php include("sidebar.html"); ?>

        <div id="mainContent" class="main-content">
            <h1 style="text-align:center;"><?php echo $title; ?></h1>
            <h2>Bienvenido <?php // echo $_SESSION["user"]; ?></h2>
			<p><strong><?php 
				if( !empty($vacation) && $vacation === "SI" ){
					echo "Usted se encuentra de vacaciones.";
				}
				elseif( !empty($turno) && $turno === "SI" ){
					echo "Usted se encuentra de turno.";
				}
				elseif( !empty($turno) && !empty($vacation) && $turno === "SI" && $vacation === "SI" ){
					echo "Conflicto en vacaciones-turno.";
					$cCfn->my_log("[$file] Turno: $turno | Vacaciones: $vacation " );
				}
			?></strong></p>
			<p><?php 
				foreach( $arrPendientes as $key => $pend ){
					if( !empty($pend) ) $arrPend[$key]=$pend;
				}
				if( !empty($arrPend) ) echo "Tiene asuntos pendientes:"; 
				else echo "No tienes pendientes:";
			?></p>
			<p><ul><?php 
				foreach( $arrPend as $ky => $valor ){
					if( $ky === "trabajos" ) echo "<li>Trabajos programados asignados: <span class='badge'>".$arrPend[$ky]."</span></li>";
					if( $ky === "problemas" ) echo "<li>Trabajos Programados: <span class='badge'>".$arrPend[$ky]."</span></li>";
					if( $ky === "tareas" ) echo "<li>Tareas pendientes: <span class='badge'>".$arrPend[$ky]."</span></li>";
					if( $ky === "bitacoras" ) echo "<li>Bit&aacute;coras abiertas: <span class='badge'>".$arrPend[$ky]."</span></li>";
					if( $ky === "inventario" ) echo "<li>Inventario por recibir: <span class='badge'>".$arrPend[$ky]."</span></li>";
					if( $ky === "mensajes" ) echo "<li>Mensajes en su inbox: <span class='badge'>".$arrPend[$ky]."</span></li>";
				}
			?></ul></p>
        </div>
		<div id='modalAviso' tabindex='-1' class='modal fade' role='dialog' >
			<div class='modal-dialog ' id='modalDialog' role='document'>
				<div class='modal-content' id='modalContent' ><?php echo $modalInfo; ?></div>
			</div>
		</div>
		
    </body>
</html>