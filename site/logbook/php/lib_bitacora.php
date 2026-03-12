<?php
	session_start();
	require "../../autoloader.php";
    use App\Funciones\classFunciones;
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	
	$cCfn->checkSession();
	$modulo="logbook";
	$iduser=$cCfn->getUser();
	
	function list_fallas_core_Telefonia($xop){
		// 
		global $iduser,$cCfn,$modulo;
		$data=null;
		$data="<option value=-1>- Seleccione Falla </option>";
		$sql=null;
		$params = [ $xop ];
		$result=$cCfn->exeQuery("nueva_bit_fallas",$params,'i',$cCfn->getLocal(),null,$modulo);
		$cntrow=$result->num_rows;
		$str_select = null;
		
		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select ='';
				if ($iduser == $row['IDREL']){
					$str_select =' selected ';
				}
				$data .= "<option ".$str_select." value='".$row['IDREL']."'>".$row['DESCRIPCION_FALLA']."</option>";
			}
		}
		
		return $data;
	}
	
	function list_severidad(){
		// 
		global $iduser,$cCfn,$modulo;
		$data=null;
		$data="<option value='-1'>- Seleccione Severidad </option>";
		$sql=null;
		$result = $cCfn->exeQuery("severidad_bit",null,null,$cCfn->getLocal(),null,$modulo);
		$cntrow = $result->num_rows;
		$str_select = null;
		
		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select ='';
				if ($iduser == $row['ID']){
					$str_select =' selected ';
				}
				$data .= "<option $str_select  value='".$row['ID']."'>".$row['NOMBRE']."</option>";
			}
		}
		
		return $data;
	}
	
	function list_bit_opciones(){
		// 
		global $iduser,$cCfn,$modulo;
		$data=null;
		$data="<option value='-1'>- Seleccione Item </option>";
		$result = $cCfn->exeQuery("nueva_bit_opc",null,null,$cCfn->getLocal(),null,$modulo);
		$cntrow = $result->num_rows;
		$str_select ='';
		
		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select ='';
				if ($iduser == $row['ID']){
					$str_select =' selected ';
				}
				$data .= "<option ".$str_select." value='".$row['ID']."'>".$row['OPCION']."</option>";
			}
		}
		
		return $data;
	}

	function list_paises(){
		// 
		global $iduser,$cCfn,$modulo;
		$data='';
		$data="<option value=-1 selected='selected' > - Seleccione Pais </option>";
		$result = $cCfn->exeQuery("nueva_bit_fallas2",null,null,$cCfn->getLocal(),null,$modulo);
		$cntrow = $result->num_rows;
		$str_select ='';

		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select = null;
				$data .= "<option ".$str_select." value='".$row['ID_PAIS']."'>".$row['PAIS']."</option>";
			}
		}
		
		return $data;
	}

	function list_regiones($xidpais='-1'){
		// 
		global $iduser,$cCfn,$modulo;
		$strvalor = ($xidpais == '-1' ? "ID_PAIS" : "'$xidpais'");
		
		$data=null;
		$data="<option value='-1' selected>- Seleccione Region </option>";
		$params = [ $strvalor ];
		$result=$cCfn->exeQuery("nueva_bit_fallas3",$params,'s',$cCfn->getLocal(),null,$modulo);
		$cntrow = $result->num_rows;
		$str_select ='';
		
		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select ='';
				if ($xidpais == $row['ID_REGION']){
					$str_select =' selected ';
				}

				$data .= "<option ".$str_select." value='".$row['ID_PAIS'].";".$row['ID_REGION']."'>".$row['REGION']."</option>";
			}
		}
		
		return $data;
	}

	function list_zona($xidpais='-1',$xidregi='-1'){
		// 
		global $iduser,$cCfn,$modulo;
		$strpais = ($xidpais == "-1" ? "IFNULL(ID_PAIS,'')"   : "'$xidpais'");
		$strregi = ($xidregi == "-1" ? "IFNULL(ID_REGION,'')" : "'$xidregi'");
		
		$data=null;
		$data="<option value=-1>- Seleccione Zona </option>";
		$params = [ $strpais,$strregi ];
		$result=$cCfn->exeQuery("nueva_bit_fallas4",$params,'ii',$cCfn->getLocal(),null,$modulo);
		$cntrow = $result->num_rows;
		$str_select ='';
		
		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select ='';
				if ($iduser == $row['ID_REGION']){
					$str_select =' selected ';
				}
				$data .= "<option ".$str_select." value='".$row['ID_PAIS'].";".$row['ID_REGION'].";".$row['ID_ZONA']."'>".$row['ZONA']."</option>";
			}
		}
		
		return $data;
	}


	function list_ubicaciones($xidpais='-1',$xidregi='-1',$xidzona='-1',$xidlugar='-1'){
		// 
		global $iduser,$cCfn,$modulo;
		$strzona = ($xidzona == "-1" ? "Z.ID_ZONA"   : "'$xidzona'");
		$data=null;
		$data="<option value='-1'>- Seleccione Lugar </option>";
		$params = [ $strzona ];
		$result=$cCfn->exeQuery("nueva_bit_fallas5",$params,'i',$cCfn->getLocal(),null,$modulo);
		$cntrow = $result->num_rows;
		$str_select ='';
		
		if ($cntrow >0){
			while( $row = $result->fetch_assoc() ){
				$str_select =null;
				$data .= "<option ".$str_select."  value='".$row['ID_PAIS'].";".$row['ID_REGION'].";".$row['ID_ZONA'].";".$row['ID_LUGAR']."'>".$row['LUGAR']."</option>";
			}
		}
		
		return $data;
	}
	
	/*******************************************************************************/
	
	$caso = $_GET["caso"] ?? $_POST["caso"] ?? '';
	$xidp = $_GET["idp"] ?? $_POST["idp"] ?? '';	/*	id Pais			*/
	$xidr = $_GET["idr"] ?? $_POST["idr"] ?? '';	/*	id Region		*/
	$xidz = $_GET["idz"] ?? $_POST["idz"] ?? '';	/*	id Zonas		*/
	$xidl = $_GET["idl"] ?? $_POST["idl"] ?? '';	/*	id Lugar 		*/
	$xidf = $_GET["idf"] ?? $_POST["idf"] ?? '';	/*  tipo de bitacora        */
	
	switch ($caso) {
		case 1:			
			$result = list_severidad();
			$data	= "<select id='id_severidad'>$result</select>";
			echo $data;
			break;
		case 2:
			$result = list_bit_opciones();
			$data   = "<select id='id_fallas_titulo'>$result</select>";
			echo $data;
			break;
		case 3:
			$result = list_fallas_core_Telefonia($xidf);
			$data   = "<select id='id_fallas_telefonia'>$result</select>";
			echo $data;
			break;

		case 4:
			$result = list_paises();
			$data   = "<select id='id_paises' class='bitbox'>$result</select>";
			echo $data;
			break;
		case 5:
			$result = list_regiones();
			$data   = "<select id='id_regiones' class='bitbox'>$result</select>";
			echo $data;
			break;
		case 6:
			$result = list_zona();
			$data   = "<select id='id_zonas' class='bitbox'>$result</select>";
			echo $data;
			break;

		case 7:
			$result = list_ubicaciones();
			$data   = "<select id='id_lugar' class='bitbox'>$result</select>";
			echo $data;
			break;
		case 8:		
			$result = list_regiones("$xidp");
			$data   = "<select id='id_regiones' class='bitbox'>$result</select>";
			echo $data;
			break;
		case 9:		
			$result = list_zona("$xidp");
			$data   = "<select id='id_zonas' class='bitbox'>$result</select>";
			echo $data;
			break;
		case 10:
			$result = list_zona("$xidp","$xidr");
			$data   = "<select id='id_zonas' class='bitbox'>$result</select>";
			echo $data;
			break;

		case 11:
			$result = list_ubicaciones("$xidp","$xidr","$xidz","$xidl");
			$data   = "<select id='id_lugar' class='bitbox'>$result</select>";
			echo $data;
			break;
	}	
?>