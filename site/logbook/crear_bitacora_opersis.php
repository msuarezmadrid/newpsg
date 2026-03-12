<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$usr=$cCfn->getUser();
	
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
	<title>Crear bit&aacute;cora</title>
	<h1 align="center">Crear bit&aacute;cora</h1>
	<form name='test' action='nueva_bitacora_opersis.php' method='POST'>
	
		<p>Severidad: (obligatorio)
			<select name='severidad' required>
				<?php
				if( empty($severidad) ){
					echo "<option value='' selected>Seleccionar</option>";
				}
				$result = $cCfn->exeQuery("severidad_bit",NULL,NULL,$cCfn->getLocal(),null,$modulo);
				while( $row = $result->fetch_assoc() ){
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
				$result = $cCfn->exeQuery("tipo_problema_bit",NULL,NULL,$cCfn->getLocal(),null,$modulo);
				while( $row=$result->fetch_assoc() ){
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
					$result = $cCfn->exeQuery("falla_bit",NULL,NULL,$cCfn->getLocal(),null,$modulo);
					while( $row=$result->fetch_assoc() ){
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
					$result = $cCfn->exeQuery("sistema_bit",NULL,NULL,$cCfn->getLocal(),null,$modulo);
					if( empty($sistema) ) echo "<option value='' selected>Seleccionar</option>";

					while( $row=$result->fetch_assoc() ){
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
					$params = [ $sistema ];
					$result = $cCfn->exeQuery("plataform_bit",$params,'s',$cCfn->getLocal(),null,$modulo);
					$hit=0;
					if( empty($plataforma) ) echo "<option value='' selected>Seleccionar</option>";
					
					while( $row=$result->fetch_assoc() ){
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
					$params = [ $sistema, $plataforma ];
					$result = $cCfn->exeQuery("servicio_bit",$params,'ss',$cCfn->getLocal(),null,$modulo);
					$hit=0;
					
					if( empty($servicio) ) echo "<option value='' selected>Seleccionar</option>";

					while( $row=$result-fetch_assoc() ){
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
				$params = [$sistema, $plataforma,$servicio ];
				$result = $cCfn->exeQuery("elemento_bit",$params,'sss',$cCfn->getLocal(),null,$modulo);
				$hit=0;
				
				if( empty($elemento) ) echo "<option value='' selected>Seleccionar</option>";
				
				while( $row=$result->fetch_assoc() ){
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
	