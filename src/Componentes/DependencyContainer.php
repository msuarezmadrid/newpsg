<?php
	namespace App\Componentes;
	
	use App\Config\classConfig;
	use App\Funciones\classFunciones;
	use App\Funciones\classFuncionesSeguridad;
	
	class DependencyContainer {
		private $config;
		private $funciones;
		private $funcionesSeguridad;
		
		public function __construct() {
			$this->config = new classConfig();
			$this->funcionesSeguridad = new classFuncionesSeguridad($this->config);
			$this->funciones = new classFunciones($this->config, $this->funcionesSeguridad);
		}
		
		public function getConfig() {
			return $this->config;
		}
		
		public function getFunciones() {
			return $this->funciones;
		}
		
		public function getFuncionesSeguridad() {
			return $this->funcionesSeguridad;
		}
	}