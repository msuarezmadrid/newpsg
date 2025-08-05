<?php
	/* include '../../protected/config_session.php';
	include_once("../../protected/config.php");
	include_once("../../protected/control.php");
	include_once("../../protected/user.php");
	include_once("../mark/jump_mark.php"); */
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	
	$db="intradb";
	
	$accion = $_GET["accion"] ?? $_POST["accion"] ?? '';
	$clasificacion = $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? '';
	$severidad = $_GET["severidad"] ?? $_POST["severidad"] ?? '';
	$sistema = $_GET["sistema"] ?? $_POST["sistema"] ?? '';
	$plataforma = $_GET["plataforma"] ?? $_POST["plataforma"] ?? '';
	$servicio = $_GET["servicio"] ?? $_POST["servicio"] ?? '';
	$elemento = $_GET["elemento"] ?? $_POST["elemento"] ?? '';
	$tipo = $_GET["tipo"] ?? $_POST["tipo"] ?? '';
	$falla = $_GET["falla"] ?? $_POST["falla"] ?? '';
	$cerrar = $_GET["cerrar"] ?? $_POST["cerrar"] ?? '';
	$comentario = $_GET["comentario"] ?? $_POST["comentario"] ?? '';
	$titulo = $_GET["titulo"] ?? $_POST["titulo"] ?? '';
	$sitio = $_GET["sitio"] ?? $_POST["sitio"] ?? '';
	$nodo = $_GET["nodo"] ?? $_POST["nodo"] ?? '';
	$iyear = $_GET["iyear"] ?? $_POST["iyear"] ?? '';
	$imonth = $_GET["imonth"] ?? $_POST["imonth"] ?? '';
	$iday = $_GET["iday"] ?? $_POST["iday"] ?? '';
	$ihour = $_GET["ihour"] ?? $_POST["ihour"] ?? '';
	$iminute = $_GET["iminute"] ?? $_POST["iminute"] ?? '';
	$fyear = $_GET["fyear"] ?? $_POST["fyear"] ?? '';
	$fmonth = $_GET["fmonth"] ?? $_POST["fmonth"] ?? '';
	$fday = $_GET["fday"] ?? $_POST["fday"] ?? '';
	$fhour = $_GET["fhour"] ?? $_POST["fhour"] ?? '';
	$fminute = $_GET["fminute"] ?? $_POST["fminute"] ?? '';


	if( $accion != "Ingresar" ){
		include_once("crear_bitacora.php");
		exit;
	}
	
	/* if ( ($clasificacion == "") or ($severidad == ""))
	{
	  echo" <script languaje=javascript>alert('Debe completar los campos obligatorios --- 1 $clasificacion --- 2 $severidad')</script>";
	  echo "marcador 2 <br>";
	  include_once("crear_bitacora_gsm.php");
	  exit;
	} */
	
	if( $clasificacion == "FALLA" && empty($falla) ){
		echo" <script languaje=javascript>alert('Debe completar los campos obligatorios')</script>";
		include_once("crear_bitacora_gsm.php");
		exit;
	}
	
	if( $cerrar == "C" && empty($comentario) ){
		include_once("crear_bitacora_gsm.php");
		exit;
	}
	
	// $usr=get_user();
	$usr=$cCfn->getUser();
	
	if( !empty($falla) ){
		$titulo=$falla . " " . $titulo;
	}
	else{
		$titulo=$clasificacion . " " . $titulo;
	}
	
	$params = [
		":titulo" => $titulo,
		":usr" => $usr,
		":tipo" => $tipo,
		":severidad" => $severidad,
		":lista" => NULL,
		":bit_class" => $clasificacion,
		":usr" => $usr 
	];
	$sql=$cCfn->getQuery("nueva_bit_opersis", $params);
	// echo "sql nueva_bit_opersis: ".$sql."<br/>";
	$result = $cCfn->exeQuery($sql,$db);
	
	//$id=mysqli_insert_id();
	$id=$result["insert_id"];
	
	
	### debug 
	$archivo = './debug_bit.txt';
	$fp = fopen($archivo, "a");
	
	$string = "\nID: $id ,nueva_bitacora.php\n";
	$write = fputs($fp, $string);
	
	$string = "Datos: $sistema $plataforma  $servicio $elemento\n";
	$write = fputs($fp, $string);
	
	$string = "Insert: $sql\n";
	$write = fputs($fp, $string);
	
	fclose($fp);
	#####################################################################
	
	if( !empty($comentario) ){
		$params = [
			":usr" => $usr,
			":comentario" => $comentario,
			":id" => $id 
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
	
	
	$lista="";
	if( !empty($sitio) ){
		foreach( $sitio as $key => $value ){
			$lista.=$value."/";
			
			$params = [
				":id" => $id, 
				":value" => addslashes($value)
			];
			$sql=$cCfn->getQuery("nueva_bit_bitSitio", $params);
			// echo "sql nueva_bit_bitSitio: ".$sql."<br/>";
			$result = $cCfn->exeQuery($sql,$db);
			
			##########################################################################
			
			$site=trim(substr($value,0,6));
			$params = [
				":id" => $id, 
				":site" => $site
			];
			$sql=$cCfn->getQuery("update_bit4", $params);
			// echo "sql update_bit4: ".$sql."<br/>";
			$result = $cCfn->exeQuery($sql,$db);
			$log="Insertando desde .../logbook/nueva_bitacora.php -- $sql ";
			// my_log($log);
			$cCfn->my_log($log);
			
			#######
			## CVZ
			## 23-06-2017
			## Modificacion: Se agrega logica de actualizacion de tablas act_alarm_panel_TEC, id bitacora| titulo, inicio 
			#######
			$params = [
				":id" => $id 
			];
			$sql=$cCfn->getQuery("select_bit", $params);
			// echo "sql select_bit: ".$sql."<br/>";
			$row_act = $cCfn->exeQuery($sql,$db);
			$ss = substr($site, 1, 5);
			
			$params = [
				":id" => $id,
				":titulo" => $titulo,
				":inicio" => $inicio,
				":ss" => $ss
			];
			$sql=$cCfn->getQuery("update_2g", $params);
			$cCfn->exeQuery($sql,$db);
			
			$params = [
				":id" => $id,
				":titulo" => $titulo,
				":inicio" => $inicio,
				":ss" => $ss
			];
			$sql=$cCfn->getQuery("update_3g", $params);
			$cCfn->exeQuery($sql,$db);
			
			$params = [
				":id" => $id,
				":ss" => $ss
			];
			$sql=$cCfn->getQuery("update_4g", $params);
			$cCfn->exeQuery($sql,$db);
			
			##########################################################################
		}
	}
	
	if( !empty($nodo) ){
		foreach($nodo as $key => $value){
			$lista.=$value."/";
			$params = [
				":id" => $id,
				":servicio" => $value
			];
			$sql=$cCfn->getQuery("nueva_bit_opersis2", $params);
			$cCfn->exeQuery($sql,$db);
		}
	}
	
	if( !empty($servicio) ){
		foreach($servicio as $key => $value){
			$lista.=$value."/";
			$params = [
				":id" => $id,
				":elemento" => $value
			];
			$sql=$cCfn->getQuery("nueva_bit_opersis3", $params);
			$cCfn->exeQuery($sql,$db);
		}
	}
	
	$params = [
		":id" => $id,
		":lista" => $lista
	];
	$sql=$cCfn->getQuery("update_bit5", $params);
	$cCfn->exeQuery($sql,$db);
	
	
	if( !empty($ihour) && !empty($iminute) ){
		$time=$iyear . "-" . $imonth . "-" . $iday . " " . $ihour . ":" . $iminute . ":00";	
		$params = [
			":id" => $id,
			":time" => $time
		];
		$sql=$cCfn->getQuery("update_bit2", $params);
		$cCfn->exeQuery($sql,$db);
	}

	if( !empty($fhour) && !empty($fminute) ){
		$time=$fyear . "-" . $fmonth . "-" . $fday . " " . $fhour . ":" . $fminute . ":00";
		$params = [
			":id" => $id,
			":time" => $time
		];
		$sql=$cCfn->getQuery("update_bit3", $params);
		$cCfn->exeQuery($sql,$db);
	}
	
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Bitacora</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<form name='test' action='mi_bitacora.php' method='post'>
				Bitacora <?php echo $id; ?> creada.
				<p>
					<input name='accion' type='submit' value='Volver' >
				</p>
			</form>
		</body>
	</html>
