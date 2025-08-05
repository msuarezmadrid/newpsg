<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	include_once("ver_log.php");
	
	$offset = $_GET["offset"] ?? $_POST["offset"] ?? '';
	$nr_records = $_GET["nr_records"] ?? $_POST["nr_records"] ?? '';
	$horiz = $_GET["horiz"] ?? $_POST["horiz"] ?? '';
	$vert = $_GET["vert"] ?? $_POST["vert"] ?? '';
	
	if ($offset=="") $offset=0;
	if ($nr_records=="") $nr_records=30;
	
	if ( $horiz == "<-" ){
		$nr_records=$nr_records-5;
		if ($nr_records < 5) $nr_records=5;
	}
	else if ($horiz == "->"){
		$nr_records=$nr_records+5;
	}
	
	if ($vert == "^"){
		$offset=$offset-$nr_records;
		if ($offset < 0) $offset=0;
	}
	else if ($vert == "v"){
		$offset=$offset+$nr_records;
	} ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Subgerencia Operaciones y Mantenimiento de Red</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h3 align="CENTER">	Logbook	</h3>
			<form action="scroll_log.php" method="post" align="CENTER">
				<input name="horiz" type="submit" value="<-" />Records: <?php echo $nr_records?><input name="horiz"  type="submit" value="->" />
				<input name="vert" type="submit" value="^" />Offset: <?php echo $offset?><input name="vert"  type="submit" value="v" />
				<input type="submit" value="Refresh" />
				<input type="hidden" name="offset" value="<?php echo $offset?>" /> 
				<input type="hidden" name="nr_records" value="<?php echo $nr_records?>" />
			</form>
			<p>
			<?php
				$params = [
					":offset" => $offset,
					":nr_records" => $nr_records
				];
				$sql=$cCfn->getQuery("sel_log_scroll", $params);
				ver_log(null,$sql); ?>
			</p>
		</body>
	</html>