<?php 
	namespace App\Funciones;
    
	use App\Config\classConfig;
	use App\Config\ConfigInterface;
	
    class classFuncionesSeguridad {
		// 
		private $cCfn;
		private $local;
		
		public function __construct(ConfigInterface $cCfn){
            $this->cCfn = $cCfn;
			$this->local = $this->cCfn->local();
        }
		
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
			// 
			$last_conexion=null;
			$servicio="web_psg";
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$server_ip=$_SERVER['SERVER_ADDR'];
			$result=$this->traeLastconexion($user);
			$res = $result->fetch_all(MYSQLI_ASSOC);
			if( !empty($res) ){
				foreach( $res as $row ) {
					if ( !empty($row['ingreso']) ){
						$last_conexion=$row['ingreso'];  // Imprime los datos de la fila
					}
				}
			}
			// $this->cCfn->my_log("[". __FUNCTION__ ."] last_conex [$last_conexion] res-> ".json_encode($res) );
			
			$params = [
				$server_ip,
				$servicio,
				$subservicio,
				$status,
				$remote_ip,
				$user,
				$glosa,
				$hashpwd,
				$last_conexion
			];
			## REGISTRO EN ERROR_LOGIN_LOG 
			$queryErrorlog="INSERT INTO intradb.ERROR_LOGIN_LOG(`FECHA`, `IP_HOST`, `SERVICIO`, `SUBSERVICIO`, `STATUS`, `IP_ORIGEN`, `USUARIO`, `GLOSA`, `HASHPWD`, `LAST_CONEXION`, `CORREO_ENVIADO`) 
			VALUES( NOW(),?,?,?,?,?,?,?,?,?,'' ) ";
			$result = $this->cCfn->executQuery(null,$params,'sssssssss',$this->local,$queryErrorlog);
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
			$this->escribe_log($data);
		}
		
		function seteaFecha(){
			$fecha=date("Y-m-d H:i:s");
			return $fecha;
		}
		
		function intento_fallido($user){
			$params=[$user];
			## ACTUALIZO INTENTO FALLIDO 
			$sql="UPDATE intradb.users SET intentos_fallidos = IFNULL( intentos_fallidos, 0 ) + 1 WHERE usr = ? ";
			$this->cCfn->executQuery(null,$params,'s',$this->local,$sql);
		}
		
		function intento_exitoso($user){
			$params=[$user];
			## ACTUALIZO INTENTO EXITOSO 
			$sql="UPDATE intradb.users SET intentos_fallidos = 0 WHERE usr = ? ";
			$this->cCfn->executQuery(null,$params,'s',$this->local,$sql);
			$this->anulaBloqueo($user);
		}
		
		function traerParamPass($llave){
			$params=[ $llave ];
			$sql="SELECT * FROM intradb.params_password WHERE llave=? AND Activo=1 ";
			$result = $this->cCfn->executQuery(null,$params,'s',$this->local,$sql);
			$res = $result->fetch_all(MYSQLI_ASSOC);
			return $res;
		}
		
		function actualiza_estado($user,$estado){
			$params=[ $estado,$user ];
			$sql="UPDATE intradb.users SET estado=? WHERE usr=? ";
			$result = $this->cCfn->executQuery(null,$params,'is',$this->local,$sql);
			return $result['affected'];
		}
		
		function check_estado_usuario($user,$intentos_max){
			$params=[ $intentos_max, $intentos_max, $user ];
			$sql="SELECT CASE 
					WHEN estado IN (2,3) AND intentos_fallidos >= ? THEN 'bloqueada por intentos fallidos' 
					WHEN estado IN (2,3) AND intentos_fallidos < ? THEN	'bloqueada por inactividad' ELSE 'CUENTA_OK' 
				END check_cta 
			FROM intradb.users 
			WHERE usr = ? ";
			$result = $this->cCfn->executQuery(null,$params,'iis',$this->local,$sql);
			$res = $result->fetch_all(MYSQLI_ASSOC);
			return $res;
		}
		
		function traeLastconexion($user){
			// 
			$params=[$user];
			$sql="SELECT MAX(INGRESO) ingreso FROM intradb.LOG WHERE usr=? AND HOST LIKE '%.%' ";
			$result=$this->cCfn->executQuery(null,$params,'s',$this->local,$sql);
			return $result;
		}
		
		function escribe_log($array){
			$ahora=$this->seteaFecha();
			$lg = $this->cCfn->getConfig();
			$str=NULL;
			// $debug_file=fopen("/var/log/psg/login_log","a+");
			$debug_file=fopen($lg['logs']['login_log'],"a+");
			$str="fecha=$ahora;";
			foreach( $array as $key=>$value  ){
				$str.="$key=$value;";
			}
			$str.="correo_enviado_a=''";
			fwrite($debug_file,"$str\n");
			fclose($debug_file);
		}
		
		function traePoliticas(){
			$result=$this->traerParamPass("POLITICAS_CLAVE");
			if (!empty($result)) $msje_politicas = $result[0]['mensaje_error'];

			$result=$this->traerParamPass("LARGO_MINIMO");
			if (!empty($result)) $largo_min = $result[0]['descripcion'];

			$result=$this->traerParamPass("LARGO_MAXIMO");
			if (!empty($result)) $largo_max = $result[0]['descripcion'];

			$result=$this->traerParamPass("INCLUSION_CARACTERES_ESPECIALES");
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
			//
			$params=[$user];
			$sql="SELECT ID FROM intradb.MAILS_BLOQUEO_USUARIOS WHERE USUARIO=? AND ESTADO_MAIL='PENDIENTE' ";
			$result = $this->cCfn->executQuery(null,$params,'s',$this->local,$sql);
			$rows=$result->num_rows;
			if( $rows > 0 ){
				$update="UPDATE intradb.MAILS_BLOQUEO_USUARIOS SET ESTADO_MAIL='ENVIADO' WHERE USUARIO=? ";
				$this->cCfn->executQuery(null,$params,'s',$this->local,$update);
			}
			
			return true;
		}
		
		
	}
?>