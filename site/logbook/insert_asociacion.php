<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$assoc_id = $_GET["assoc_id"] ?? $_POST["assoc_id"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if ( empty($assoc_id) ){
		include_once("asociar_bitacora.php");
		exit;
	} ?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Crear/Quitar asociaci&oacute;n</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
		<?php 
			$params = [
				":id" => $id
			];
			$sql=$cCfn->getQuery("sel_bitacora", $params);
			$result = $cCfn->exeQuery($sql,$db);
			$pid   = $row[0]['PROBLEMA_ID'];
			$tp    = $row[0]['PLANNED_ID'];
			$tar   = $row[0]['TAREA_ID'];
			$sc    = $row[0]['SC_ID'];
			$csr   = $row[0]['CSR'];
			$bred  = $row[0]['BoletaRED'];
			$tot   = $row[0]['TOT'];
			$incpe = $row[0]['INC_PE']; ?>
			<p>
			<?php 
			$var=0;
			if( $accion == "Q" ){
				$params = [
					":id" => $id,
					":var" => $var
				];
				if ( $sel == "PID" ){
					if ( $assoc_id == $pid ){
						$sql=$cCfn->getQuery("update_bit_asoc_pid", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con Problema $pid borrada";
					}
					else{
						echo "Problema $pid no esta asociado a Bitacora $id";
					}
				}
				else if ( $sel == "TOT"){
					if ( $assoc_id == $tot ){
						$sql=$cCfn->getQuery("update_bit_asoc_tot", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con Tarea Office Track $tot borrada";
					}
					else{
						echo "Tarea Office Track $tot no esta asociado a Bitacora $id";
					}
				}
				else if ( $sel == "TP"){
					if ( $assoc_id == $tp ){
						$sql=$cCfn->getQuery("update_bit_asoc_tp", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con Trabajo programado $tp borrada";
					}
					else{
						echo "Trabajo programado $tp no esta asociado a Bitacora $id";
					}
				}
				else if ( $sel == "SC"){
					if ( $assoc_id == $sc ){
						$sql=$cCfn->getQuery("update_bit_asoc_sc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con Solicitud de Cambio $sc borrada";
					}
					else{
						echo "Solicitud de cambio $sc no esta asociado a Bitacora $id";
					}
				}
				else if ( $sel == "TAR"){
					if ( $assoc_id == $tar ){
						$sql=$cCfn->getQuery("update_bit_asoc_tar", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con Tarea $tar borrada";
					}
					else{
						echo "Tarea $tar no esta asociada a Bitacora $id";
					}
				}
				else if ( $sel == "CSR"){
					if ( $assoc_id == $csr ){
						$sql=$cCfn->getQuery("update_bit_asoc_csr", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con CSR $csr borrada";
					}
					else{
						echo "CSR $csr no esta asociada a Bitacora $id";
					}
				}
				else if ( $sel == "INCPE"){
					if ( $assoc_id == $incpe){
						$sql=$cCfn->getQuery("update_bit_asoc_incpe", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con INC_PE $incpe borrada";
					}
					else{
						echo "INC_PE $incpe no esta asociada a Bitacora $id";
					}
				}
				else{
					if ( $assoc_id == $bred ){
						$sql=$cCfn->getQuery("update_bit_asoc_bred", $params);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Asociaci&oacute;n con Boleta de red $bred borrada";
					}
					else{
						echo "Boleta de red $bred no esta asociada a Bitacora $id";
					}
				}
			}
			else{
				$params_a = [
					":id" => $id,
					":var" => $assoc_id
				];
				$params = [
					":assoc_id" => $assoc_id
				];
				if ( $sel == "PID" ){
					$sql=$cCfn->getQuery("asoc_bit_pid", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if( !empty($result) ){
						echo "Problema $assoc_id ya esta asociado a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("asoc_pid", $params);
						$result = $cCfn->exeQuery($sql,$db);
						if ( empty($result) ){
							echo "Problema $assoc_id no existe";
						}
						else{
							$sql=$cCfn->getQuery("update_bit_asoc_pid", $params_a);
							$result = $cCfn->exeQuery($sql,$db);
							echo "Problema $assoc_id asociado a Bitacora $id";
						}
					}
				}
				else if ( $sel == "TOT" ){
					$sql=$cCfn->getQuery("asoc_bit_pid", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "Tarea Office Track $assoc_id ya esta asociado a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("update_bit_asoc_pid", $params_a);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Tarea Office Track $assoc_id asociado a Bitacora $id";
					}
				}
				else if ( $sel == "TP" ){
					$sql=$cCfn->getQuery("asoc_bit_pid", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "Trabajo programado $assoc_id ya esta asociado a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("asoc_tp", $params);
						$result = $cCfn->exeQuery($sql,$db);
						if ( empty($result) ){
							echo "Trabajo programado $assoc_id no existe";
						}
						else{
							$sql=$cCfn->getQuery("update_bit_asoc_tp", $params_a);
							$result = $cCfn->exeQuery($sql,$db);
							echo "Trabajo programado $assoc_id asociado a Bitacora $id";
						}
					}
				}
				else if ( $sel == "SC" ){
					$sql=$cCfn->getQuery("asoc_bit_sc", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "Solicitud de Cambio $assoc_id ya esta asociado a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("asoc_sc", $params);
						$result = $cCfn->exeQuery($sql,$db);
						if ( empty($result) ){
							echo "Solicitud de cambio $assoc_id no existe";
						}
						else{
							$sql=$cCfn->getQuery("update_bit_asoc_sc", $params_a);
							$result = $cCfn->exeQuery($sql,$db);
							echo "Solicitud de cambio $assoc_id asociado a Bitacora $id";
						}
					}
				}
				else if ( $sel == "TAR" ){
					$sql=$cCfn->getQuery("asoc_bit_tar", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "Tarea $assoc_id ya esta asociada a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("asoc_tar", $params);
						$result = $cCfn->exeQuery($sql,$db);
						if ( empty($result) ){
							echo "Tarea $assoc_id no existe";
						}
						else{
							$sql=$cCfn->getQuery("update_bit_asoc_tar", $params_a);
							$result = $cCfn->exeQuery($sql,$db);
							echo "Tarea $assoc_id asociada a Bitacora $id";
						}
					}
				}
				else if ( $sel == "CSR" ){
					$sql=$cCfn->getQuery("asoc_bit_csr", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "CSR $assoc_id ya esta asociado a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("update_bit_asoc_csr", $params_a);
						$result = $cCfn->exeQuery($sql,$db);
						echo "CSR $assoc_id asociado a Bitacora $id";
					}
				}
				else if ( $sel == "INCPE" ){
					$sql=$cCfn->getQuery("asoc_bit_incpe", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "INC_PE $assoc_id ya esta asociado a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("update_bit_asoc_incpe", $params_a);
						$result = $cCfn->exeQuery($sql,$db);
						echo "INC_PE $assoc_id asociado a Bitacora $id";
					}
				}
				else{
					$sql=$cCfn->getQuery("asoc_bit_bred", $params);
					$result = $cCfn->exeQuery($sql,$db);
					if ( !empty($result) ){
						echo "Boleta de RED $assoc_id ya esta asociada a una Bitacora abierta (BIT ".$row[0]['ID'].")";
					}
					else{
						$sql=$cCfn->getQuery("update_bit_asoc_bred", $params_a);
						$result = $cCfn->exeQuery($sql,$db);
						echo "Boleta de RED $assoc_id asociada a Bitacora $id.";
					}
				}
			} ?>
			</p>
		</body>
	</html>