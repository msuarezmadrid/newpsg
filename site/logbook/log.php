<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	$date = new DateTime();
	
	include_once("ver_log.php");
	
	$ahora=$date->format('Y-m-d H:i:s'); ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<meta HTTP-EQUIV="Refresh" content="90">
			<title>Log</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h3 align="CENTER">	Logbook al <?php echo $ahora ?>	</h3>
			<form action="log.php" method="post">
				<p>	<input type="submit" value="Refresh"> </p>
			</form>
			<?php 
				$sql=$cCfn->getQuery("log_sel_conso", NULL);
				
				ver_log(null,$sql); ?>
		</body>
	</html>