<?php 
	namespace App\Funciones;
    
	use App\Config\classConfig;
	use App\Config\ConfigInterface;
	use App\Funciones\classFuncionesSeguridad;
	
    class classFunciones {
		private $title = "PSG Redes";
        private $cConfig;
		
		private $favicon = BASE_URL . "/img/favicon.png";
		private $siteHeader = "header.php";
		private $header = "header.php";
		private $bodyCss = "body.css";
		private $boostrapCss = "bootstrap3.4.1.min.css";
		private $boostrapJs = "bootstrap3.4.1.min.js";
		private $functions = "functions.js";
		private $flatpickr = "flatpickr";
		private $flatpickres = "es.js";
		private $flatpickrcss = "flatpickr.min.css";
		private $jqueryMin = "jquery.3.7.1.min.js";
		
		
		public $tp_ini = 1000;
		public $tp_fin = 1000000;
		
		private $local;
		private $cCfnSeg;

        public function __construct(ConfigInterface $cConfig, classFuncionesSeguridad $cCfnSeg){
            $this->cConfig = $cConfig;
			$this->cCfnSeg = $cCfnSeg;
			// $this->cConfig->my_log("[". __FUNCTION__ ."] local: ".$this->local." " );
			$this->siteHeader = BASE_PATH . "/site/lib/" . $this->siteHeader; #header de site
			$this->header =  "./lib/" . $this->header; # header de HOME 
			$this->bodyCss = BASE_URL . "/site/lib/css/" . $this->bodyCss;
			$this->boostrapCss = BASE_URL . "/site/lib/css/" . $this->boostrapCss;
			$this->flatpickrcss = BASE_URL . "/site/lib/css/" . $this->flatpickrcss;
			$this->functions = BASE_URL . "/site/lib/js/" . $this->functions;
			$this->flatpickr = BASE_URL . "/site/lib/js/" . $this->flatpickr;
			$this->flatpickres = BASE_URL . "/site/lib/js/" . $this->flatpickres;
			$this->jqueryMin = BASE_URL . "/site/lib/js/" . $this->jqueryMin;
			$this->boostrapJs = BASE_URL . "/site/lib/js/" . $this->boostrapJs;
			
			$this->local = $this->cConfig->local();
        }
		
		function getLocal(){
			return $this->local;
		}
		
		function getCnfg(){
			return $this->cConfig->getConfig();
		}
		
		function siteHeader(){
			return $this->siteHeader;
		}
		
		function getHeader(){
			return $this->header;
		}
		
		function bodyCss(){
			return $this->bodyCss;
		}
		
		function boostrapCss(){
			return $this->boostrapCss;
		}
		
		function flatpickrcss(){
			return $this->flatpickrcss;
		}
		
		function functions(){
			return $this->functions;
		}
		
		function flatpickr(){
			return $this->flatpickr;
		}
		
		function flatpickres(){
			return $this->flatpickres;
		}
		
		function jqueryMin(){
			return $this->jqueryMin;
		}
		
		function boostrapJs(){
			return $this->boostrapJs;
		}
		
		function favicon(){
			return $this->favicon;
		}
		
		function title(){
			return $this->title;
		}
		
		function exeQuery($sql=null, $params, $tipos, $db, $dinamQry=null, $modulo=null){
			return $this->cConfig->executQuery($sql,$params,$tipos,$db,$dinamQry,$modulo);
		}
		
		function cConn($db=null){
			return $this->cConfig->cConnMysql($db);
		}
		
		function checkSession() {
			if (!isset($_SESSION['user'])) {
				header("Location: " . BASE_URL . "/login.php");
				exit;
			}
		}
		
		function getUser(){
			return $_SESSION['user'];
		}
		
		function checkProfile($req){
			$profile=null;
			$result=false;
			$pfile = [];
			if ( isset($_SESSION['profile']) ) {
				$pfile = explode(':',$_SESSION['profile']);
			}
			foreach($pfile as $pfile){
				if( $pfile == $req ){
					$result=true;
				}
			}
			return $result;
		}
		
		
		function login($user, $pass){
			// 
			try {
				## LIMPIEZA Y SANEAMIENTO 
				$usuario = trim(strtolower($user));
				$passtmp = $pass;
				if( empty($usuario) || empty($passtmp) ){
					return "Empty";
					// echo "Empty";
					exit;
				}
				$password = md5($passtmp);
				$password_sha256 = hash('sha256', $passtmp);
				
				## Validación de caracteres restringidos
				if ( $this->valida_caracteres($usuario) > 0 || $this->valida_caracteres($passtmp) > 0 ) {
					$fecha_log = date("Y-m-d H:i:s");
					$params=[$usuario];
					$sql_log = "INSERT INTO intradb.LOG_EXCLUSION_CARACTERES (user, fecha, modulo) VALUES (?, NOW(), 'login') ";
					$this->exeQuery(null,$params,'s',$this->local,$sql_log);
					// return "CharWrong";
					echo "CharWrong";
					exit;
				}
				
				## VERIFICACION DE EXISTENCIA DE USUARIO 
				$params=[$usuario];
				$sql = "SELECT COUNT(1) FROM intradb.users WHERE usr = ? ";
				$result=$this->exeQuery(null,$params,'s',$this->local,$sql);
				if( empty($result) ){
					// return "UserNot";
					echo "UserNot";
					exit;
				}
				
				## CREDENCIALES USUARIO 
				$params=[$usuario];
				$sql = "SELECT md5_passw, lastchange, categoria, profile, roles, usr, IFNULL(intentos_fallidos, 0) AS intentos, estado FROM intradb.users WHERE usr=? ";
				$result=$this->exeQuery(null,$params,'s',$this->local,$sql);
				$row = $result->fetch_assoc();
				if( !empty($row) ) {
					$md5_passw = $row['md5_passw'];
					$lastchange = $row['lastchange'];
					$categoria = $row['categoria'];
					$profile = $row['profile'];
					$roles = $row['roles'];
					$usuario_base = $row['usr'];
					$intentos = $row['intentos'] + 1;
					$estado = $row['estado'];
					
					$res_params = $this->cCfnSeg->traerParamPass("INTENTOS_FALLIDOS");
					$max_intentos = $res_params[0]['valor'];
					$msje_max_intentos = "Ha alcanzado el máximo de intentos permitidos";
					
					## Verificación del estado de la cuenta
					$res_estado = $this->cCfnSeg->check_estado_usuario($usuario, $max_intentos);
					if ($res_estado[0]['check_cta'] != "CUENTA_OK") {
						$check_cta = $res_estado[0]['check_cta'];
						$this->cCfnSeg->registro_intento($usuario, "CLAVE_LOGIN", "FALLA", "hash_erroneo", "CUENTA $usuario {".$res_estado[0]['check_cta']."}");
						if( $check_cta === "bloqueada por intentos fallidos" ){
							// return "BloqIntFall";
							echo "BloqIntFall";
						}else{
							// return "BloqInact";
							echo "BloqInact";
						}
						exit;
					}
					
					## Control de intentos fallidos
					if ($intentos >= $max_intentos) {
						$this->cCfnSeg->actualiza_estado($usuario, 2);
						$this->cCfnSeg->intento_fallido($usuario);
						$this->cCfnSeg->registro_intento($usuario, "CLAVE_LOGIN", "FALLA", "hash_erroneo", "MAXIMO INTENTO DE CONEXIONES");
						// return "MaxIntent";
						echo "MaxIntent";
						exit;
					}
					
					## Verificación de contraseña
					if ($password_sha256 != $md5_passw && $password != $md5_passw) {
						$this->cCfnSeg->intento_fallido($usuario);
						$this->cCfnSeg->registro_intento($usuario, "CLAVE_LOGIN", "FALLA", "hash_erroneo", "CREDENCIALES INCORRECTAS");
						// return "CredenErr";
						echo "CredenErr";
						exit;
					}
				}
				
				## Consultar detalles adicionales del usuario
				$params=[$usuario];
				$sql = "SELECT VACACIONES, U.tipo_password, lastchange, fecha_clave_temporal 
						FROM intradb.users U INNER JOIN o_m.PERSONAL P ON U.personal_id = P.ID WHERE U.usr = ? ";
				$result=$this->exeQuery(null,$params,'s',$this->local,$sql);
				$row = $result->fetch_assoc();
				$vacaciones = $row['VACACIONES'];
				$tipo_pass = $row['tipo_password'];
				$lastchange = $row['lastchange'];
				$fecha_clave_tmp = $row['fecha_clave_temporal'];

				if ($vacaciones === "SI") {
					$roles = "";
				}
				
				## Control de fechas de cambio de clave
				$res_params = $this->cCfnSeg->traerParamPass("TIEMPO_CLAVE_TEMPORAL");
				$tiempo_clave_temporal_seg = $res_params[0]['valor'];
				$tiempo_clave_tmp_hrs = $tiempo_clave_temporal_seg / 3600;

				$res_params = $this->cCfnSeg->traerParamPass("TIEMPO_CLAVE_PERMANENTE");
				$tiempo_clave_permanente = $res_params[0]['valor'];
				
				## Comparación de fechas y tiempos de clave
				$time = time();
				$delta = date("Y-m-d H:i:s", strtotime("-$tiempo_clave_tmp_hrs hours"));
				
				if ($tipo_pass == 0) {
					if ($fecha_clave_tmp <= $delta) {
						// return "PassCad";
						echo "PassCad";
					} else {
						// header("Location: " . BASE_URL . "/change_form.php");
						echo "redirect:" . BASE_URL . "/change_form.php";
						exit();
					}
				}
				else {
					$lastchange = (int)$lastchange;
					$difference = $time - $lastchange;
					$tiempo_clave_permanente = (int)$tiempo_clave_permanente;
					$sss = 3600 * 24 * $tiempo_clave_permanente;

					if ($difference >= $sss) {
						// return "PassCadCamb";
						echo "PassCadCamb";
						exit;
					} 
					else {
						$_SESSION['user'] = $usuario_base;
						$_SESSION['profile'] = $profile;
						$_SESSION['roles'] = $roles;
						$_SESSION['categoria'] = $categoria;
						$_SESSION['lastchange'] = $lastchange;
						$_SESSION['estado'] = $estado;
						
						$this->cConfig->my_log("[". __FUNCTION__ ."] LOGIN EXITOSO [$usuario_base] ");
						// header("Location: site/home");
						echo "redirect:site/home";
						exit;
					}
				}
			} catch (\Exception $e) {
				$this->cConfig->my_log("[". __FUNCTION__ ."] ERROR [" .$e->getMessage(). "]" );
				return false;
			}
		}
		
		
		function getBanned(){
			$path = BASE_PATH . "/src/caracteresRestringidos.conf";
			if (file_exists($path)) {
				$banned = [];
				$fp = fopen($path, "r");
				while (($linea = fgets($fp)) !== false) {
					$banned[] = htmlentities(trim($linea), ENT_QUOTES);
				}
				fclose($fp);
				return $banned;
			}
			return ["<", ">"];
		}

		## Validación de caracteres restringidos en una cadena
		function valida_caracteres($string){
			$cnp = $this->getBanned();
			$string_entrada = str_split($string);
			return count(array_intersect($cnp, $string_entrada));
		}
		
		
		function logOut(){
			if( isset($_SESSION['user']) ){
				$usr=$_SESSION['user'];
				$this->cConfig->my_log("[". __FUNCTION__ ."] DESCONEXION EXITOSA [$usr] ");
				session_destroy();
				
				echo "<script type='text/javascript'>
				console.log('Ejecutando logout');
				try {
					sessionStorage.removeItem('modalShown');
					window.location.href = 'index.php';
				} catch (error) {
					console.error('Error durante el logout:', error);
					window.location.href = 'index.php';
				}
				</script>";
				
			}
		}
		
		### PARA VERIFICAR SI LOS ELEMENTOS DE UN ARRAY, ESTAN VACIOS 
		function hayElementosVacios(array $elementos): bool {
			foreach ($elementos as $elemento) {
				if (empty($elemento)) {
					return true; # Si encuentra un elemento vacío, retorna true
				}
			}
			return false; # Si ningún elemento está vacío, retorna false
		}
		
		## HOME 
		function getVacaciones($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_vacaciones", $params, 's', $this->local,null);
			return $res;
		}
		function getProblemas($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_problemas", $params, 's', $this->local,null);
			return $res;
		}
		function getTareas($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_tareas", $params, 's', $this->local,null);
			return $res;
		}
		function getTrabajos($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_trabajos", $params, 's', $this->local,null);
			return $res;
		}
		function getBitacoras($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_bitacoras", $params, 's', $this->local,null);
			return $res;
		}
		function getInventario($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_inventario", $params, 's', $this->local,null);
			return $res;
		}
		function getMensajes($user){
			$params = [ $user ];
			$res = $this->cConfig->executQuery("qry_mensajes", $params, 's', $this->local,null);
			return $res;
		}
		function getInfoModalInicio(){
			$result = $this->cConfig->executQuery("qry_modal", NULL, '', $this->local,null);
			$res = $result->fetch_assoc();
			$politicas = $this->cCfnSeg->traePoliticas();
			
			$modal="<div class='modal-header'>
				<h4 class='modal-title'>".$res['TITULO']."</h4>
			</div>";
			$modal.="<div class='modal-body'>".$res['TEXTO'].$politicas."</div>";
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			return $modal;
		}
		##################################
		
		
        function convertMes($nro){
			$this->cConfig->my_log("[". __FUNCTION__ ."] INICIA " );
			$mes=null;
			if( !empty($nro) ){
				switch ($nro) {
					case '01':
						$mes="Enero";
						break;
					case '02':
						$mes="Febrero";
						break;
					case '03':
						$mes="Marzo";
						break;
					case '04':
						$mes="Abril";
						break;
					case '05':
						$mes="Mayo";
						break;
					case '06':
						$mes="Junio";
						break;
					case '07':
						$mes="Julio";
						break;
					case '08':
						$mes="Agosto";
						break;
					case '09':
						$mes="Septiembre";
						break;
					case '10':
						$mes="Octubre";
						break;
					case '11':
						$mes="Noviembre";
						break;
					case '12':
						$mes="Diciembre";
						break;
				}
			}// fin if 
			return $mes;
		}

    } // fin class 
?>