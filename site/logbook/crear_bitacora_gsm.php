<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$usr=$cCfn->getUser();
	$db="intradb";
	/* include '../../protected/config_session.php';
	include_once("../../protected/config.php");
	include_once("../../protected/control.php");
	include_once("../../protected/user.php"); */
	$date = new DateTime();
	$ahora=$date->format('Y-m-d H:i:s');
	$ayear=$date->format('Y');
	$amonth=$date->format('m');
	$aday=$date->format('d');
	
	$all_sites = $_GET["all_sites"] ?? $_POST["all_sites"] ?? '';
	$severidad = $_GET["severidad"] ?? $_POST["severidad"] ?? '';
	$clasificacion = $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? '';
	$titulo = $_GET["titulo"] ?? $_POST["titulo"] ?? '';
	$comentario = $_GET["comentario"] ?? $_POST["comentario"] ?? '';
	$cerrar = $_GET["cerrar"] ?? $_POST["cerrar"] ?? '';
	$tipo = $_GET["tipo"] ?? $_POST["tipo"] ?? '';
?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Crear bitacora</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h1 align="center">Crear bitacora</h1>
			<form name='site' action='crear_bitacora_gsm.php' method='post'>
				<?php
					if( $all_sites == "todos" ){
						$all_chec="checked";
						$ala_chec="";
					}
					else{
						$all_chec="";
						$ala_chec="checked";
					} ?>
				<input type='radio' name='all_sites' value='todos' <?php echo $all_chec; ?> onclick='this.form.submit()' /> Lista de TODOS los sitios <br/>
				<input type='radio' name='all_sites' value='alarmas' <?php echo  $ala_chec; ?> onclick='this.form.submit()' /> Lista solo sitios ALARMADOS <br/>
				<p>Severidad: (obligatorio)
					<select name='severidad' required >
					<?php 
						if( empty($severidad) ){
							echo "<option value='' selected >Seleccionar</option>";
						}
						$sql=$cCfn->getQuery("severidad_bit", NULL);
						$result = $cCfn->exeQuery($sql,$db);
						// print_r($result); die;
						foreach( $result as $row ){
							if( $severidad == $row["ID"] ){
								echo "<option value='".$row['ID']."' selected>".$row['NOMBRE']."</option>";
							}
							else{
								echo "<option value='".$row['ID']."'>".$row['NOMBRE']."</option>";
							}
						} ?>
					</select>
				</p>
				
				<p>T&iacute;tulo: (parte fija, obligatorio)
					<select name='clasificacion' onchange='this.form.submit()' required >
					<?php
						if( empty($clasificacion) ){
							echo "<option value='' selected>Seleccionar</option>";
						}
						$sql=$cCfn->getQuery("nueva_bit_opc", NULL);
						$result = $cCfn->exeQuery($sql,$db);
						foreach( $result as $row ){
							if( $clasificacion == $row['OPCION'] ){
								echo "<option value='".$row['OPCION']."' selected>".$row['OPCION']."</option>";
							}
							else{
								echo "<option value='".$row['OPCION']."'>".$row['OPCION']."</option>";
							}
						} ?>
					</select>
				</p>
			</form>
			
			<form name='test' action='nueva_bitacora.php' method='post' >
				<?php
				// OPCION FALLAS INICIO
				$clasificacion = $clasificacion;
				$severidad = $severidad; ?>
				<p><select name='severidad' style='display:none' >
						<option value='<?php echo $severidad; ?>'></option>
				</select></p>
				<p><select name='clasificacion' style='display:none' >
					<option value='<?php echo $clasificacion; ?>' ></option>
				</select></p>

				<?php
					if($clasificacion == "FALLA"){
						echo "<p>Fallas: (Obligatorio)"; ?>
						<select name='falla' >
							<?php
								if( empty($falla) ){
									echo "<option value='' selected>Seleccionar</option>";
								}
								$sql=$cCfn->getQuery("falla_bit", NULL);
								$result = $cCfn->exeQuery($sql,$db);
								foreach( $result as $row ){
									if( $falla==$row['FALLA'] ){
										echo "<option value='".$row['FALLA']."' selected>".$row['FALLA']."</option>";
									}
									else{
										echo "<option value='".$row['FALLA']."'>".$row['FALLA']."</option>";
									}
								} ?>
						</select></p>
				<?php 
					} ?>
					
				<p>T&iacute;tulo (parte variable, opcional):</p>
				<p>
					<textarea name='titulo' rows='3' cols='49' wrap='soft'><?php echo $titulo; ?></textarea>
				</p>
				<p>
					Sitio/Nodo/Servicio (opcional): Selecci&oacute;n m&uacute;ltiple. Para seleccionar m&aacute;s de uno de la lista, se debe presionar CTRL al mismo tiempo. 
				</p>
				<p>
					<select name='sitio[]' size='5' multiple >
					<?php
						if ( $all_sites == "todos" ){
							$sql=$cCfn->getQuery("nueva_bit_SitNodServ1", NULL);
						}
						else{
							$sql=$cCfn->getQuery("nueva_bit_SitNodServ2", NULL);
						}
						$result = $cCfn->exeQuery($sql,$db);
						foreach( $result as $row ){
							echo "<option value='".$row['SITTO']." ".$row['NOMBRE']."'>".$row['NOMBRE']." ".$row['SITIO']."</option>";
						} ?>
					</select>
				</p>
				<p>
					<select name='nodo[]' size='5' multiple >
					<?php
						$sql=$cCfn->getQuery("nueva_bit_ne", NULL);
						$result = $cCfn->exeQuery($sql,$db);
						foreach( $result as $row ){
							echo "<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
						} ?>
					</select>
				</p>
				<p>
					<select name='servicio[]' size='5' multiple >
					<?php
						$sql=$cCfn->getQuery("nueva_bit_serv", NULL);
						$result = $cCfn->exeQuery($sql,$db);
						foreach( $result as $row ){
							echo "<option value='".$row['NOMBRE']."'>".$row['NOMBRE']."</option>";
						} ?>
					</select>
				</p>
				<p>
					Comentario inicial (opcional, obligatorio cuando se usa Crear y Cerrar)</p>
				<p>
					<textarea name='comentario' rows='5' cols='49' wrap='SOFT'><?php echo $comentario; ?></textarea></p>
				<p>
					Inicio de evento:
					<select name='iyear'>
						<?php
							for( $i=2002; $i<=date('Y'); $i++ ){
								if ($i==$ayear){
									echo "<option value='$i' selected>$i</option>";
								}
								else{
									echo "<option value='$i'>$i</option>";
								}
							} ?>
					</select>
					<select name='imonth' >
						<?php
							for( $i=1; $i<13; $i++ ){
								if ($i==$amonth){
									echo "<option value='$i' selected>$i</option>";
								}
								else{
									echo "<option value='$i'>$i</option>";
								}
							} ?>
					</select>
					<select name='iday'>
						<?php 
							for ($i=1; $i<32; $i++){
								if ($i==$aday){
									echo "<option value='$i' selected>$i</option>";
								}
								else{
									echo "<option value='$i'>$i</option>";
								}
							} ?>
					</select>
					<select name='ihour'>
						<?php
							echo "<option value=''>Seleccionar</option>";
							for ($i=0; $i<24; $i++){
								echo "<option value='$i'>$i</option>";
							} ?>
					</select>
					<select name='iminute'>
						<?php
							echo "<option value=''>Seleccionar</option>";
							for ($i=0; $i<60; $i++){
								echo "<option value='$i'>$i</option>";
							} ?>
					</select>
				</p>
				<p>
					Fin de evento:
					<select name='fyear'>
						<?php
							for ($i=2002; $i<=date('Y'); $i++){
								if ($i==$ayear){
									echo "<option value='$i' selected>$i</option>";
								}
								else{
									echo "<option value='$i'>$i</option>";
								}
							} ?>
					</select>
					<select name='fmonth'>
						<?php 
							for ($i=1; $i<13; $i++){
								if ($i==$amonth){
									echo "<option value='$i' selected>$i</option>";
								}
								else{
									echo "<option value='$i'>$i</option>";
								}
							} ?>
					</select>
					<select name="fday">
						<?php
						for ($i=1; $i<32; $i++){
							if ($i==$aday){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						} ?>
					</select>
					<select name="fhour">
						<?php
							echo "<option value=''>Seleccionar</option>";
							for ($i=0; $i<24; $i++){
								echo "<option value='$i'>$i</option>";
							} ?>
					</select>
					<select name="fminute">
						<?php
							echo "<option value=''>Seleccionar</option>";
							for ($i=0; $i<60; $i++){
								echo "<option value='$i'>$i</option>";
							} ?>
					</select>
				</p>
				<p>
					<input type="radio" name="cerrar" <?php if (($cerrar=="") or ($cerrar=="A")) echo "checked"; ?> value="A">Abrir
					<input type="radio" name="cerrar" <?php if ($cerrar=="C") echo "checked"; ?> value="C">Crear y Cerrar
				</p>
				<p>
					<input type="radio" name="tipo" <?php if (($tipo=="") or ($tipo==0)) echo "checked"; ?> value="0">P&uacute;blico
					<input type="radio" name="tipo" <?php if ($tipo==1) echo "checked"; ?> value="1">Privado
				</p>
				<p>
					<input  name="accion" type="submit" value="Ingresar"> <input type="reset" value="Borrar">
				</p>
			</form>
		</body>
	</html>
