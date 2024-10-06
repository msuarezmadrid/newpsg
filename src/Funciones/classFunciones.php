<?php
	namespace App\Funciones;
    
	use App\Config\classConfig;

    class classFunciones {
		private $title = "PSG Redes";
        private $cConfig;
		private $favicon = "./img/favicon.png"; 

        public function __construct(){
            $this->cConfig = new classConfig();
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
