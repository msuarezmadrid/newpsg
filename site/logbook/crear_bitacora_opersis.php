<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$usr=$cCfn->getUser();
	$db="intradb";
	
	$iyear	= $_GET["iyear"] ?? $_POST["iyear"] ?? "" ;
	$iyear	= ( !ctype_digit($iyear) || empty($iyear) ) ? date("Y") : $iyear;
	$imonth	= $_GET["imonth"] ?? $_POST["imonth"] ?? "";
	$imonth	= ( !ctype_digit($imonth) || empty($imonth) ) ? date("m") : $imonth;
	$iday	= $_GET["iday"] ?? $_POST["iday"] ?? "";
	$iday	= ( !ctype_digit($iday) || empty($iday) ) ? date("d") : $iday;
	$fyear	= $_GET["fyear"] ?? $_POST["fyear"] ?? "";
	$fyear	= ( !ctype_digit($fyear) || empty($fyear) ) ? date("Y") : $fyear;
	$fmonth	= $_GET["fmonth"] ?? $_POST["fmonth"] ?? "";
	$fmonth	= ( !ctype_digit($fmonth) || empty($fmonth) ) ? date("m") : $fmonth;
	$fday	= $_GET["fday"] ?? $_POST["fday"] ?? "";
	$fday	= ( !ctype_digit($fday) || empty($fday) ) ? date("d") : $fday;
	
	$ihour	= $_GET["ihour"] ?? $_POST["ihour"] ?? "";
	$iminute	= $_GET["iminute"] ?? $_POST["iminute"] ?? "";
	$fhour	= $_GET["fhour"] ?? $_POST["fhour"] ?? "";
	$fminute	= $_GET["fminute"] ?? $_POST["fminute"] ?? "";
	
	$severidad	= $_GET["severidad"] ?? $_POST["severidad"] ?? "";
	$clasificacion	= $_GET["clasificacion"] ?? $_POST["clasificacion"] ?? "";
	$sistema	= $_GET["sistema"] ?? $_POST["sistema"] ?? "";
	$plataforma	= $_GET["plataforma"] ?? $_POST["plataforma"] ?? "";
	$comentario	= $_GET["comentario"] ?? $_POST["comentario"] ?? "";
	
	$cerrar	= $_GET["cerrar"] ?? $_POST["cerrar"] ?? "";
	$tipo	= $_GET["tipo"] ?? $_POST["tipo"] ?? "";
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Crear bitacora</title>
		<?php include_once("../../protected/style.php") ?>
	</head>
	<body>
		<h1 align="center">Crear bitacora</h1>
		<form name='test' action='nueva_bitacora_opersis.php' method='POST'>
		
			<p>Severidad: (obligatorio)
				<select name='severidad' required>
					<?php
					if( empty($severidad) ){
						echo "<option value='' selected>Seleccionar</option>";
					}
					$sql=$cCfn->getQuery("severidad_bit", NULL);
					$result = $cCfn->exeQuery($sql,$db);
					foreach( $result as $row ){
						if( $severidad == $row["ID"] ){
							echo "<option value='".$row["ID"]."' selected>".$row["NOMBRE"]."</option>";
						}
						else{
							echo "<option value='".$row["ID"]."'>".$row["NOMBRE"]."</option>";
						}
					} ?>
				</select>
			</p>
			
			<p>Tipo de problema: (obligatorio)
				<!--select name='clasificacion' onchange='this.form.submit()'-->
				<select name='clasificacion' onchange='' required>
				<?php
					if ( isset($clasificacion) && empty($clasificacion) ){
						echo "<option value='' selected>Seleccionar</option>";
					}
					$sql=$cCfn->getQuery("tipo_problema_bit", NULL);
					$result = $cCfn->exeQuery($sql,$db);
					
					foreach( $result as $row ){
						if( $clasificacion == $row["NOMBRE"] ){
							echo "<option value='".$row["NOMBRE"]."' selected>".$row["NOMBRE"]."</option>\n";
						}
						else{
							echo "<option value='".$row["NOMBRE"]."'>".$row["NOMBRE"]."</option>\n";
						}
					} ?>
				</select>
			</p>
		
			<?php 
			## OPCION FALLAS INICIO
			if( $clasificacion == "FALLA" ){
				echo "<p>Fallas: (Obligatorio)</p>"; ?>
				<p>
					<select name='falla'>
					<?php
						if( empty($falla) ){
							echo "<option value='' selected>Seleccionar</option>";
						}
						$sql=$cCfn->getQuery("falla_bit", NULL);
						$result = $cCfn->exeQuery($sql,$db);

						foreach( $result as $row ){
							if( $falla == $row["FALLA"] ){
								echo "<option value='".$row["ID"]."' selected>".$row["FALLA"]."</option>";
							}
							else{
								echo "<option value='".$row["ID"]."'>".$row["FALLA"]."</option>";
							}
						} ?>
					</select>
				</p>
		<?php 
			}
			## OPCION FALLAS FIN 
			?>
				<p>
					Sistema: 
					<!--select name='sistema' onchange='this.form.submit()' -->
					<select name='sistema' onchange='' required>
						<?php 
						$sql=$cCfn->getQuery("sistema_bit", NULL);
						$result = $cCfn->exeQuery($sql,$db);
						if( empty($sistema) ) echo "<option value='' selected>Seleccionar</option>";

						foreach( $result as $row ){
							if( $sistema == $row["SISTEMA"] ){
								echo "<option value='".$row["SISTEMA"]."' selected>".$row["SISTEMA"]."</option>\n";
							}
							else{
								echo "<option value='".$row["SISTEMA"]."'>".$row["SISTEMA"]."</option>\n";
							}
						} ?>
						
					</select>
				</p>
		<?php 
			if( !empty($sistema) ){ ?>
				<p>
					Plataforma: 
					<!--select name='plataforma' onchange='this.form.submit()'-->
					<select name='plataforma' onchange='' required>
					<?php 
						$params = [
							":sistema" => $sistema
						];
						$sql=$cCfn->getQuery("plataform_bit", $params);
						$result = $cCfn->exeQuery($sql,$db);
						$hit=0;
						if( empty($plataforma) ) echo "<option value='' selected>Seleccionar</option>";
						
						foreach( $result as $row ){
							if ($plataforma == $row["PLATAFORMA"] ){
								echo "<option value='".$row["PLATAFORMA"]."' selected>".$row["PLATAFORMA"]."</option>";
								$hit=1;
							}
							else{
								echo "<option value='".$row["PLATAFORMA"]."'>".$row["PLATAFORMA"]."</option>\n";
							}
						}
						
						if( $hit == 0 && $plataforma != "" ){
							echo "<option value='' selected>Seleccionar</option>";
							$plataforma="";
							$servicio="";
							$elemento="";
						} ?>
					</select>
				</p>
		<?php 
			}
			
			if( !empty($plataforma) ){ ?>
				<p>
					Servicio:
					<!--select name='servicio' onchange='this.form.submit()'-->
					<select name='servicio' onchange='' required>
					<?php 
						$params = [
							":sistema" => $sistema,
							":plataforma" => $plataforma
						];
						$sql=$cCfn->getQuery("servicio_bit", $params);
						$result = $cCfn->exeQuery($sql,$db);
						$hit=0;
						
						if( empty($servicio) ) echo "<option value='' selected>Seleccionar</option>";

						foreach( $result as $row ){
							if ($servicio == $row["SERVICIO"] ){
								echo "<option value='".$row["SERVICIO"]."' selected>".$row["SERVICIO"]."</option>";
								$hit=1;
							}
							else{
								echo "<option value='".$row["SERVICIO"]."'>".$row["SERVICIO"]."</option>";
							}
						}
						
						if( $hit == 0 && $servicio != "" ){
							echo "<option value='' selected>Seleccionar</option>";
							$servicio="";
							$elemento="";
						} ?>
					</select>
				</p>
		<?php 
			}

			if ( !empty($servicio) ){ ?>
			<p>
				Elemento:
				<!--select name='elemento' onchange='this.form.submit()'-->
				<select name='elemento' onchange='' required>
				
				<?php 
					$params = [
						":sistema" => $sistema,
						":plataforma" => $plataforma,
						":servicio" => $servicio
					];
					$sql=$cCfn->getQuery("elemento_bit", $params);
					$result = $cCfn->exeQuery($sql,$db);
					$hit=0;
					
					if( empty($elemento) ) echo "<option value='' selected>Seleccionar</option>";
					
					foreach( $result as $row ){
						if ( $elemento == $row["ELEMENTO"] ){
							echo "<option value='".$row["ELEMENTO"]."' selected>".$row["ELEMENTO"]."</option>";
							$hit=1;
						}
						else{
							echo "<option value='".$row["ELEMENTO"]."'>".$row["ELEMENTO"]."</option>\n";
						}
					}
					
					if ( $hit == 0 && $elemento != "" ){
						echo "<option value='' selected>Seleccionar</option>";
						$elemento="";
					} ?>
				
				</select>
			</p>
		<?php 
			} ?>
			
			<p>Comentario inicial (opcional, obligatorio cuando se usa Crear y Cerrar)</p>
			<p><textarea name='comentario' rows='5' cols='49' wrap='soft'><?php echo $comentario; ?></textarea></p>
			<p>
				Inicio de evento: 
				<select name='iyear'>
					<?php
						for( $i=2002; $i <= date('Y'); $i++ ){
							if( $i==$iyear ){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						} ?>
				</select>
				<select name='imonth'>
					<?php
						for( $i=1; $i<13; $i++ ){
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if( $i==$imonth ){
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
						$i=str_pad($i,2,"0",STR_PAD_LEFT);
						if ($i==$iday){
							echo "<option value='$i' selected>$i</option>";
						}
						else{
							echo "<option value='$i'>$i</option>";
						}
					} ?>
				</select>
				<select name='ihour'>
					<?php
						if( empty($ihour) ) echo "<option value='' selected>Seleccionar</option>";
						for ($i=0; $i<24; $i++){
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if ( $i == $ihour && $ihour != "" ){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						} ?>
				</select>
				<select name='iminute'>
					<?php
						if( empty($iminute) ) echo "<option value='' selected>Seleccionar</option>";
						for( $i=0; $i<60; $i++ ){
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if( $i == $iminute && !empty($iminute) ){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
				</select>
			</p>
			<p>
				Fin de evento:
				<select name='fyear'>
					<?php
						for( $i=2002; $i<=date('Y'); $i++ ){
							if( $i == $fyear ){
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
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if ($i == $fmonth){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						} ?>
				</select>
				<select name='fday'>
					<?php
						for ($i=1; $i<32; $i++){
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if ($i == $fday){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
				</select>
				<select name='fhour'>
					<?php
						if ($fhour == "") echo "<option value='' selected>Seleccionar</option>";
						for ($i=0; $i<24; $i++){
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if ( ($i==$fhour) and ($fhour != "") ){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
				</select>
				<select name='fminute'>
					<?php
						if ($fminute == "") echo "<option value='' selected>Seleccionar</option>";
						for ($i=0; $i<60; $i++){
							$i=str_pad($i,2,"0",STR_PAD_LEFT);
							if ( ($i == $fminute) and ($fminute != "") ){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
				</select>
			</p>
			<p>
				<input type='radio' name='cerrar' <?php if ( empty($cerrar) || $cerrar=="A" ) echo "checked"; ?> value='A'>Abrir
				<input type='radio' name='cerrar' <?php if ( $cerrar=="C") echo "checked"; ?> value='C'>Crear y Cerrar
			</p>
			<p>
				<input type='radio' name='tipo' <?php if ( empty($tipo) ) echo "checked"; ?> value='0'>P&uacute;blico
				<input type='radio' name='tipo' <?php if ( $tipo==1 ) echo "checked"; ?> value='1'>Privado
			</p>
			<p>
				<input name='accion' type='submit' value='Ingresar'> <input name='accion' type='submit' value='Volver'>
			</p>
		</form>
	</body>
</html>
