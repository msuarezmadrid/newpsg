<?php
	namespace App\Funciones;
    
	use App\Config\classConfig;

    class classFunciones {
		private $title = "PSG Redes";
        private $cConfig;
		private $favicon = "favicon.png"; 
		private $siteHeader = "header.php";
		private $bodyCss = "body.css";
		private $functions = "functions.js";

        public function __construct(){
			$this->favicon = BASE_URL . "/img/" . $this->favicon;
			$this->siteHeader = BASE_PATH . "/site/lib/" . $this->siteHeader;
			$this->bodyCss = BASE_URL . "/site/lib/css/" . $this->bodyCss;
			$this->functions = BASE_URL . "/site/lib/js/" . $this->functions;
			
            $this->cConfig = new classConfig();
        }
		
		function siteHeader(){
			return $this->siteHeader;
		}
		
		function bodyCss(){
			return $this->bodyCss;
		}
		
		function functions(){
			return $this->functions;
		}

		function favicon(){
			return $this->favicon;
		}

		function title(){
			return $this->title;
		}

		function login($p1,$p2=null){
			return $this->cConfig->login($p1,$p2);
		}

		function checkSession() {
			if (!isset($_SESSION['user'])) {
				header("Location: login.php");
				exit;
			}
		}

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
