<?php
    session_start();
	require "../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	
	$cCfn->checkSession();
	
    $favicon=$cCfn->favicon();
    $header=$cCfn->siteHeader();
	
	$file=basename(__FILE__);
	
	$bodyCss=$cCfn->bodyCss();
	$boostrapCss=$cCfn->boostrapCss();
	$functions=$cCfn->functions();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
    
	$title=$cCfn->title();
	
	$gVacaciones = $cCfn->getVacaciones($_SESSION["user"]);
	$vac = $gVacaciones->fetch_assoc();
	$vacation = (!empty($vac['VACACIONES'])) ? $vac['VACACIONES'] : NULL;
	$turno = (!empty($vac['TURNO'])) ? $vac['TURNO'] : NULL;
	
	$arrPendientes=array();
	$arrPend=array();
	$resTrabajos = $cCfn->getTrabajos($_SESSION["user"]); 
	$gTrabajos = $resTrabajos->fetch_assoc();
	$arrPendientes['trabajos']=$gTrabajos['N'];
	
	$resProblemas = $cCfn->getProblemas($_SESSION["user"]); 
	$gProblemas = $resProblemas->fetch_assoc();
	$arrPendientes['problemas']=$gProblemas['N'];
	
	$resTareas = $cCfn->getTareas($_SESSION["user"]); 
	$gTareas = $resTareas->fetch_assoc();
	$arrPendientes['tareas']=$gTareas['N'];
	
	$resBitacoras = $cCfn->getBitacoras($_SESSION["user"]); 
	$gBitacoras = $resBitacoras->fetch_assoc();
	$arrPendientes['bitacoras']=$gBitacoras['N'];
	
	$resInventario = $cCfn->getInventario($_SESSION["user"]); 
	$gInventario = $resInventario->fetch_assoc();
	$arrPendientes['inventario']=$gInventario['N'];
	
	$resMensajes = $cCfn->getMensajes($_SESSION["user"]); 
	$gMensajes = $resMensajes->fetch_assoc();
	$arrPendientes['mensajes']=$gMensajes['N'];
	
	$modalInfo=$cCfn->getInfoModalInicio();
	
	$base_path=BASE_URL . "/site/logbook";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo $title; ?> - Entel</title>
        <?php include($header); ?> 
    </head>
    <body > 
		<div class="container-fluids">
			<!-- Navbar -->
			<nav class="navbar navbar-light " style="background-color: #e6e6e6;" >
				<div class="container-fluid">
					<div class="navbar-header">
						<button class="btn btn-primary navbar-btn" onclick="toggleSidebar()">
							<span class="glyphicon glyphicon-menu-hamburger"></span>
						</button>
						<h6 class="navbar-brand" >Hola <a href='home'><?php echo htmlspecialchars($_SESSION["user"]); ?></a></h6>
					</div>
					<div class="d-flex justify-content-center align-items-center" id="navbar-title" ><h3><?php echo $title; ?></h3></div>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../logout.php" class="nav-link"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
					</ul>
				</div>
			</nav>
			
			<div class="content-wrapper" >
				<?php include("sidebar.html"); ?>
				
				<div id="mainContent" class="main-content" data-current-url='' >
					<p><br/><br/></p>
					<h2>Bienvenido </h2>
					<p><strong><?php 
						if( !empty($vacation) && $vacation === "SI" ){
							echo "Usted se encuentra de vacaciones.";
						}
						elseif( !empty($turno) && $turno === "SI" ){
							echo "Usted se encuentra de turno.";
						}
						elseif( !empty($turno) && !empty($vacation) && $turno === "SI" && $vacation === "SI" ){
							echo "Conflicto en vacaciones-turno.";
							$cCfg->my_log("[$file] Turno: $turno | Vacaciones: $vacation " );
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
			</div>
			<div id='modalAviso' tabindex='-1' class='modal fade' role='dialog' >
				<div class='modal-dialog ' id='modalDialog' role='document'>
					<div class='modal-content' id='modalContent' ><?php echo $modalInfo; ?></div>
				</div>
			</div>
		</div>
    </body>
</html>