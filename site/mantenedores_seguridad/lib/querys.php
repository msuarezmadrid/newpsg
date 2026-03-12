<?php
	$queries = [
		## CLAVES 
		"queryParamClave"=>"SELECT llave,descripcion,valor,mensaje_error,Activo,id FROM intradb.params_password ",
		"queryParamClaveId"=>"SELECT llave,descripcion,valor,mensaje_error,Activo,id FROM intradb.params_password WHERE id=? ",
		"update_llave"=>"UPDATE intradb.params_password SET llave=?,descripcion=?,valor=?,Activo=?,mensaje_error=? WHERE id=? ",
		"insertClave"=>"INSERT INTO intradb.params_password(`llave`, `descripcion`, `valor`, `Activo`, `mensaje_error`) VALUES ( ?,?,?,?,? ) ",
		## BLOQUEO 
		"queryParamBloqueo"=>"SELECT clave,detalle,descripcion,activo,id FROM intradb.config_block_user ",
		"queryParamBloqueoId"=>"SELECT clave,detalle,descripcion,activo,id FROM intradb.config_block_user WHERE id=? ",
		"updateBloqueo"=>"UPDATE intradb.config_block_user SET clave=?,detalle=?,descripcion=?,activo=? WHERE id=? ",
		"insertBloqueo"=>"INSERT INTO intradb.config_block_user(`clave`, `detalle`, `descripcion`, `activo` ) VALUES ( ?,?,?,? ) ",
	];
	