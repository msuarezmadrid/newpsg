<?php 
    session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$base_path=BASE_URL . "/site/usuarios";
	
	$cCfn->checkSession(); ?>
	
	<p><h1 align="CENTER">Identificaci&oacute;n Usuarios</h1></p>
	
	<p>
		<a href="<?php echo $base_path; ?>/listado_psg_users.php">Usuarios de PSG.</a> 
		</p>
	
	<p>
		<a href="<?php echo $base_path; ?>/listado_psg_users_submenu.php">Submen&uacute; usuarios de PSG.</a>
		</p>
	