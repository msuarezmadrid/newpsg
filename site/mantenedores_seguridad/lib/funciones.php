<?php 
	$modulo="mantenedores_seguridad";
	
	$files=basename($_SERVER['PHP_SELF']);
	
	function traeParametrosClave(){
		global $cCfn,$modulo;
		$result=$cCfn->exeQuery("queryParamClave",null,null,$cCfn->getLocal(),null,$modulo);
		while( $row=$result->fetch_row() ){
			$array[]=$row;
		}
		return $array;
	}
	
	function traeParamPass($id){
		global $cCfn,$modulo;
		$params=[$id];
		$result=$cCfn->exeQuery("queryParamClaveId",$params,'i',$cCfn->getLocal(),null,$modulo);
		$array=null;
		while( $row=$result->fetch_row() ){
			$array[]=$row;
		}
		return $array;
	}
	
	function actualizarRegistro($llave,$descripcion,$valor,$mensaje,$activo,$id){
		global $cCfn,$modulo;
		$desc=addslashes($descripcion);
		$valor=addslashes($valor);
		$msje=addslashes($mensaje);
		$params=[$desc,$valor,$activo,$msje,$id];
		$result=$cCfn->exeQuery("update_llave",$params,'sssssi',$cCfn->getLocal(),null,$modulo);
		$affected=$result['affected'];
		return $affected;
	}
	
	function guardarRegistro($llave,$descripcion,$valor,$mensaje,$activo){
		global $cCfn,$modulo;
		$desc=addslashes($descripcion);
		$valor=addslashes($valor);
		$msje=addslashes($mensaje);
		$params=[$llave,$desc,$valor,$activo,$msje];
		$result=$cCfn->exeQuery("insertClave",$params,'sssis',$cCfn->getLocal(),null,$modulo);
		$affected=$result['affected'];
		return $affected;
	}
	
	## MANTENEDOR BLOQUEO 
	function traeParametrosBloqueo(){
		global $cCfn,$modulo;
		$result=$cCfn->exeQuery("queryParamBloqueo",null,null,$cCfn->getLocal(),null,$modulo);
		$array=null;
		while( $row = $result->fetch_row() ){
			$array[]=$row;
		}
		return $array;
	}
	
	function traeParamBloq($id){
		global $cCfn,$modulo;
		$params=[$id];
		$result=$cCfn->exeQuery("queryParamBloqueoId",$params,null,$cCfn->getLocal(),null,$modulo);
		$array=null;
		while( $row=$result->fetch_row() ){
			$array[]=$row;
		}
		return $array;
	}
	
	function actualizarRegistroBloq($clave,$detalle,$descripcion,$activo,$id){
		global $cCfn,$modulo;
		$desc=addslashes($descripcion);
		$params=[$clave,$detalle,$desc,$activo,$id];
		$result=$cCfn->exeQuery("updateBloqueo",$params,'sssii',$cCfn->getLocal(),null,$modulo);
		$affected=$result['affected'];
		return $affected;
	}
	
	function guardarRegistroBloq($clave,$detalle,$descripcion,$activo){
		global $cCfn,$modulo;
		$desc=addslashes($descripcion);
		$params=[$clave,$detalle,$desc,$activo];
		$result=$cCfn->exeQuery("insertBloqueo",$params,'sssi',$cCfn->getLocal(),null,$modulo);
		$affected=$result['affected'];
		return $affected;
	}
	
?>