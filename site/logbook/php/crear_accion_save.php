<?php
	include_once("../../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$str_id		= $_REQUEST["id"];
	$str_desc	= $_REQUEST["desc"];
	
	$usr=$cCfn->getUser();
	
	$sql =null;
	$sql ="CALL sp_genera_nueva_accion (@result,@idbitacora,'$str_id','". addslashes($str_desc) ."','$usr');";
	
	$result = $cCfn->exeQuery($sql,$db);
		$rs     = $cCfn->exeQuery("SELECT @result AS resultado, @idbitacora as resultado2  ",$db);
		$row    = $rs;
		$resultado  = $row[0]['resultado'];
	$resultado2 = $row[0]['resultado2'];
		// $pos            = strrpos($href,"/");
        // $href_direccion = substr($href, $pos+1);
        $result = "";
        //$result = "$resultado2;$resultado;$href_direccion;$href";
        $result = "$resultado2;$resultado";
        echo $result;
?>