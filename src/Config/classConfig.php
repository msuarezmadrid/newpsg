<?php
	namespace App\Config;
	
	interface ConfigInterface {
		// DECLARACION DE METODOS, SOLO DECLARACION 
		function cConnMysql($db);
		function local();
		function executQuery($queryName=null, $params = [], $types = '', $db = 'local', $dynamicQuery = null);
		function my_log($str);
	}
	
	class classConfig implements ConfigInterface {
		
		private $credentials;
		private $_config;
<<<<<<< Updated upstream:src/Config/classConfig.php
=======
		private $local;
>>>>>>> Stashed changes:src/config/classConfig.php
		private $queries;
		private $qrys_seg;
		private $activeConn;
<<<<<<< Updated upstream:src/Config/classConfig.php
		private $oLinkId;
		private $logs;
		
		public $local = "local"; # conectividad a BD 
		
		public function __construct(){
			$this->credentials = BASE_PATH . "/src/Config/credentials.php";
			$this->queries = BASE_PATH . "/site/lib/querys.php";
			$this->logs = BASE_PATH . "/logs/web_log";
=======
		private $env = 1; ## 1 BD SERVIDOR - 0 BD EN PC 
		
		public function __construct(){
			$this->credentials = BASE_PATH . "/src/Config/credentials.php";
			
>>>>>>> Stashed changes:src/config/classConfig.php
			try {
				# ARCHIVO CREDENCIALES 
				if( !file_exists($this->credentials) ){
					throw new \Exception("Msg->No existe o no se encuentra archivo credentials");
				} else {
					$this->getConfig();
				}
				
				# VARIABLES DE ENTORNO 
				if( $this->env === 1 ){ #servidor 
					$this->local = "intradb";
				}
				else{ # PC 
					$this->local = "local";
				}
				
			} catch (\Exception $e) {
				$this->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "] " );
				return false;
			}
			
		}

<<<<<<< Updated upstream:src/Config/classConfig.php
		private function loadQueries(){
			global $queries;
			require $this->queries;
			$this->queries = $queries;
		}

		function getQuery($name, $params=[]){
			$this->activeConn = $this->cConnMysql();
			$query=$this->queries[$name] ?? null;
			if ($query && !empty($params)) {
				uksort(	$params, function($a, $b){ 
					return strlen($b) - strlen($a); 
				});
				foreach ($params as $key => $value) {
					$escaped_value = $this->activeConn->real_escape_string($value);
					$query = str_replace($key, $escaped_value, $query);
				}
=======
		private function loadQueries($modulo=null, $queryName){
			$path=null;
			if( empty($modulo) ){
				$modulo="lib";
				$path=BASE_PATH . "/site/" . $modulo . "/querys.php";
>>>>>>> Stashed changes:src/config/classConfig.php
			}
			else{
				$path=BASE_PATH . "/site/" . $modulo . "/lib/querys.php";
			}
			try{
				if( !file_exists($path) ) throw new \Exception("El archivo de querys no está definido [$path].");;
				if( empty($queryName) ) throw new \Exception("No se definió la query a ejecutar queryName: [$queryName].");;
				
				require($path);
				
				if ( !isset($queries[$queryName]) ) {
					throw new \Exception("La consulta '$queryName' no está definida en [$path].");
				}
				
				return $queries[$queryName];
			
			} catch (\Exception $e) {
				$this->my_log("[".__FUNCTION__."] ERROR [" . $e->getMessage() . " ");
				return false;
			}
			
			/* if (!file_exists($qryFile)) {
				throw new \Exception("Msg->No existe o no se encuentra el archivo querys.php");
			}
			$queries = require $qryFile; // Incluye el archivo, suponiendo que contiene un array $query
			if (!is_array($queries)) {
				throw new \Exception("Msg->Error al cargar queries, formato incorrecto.");
			}
			$this->queries = $queries; // Guarda el array en la propiedad de la clase */
		}
		
		function getConfig(){
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
		function cConnMysql($db){
			// 
			try {
				$credentials = $this->getConfig();
				$user = base64_decode($credentials[$db]["user"]);
				$pass = base64_decode($credentials[$db]["pass"]);
				$host = base64_decode($credentials[$db]["host"]);
				$db = $this->env===1 ? $credentials[$db]["db"] : "local" ;
				
				$this->activeConn = new \mysqli($host,$user,$pass,$db);
				if ( $this->activeConn->connect_error ) {
					throw new \Exception("Msg->[".$this->activeConn->connect_errno."] ".$this->activeConn->connect_error );
				}
				
			} catch (\Exception $e) {	
				$this->my_log("[". __FUNCTION__ ."] ERROR " .$e->getMessage() );
				exit;
			}
			
			return $this->activeConn;
		}
<<<<<<< Updated upstream:src/Config/classConfig.php

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
				
				// $stmt->execute();
				// Ejecutamos la consulta 
				if ( !$stmt->execute() ){
					throw new \Exception(" Msg->[".$stmt->errno."] ".$stmt->error );
				}

				if( stripos($qry, 'SELECT') === 0 ){
					$result=$stmt->get_result();
					if( $result === false ){ 
						throw new \Exception(" Msg->[".$stmt->errno."] ".$stmt->error );
					}
					$row=$result->fetch_all(MYSQLI_ASSOC);
					$stmt->close(); # CERRAMOS CONEXION 
					return $row;
				}elseif( stripos($qry, 'INSERT') === 0 ){
					$row["affected"] = $stmt->affected_rows;
					$row["insert_id"] = $this->activeConn->insert_id;
					$stmt->close(); // CERRAMOS CONEXION 
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
		
		
		function login($status, $exeLogin=null){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			try {
				if( empty($status) && is_null($exeLogin) ){
					$this->my_log("[". __FUNCTION__ ."] redireccionando.. " );
					header("Location: login.php");
				}
				else{
					$params = [
						":user" => $_REQUEST['txtUsuario'],
						":pass" => md5($_REQUEST['txtPwd'])
					];
					$this->my_log("[". __FUNCTION__ ."] Credenciales ingresadas : " .json_encode($params) );

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
						$_SESSION['perfil_adc'] = $res[0]['PERFIL_ADC'];
						$_SESSION['cargo'] = $res[0]['CARGO'];
						$_SESSION['crear_tp'] = $res[0]['CREAR_TP'];
						$_SESSION['dia_habil'] = $res[0]['DIA_HABIL'];
						$_SESSION['horario_habil'] = $res[0]['HORARIO_HABIL'];
						$_SESSION['origen'] = $res[0]['ORIGEN'];
						
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

=======
>>>>>>> Stashed changes:src/config/classConfig.php
		
		function local(){
			return $this->local;
		}
		
		function env(){
			return $this->env;
		}
		
		/**
		 * Función para rescatar, preparar y ejecutar query. 
			Si la query no existe en el archivo querys, se usa la variable $dynamicQuery con el $queryName en null 
		 * $queryName Nombre de la consulta en el archivo querys 
		 * $params Array con los parametros de la consulta 
		 * $types Tipo de los parametros, si no se entregan se intentan deducir (función deduceParamTypes)
		 * $db BD en la que se debe ejecutar la query 
		 * $dynamicQuery Query que se ejecutará 
		 * Modo de uso: 
		 *	Existe query en archivo 
		 *		$result = $this->executeQuery('nombreQuery', ['Juan', 30]);
		 *	No Existe query en archivo 
		 *		$result = $this->executeQuery(null, ['Juan', 30], 'si', 'local', "SELECT * FROM User WHERE nombre=? AND edad=?");
		**/
		function executQuery($queryName=null, $params = [], $types = '', $db = 'local', $dynamicQuery = null, $modulo = null) {
			try {
				if ( !empty($queryName) ) {
					$query=$this->loadQueries($modulo, $queryName);
				} elseif ($dynamicQuery !== null) {
					// Usar consulta dinámica
					$query = $dynamicQuery;
				} else {
					throw new \Exception("Se debe proporcionar un nombre de consulta o una consulta dinámica.");
				}
				
				$this->activeConn = $this->cConnMysql($db);
				if (!$this->activeConn) {
					throw new \Exception("No se pudo conectar a la base de datos.");
				}
				
				// Preparar la consulta
				$stmt = $this->activeConn->prepare($query);
				if (!$stmt) {
					throw new \Exception("Error al preparar la consulta: " . $this->activeConn->error);
				}
				
				// Vincular los parámetros si es necesario
				if (!empty($params)) {
					// Si no se proporciona el tipo de parámetros, se intentará deducirlo 
					if (empty($types)) {
						$types = $this->deduceParamTypes($params);
					}
					$stmt->bind_param($types, ...$params);
				}
				
				// $this->my_log(__FUNCTION__ . " query: [$query] " );
				
				// Ejecutar la consulta
				if (!$stmt->execute()) {
					throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
				}

<<<<<<< Updated upstream:src/Config/classConfig.php
		function logOut(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			if( isset($_SESSION['user']) ){
				session_destroy();
				header("Location: index.php");
=======
				// Obtener los resultados si es una consulta SELECT
				if (stripos(trim($query), 'SELECT') === 0) {
					$result = $stmt->get_result();
					// $row_count = $result->num_rows;
					// $row[] = ['rows' => $row_count];
					$stmt->close();
					$this->activeConn->close();
					return $result;
				} else {
					// Devolver información sobre el resultado
					$row = [];
					$row["affected"] = $stmt->affected_rows;
					if (stripos(trim($query), 'INSERT') === 0) {
						$row["insert_id"] = $stmt->insert_id;
					}
					$stmt->close();
					$this->activeConn->close();
					return $row;
				}
			} catch (\Exception $e) {
				$this->my_log("[".__FUNCTION__."] ERROR [" . $e->getMessage() . "] queryName:[$queryName] dynamicQuery:[$dynamicQuery]]");
				return false;
>>>>>>> Stashed changes:src/config/classConfig.php
			}
		}
		
		function deduceParamTypes($params) {
			$types = null;
			foreach ($params as $param) {
				if (is_int($param)) {
					$types .= 'i';
				} elseif (is_double($param)) {
					$types .= 'd';
				} elseif (is_string($param)) {
					$types .= 's';
				} else {
					$types .= 'b';
				}
			}
			return $types;
		}
		
		
		/**
		 * Log centralizado  
		 * var $str 
		 **/
		function my_log($str){
			global $logs;
			try {
				$date = new \DateTime(); # clase DateTime global de PHP 
				$ahora=$date->format('Y-m-d H:i:s');
				$logFile=fopen($this->logs, 'a+');
				$string="[$ahora] $str \n";
				fwrite($logFile, $string);
				fclose($logFile);
			} catch (\Exception $e) {
				error_log(__FUNCTION__." ERROR [".$e->getMessage()."] ");
			}
			
		}
		
		
	}// fin class
?>
