<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$usr=$cCfn->getUser();
	$db="intradb";
	
	$id 	= $_GET["id"] ?? $_POST["id"] ?? '';
	$year 	= $_GET["year"] ?? $_POST["year"] ?? '';
	$month 	= $_GET["month"] ?? $_POST["month"] ?? '';
	$day 	= $_GET["day"] ?? $_POST["day"] ?? '';
	$hour 	= $_GET["hour"] ?? $_POST["hour"] ?? '';
	$minute = $_GET["minute"] ?? $_POST["minute"] ?? '';
	
	$time=$year . "-" . $month . "-" . $day . " " . $hour . ":" . $minute . ":00";
	
	$params = [
		":id" => $id,
		":time" => $time
	];
	if( empty($event) ){
		$sql=$cCfn->getQuery("bit_update_time1", $params);
	}
	else{
		$sql=$cCfn->getQuery("bit_update_time2", $params);
	}
	$result = $cCfn->exeQuery($sql,$db);
	
	?>
	<html>
		<head>
			<title>Editar tiempo</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h2 align='center' >Bitacora <?php echo "$id"; ?></h2>
			<h3>Tiempo ingresado.</h3>
		</body>
	</html>