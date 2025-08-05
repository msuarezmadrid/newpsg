<?php
	/*include '../../protected/config_session.php';
	include_once("../../protected/config.php");
	include_once("../../protected/control.php");
	include_once("../../protected/user.php");
	include_once("../mark/jump_mark.php");*/
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	
	$db="intradb";
	
	$accion 		= $_GET["accion"] ?? $_POST["accion"] ?? '';
	$clasificacion 	= $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? '';
	$severidad		= $_GET["severidad"] ?? $_POST["severidad"] ?? '';
	$sistema 		= $_GET["sistema"] ?? $_POST["sistema"] ?? '';
	$plataforma 	= $_GET["plataforma"] ?? $_POST["plataforma"] ?? '';
	$servicio 		= $_GET["servicio"] ?? $_POST["servicio"] ?? '';
	$elemento 		= $_GET["elemento"] ?? $_POST["elemento"] ?? '';
	$tipo 			= $_GET["tipo"] ?? $_POST["tipo"] ?? '';
	$falla			= $_GET["falla"] ?? $_POST["falla"] ?? '';
	$cerrar 		= $_GET["cerrar"] ?? $_POST["cerrar"] ?? '';
	$comentario 	= $_GET["comentario"] ?? $_POST["comentario"] ?? '';
	$iyear 			= $_GET["iyear"] ?? $_POST["iyear"] ?? '';
	$imonth 		= $_GET["imonth"] ?? $_POST["imonth"] ?? '';
	$iday			= $_GET["iday"] ?? $_POST["iday"] ?? '';
	$ihour			= $_GET["ihour"] ?? $_POST["ihour"] ?? '';
	$iminute		= $_GET["iminute"] ?? $_POST["iminute"] ?? '';
	$fyear			= $_GET["fyear"] ?? $_POST["fyear"] ?? '';
	$fmonth			= $_GET["fmonth"] ?? $_POST["fmonth"] ?? '';
	$fday			= $_GET["fday"] ?? $_POST["fday"] ?? '';
	$fhour			= $_GET["fhour"] ?? $_POST["fhour"] ?? '';
	$fminute		= $_GET["fminute"] ?? $_POST["fminute"] ?? '';
	
	if( $accion == "Volver" ){
		echo "<script type='text/javascript'> window.history.back(); </script>";
		exit;
	}

	if( $accion !== "Ingresar" ){
		include_once("crear_bitacora_opersis.php");
		exit;
	}

	/*if ( ($clasificacion == "") or ($severidad == "") or ($sistema == "") or ($plataforma == "") or ($servicio == "") or ($elemento == "") )
	{
		echo" <script languaje=javascript>alert('Debe completar los campos obligatorios')</script>";
		echo "marcador 3 <br>";
		echo "clasificacion: [$clasificacion] <br> severidad: [$severidad] <br> sistema: [$sistema] <br> plataforma:[$plataforma] <br> servicio: [$servicio] <br> elemento: [$elemento]<br>"; 
		include_once("crear_bitacora_opersis.php");
		exit;
	}*/

	if( $clasificacion == 1 && empty($falla) ){
		echo" <script languaje=javascript>alert('Debe completar los campos obligatorios')</script>";
		include_once("crear_bitacora.php");
		exit;
	}


	if( $cerrar == "C" && empty($comentario) ){
		include_once("crear_bitacora.php");
		exit;
	}
	
	//$usr=get_user();
	$usr=$cCfn->getUser();
	$lista=$sistema . "/" . $plataforma . "/" . $servicio . "/" . $elemento;

	if( !empty($falla) ){
		$clasificacion = $falla;
	}else{
		$clasificacion = $clasificacion;
	}
	
	$params = [
		":titulo" => $clasificacion,
		":usr" => $usr,
		":tipo" => $tipo,
		":severidad" => $severidad,
		":lista" => $lista,
		":bit_class" => $clasificacion,
		":usr" => $usr 
	];
	$sql=$cCfn->getQuery("nueva_bit_opersis", $params);
	// echo "sql nueva_bit_opersis: ".$sql."<br/>";
	$result = $cCfn->exeQuery($sql,$db);
	
	//$id=mysqli_insert_id();
	$id=$result["insert_id"];

	## debug 
	$archivo = "./debug_bit.txt";
	$fp = fopen($archivo, "a");

	$string = "\nID: $id , nueva_bitacora_opersis.php\n";
	$write = fputs($fp, $string);

	$string = "Datos: $sistema $plataforma  $servicio $elemento\n";
	$write = fputs($fp, $string);

	$string = "Insert: $sql\n";
	$write = fputs($fp, $string);

	fclose($fp);
	#####################################################################

	$params = [
		":id" => $id,
		":servicio" => $servicio
	];
	$sql=$cCfn->getQuery("nueva_bit_opersis2", $params);
	// echo "sql nueva_bit_opersis2: ".$sql."<br/>";
	$result = $cCfn->exeQuery($sql,$db);
	
	$params = [
		":id" => $id,
		":elemento" => $elemento
	];
	$sql=$cCfn->getQuery("nueva_bit_opersis3", $params);
	// echo "sql nueva_bit_opersis3: ".$sql."<br/>";
	$result = $cCfn->exeQuery($sql,$db);
	
	
	if( !empty($comentario) ){
		$comentario = addslashes($comentario);
		$params = [
			":usr" => $usr,
			":id" => $id,
			":comentario" => $comentario
		];
		$sql=$cCfn->getQuery("nueva_bit_comment", $params);
		// echo "sql nueva_bit_comment: ".$sql."<br/>";
		$result = $cCfn->exeQuery($sql,$db);
	}
	
	if( $cerrar=="C" ){
		$params = [
			":id" => $id
		];
		$sql=$cCfn->getQuery("update_bit", $params);
		// echo "sql update_bit: ".$sql."<br/>";
		$result = $cCfn->exeQuery($sql,$db);
	}
	
	if( !empty($ihour) && !empty($iminute) ){
		$time=$iyear . "-" . $imonth . "-" . $iday . " " . $ihour . ":" . $iminute . ":00";
		$params = [
			":id" => $id,
			":time" => $time
		];
		$sql=$cCfn->getQuery("update_bit2", $params);
		// echo "sql update_bit2: ".$sql."<br/>";
		$result = $cCfn->exeQuery($sql,$db);
	}
	
	if ( !empty($fhour) && !empty($fminute) ){
	  $time=$fyear . "-" . $fmonth . "-" . $fday . " " . $fhour . ":" . $fminute . ":00";
	  $params = [
			":id" => $id,
			":time" => $time
		];
		$sql=$cCfn->getQuery("update_bit3", $params);
		// echo "sql update_bit3: ".$sql."<br/>";
		$result = $cCfn->exeQuery($sql,$db);
	}
	
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Bitacora</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			Bitacora <?php echo $id; ?> creada.
		</body>
	</html>