<?php
	namespace App\Funciones;
    
	use App\Config\classConfig;

    class classFunciones {
		private $title = "PSG Redes";
        private $cConfig;
		
		private $basePath;
		private $favicon = "favicon.png"; 
		private $siteHeader = "header.php";
		private $bodyCss = "body.css";
		private $boostrapCss = "bootstrap3.4.1.min.css";
		private $functions = "functions.js";
		private $jqueryMin = "jquery.3.7.1.min.js";
		private $boostrapJs = "bootstrap3.4.1.min.js";
		
		private $tp_ini = 1000;
		private $tp_fin = 1000000;
		
		private $bd;

        public function __construct(){
			$this->favicon = BASE_URL . "/img/" . $this->favicon;
			$this->siteHeader = BASE_PATH . "/site/lib/" . $this->siteHeader;
			$this->bodyCss = BASE_URL . "/site/lib/css/" . $this->bodyCss;
			$this->boostrapCss = BASE_URL . "/site/lib/css/" . $this->boostrapCss;
			$this->functions = BASE_URL . "/site/lib/js/" . $this->functions;
			$this->jqueryMin = BASE_URL . "/site/lib/js/" . $this->jqueryMin;
			$this->boostrapJs = BASE_URL . "/site/lib/js/" . $this->boostrapJs;
			
			$this->basePath = BASE_URL . "/site/";
			
            $this->cConfig = new classConfig();
			$this->bd = $this->cConfig->local;
        }
		
		function getConnBD(){
			return $this->bd;
		}
		
		function basePath(){
			return $this->basePath;
		}
		
		function siteHeader(){
			return $this->siteHeader;
		}
		
		function bodyCss(){
			return $this->bodyCss;
		}
		
		function boostrapCss(){
			return $this->boostrapCss;
		}
		
		function functions(){
			return $this->functions;
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

		function login($p1,$p2=null){
			return $this->cConfig->login($p1,$p2);
		}
		
		function my_log($str){
			return $this->cConfig->my_log($str);
		}
		
		function getQuery($name,$param=null){
			return $this->cConfig->getQuery($name,$param);
		}
		
		function exeQuery($sql,$bd){
			return $this->cConfig->exeQuery($sql,$bd);
		}
		
		function getVacaciones($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_vacaciones", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getProblemas($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_problemas", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getTareas($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_tareas", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getTrabajos($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_trabajos", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getBitacoras($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_bitacoras", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getInventario($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_inventario", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getMensajes($user){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":user" => $user
			];
			$sql = $this->cConfig->getQuery("qry_mensajes", $params);
			$this->my_log("[". __FUNCTION__ ."] user: $user " );
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function traePoliticas(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			
			$params = [
				":llave" => "POLITICAS_CLAVE"
			];
			$sql = $this->cConfig->getQuery("qry_params", $params);
			$resPoliticas = $this->cConfig->exeQuery($sql, $this->bd);
			
			$params = [
				":llave" => "LARGO_MINIMO"
			];
			$sql = $this->cConfig->getQuery("qry_params", $params);
			$resMinimo = $this->cConfig->exeQuery($sql, $this->bd);
			
			$params = [
				":llave" => "LARGO_MAXIMO"
			];
			$sql = $this->cConfig->getQuery("qry_params", $params);
			$resMaximo = $this->cConfig->exeQuery($sql, $this->bd);
			
			$params = [
				":llave" => "INCLUSION_CARACTERES_ESPECIALES"
			];
			$sql = $this->cConfig->getQuery("qry_params", $params);
			$resChars = $this->cConfig->exeQuery($sql, $this->bd);
			
			$msje_politicas = $resPoliticas[0]['mensaje_error'];
			$largo_min = $resMinimo[0]['valor'];
			$largo_max = $resMaximo[0]['valor'];
			$chars = $resChars[0]['valor'];
			
			$msje_politicas=str_replace("_min_",$largo_min,$msje_politicas);
			$msje_politicas=str_replace("_max_",$largo_max,$msje_politicas);
			$politicas=str_replace("_chars_",$chars,$msje_politicas);
			
			return $politicas;
		}
		
		function getInfoModalInicio(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$sql = $this->cConfig->getQuery("qry_modal", NULL);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			$politicas = $this->traePoliticas();
			
			$modal="<div class='modal-header'>
				<h4 class='modal-title'>".$res[0]['TITULO']."</h4>
			</div>";
			$modal.="<div class='modal-body'>".$res[0]['TEXTO'].$politicas."</div>";
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			return $modal;
		}
		
		function getInfoModalLogin(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$sql = $this->cConfig->getQuery("qry_modal", NULL);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			$politicas = $this->traePoliticas();
			
			$modal="<div class='modal-header'>
				<h4 class='modal-title'>Pol&iacute;ticas de seguridad Entel</h4>
			</div>";
			$modal.="<div class='modal-body small'>".$politicas."</div>";
			$modal.="<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			return $modal;
		}

		function checkSession() {
			if (!isset($_SESSION['user'])) {
				header("Location: ".BASE_URL."/login.php");
				exit;
			}
		}
		
		function getTPaux(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$flag=true;
			while( $flag ){
				$tp_aux = rand($this->tp_ini, $this->tp_fin);
				$params = [
					":tp_aux" => $tp_aux
				];
				$sql = $this->cConfig->getQuery("qry_tpaux", $params);
				$res = $this->cConfig->exeQuery($sql, $this->bd);
				if( $res[0]['N'] === 0 ){
					$flag=false;
				}
			}
			return $tp_aux;
		}
		
		function getUser(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			return $_SESSION['user'];
		}
		
		function getTPtipo(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			
			$sql = $this->cConfig->getQuery("qry_tpTipo", NULL);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getServicios($area){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":areaorigen" => $area
			];
			$sql = $this->cConfig->getQuery("qry_servicios", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getElementos($area,$servicio){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":area" => $area,
				":servicio" => $servicio
			];
			$sql = $this->cConfig->getQuery("qry_elementos", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getTipoIngreso(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			
			$sql = $this->cConfig->getQuery("qry_tipoingreso", NULL);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function comprobarZona($area=null, $tipotrabajo=null){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$zona=null;
			if( !empty($area) && !empty($tipotrabajo) ){
				$params = [
					":tipotarea" => $tipotrabajo
				];
				$sql = $this->cConfig->getQuery("qry_comp_zona", $params);
				$res = $this->cConfig->exeQuery($sql, $this->bd);
				$zona = ( $res[0]['ZONA'] === 1 ) ? "SI" : "NO";
			}
			return $zona;
		}
		
		function getRegiones(){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			
			$sql = $this->cConfig->getQuery("qry_regiones", NULL);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getComunas($region){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region
			];
			$sql = $this->cConfig->getQuery("qry_comunas", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getLugares($region,$comuna){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna
			];
			$sql = $this->cConfig->getQuery("qry_lugares", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getSites($region,$comuna){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna
			];
			$sql = $this->cConfig->getQuery("qry_sites", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getNomsites($region,$comuna){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna
			];
			$sql = $this->cConfig->getQuery("qry_nomlugares", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getNoms($region,$comuna,$lugar){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna,
				":lugar" => $lugar
			];
			$sql = $this->cConfig->getQuery("qry_noms", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getUbicacion($region,$comuna,$lugar,$elemento){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna,
				":lugar" => $lugar,
				":nombre_elemento" => $elemento
			];
			$sql = $this->cConfig->getQuery("qry_ubicacion", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getIdElemento($region,$comuna,$lugar,$elemento,$sala){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna,
				":lugar" => $lugar,
				":elemento" => $elemento,
				":sala" => $sala
			];
			$sql = $this->cConfig->getQuery("qry_idelemento", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getNewTramo($planned_aux){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":planned_aux" => $planned_aux
			];
			$sql = $this->cConfig->getQuery("qry_tramo", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getSala($region,$comuna,$lugar,$elemento,$sala){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":region" => $region,
				":comuna" => $comuna,
				":lugar" => $lugar,
				":elemento" => $elemento,
				":sala" => $sala
			];
			$sql = $this->cConfig->getQuery("qry_sala", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getDatatpclasif($id){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":tipo" => $id
			];
			$sql = $this->cConfig->getQuery("qry_tpclasif", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function getTpprocesos($idClasif){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$params = [
				":tipo" => $idClasif
			];
			$sql = $this->cConfig->getQuery("qry_tpprocesos", $params);
			$res = $this->cConfig->exeQuery($sql, $this->bd);
			return $res;
		}
		
		function actualizaRPN($id){
			$this->my_log("[". __FUNCTION__ ."] INICIA TP: $id" );
			$params = [
				":tp" => $id
			];
			$sql=$this->getQuery("qry_ptjeRPN", $params);
			$res = $this->exeQuery($sql,$this->getConnBD());
			
			$rpn_cla = $res[0]['PUNTAJE'];
			$comprobar = $res[0]['COMPROBAR'];
			$relacion_sitio = $res[0]['RELACION_SITIO'];
			$this->my_log("[". __FUNCTION__ ."] RPN clasif: ".$rpn_cla." comprobar:".$comprobar." relacion_sitio:".$relacion_sitio );
			###################################################
			$params = [
				":tp" => $id
			];
			$sql=$this->getQuery("qry_ptjetramas", $params);
			$res = $this->exeQuery($sql,$this->getConnBD());
			$rpn_tra=0;
			for( $r=0; $r<sizeof($res); $r++ ){
				if( $res[$r]['RPN_ASIG'] > $rpn_tra ){
					$rpn_tra = $res[$r]['RPN_ASIG'];
				}
			}
			$this->my_log("[". __FUNCTION__ ."] RPN trama: ".$rpn_tra );
			###################################################
			$params = [
				":tp" => $id
			];
			$sql=$this->getQuery("qry_ptjesitio", $params);
			$res = $this->exeQuery($sql,$this->getConnBD());
			$rpn_site=0;
			for( $r=0; $r<sizeof($res); $r++ ){
				if( $res[$r]['RPN_ASIG'] > $rpn_site ){
					$rpn_site = $res[$r]['RPN_ASIG'];
				}
			}
			$this->my_log("[". __FUNCTION__ ."] RPN site: ".$rpn_site );
			###################################################
			
			$last=$rpn_cla;
			
			$last = ( $rpn_tra > $rpn_cla ) ? $rpn_tra : $rpn_cla ;
			$last = ( $rpn_site > $rpn_cla ) ? $rpn_site : $rpn_cla ;
			
			$this->my_log("[". __FUNCTION__ ."] RPN last: ".$last );
			
			$params = [
				":tp" => $id
			];
			$sql=$this->getQuery("qry_tpdata", $params);
			$res = $this->exeQuery($sql,$this->getConnBD());
			$lugar = $res[0]['TIPO_INGRESO'];
			$final=null;
			$this->my_log("[". __FUNCTION__ ."] Lugar:".$lugar );
			
			if( $lugar === "5" || $lugar === "" ){
				$final = $rpn_cla;
			}
			if( $lugar === "6" ){
				if( $comprobar === 'NO' && $relacion_sitio === 'NO' ){
					$final = $rpn_cla;
				}elseif( $comprobar === 'NO' && $relacion_sitio === 'SI' ){
					$final = $rpn_cla;
				}elseif( $comprobar === 'SI' && $relacion_sitio === 'NO' ){
					$final = $last;
				}elseif( $comprobar === 'SI' && $relacion_sitio === 'SI' ){
					$final = $last;
				}
			}
			$this->my_log("[". __FUNCTION__ ."] RPN final: ".$final." TP: $id" );
			$params = [
				":final" => $final,
				":tp" => $id
			];
			$sql=$this->getQuery("update_planned", $params);
			$plannedAffected = $this->exeQuery($sql,$this->getConnBD());
			$ret=( $plannedAffected['affected'] === 1 ) ? true : false ;
			
			return $ret;
		}
		
		function crearTP($data){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
			$ids=array();
			$this->my_log("[". __FUNCTION__ ."] Recibo data: ".json_encode($data) );
			$correlativo = $data['correlativo'];
			$fullTitulo = $data['fullTitulo'];
			$planned_aux = $data['planned_aux'];
			$tipoTrabajo = $data['tipoTrabajo'];
			$tipoingreso = $data['tipoingreso'];
			
			####################### TP ########################
			# DATOS TP CLASIFICACION 
			$dtTpclasif = $this->getDatatpclasif($tipoTrabajo);
			$this->my_log("[". __FUNCTION__ ."] Tipo Trabajo: ".$tipoTrabajo );
			if( !empty($dtTpclasif) ){
				$padre=$dtTpclasif[0]['PADRE'];
				$origen=$dtTpclasif[0]['ORIGEN'];
				$subelemento=$dtTpclasif[0]['SUBELEMENTOS'];
				$comprobar=$dtTpclasif[0]['COMPROBAR'];
				$relacion_sitio=$dtTpclasif[0]['RELACION_SITIO'];
				$id_region=$dtTpclasif[0]['ZONA'];
				$rpn_mayor=$dtTpclasif[0]['PUNTAJE'];
				$tp_tipo=$dtTpclasif[0]['TIPO'];
				$tp_clasif=$dtTpclasif[0]['CLASIFICACION'];
				$tp_elem=$dtTpclasif[0]['ELEMENTOS'];
			}
			
			# INSERTAMOS EN PLANNED 
			$params = [
				":titulo" => $fullTitulo, 
				":rpn" => $rpn_mayor, 
				":impacto" => 6, 
				":estados_id" => 1, 
				":tp_ver" => 1
			];
			$sql=$this->getQuery("insert_planned", $params);
			$result = $this->exeQuery($sql,$this->getConnBD());
			
			$id = $result['insert_id']; # ID TP -> REGISTRO RECIEN INSERTADO 
			$this->my_log("[". __FUNCTION__ ."] nuevo TP creado: ".$id);
			$ids = [ "TP" => $id ];
			
			$params = [
				":id" => $id, 
				":planned_aux" => $planned_aux
			];
			# ACTUALIZAMOS TMP_TP_DATA 
			$sql=$this->getQuery("update_tmp_tp_data", $params);
			$upTmpDataAffected = $this->exeQuery($sql,$this->getConnBD());
			
			$res = $this->getNewTramo($planned_aux);
			
			$params = [
				":id" => $id, 
				":usr" => $this->getUser(), 
				":tp_tipo" => $tp_tipo, 
				":tp_area" => $origen, 
				":tp_clasif" => $tp_clasif, 
				":id_region" => $res[0]['region'], 
				":correlativo" => $correlativo, 
				":tp_elem" => $tp_elem, 
				":tp_subelem" => $subelemento, 
				":idtp_clasif" => $tipoTrabajo, 
				":id_comuna" => $res[0]['comuna'], 
				":nom_elemen" => $res[0]['nombre_lugar'], 
				":sala" => $res[0]['sala'], 
				":tipo_ingreso" => $tipoingreso, 
				":lugar" => $res[0]['lugar']
			];
			
			# INSERT TP_DATA
			$sql=$this->getQuery("insert_tpdata", $params);
			$this->my_log("[". __FUNCTION__ ."] Insertamos TP_DATA: ".$sql);
			$tpdataAffected = $this->exeQuery($sql,$this->getConnBD());
			####################### FIN TP ########################
			
			if( $padre === "SI" ){
				$this->my_log("[". __FUNCTION__ ."] TP ".$id." es TP padre:".$padre." ");
				$res = $this->getTpprocesos($tipoTrabajo);
				$t=0;
				for( $r=0; $res<sizeof($res); $r++ ){
					$dtTpclasif_hijo = $this->getDatatpclasif($res[$r]['ID_HIJO']);
					if( !empty($dtTpclasif_hijo) ){
						$padre=$dtTpclasif_hijo[0]['PADRE'];
						$origen=$dtTpclasif_hijo[0]['ORIGEN'];
						$subelemento=$dtTpclasif_hijo[0]['SUBELEMENTOS'];
						$comprobar=$dtTpclasif_hijo[0]['COMPROBAR'];
						$relacion_sitio=$dtTpclasif_hijo[0]['RELACION_SITIO'];
						$id_region=$dtTpclasif_hijo[0]['ZONA'];
						$rpn_mayor=$dtTpclasif_hijo[0]['PUNTAJE'];
						$tp_tipo=$dtTpclasif_hijo[0]['TIPO'];
						$tp_clasif=$dtTpclasif_hijo[0]['CLASIFICACION'];
						$tp_elem=$dtTpclasif_hijo[0]['ELEMENTOS'];
					}
					# INSERTAMOS EN PLANNED 
					$params = [
						":titulo" => $fullTitulo, 
						":rpn" => $dtTpclasif_hijo[0]['PUNTAJE'], 
						":impacto" => 6, 
						":estados_id" => 1, 
						":tp_ver" => 1
					];
					$sql=$this->getQuery("insert_planned", $params);
					$result = $this->exeQuery($sql,$this->getConnBD());
					$id_hijo = $result['insert_id']; # ID TP HIJO 
					// array_push($ids,$id_hijo);
					$ids = [ "TP_hijo_$t" => $id_hijo ];
					
					$params = [
						":usr" => $this->getUser(), 
						":id" => $id_hijo, 
						":solicitante" => "SOLICITANTE"
					];
					# INSERT ASIGNACION 
					$sql=$this->getQuery("insert_asignacion", $params);
					$asignhijoAffected = $this->exeQuery($sql,$this->getConnBD());
					
					$params = [
						":planned_aux" => $planned_aux
					];
					$sql=$this->getQuery("qry_tramo", $params);
					$res = $this->exeQuery($sql,$this->getConnBD());
					
					$params = [
						":id" => $id_hijo, 
						":usr" => $this->getUser(), 
						":tp_tipo" => $tp_tipo, 
						":tp_area" => $origen, 
						":tp_clasif" => $tp_clasif, 
						":id_region" => $res[0]['region'], 
						":correlativo" => $correlativo, 
						":tp_elem" => $tp_elem, 
						":tp_subelem" => $subelemento, 
						":idtp_clasif" => $tipoTrabajo, 
						":id_comuna" => $res[0]['comuna'], 
						":nom_elemen" => $res[0]['nombre_lugar'], 
						":sala" => $res[0]['sala'], 
						":tipo_ingreso" => $tipoingreso, 
						":lugar" => $res[0]['lugar']
					];
					# INSERT TP_DATA
					$sql=$this->getQuery("insert_tpdata", $params);
					$tpdataAffected = $this->exeQuery($sql,$this->getConnBD());
					
					$params = [
						":id" => $id, 
						":id_hijo" => $id_hijo
					];
					# INSERT TP_HIJO 
					$sql=$this->getQuery("insert_tphijo", $params);
					$tphijoAffected = $this->exeQuery($sql,$this->getConnBD());
					
					$t++;
				} # FOR TP_PROCESOS 
			} # FIN IF PADRE 
			
			$params = [
				":usr" => $this->getUser(), 
				":id" => $id, 
				":solicitante" => "SOLICITANTE"
			];
			# INSERT ASIGNACION 
			$sql=$this->getQuery("insert_asignacion", $params);
			$asignTPAffected = $this->exeQuery($sql,$this->getConnBD());
			
			$sites = $this->getNewTramo($planned_aux);
			if( strpos($sites[0]['nombre_lugar'], '|') ){
				$sitios=explode('|',$sites[0]['nombre_lugar']);
				
				if( !empty($sitios) ){
					foreach($sitios as $sitio){
						if( empty($sitio) ) continue;
						$params = [
							":sitio" => $sitio
						];
						$sql=$this->getQuery("qry_sitios", $params);
						$resSitio = $this->exeQuery($sql,$this->getConnBD());
						$nomSitio = $resSitio[0]['NOMBRE'];
						$dirSitio = $resSitio[0]['DIRECCION'];
						$id_sitio = $resSitio[0]['SITE_ID'];
						
						// $sql="call sp_agrega_quita_sitios_relacionados(@asignTPAffected['affected'],'ADD',$id,$id_sitio);";
						// $resSP = $this->exeQuery($sql,$this->getConnBD());
						
						### INSERTAR NODO ################################### 
						$params = [
							":siteID" => $id_sitio
						];
						$sql=$this->getQuery("qry_nodo", $params);
						$res = $this->exeQuery($sql,$this->getConnBD());
						if( !empty($res[0]['ID']) ){
							$params = [
								":tpId" => $id,
								":nodoID" => $res[0]['ID']
							];
							$sql=$this->getQuery("qry_validaNodo", $params);
							$res = $this->exeQuery($sql,$this->getConnBD());
							if( empty($res[0]['NOMBRE']) ){
								$params = [
									":user" => $this->getUser(),
									":nodoID" => $res[0]['ID'],
									":tpId" => $id
								];
								$sql=$this->getQuery("insert_nodo", $params);
								$nodoAffected = $this->exeQuery($sql,$this->getConnBD());
							}
						}
						######################################################
					} # FIN FOREACH 
				} # FIN !EMPTY SITIOS 
			}
			
			$actualiza_rpn = null;
			### ACTUALIZA RPN #################################
			$actualiza_rpn = $this->actualizaRPN($id);
			
			return $ids;
		}
		
		
        function convertMes($nro){
			$this->my_log("[". __FUNCTION__ ."] INICIA " );
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
