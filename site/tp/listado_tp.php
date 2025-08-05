<?php 
    require "../../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
    $cCfn->checkSession();
	
	$favicon=$cCfn->favicon();
    $header=$cCfn->siteHeader();
	
	$bodyCss=$cCfn->bodyCss();
	$boostrapCss=$cCfn->boostrapCss();
	$functions=$cCfn->functions();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
	
	$basePath=$cCfn->basePath();
	
	$user=$cCfn->getUser();
	
?>
<!DOCTYPE html>
<html lang='es'>
	<head>
		<title>Listado TPs - Entel</title>
		<?php include($header); ?> 
	</head>
</html>
