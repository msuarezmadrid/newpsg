<?php
	session_start();
	require "autoloader.php";
    use App\Funciones\classFunciones;
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfnSeg = $container->getFuncionesSeguridad();
	
	include_once(BASE_URL . "/site/messages/funciones/sms.php");
	
	$favicon=$cCfn->favicon();
	$politicas = $cCfnSeg->traePoliticas();
	
	$aux=session_id() . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_HOST'];
	$encriptado=md5($aux);
	$volver="site/home";
	## PARA LA NUEVA CLAVE 
	$largo_minimo=20;
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	$user_olvido=$_REQUEST['rUser'];
	
	## Validación de caracteres restringidos
	if ( $cCfn->valida_caracteres($user_olvido) > 0 ) {
		$params=[$user_olvido];
		$sql_log = "INSERT INTO intradb.LOG_EXCLUSION_CARACTERES (user, fecha, modulo) VALUES (?, NOW(), 'recuperacion_sms') ";
		$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql_log);
		session_destroy();
		echo "<script type='text/javascript'> alert('Usuario, usted ha ingresado caracteres no permitidos, debe cumplir con politicas de Seguridad'); 
		window.location.href = '$volver'; </script>";
	}
	
	## VALIDACION DE USUARIO - EXISTENCIA, MOVIL, ESTADO 
	$sql = "SELECT U.usr,MOVIL FROM intradb.users U INNER JOIN o_m.PERSONAL P ON P.ID = U.personal_id WHERE U.usr=? ";
	$params=[$user_olvido];
	$result=$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql);
	
	if( $result->num_rows === 0 ){
		$cCfnSeg->registro_intento($user_olvido,"CLAVE_CAMBIO","FALLA","hash_erroneo","CUENTA $user_olvido NO EXISTE"); # REGISTRO EN TABLA ERROR_LOGIN_LOG 
		session_destroy();
		echo "<script type='text/javascript'> alert('Error en la data ingresada. \\nUsuario no se encuentra registrado.'); window.location.href = '$volver'; </script>";
	}
	
	$row = $result->fetch_assoc();
	$movil=null;
	if( empty($row['MOVIL']) ){
		$cCfnSeg->registro_intento($user_olvido,"CLAVE_CAMBIO","FALLA","hash_erroneo","CUENTA $user_olvido NO TIENE MOVIL"); # REGISTRO EN TABLA ERROR_LOGIN_LOG 
		session_destroy();
		echo "<script type='text/javascript'> alert('Usuario no tiene celular registrado.'); window.location.href = '$volver'; </script>";
	}
	else{
		$movil=$row['MOVIL'];
	}
	
	$row=$cCfnSeg->traerParamPass("INTENTOS_FALLIDOS");
	$max_intentos=0;
	if( !empty($row) ){
		$max_intentos=$row[0]['valor'];
	}
	
	$row = $cCfnSeg->check_estado_usuario($user_olvido, $max_intentos);
	print_r($row);
	if( $row[0]['check_cta'] !== "CUENTA_OK" ){
		$msje="<br/><br/>Estimado $user_olvido:<br/>Su cuenta se encuentra ".$row[0]['check_cta'].". Contactese con el administrador de la plataforma. ";
		$cCfnSeg->registro_intento($user_olvido,"CLAVE_CAMBIO","FALLA","hash_erroneo","CUENTA ".$user_olvido." ".$row[0]['check_cta']." "); # REGISTRO EN TABLA ERROR_LOGIN_LOG 
		session_destroy();
		echo "<script type='text/javascript'> alert('".$msje."'); window.location.href = '$volver'; </script>";
	}
	
	############ DATOS VALIDADOS - SE GENERA NUEVA CLAVE 
	
	$sql = "SELECT llave,valor,mensaje_error FROM intradb.params_password WHERE activo=1 AND llave=? ";
	$params=["LARGO_MINIMO"];
	$result=$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql);
	if( !empty($result) && $result->num_rows > 0 ){
		$row=$result->fetch_row();
		$largo_minimo=$row[1];
	}
	
	$app = $cCfnSeg->generate_string($permitted_chars,$largo_minimo);
	$app = strtoupper($app);
	$newpass_sha256 = hash('sha256', $app);
	$newpass=$app;
	
	$ahora=date("Y-m-d H:i:s");
	$fecha_clave_temporal = date("Y-m-d H:i:s",strtotime($ahora."+ 1 day"));
	
	$sql="UPDATE intradb.users SET lastchange=0, md5_passw=?, tipo_password = '0', intentos_fallidos= '0', fecha_clave_temporal = ? WHERE usr=? ";
	$params=[$newpass_sha256,$fecha_clave_temporal,$user_olvido];
	$result=$cCfn->exeQuery(null,$params,'sss',$cCfn->getLocal(),$sql);
	$cCfnSeg->registro_intento($user_olvido,"CLAVE_CAMBIO","OK","","CUENTA $user_olvido CLAVE ACTUALIZADA"); # REGISTRO EN TABLA ERROR_LOGIN_LOG 
	
	## PREPARAMOS EL SMS 
	$texto="Estimado $user_olvido, su nueva password es: $newpass"; 
	sms($movil,"$texto", "PSG-USR");
	
	$sql="INSERT INTO intradb.LOG (usr,INGRESO,HOST,REM) VALUES ( '$user_olvido',NOW(),'$newpass','".$_SERVER['REMOTE_ADDR']."' ) ";
	$params=[$user_olvido,$newpass];
	$result=$cCfn->exeQuery(null,$params,'ss',$cCfn->getLocal(),$sql);
	
	echo "<script type='text/javascript'> alert('Mensaje SMS enviado.'); window.location.href = '$volver'; </script>";
	