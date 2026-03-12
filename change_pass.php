<?php
	session_start();
	require "autoloader.php";
    use App\Funciones\classFunciones;
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	
	$usr = $_REQUEST["user"] ?? $_POST["user"] ?? '';
	
	$sql = "SELECT llave,valor,mensaje_error FROM intradb.params_password WHERE activo=1 ";
	$result = $cCfn->exeQuery(null,null,null,$cCfn->getLocal(),$sql,null);
	
	if ( !empty($result) ) {
		// 
		while ($datos = $result->fetch_assoc()) {
			if (strcmp($datos['llave'], "LARGO_MINIMO") == 0) {
				$largo_minimo = $datos['valor'];
				$largo_minimo_error = str_replace('_min_', $largo_minimo, $datos['mensaje_error']);
			} else if (strcmp($datos['llave'], "LARGO_MAXIMO") == 0) {
				$largo_maximo = $datos['valor'];
				$largo_maximo_error = str_replace('_max_', $largo_maximo, $datos['mensaje_error']);
			} else if (strcmp($datos['llave'], "INCLUSION_CARACTERES_ESPECIALES") == 0) {
				$inclusion_caracteres_especiales = $datos['valor'];
				$inclusion_caracteres_especiales_error = str_replace('_chars_', $inclusion_caracteres_especiales, $datos['mensaje_error']);
			} else {
				$arr = explode(",", $datos['valor']);
				$variable = strtolower($datos['llave']);
				$variable_error = strtolower($datos['llave']) . "_error";
				$variable = $arr;
				$variable_error = $datos['mensaje_error'];
			}
		}
	} else {
		echo '<script type="text/javascript"> alert("Error al obtener parametros de contraseña"); window.location.href = "index.php"; </script>';
		exit;
	}
	###############################################
	$params=[$usr];
	$sql = "SELECT personal_id FROM intradb.users WHERE usr=? ";
	$result = $cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
	if ( empty($result) ) {
		echo '<script type="text/javascript"> 
			alert("Usuario no existe"); 
			window.location.href = "index.php";
		</script>';
		session_destroy();
		// header("Location: index.php");
		exit;
	}
	$row = $result->fetch_assoc();
	$pid = $row['personal_id'];
	
	###############################################
	
	$excludes_personal = array();
	$params=[$pid];
	$sql = "SELECT P_NOMBRE,S_NOMBRE,P_APELLIDO,S_APELLIDO,AREA,SUBGERENCIA FROM o_m.PERSONAL WHERE ID=? ";
	$result = $cCfn->exeQuery(null,$params,'i',$cCfn->getLocal(),$sql,null);
	$texto = "";
	$textoArr = "";
	if ( $row = $result->fetch_assoc() ) {
		if( !empty($row['P_NOMBRE']) ) array_push($excludes_personal, $row['P_NOMBRE']); 
		if( !empty($row['S_NOMBRE']) ) array_push($excludes_personal, $row['S_NOMBRE']); 
		if( !empty($row['P_APELLIDO']) ) array_push($excludes_personal, $row['P_APELLIDO']); 
		if( !empty($row['S_APELLIDO']) ) array_push($excludes_personal, $row['S_APELLIDO']); 
		if( !empty($row['AREA']) ) array_push($excludes_personal, $row['AREA']); 
		if( !empty($row['SUBGERENCIA']) ) array_push($excludes_personal, $row['SUBGERENCIA']); 
		
		/*for ($i = 0; $i < 6; $i++) {
			if (!empty($datos[$i])) {
				if ($i < 4) {
					array_push($excludes_personal, $datos[$i]);
				} else {
					$values = explode(" ", $datos[$i]);
					for ($j = 0; $j < count($values); $j++) {
						if (!empty($values[$j]) && strlen($values[$j]) > 2) {
							//echo '<script type="text/javascript"> alert("value: '.$values[$j].'");  </script>';
							array_push($excludes_personal, $values[$j]);
						}
					}
				}
				$texto = $texto . $datos[$i] . '-';
			}
		}*/
	}
	
	try {
		if (empty($_POST['oldpassw']) || empty($_POST['newpassw']) || empty($_POST['newpassw2'])) {
			throw new Exception("Las contraseñas no pueden estar vacías.");
		}
		
		$oldpass = $_POST['oldpassw'];  
		$newpass1 = $_POST['newpassw'];
		$newpass2 = $_POST['newpassw2'];
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
	}

	$cnp_newpass1 = $cCfn->valida_caracteres($newpass1);
	$cnp_newpass2 = $cCfn->valida_caracteres($newpass2);
	$params=[$usr];
	if ($cnp_newpass1 > 0 || $cnp_newpass2 > 0) {
		$sql_log = "INSERT INTO LOG_EXCLUSION_CARACTERES (user, fecha, modulo) VALUES (?,NOW(),'cambio_pass') ";
		$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql_log,null);
		echo "<script type='text/javascript'> 
			alert('Usuario, usted ha ingresado caracteres no permitidos, debe cumplir con politicas de Seguridad'); 
			window.location.href = 'index.php';
		</script>";
		// header("Location: index.php");
	}
	## VALIDACIONES PREVIAS, SEGUN JAVASCRIPT 
	if (isset($largo_minimo)) {
		if (strlen($newpass1) < $largo_minimo) {
			$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "LARGO DE CLAVE NO VALIDO (LONGITUD MINIMA)"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
			echo '<script type="text/javascript"> 
				alert("' . $largo_minimo_error . '"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			exit;
		}
	}

	if (isset($largo_maximo)) {
		if (strlen($newpass1) > $largo_maximo) {
			$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "LARGO DE CLAVE NO VALIDO (LONGITUD MAXIMA)");
			echo '<script type="text/javascript"> 
				alert("' . $largo_maximo_error . '"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			exit;
		}
	}
	if (isset($inclusion_caracteres_especiales)) {
		$validation = $cCfnSeg->validate_format($newpass1, $inclusion_caracteres_especiales);
		if ( empty($validation) ) {
			$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "CLAVE NO TIENE FORMATO VALIDO (CARACTERES ESPECIALES)");
			echo '<script type="text/javascript"> 
				alert("' . $inclusion_caracteres_especiales_error . '"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			exit;
		}
	}
	if (isset($exclusion_palabras)) {
		$validation_exclusion_palabras = $cCfnSeg->validate_exclusions($newpass1, $exclusion_palabras);
		if ( empty($validation_exclusion_palabras) ) {
			$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "CLAVE NO TIENE FORMATO VALIDO (ENTEL O SUS DERIVADOS)");
			echo '<script type="text/javascript"> 
				alert("' . $exclusion_palabras_error . '"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			exit;
		}
	}
	if (isset($exclusion_numeros_consecutivos)) {
		$validation_exclusion_numeros_consecutivos = $cCfnSeg->validate_exclusions($newpass1, $exclusion_numeros_consecutivos);
		if ( empty($validation_exclusion_numeros_consecutivos) ) {
			$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "CLAVE NO TIENE FORMATO VALIDO (NUMEROS CONSECUTIVOS)");
			echo '<script type="text/javascript"> 
				alert("' . $exclusion_numeros_consecutivos_error . '"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			exit;
		}
	}
	if (isset($exclusion_anios)) {
		$validation_exclusion_anios = $cCfnSeg->validate_exclusions($newpass1, $exclusion_anios);
		if ( empty($validation_exclusion_anios) ) {
			$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "CLAVE NO TIENE FORMATO VALIDO (AÑOS)");
			echo '<script type="text/javascript"> 
				alert("' . $exclusion_anios_error . '"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			exit;
		}
	}

	$validPersonal = 1;
	$validPersonal = $cCfnSeg->validate_exclusions($newpass1, $excludes_personal);

	if ( empty($validPersonal) ) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "CLAVE TIENE INFORMACION PERSONAL");
		echo '<script type="text/javascript"> 
			alert("Password no debe contener información personal, tales como: \n - Nombres\n - Apellidos\n - Area o Subgerencia "); 
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		exit;
	}
	if ($oldpass == $newpass1) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "NUEVA CLAVE NO VALIDA"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo '<script type="text/javascript"> 
			alert("No puede utilizar el mismo Password"); 
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		exit;
	}
	if ($newpass1 != $newpass2) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "CONFIRMACION DE CLAVE NO VALIDA"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo '<script type="text/javascript"> 
			alert("Error al confirmar la nueva Password");  
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		exit;
	}
	$md5_old = md5($oldpass);
	$md5_new = md5($newpass1);

	$sha256_old = hash('sha256', $oldpass);
	$sha256_new = hash('sha256', $newpass1);

	###############################################
	$params=[$usr];
	$sql = "SELECT md5_passw FROM intradb.users WHERE usr=? ";
	$result = $cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql,null);
	
	if ( empty($result) ) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "USUARIO NO EXISTE"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo '<script type="text/javascript"> 
			alert("Usuario no existe"); 
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		session_destroy();
		exit;
	}
	
	$row = $result->fetch_assoc();

	$md5_passw = $row['md5_passw'];
	$lastchange = time();
	if (strlen($md5_passw) == 64) { //sha256
		if ($md5_passw != $sha256_old) {
			echo '<script type="text/javascript"> 
				alert("Password no valido."); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			session_destroy();
			exit;
		}
	} else { //md5
		if ($md5_passw != $md5_old) {
			echo '<script type="text/javascript"> 
				alert("Password no válido"); 
				window.location.href = "index.php";
			</script>';
			// header("Location: index.php");
			session_destroy();
			exit;
		}
	}
	
	$sql = "SELECT ingreso FROM intradb.OLD_PASS WHERE usr=? AND md5_passw=? ";
	$params=[$usr,$md5_new];
	$result = $cCfn->exeQuery(null,$params,'ss',$cCfn->getLocal(),$sql,null);
	if ( empty($result) ) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "NUEVA CLAVE YA HA SIDO UTILIZADA ANTES"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo '<script type="text/javascript"> 
			alert("Password ya ha sido utilizada anteriormente"); 
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		session_destroy();
		exit;
	}
	
	$sql = "SELECT ingreso FROM intradb.OLD_PASS WHERE usr=? AND md5_passw=? ";
	$params=[$usr,$sha256_new];
	$result = $cCfn->exeQuery(null,$params,'ss',$cCfn->getLocal(),$sql,null);
	if ( empty($result) ) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "NUEVA CLAVE YA HA SIDO UTILIZADA ANTES"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo '<script type="text/javascript"> 
			alert("Password ya ha sido utilizada anteriormente"); 
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		session_destroy();
		exit;
	}
	
	$sql = "UPDATE intradb.users SET lastchange=?,md5_passw=?,tipo_password=1, estado = 0  WHERE usr=? ";
	$params=[$lastchange,$sha256_new,$usr];
	$result = $cCfn->exeQuery(null,$params,'sss',$cCfn->getLocal(),$sql,null);
	$affected = $result['affected'];
	if ( empty($affected) ) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "NUEVA CLAVE NO ACTUALIZADA"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo '<script type="text/javascript"> 
			alert("Password no pudo ser actualizada"); 
			window.location.href = "index.php";
		</script>';
		// header("Location: index.php");
		session_destroy();
		exit;
	} elseif ($affected == -1) {
		$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "FALLA", "hash_erroneo", "ERROR EN QUERY DE ACTUALIZACION DE NUEVA CLAVE"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
		echo "<script type='text/javascript'> 
			alert(?Error en Query registro_intento'); 
			window.location.href = 'index.php';
		</script>";
		// header("Location: index.php");
		session_destroy();
		exit;
	}
	$sql = "INSERT INTO intradb.OLD_PASS (usr,ingreso,md5_passw) VALUES (?,NOW(),?)";
	$params=[$usr,$sha256_new];
	$result = $cCfn->exeQuery(null,$params,'ss',$cCfn->getLocal(),$sql,null);
	$cCfnSeg->registro_intento($usr, "CLAVE_CAMBIO", "OK", "", "NUEVA CLAVE ACTUALIZADA"); # REGISTRO DE LOGIN, TABLA ERROR_LOGIN_LOG 
	
	$params=[$usr];
	$sql = "SELECT md5_passw, lastchange, categoria, profile, roles, usr, IFNULL(intentos_fallidos, 0) AS intentos, estado FROM intradb.users WHERE usr=? ";
	$result=$cCfn->exeQuery(null,$params,'s',$cCfn->getLocal(),$sql);
	$row = $result->fetch_assoc();
	if( !empty($row) ) {
		## GENERAMOS DATOS DE SESION 
		$_SESSION['user'] = $usr;
		$_SESSION['profile'] = $row['profile'];
		$_SESSION['roles'] = $row['roles'];
		$_SESSION['categoria'] = $row['categoria'];
		$_SESSION['lastchange'] = $row['lastchange'];
		$_SESSION['estado'] = $row['estado'];
	}
	header("Location: site/home");
	