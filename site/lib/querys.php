<?php 
    $queries = [
        "qry_login" => "SELECT u.ID,u.USER,u.NOMBRE,u.APELLIDO,u.CORREO,u.MOVIL,u.ID_EMPRESA,p.PERFIL_ADC,p.CARGO,p.CREAR_TP,p.DIA_HABIL,p.HORARIO_HABIL,a.ORIGEN FROM intradb.users u INNER JOIN o_m.PERSONAL p ON p.ID = u.personal_id LEFT JOIN intradb.TP_AREA_ORIGEN a ON a.USUARIO = u.USER WHERE USER=':user' AND CLAVE=':pass' AND ELIMINADO IS NULL ",
		# query home vacaciones 
		"qry_vacaciones" => "SELECT VACACIONES,TURNO FROM intradb.users U INNER JOIN o_m.PERSONAL P ON U.personal_id=P.ID  WHERE USER=':user' ",
		# querys inicio 
		"qry_problemas" => "SELECT COUNT(P.ID)N FROM intradb.ASIGNACION A INNER JOIN intradb.PROBLEMA P ON A.PROBLEMA_ID=P.ID WHERE A.usr=':user' AND CIERRE IS NULL ",
		"qry_tareas" => "SELECT COUNT(T.ID)N FROM intradb.ASIGNACION_TAREA A INNER JOIN intradb.TAREA T ON A.TAREA_ID=T.ID WHERE A.usr=':user' AND CIERRE IS NULL ",
		"qry_trabajos" => "SELECT COUNT(PLANNED_ID)N FROM intradb.ASIGNACION_PLANNED A INNER JOIN intradb.PLANNED P ON A.PLANNED_ID=P.ID WHERE A.usr=':user' AND CIERRE IS NULL ",
		"qry_bitacoras" => "SELECT COUNT(ID)N FROM intradb.BITACORA WHERE usr=':user' AND FIN IS NULL ",
		"qry_inventario" => "SELECT COUNT(ID)N FROM o_m.INVENTARIO WHERE RESPONSABLE=':user' AND STATUS='ENVIADA' ",
		"qry_mensajes" => "SELECT COUNT(ID)N FROM intradb.MESSAGE_QUEUE WHERE DESTINO=':user' AND RECIBIDO IS NULL ",
		
		# query modal inicio 
		"qry_modal" => "SELECT TITULO,TEXTO FROM intradb.PARAM_INFO_MODAL WHERE ACTIVO=1 ",
		"qry_params" => "SELECT llave,descripcion,mensaje_error,valor FROM intradb.params_password WHERE llave=':llave' AND Activo=1 ",
		
		# query tp aux
		"qry_tpaux" => "SELECT COUNT(1)N FROM intradb.TMP_TP_DATA WHERE planned_aux=:tp_aux ",
		
		# querys creacion TP 
		"qry_tpTipo" => "SELECT ID,ORIGEN,TIPO FROM intradb.TP_TIPO ORDER BY ORIGEN ",
		"qry_servicios" => "SELECT DISTINCT ELEMENTOS FROM intradb.TP_CLASIFICACION WHERE ORIGEN=':areaorigen' AND FLAG = 0 AND HABILITADO = 'SI' ORDER BY ELEMENTOS ASC ",
		"qry_elementos" => "SELECT DISTINCT t.SUBELEMENTOS FROM intradb.TP_CLASIFICACION t WHERE t.ORIGEN = ':area' AND t.ELEMENTOS = ':servicio' AND FLAG=0 ORDER BY t.SUBELEMENTOS ASC ",
		"qry_tipotrabajo" => "SELECT DISTINCT(TIPO),ID FROM intradb.TP_CLASIFICACION WHERE ORIGEN=':area' ",
		"qry_tipoingreso" => "SELECT ID,TIPO_INGRESO FROM intradb.TIPO_INGRESO_TP ",
		"qry_comp_zona" => "SELECT ZONA FROM intradb.TP_CLASIFICACION WHERE ID=:tipotarea AND FLAG=0 AND HABILITADO = 'SI' ",
		"qry_valTitulo" => "SELECT DISTINCT ID,TIPO,CLASIFICACION,ORIGEN FROM intradb.TP_CLASIFICACION WHERE ORIGEN=':area' LIMIT 1 ",
		"qry_regiones" => "SELECT reg.REGION, reg.REGION_NOMBRE, reg.ID_REGION FROM o_m.TP_REGION reg ORDER BY reg.ID_REGION ASC ",
		"qry_comunas" => "SELECT tpc.ID,tpc.COMUNA,tpc.REGION, tpr.ID_REGION FROM o_m.TP_REGION tpr INNER JOIN o_m.COMUNAS tpc ON tpr.REGION=tpc.REGION WHERE tpr.ID_REGION=':region' ORDER BY tpc.COMUNA ",
		"qry_lugares" => "SELECT DISTINCT ELEMENTO LUGAR FROM intradb.TP_ELEMENTO WHERE ID_TP_REGION =':region' AND ID_TP_COMUNA=':comuna' 
			UNION ALL 
			SELECT DISTINCT 'Sitios' LUGAR 
			FROM o_m.TP_REGION r 
			INNER JOIN o_m.COMUNAS c ON (r.REGION = c.REGION) 
			INNER JOIN o_m.SITIOS s ON (s.REGION = r.region  AND s.COMUNA = c.comuna ) 
			WHERE r.ID_REGION =':region'  AND c.id = ':comuna' 
			AND CASE WHEN (SELECT 'S' FROM intradb.TP_ELEMENTO 
							WHERE ID_TP_REGION =':region' 
							AND ID_TP_COMUNA=':comuna' 
							AND ELEMENTO ='Sitios' LIMIT 1) ='S' THEN 'S' 
				ELSE 'N' 
				END='N' 
			ORDER BY 1 ASC ; ",
		"qry_sites" => "SELECT s.* FROM o_m.SITIOS s INNER JOIN o_m.COMUNAS c ON s.COMUNA = c.COMUNA AND c.ID= ':comuna' AND c.REGION = ':region' AND s.ESTADO != 'ELIMINADO' ORDER BY s.SITIO ASC ",
		"qry_nomlugares" => "SELECT s.* FROM o_m.SITIOS s INNER JOIN o_m.COMUNAS c ON (s.COMUNA = c.COMUNA  AND s.region = c.REGION) INNER JOIN o_m.TP_REGION r ON (r.REGION = c.REGION) WHERE r.ID_REGION =':region' AND c.ID = ':comuna' AND ESTADO!='ELIMINADO' ORDER BY s.SITIO ASC ",
		"qry_noms" => "SELECT DISTINCT NOMBRE FROM intradb.TP_ELEMENTO WHERE ID_TP_REGION = ':region' AND ID_TP_COMUNA = ':comuna' AND ELEMENTO = ':lugar' ORDER BY NOMBRE ",
		"qry_ubicacion" => "SELECT DISTINCT IF(SALA=' ','N/A',SALA) 'SALA' FROM intradb.TP_ELEMENTO WHERE ID_TP_REGION = ':region' AND ID_TP_COMUNA = ':comuna' AND ELEMENTO = ':lugar' AND NOMBRE = ':nombre_elemento' ORDER BY SALA ",
		"qry_idelemento" => "SELECT ID_TP_ELEMENTO FROM intradb.TP_ELEMENTO WHERE ID_TP_REGION = ':region' AND ID_TP_COMUNA = ':comuna' AND ELEMENTO = ':lugar' AND NOMBRE = ':elemento' AND SALA = ':sala' ",
		
		"qry_tramo" => "SELECT d.id, d.region, r.REGION_NOMBRE, d.comuna, c.COMUNA, d.lugar, d.nombre_lugar, d.sala, d.id_elemento FROM intradb.TMP_TP_DATA d INNER JOIN o_m.TP_REGION r ON r.ID_REGION=d.region INNER JOIN o_m.COMUNAS c ON c.ID=d.comuna WHERE d.planned_aux=:planned_aux ",
		"qry_sala" => "SELECT ID_TP_ELEMENTO FROM intradb.TP_ELEMENTO WHERE ID_TP_REGION = ':region' AND ID_TP_COMUNA = ':comuna' AND ELEMENTO = ':lugar' AND NOMBRE = ':elemento' AND SALA = ':sala' ",
		
		"insert_tmp_tp_data" => "INSERT INTO intradb.TMP_TP_DATA (planned_aux, region, comuna, lugar, nombre_lugar, sala, id_elemento, modalidadTrabajo) VALUES (:planned_aux, :region, :comuna, ':lugar', ':elemento', ':sala', :id_elemento, :modalidadTrabajo)",
		"update_tmp_tp_data" => "UPDATE intradb.TMP_TP_DATA SET planned_id=:id WHERE planned_aux=:planned_aux ",
		
		"qry_tpclasif" => "SELECT TIPO,CLASIFICACION,ORIGEN,ZONA,ELEMENTOS,SUBELEMENTOS,PUNTAJE,PADRE,COMPROBAR,RELACION_SITIO FROM intradb.TP_CLASIFICACION WHERE ID=:tipo ",
		
		"insert_planned" => "INSERT INTO intradb.PLANNED(OWNER,TITULO,DESCRIPCION,IMPACTO_ID,ESTADOS_ID,TP_VER,RPN) VALUES (NULL,':titulo',NULL,:impacto,:estados_id,:tp_ver,':rpn')",
		"insert_tpdata" => "INSERT INTO intradb.TP_DATA (PLANNED_ID,TP_SOLIC,TP_FECHA,TP_TIPO,TP_AREA,TP_CLASIF,ID_REGION,TP_REF,TP_ELEMENTOS,TP_SUBELEMENTOS,ID_TP_CLASIFICACION,ID_COMUNA,NOMBRE_ELEMENTO,SALA,TIPO_INGRESO,LUGAR) 
			VALUES (':id',':usr',NOW(),':tp_tipo',':tp_area',':tp_clasif',':id_region',':correlativo',':tp_elem',':tp_subelem',':idtp_clasif',':id_comuna',':nom_elemen',':sala',':tipo_ingreso',':lugar')",
		
		"qry_tpprocesos" => "SELECT ID_PADRE, ID_HIJO FROM intradb.TP_PROCESOS WHERE ID_PADRE = ':tipo' ",
		
		"insert_asignacion" => "INSERT INTO intradb.ASIGNACION_PLANNED (ASIGNA,usr,INGRESO,PLANNED_ID,TP_ROL) VALUES (':usr', ':usr', NOW(), :id, ':solicitante') ",
		"insert_tphijo" => "INSERT INTO intradb.TP_HIJO (TP_PADRE, TP_HIJO) VALUES (:id,:id_hijo) ",
		
		"qry_sitios" => "SELECT SITE_ID,SITIO,NOMBRE,RSITE,ESTADO,FECHA_INTEGRACION,FECHA_ELIMINACION,DIRECCION,COMUNA,REGION,BSC,S_TIPO,P_ENERGIA,P_TRAMAS,III_G,IN_SITE,ESTADOS_OSS,PE_3G,RED_MINIMA,FDT FROM o_m.SITIOS WHERE SITIO = ':sitio' ", 
		
		"qry_nodo" => "SELECT A.ID FROM intradb.NE A INNER JOIN o_m.SITIOS S ON A.NOMBRE=S.BSC WHERE S.SITE_ID=:siteID ",
		"qry_validaNodo" => "SELECT NE.NOMBRE FROM intradb.NE NE INNER JOIN intradb.NE_PLANNED NEP ON NE.ID=NEP.NE_ID WHERE NEP.PLANNED_ID=:tpId AND NE.ID=:nodoID ",
		"insert_nodo" => "INSERT INTO intradb.NE_PLANNED (usr,INGRESO,NE_ID,PLANNED_ID) VALUES (':user',NOW(),:nodoID,:tpId) ",
		
		"qry_ptjeRPN" => "SELECT t.PUNTAJE, t.COMPROBAR, t.RELACION_SITIO FROM intradb.TP_CLASIFICACION t LEFT JOIN intradb.TP_DATA d ON d.ID_TP_CLASIFICACION = t.ID WHERE d.PLANNED_ID = :tp ",
		"qry_ptjetramas" => "SELECT e.RPN_ASIG FROM intradb.TP_ELEMENTO e LEFT JOIN intradb.TP_DATA d ON d.ID_COMUNA = e.ID_TP_COMUNA AND d.SALA = e.SALA AND d.NOMBRE_ELEMENTO = e.NOMBRE WHERE d.PLANNED_ID = :tp ",
		"qry_ptjesitio" => "SELECT e.RPN_ASIG FROM intradb.TP_ELEMENTO e LEFT JOIN o_m.SITIOS s ON s.SITIO = e.NEMONICO LEFT JOIN intradb.SITIOS_PLANNED sp ON sp.SITE_ID = s.SITE_ID WHERE sp.PLANNED_ID = ':tp' ",
		
		"qry_tpdata" => "SELECT ID, TP_SOLIC, TP_FECHA, TP_AREA, TP_SUBELEMENTOS, TP_ELEMENTOS, TP_TIPO, TP_CLASIF, TP_ZONA, TP_REF, TP_INTERNA, TP_FECHA_SOLICITADA, PLANNED_ID, TP_FECHA_GESTION, ID_TP_CLASIFICACION, ID_COMUNA, NOMBRE_ELEMENTO, SALA, ID_REGION, TIPO_INGRESO, LUGAR, ID_ELEMENTO, MODALIDAD_TRABAJO FROM intradb.TP_DATA WHERE PLANNED_ID = :tp ",
		"update_planned" => "UPDATE intradb.PLANNED SET RPN=:final WHERE ID=:tp ",
		
		"delete_tmp_tp_data" => "DELETE FROM intradb.TMP_TP_DATA WHERE id=:id AND planned_aux=:planned_aux ",
		"qry_editaTramo" => "SELECT d.id, d.region, r.REGION_NOMBRE, d.comuna, c.COMUNA, d.lugar, d.nombre_lugar, d.sala, d.id_elemento,d.planned_aux FROM intradb.TMP_TP_DATA d INNER JOIN o_m.TP_REGION r ON r.ID_REGION=d.region INNER JOIN o_m.COMUNAS c ON c.ID=d.comuna WHERE d.id=:id_reg ",
		"update_tramo" => "UPDATE intradb.TMP_TP_DATA SET region=:region,comuna=:comuna,lugar=':lugar',nombre_lugar=':nom_lugar',sala=':ubicacion',id_elemento=:id_elemento WHERE id=:id_reg ",
		
		# VER TP 
		"qry_tp" => "SELECT P.TITULO,P.DESCRIPCION,A.NOMBRE,B.NOMBRE NOMBRE_EDO,CONCAT_WS(' ',P.FECHA_INICIO,P.HORA_INICIO) as TP_PLANNED,P.TP_FECHA_EXEC,P.FECHA_FIN,P.OWNER,P.TP_VER,P.RPN ,P.ESTADOS_ID,CONCAT(P.FECHA_TERMINO, ' ' , P.HORA_TERMINO) FECHA_PLANIF_FIN  
			FROM intradb.PLANNED P 
				LEFT JOIN intradb.IMPACTO A ON P.IMPACTO_ID=A.ID 
				LEFT JOIN intradb.ESTADOS_PLANNED B ON P.ESTADOS_ID=B.ID 
				LEFT JOIN intradb.TP_DATA D ON D.PLANNED_ID=P.ID 
				LEFT JOIN intradb.TP_CLASIFICACION C ON C.ID=D.ID_TP_CLASIFICACION 
			WHERE P.ID=:id ;",
		
		"qry_terreno" => "SELECT C.TERRENO FROM intradb.TP_TERRENO C WHERE C.ID_PLANNED=:id ORDER BY FECHA DESC LIMIT 1 ",
		
		"qry_detalleTp" => "SELECT B.TP_SOLIC,B.TP_FECHA,B.TP_AREA,B.TP_TIPO,B.TP_CLASIF,B.ID_REGION,B.TP_REF,B.TP_FECHA_SOLICITADA,A.PUNTAJE,
				CASE WHEN B.TP_ELEMENTOS='Energia y Clima' AND (B.ID_REGION IS NULL AND B.ID_COMUNA IS NULL) 
					THEN 'Energia y Clima.' ELSE B.TP_ELEMENTOS END AS TP_ELEMENTOS,B.TP_SUBELEMENTOS,A.ID,c.COMUNA,B.NOMBRE_ELEMENTO,B.SALA,B.TP_ZONA,c.ID ID_COMU,(SELECT REGION_NOMBRE FROM o_m.TP_REGION tpreg WHERE tpreg.REGION=B.ID_REGION LIMIT 1) AS NOMBREREGION, CONCAT(re.REGION,'-',re.REGION_NOMBRE) AS REGION,
				B.LUGAR as ELEMENTO,ti.TIPO_INGRESO, B.ID as ID_TP_DATA 
			FROM intradb.TP_CLASIFICACION A 
				LEFT JOIN intradb.TP_DATA B ON A.ID = B.ID_TP_CLASIFICACION 
				LEFT JOIN o_m.COMUNAS c ON B.ID_COMUNA=c.ID 
				LEFT JOIN o_m.TP_REGION re ON (re.ID_REGION = B.ID_REGION) 
				LEFT JOIN TIPO_INGRESO_TP ti ON (ti.ID=B.TIPO_INGRESO) 
			WHERE B.PLANNED_ID=:tp LIMIT 1 ",
		
    ];