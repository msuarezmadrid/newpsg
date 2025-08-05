<?php 
	
	function generate_string($input, $strength = 16) {
		$input_length = strlen($input);
		$random_string = '';
		for($i = 0; $i < $strength; $i++) {
			$random_character = $input[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}
		
		return $random_string;
	}
	
	function registro_intento($user,$subservicio,$status,$hashpwd,$glosa=""){
		global $cCfn;
		$last_conexion=NULL;
		$servicio="web_psg";
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$server_ip=$_SERVER['SERVER_ADDR'];
		$res=traeLastconexion($user);
		
		while ($row = $res->fetch_assoc()) {
			if ( !empty($row['ingreso']) ){
				$last_conexion=$row['ingreso'];  // Imprime los datos de la fila
			}
		}
		
		
		## REGISTRO EN ERROR_LOGIN_LOG 
		$queryErrorlog="INSERT INTO intradb.ERROR_LOGIN_LOG(`FECHA`, `IP_HOST`, `SERVICIO`, `SUBSERVICIO`, `STATUS`, `IP_ORIGEN`, `USUARIO`, `GLOSA`, `HASHPWD`, `LAST_CONEXION`, `CORREO_ENVIADO`) 
		VALUES( NOW(),?,?,?,?,?,?,?,?,?,null ) ";
		//$cCfn->my_log("[". __FUNCTION__ ."] Query->[" .$queryErrorlog. "]" );
		$params=[ $server_ip,$servicio,$subservicio,$status,$remote_ip,$user,$glosa,$hashpwd,$last_conexion ];
		$result = $cCfn->exeQuery(null,$params,'sssssssss',$cCfn->getLocal(),$queryErrorlog,null);
		## REGISTRO EN COLA DE MENSAJES 
		// $queryQueue="INSERT INTO intradb.MAILS_BLOQUEO_USUARIOS ( `USUARIO`, `CORREO_DESTINO`, `INGRESO`, `TEXTO`, `ASUNTO`, `ESTADO_USUARIO`, `ESTADO_MAIL`, `RESPONSABLE`, `RESPONSABLE_ENTEL`, `MAIL_ADC`, `MAIL_OVAS`) 
		// VALUES( '$usuario', '$mail', NOW(), '$mensaje', '$subject', $estado, '$estadoMail', '$responsable', '$responsable_entel', '$correoADC', '$correoOvas' ) ";
		
		$status=($status=="FALLA") ? "NOOK" : "OK" ;
		$data['host']=$server_ip;
		$data['servicio']=$servicio;
		$data['subservicio']=$subservicio;
		$data['status']=$status;
		$data['iporigen']=$remote_ip;
		$data['username']=$user;
		$data['glosa']=$glosa;
		$data['hashpwd']=$hashpwd;
		$data['last_conexion']=$last_conexion;
		## ESCRIBE ARCHIVO DE LOG 
		escribe_log($data);
	}
	
	function seteaFecha(){
		$fecha=date("Y-m-d H:i:s");
		return $fecha;
	}
	
	function intento_fallido($user){
		global $cCfn;
		$params=[$user];
		## ACTUALIZO INTENTO FALLIDO 
		$sql="UPDATE intradb.users SET intentos_fallidos = IFNULL( intentos_fallidos, 0 ) + 1 WHERE usr = ? ";
		$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
	}
	
	function intento_exitoso($user){
		global $cCfn;
		$params=[$user];
		## ACTUALIZO INTENTO EXITOSO 
		$sql="UPDATE intradb.users SET intentos_fallidos = 0 WHERE usr = ? ";
		$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
		anulaBloqueo($user);
	}
	
	function traerParamPass($llave){
		global $cCfn;
		$params=[ $llave ];
		$sql="SELECT * FROM intradb.params_password WHERE llave=? AND Activo=1 ";
		$result = $cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
		$res = $result->fetch_all(MYSQLI_ASSOC);
		return $res;
	}
	
	function actualiza_estado($user,$estado){
		global $cCfn;
		$params=[$estado,$user];
		$sql="UPDATE intradb.users SET estado=$estado WHERE usr='$user' ";
		$result = $cCfn->exeQuery(null,$params,'is',$cCfn->getLocal(),$sql,null);
		return $result['affected'];
	}
	
	function check_estado_usuario($user,$intentos_max){
		global $cCfn;
		$params=[ $intentos_max,$intentos_max, $user ];
		$sql="SELECT CASE 
				WHEN estado IN (2,3) AND intentos_fallidos >= ? THEN 'bloqueada por intentos fallidos' 
				WHEN estado IN (2,3) AND intentos_fallidos < ? THEN	'bloqueada por inactividad' ELSE 'CUENTA_OK' 
			END check_cta 
		FROM intradb.users 
		WHERE usr = ? ";
		$result = $cCfn->exeQuery(null,$params,'iis',$cCfn->getLocal(),$sql,null);
		$res = $result->fetch_all(MYSQLI_ASSOC);
		return $res;
	}
	
	function traeLastconexion($user){
		global $cCfn;
		$params=[$user];
		$sql="SELECT MAX(INGRESO) ingreso FROM intradb.LOG WHERE usr=? AND HOST LIKE '%.%' ";
		$result = $cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
		return $result;
	}
	
	function escribe_log($array){
		$ahora=seteaFecha();
		$str=NULL;
		//$debug_file=fopen("/tmp/login_log","a+");
		$debug_file=fopen("/var/log/psg/login_log","a+");
		$str="fecha=$ahora;";
		foreach( $array as $key=>$value  ){
			$str.="$key=$value;";
		}
		$str.="correo_enviado_a=''";
		fwrite($debug_file,"$str\n");
		fclose($debug_file);
	}
	
	function traePoliticas(){
		$result=traerParamPass("POLITICAS_CLAVE");
		if (!empty($result)) $msje_politicas = $result[0]['mensaje_error'];

		$result=traerParamPass("LARGO_MINIMO");
		if (!empty($result)) $largo_min = $result[0]['descripcion'];

		$result=traerParamPass("LARGO_MAXIMO");
		if (!empty($result)) $largo_max = $result[0]['descripcion'];

		$result=traerParamPass("INCLUSION_CARACTERES_ESPECIALES");
		if (!empty($result)) $chars = $result[0]['descripcion'];

		## REEMPLAZAMOS VARIABLES 
		$msje_politicas=str_replace("_min_",$largo_min,$msje_politicas);
		$msje_politicas=str_replace("_max_",$largo_max,$msje_politicas);
		$politicas=str_replace("_chars_",$chars,$msje_politicas);
		
		return $politicas;
	}
	
	function validate_format($password,$inclusions){
		$validation = 0;
		$pattern = preg_quote($inclusions, '/');
		
		$validation = intval(preg_match('/[a-z]/', $password)        // has at least one char lowercase
				   && preg_match('/[A-Z]/', $password)		// has at least one char uppercase
				   && preg_match('/[' . $pattern . ']/', $password) 	// has at least one special char
				   && preg_match('/\d/', $password)); 			// has at least one digit
		return $validation;
	}
	
	function validate_exclusions($password,$exclusions){
		$valid=1;
		if (is_array($exclusions)&&!empty($exclusions)) {
			foreach ($exclusions as $exclude) {
				$x=stripos($password,$exclude); 
				if(strcmp($x, "") !== 0 && $x>=0){
					//echo "stripos(".$string.", ".$exclude."): ".$x;
					//echo '<script type="text/javascript"> alert("'.$exclude.'-'.$x.'"); </script>';
					$valid = 0;
					break;
				}
			}
		}
		return $valid;
	}
	
	function anulaBloqueo($user){
		global $cCfn;
		$params=[$user];
		$sql="SELECT ID FROM intradb.MAILS_BLOQUEO_USUARIOS WHERE USUARIO=? AND ESTADO_MAIL='PENDIENTE' ";
		$result = $cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
		// $nRow=$result[0]['rows'];
		if( sizeof($result) > 0 ){
			$update="UPDATE intradb.MAILS_BLOQUEO_USUARIOS SET ESTADO_MAIL='ENVIADO' WHERE USUARIO=? ";
			$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$update,null);
		}
		
		return true;
	}
	
	
	
?>
