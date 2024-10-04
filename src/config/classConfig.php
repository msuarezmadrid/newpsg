<?php
	namespace App\Config;

	class classConfig {
		
		private $credentials;
		private $_config;
		private $local = "local";
		private $queries;
		private $activeConn;
		private $oLinkId;
		
		public function __construct(){
			$this->credentials = BASE_PATH . "\src\config\credentials.php";
			$this->queries = BASE_PATH . "\site\lib\querys.php";
			try {
				# ARCHIVO CREDENCIALES 
				if( !file_exists($this->credentials) ){
					throw new \Exception("Msg->No existe o no se encuentra archivo credentials");
				} else {
					$this->getConfig();
				}
				
				# ARCHIVO QUERYS 
				if( !file_exists($this->queries) ){
					throw new \Exception("Msg->No existe o no se encuentra archivo querys.php");
				} else {
					$this->loadQueries();
				}
				
			} catch (\Exception $e) {
				$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "] " );
				return false;
			}
			
		}

		private function loadQueries(){
			global $queries;
			require $this->queries;
			$this->queries = $queries;
		}

		function getQuery($name, $params=[]){
			$query=$this->queries[$name] ?? null;
			if ($query && !empty($params)) {
				foreach ($params as $key => $value) {
					$query = str_replace($key, $value, $query);
				}
			}
			return $query;
		}
		
		private function getConfig(){
			if ($this->_config === null) {
				global $credentials;
				require $this->credentials;
				$this->_config = $credentials;
			}
			return $this->_config;
		}

		/**
		 * Abre una conexión MySQL
		 * var $db 
		**/
		function cConnMysql($db="local"){
			$this->my_log("[". __FUNCTION__ ."] INICIA ");
			try {
				$credentials = $this->getConfig();
				$user = base64_decode($credentials[$db]["user"]);
				$pass = base64_decode($credentials[$db]["pass"]);
				$host = base64_decode($credentials[$db]["host"]);
				$db = $db==="local" ? $credentials[$db]["db"] : $db ;
				
				$this->activeConn = new \mysqli($host,$user,$pass,$db);
				if ( $this->activeConn->connect_error ) {
					throw new \Exception("Msg->[".$this->activeConn->connect_errno."] ".$this->activeConn->connect_error );
				}
				
			} catch (\Exception $e) {	
				$this->my_log("[". __FUNCTION__ ."] ERROR " .$e->getMessage() );
				exit;
			}
			
			$this->my_log("[". __FUNCTION__ ."] Conexion OK ");
			return $this->activeConn;
		}

		/**
		 * Abre una conexión a siebel oracle 
		 * 
		 **/
		function abrirConexionSiebel(){
			//$this->my_log("[". __FUNCTION__ ."] INICIA ");
			try {
				$credenciales = $this->getConfig();
				
				$user = base64_decode($credenciales['db_user_siebel']);
				$pass = base64_decode($credenciales['db_pass_siebel']);
				$host = base64_decode($credenciales['db_host_siebel']);
				
				$this->oLinkId = oci_connect($user,$pass,$host);
				$oError = oci_error();
				if ( !empty($oError) ) {
					throw new \Exception("Msg->No se pudo conectar (" .$oError['code']. ") " .$oError['message'] );
				}
				
			} catch (\Exception $e) {	
				//$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "] " );
				exit;
			}
			//$this->my_log("[". __FUNCTION__ ."] Conexion OK ");
			return $this->oLinkId;
		}

		/**
		 * Abre una conexión a remedy oracle 
		 * 
		 **/
		function abrirConexionRemedy(){
			//$this->my_log("[". __FUNCTION__ ."] INICIA ");
			try {
				$credenciales = $this->getConfig();
				
				$user = base64_decode($credenciales['db_user_remedy']);
				$pass = base64_decode($credenciales['db_pass_remedy']);
				$host = base64_decode($credenciales['db_host_remedy']);
				
				$this->oLinkId = oci_connect($user,$pass,$host);
				$oError = oci_error();
				if ( !empty($oError) ) {
					throw new \Exception("Msg->No se pudo conectar (" .$oError['code']. ") " .$oError['message'] );
				}
				
			} catch (\Exception $e) {	
				//$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "] " );
				exit;
			}
			//$this->my_log("[". __FUNCTION__ ."] Conexion OK ");
			return $this->oLinkId;
		}


		/**
		 * Ejecuta una query y devuelve resulset o filas afectadas 
		 * var $qry, $db 
		 **/
		function exeQuery($qry, $db){
			$this->my_log("[". __FUNCTION__ ."] INICIA ");
			try {
				$this->my_log("[". __FUNCTION__ ."] Query->[" .$qry. "]" );

				$this->activeConn=$this->cConnMysql($db);

				$stmt = $this->activeConn->prepare($qry);
				if( $stmt === false ){
					throw new \Exception(" Msg->[".$this->activeConn->errno."] ".$this->activeConn->error );
				}
				
				$stmt->execute();

				if( stripos($qry, 'SELECT') === 0 ){
					$result=$stmt->get_result();
					$row=$result->fetch_all(MYSQLI_ASSOC);
					$stmt->close(); # CERRAMOS CONEXION 
					return $row;
				}
				else{
					$row["affected"] = $stmt->affected_rows;
					$stmt->close(); # CERRAMOS CONEXION 
					return $row;
				}
			} catch (\Exception $e) {
				$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "]" );
				return false;
			}
		}

		/**
		 * Ejecuta una query oracle y devuelve resultados 
		 * var $qry y $host (solo IP)  
		 **/
		function exeoQuery($qry, $host){
			try {
				switch ($host) {
					case '172.16.105.19':
						$this->oLinkId=$this->abrirConexionSiebel();
					break;
					case '172.16.105.25':
						$this->oLinkId=$this->abrirConexionRemedy();
					break;
				}

				$command = substr($qry,0,6); ## VEMOS EL COMANDO DE LA QUERY -> SELECT o UPDATE o INSERT.. etc 
				//$this->my_log("[". __FUNCTION__ ."] Query->[" .$qry. "]" );

				$stid = oci_parse($this->oLinkId, $qry);
				@oci_execute($stid);
				if ( !$stid ) {
					$err = oci_error($this->oLinkId);
					throw new \Exception("Msg->" .$err['message'] );
					return false;
				}

				if( strtoupper($command) === "SELECT" ){
					while( $r = oci_fetch_assoc($stid) ) {
						$row[] = $r;
					}
					## RESCATAMOS EL RESULTSET 
					if( !empty($row) ) return $row;
					else return false;
				}
				else{
					$row["affected"] = oci_num_rows($stid); ## FILAS AFECTADAS 
					return $row;
				}

			} catch (\Exception $e) {
				//$this->my_log("[". __FUNCTION__ ."] ERROR->[" .$e->getMessage(). "]" );
				return false;
			}

			## CERRAMOS LA CONEXION 
			oci_close($this->oLinkId);
		}


		/*function register($data){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			try {
				if( !empty($data) ){
					$rut=$data['txtRut'];
					$nombre=$data['txtNombre'];
					$apellido=$data['txtApellidos'];
					$user=$data['txtUsuario'];
					$movil=$data['txtMovil'];
					$pass=md5($data['txtPwd']);
					$email=( isset($data['txtEmail']) ? $data['txtEmail'] : null );
					$empresa=( isset($data['cbEmpresa']) ? $data['cbEmpresa'] : null );
				}
				else{
					throw new \Exception("Msg-> No se recibieron los datos. Array data vacio.");
				}
				
				$sql="INSERT INTO users ( rut, username, password, nombre, apellido, movil, correo, enterprise_id, created ) 
				VALUES ( '$rut', '$user', '$pass', '$nombre', '$apellido', $movil, '$email', $empresa, NOW() ) ";
				// $this->my_log("[". __FUNCTION__ ."] sql-> " .$sql );
				$result = $this->exeQuery($sql, $this->local_db, "localhost");
				// $this->my_log("[". __FUNCTION__ ."] result->[" .json_encode($result). "]" );
				if( !empty($result) && $result['affected']==1){
					return 200;
				}
				else{
					return false;
				}

			} catch (\Exception $e) {
				$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "]" );
				return false;
			}
		}*/


		function login($status, $exeLogin=null){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			try {
				if( empty($status) && is_null($exeLogin) ){
					$this->my_log("[". __FUNCTION__ ."] redireccionando.. " );
					header("Location: login.php");
				}
				else{
					$this->my_log("[". __FUNCTION__ ."] request->" .json_encode($_REQUEST) );
					$params = [
						":user" => $_REQUEST['txtUsuario'],
						":pass" => md5($_REQUEST['txtPwd'])
					];

					$sql=$this->getQuery("qry_login", $params);
					$res = $this->exeQuery($sql, $this->local); 
					$this->my_log("[". __FUNCTION__ ."] Resultset res->" .json_encode($res) );
					
					if( empty($res) ){
						$this->my_log("[". __FUNCTION__ ."] Query no devolvio resultset [$sql] " );
						return "Empty";
						header("Location: login.php");
					}
					else{
						session_start();
						$_SESSION['id'] = $res[0]['ID'];
						$_SESSION['user'] = $res[0]['USER'];
						$_SESSION['name'] = $res[0]['NOMBRE'];
						$_SESSION['lastname'] = $res[0]['APELLIDO'];
						$_SESSION['correo'] = $res[0]['CORREO'];
						$_SESSION['movil'] = $res[0]['MOVIL'];
						$_SESSION['enterprise_id'] = $res[0]['ID_EMPRESA'];
						
						$this->my_log("[". __FUNCTION__ ."] SESSION->" .json_encode($_SESSION) );
						
						## REDIRECCIONAMIENTO AL HOME, UNA VEZ VALIDADO EL LOGIN 
						header("Location: site/home.php");
					}
				}

			} catch (\Exception $e) {
				$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "]" );
				return false;
			}
		}

		

		function logOut(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			
			if( isset($_SESSION['user']) ){
				session_destroy();
				header("Location: index.php");
			}
		}
		
		/**
		 * Log centralizado  
		 * var $str 
		 **/
		function my_log($str){
			try {
				$date = new \DateTime(); # clase DateTime global de PHP 
				$config = $this->getConfig();
				$ahora=$date->format('Y-m-d H:i:s');
				$logFile=fopen($config['logs']['tmp_log'], 'a+');
				$string="[$ahora] : $str ";
				fwrite($logFile, $string."\r\n");
				fclose($logFile);
			} catch (\Exception $e) {
				error_log("[".$e->getMessage()."]");
				return false;
			}
			
		}


	}// fin class
?>