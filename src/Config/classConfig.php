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
		private $local;
		private $queries;
		private $qrys_seg;
		private $activeConn;
		private $env = 1; ## 1 BD SERVIDOR - 0 BD EN PC 
		
		public function __construct(){
			$this->credentials = BASE_PATH . "/src/Config/credentials.php";
			
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

		private function loadQueries($modulo=null, $queryName){
			$path=null;
			if( empty($modulo) ){
				$modulo="lib";
				$path=BASE_PATH . "/site/" . $modulo . "/querys.php";
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