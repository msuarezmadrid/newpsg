<?php 
    $queries = [
		## HOME 
		"qry_vacaciones" => "SELECT VACACIONES,TURNO FROM intradb.users U INNER JOIN o_m.PERSONAL P ON U.personal_id=P.ID  WHERE usr=? ",
		"qry_problemas" => "SELECT COUNT(P.ID)N FROM intradb.ASIGNACION A INNER JOIN intradb.PROBLEMA P ON A.PROBLEMA_ID=P.ID WHERE A.usr=? AND CIERRE IS NULL ",
		"qry_tareas" => "SELECT COUNT(T.ID)N FROM intradb.ASIGNACION_TAREA A INNER JOIN intradb.TAREA T ON A.TAREA_ID=T.ID WHERE A.usr=? AND CIERRE IS NULL ",
		"qry_trabajos" => "SELECT COUNT(PLANNED_ID)N FROM intradb.ASIGNACION_PLANNED A INNER JOIN intradb.PLANNED P ON A.PLANNED_ID=P.ID WHERE A.usr=? AND CIERRE IS NULL ",
		"qry_bitacoras" => "SELECT COUNT(ID)N FROM intradb.BITACORA WHERE usr=? AND FIN IS NULL ",
		"qry_inventario" => "SELECT COUNT(ID)N FROM o_m.INVENTARIO WHERE RESPONSABLE=? AND STATUS='ENVIADA' ",
		"qry_mensajes" => "SELECT COUNT(ID)N FROM intradb.MESSAGE_QUEUE WHERE DESTINO=? AND RECIBIDO IS NULL ",
		"qry_modal" => "SELECT TITULO,TEXTO FROM intradb.PARAM_INFO_MODAL WHERE ACTIVO=1 ",
		
	];
	return $queries;