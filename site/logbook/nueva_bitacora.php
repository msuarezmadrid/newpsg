<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
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
	
	$params = [ $titulo, $usr, $tipo, $severidad, NULL, $clasificacion, $usr ];
	$types="ssiisss";
	$result = $cCfn->exeQuery("nueva_bit_opersis",$params,$types,$cCfn->getLocal(),null,$modulo);
	
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
		$params = [ $usr, $comentario, $id ];
		$types="ssi";
		$result = $cCfn->exeQuery("nueva_bit_comment",$params,$types,$cCfn->getLocal(),null,$modulo);
	}
	
	if( $cerrar=="C" ){
		$params = [ $id ];
		$types="i";
		$result = $cCfn->exeQuery("update_bit",$params,$types,$cCfn->getLocal(),null,$modulo);
	}
	
	
	$lista="";
	if( !empty($sitio) ){
		foreach( $sitio as $key => $value ){
			$lista.=$value."/";
			
			$params = [ $id, addslashes($value) ];
			$types="is";
			$result = $cCfn->exeQuery("nueva_bit_bitSitio",$params,$types,$cCfn->getLocal(),null,$modulo);
			
			##########################################################################
			
			$site=trim(substr($value,0,6));
			$params = [ $id, $site ];
			$types="is";
			$result = $cCfn->exeQuery("update_bit4",$params,$types,$cCfn->getLocal(),null,$modulo);
			$log="Insertando desde .../logbook/nueva_bitacora.php -- $sql ";
			// my_log($log);
			$cCfn->my_log($log);
			
			$params = [ $id ];
			$types="i";
			$result = $cCfn->exeQuery("select_bit",$params,$types,$cCfn->getLocal(),null,$modulo);
			$ss = substr($site, 1, 5);
			
			$params = [ $id, $titulo, $inicio, $ss ];
			$types="isss";
			$result = $cCfn->exeQuery("update_2g",$params,$types,$cCfn->getLocal(),null,$modulo);
			
			$params = [ $id, $titulo, $inicio, $ss ];
			$types="isss";
			$result = $cCfn->exeQuery("update_3g",$params,$types,$cCfn->getLocal(),null,$modulo);
			
			$params = [ $id, $ss ];
			$types="is";
			$result = $cCfn->exeQuery("update_4g",$params,$types,$cCfn->getLocal(),null,$modulo);
			
			##########################################################################
		}
	}
	
	if( !empty($nodo) ){
		foreach($nodo as $key => $value){
			$lista.=$value."/";
			$params = [ $id, $value ];
			$types="is";
			$result = $cCfn->exeQuery("nueva_bit_opersis2",$params,$types,$cCfn->getLocal(),null,$modulo);
		}
	}
	
	if( !empty($servicio) ){
		foreach($servicio as $key => $value){
			$lista.=$value."/";
			$params = [ $id, $value ];
			$types="is";
			$result = $cCfn->exeQuery("nueva_bit_opersis3",$params,$types,$cCfn->getLocal(),null,$modulo);
		}
	}
	
	$params = [ $id, $lista ];
	$types="is";
	$result = $cCfn->exeQuery("update_bit5",$params,$types,$cCfn->getLocal(),null,$modulo);
	
	if( !empty($ihour) && !empty($iminute) ){
		$time=$iyear . "-" . $imonth . "-" . $iday . " " . $ihour . ":" . $iminute . ":00";	
		$params = [ $id, $time ];
		$types="is";
		$result = $cCfn->exeQuery("update_bit2",$params,$types,$cCfn->getLocal(),null,$modulo);
	}

	if( !empty($fhour) && !empty($fminute) ){
		$time=$fyear . "-" . $fmonth . "-" . $fday . " " . $fhour . ":" . $fminute . ":00";
		$params = [ $id, $time ];
		$types="is";
		$result = $cCfn->exeQuery("update_bit3",$params,$types,$cCfn->getLocal(),null,$modulo);
	}
	
	?>
	<title>Bit&aacute;cora</title>
	<form name='test' action='mi_bitacora.php' method='post'>
		Bitacora <?php echo $id; ?> creada.
		<p>
			<input name='accion' type='submit' value='Volver' >
		</p>
	</form>
	