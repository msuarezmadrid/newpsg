<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$usr=$cCfn->getUser();
	$db="intradb";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$event = $_GET["event"] ?? $_POST["event"] ?? '';
	
	$params = [
		":bid" => $id
	];
	if( empty($event) ){
		$sql=$cCfn->getQuery("bit_edit_time1", $params);
	}
	else{
		$sql=$cCfn->getQuery("bit_edit_time2", $params);
	}
	$row = $cCfn->exeQuery($sql,$db);
	
	$syear	= $row[0]['YEAR'];
	$smonth	= $row[0]['MONTH'];
	$sday	= $row[0]['DAY'];
	$shour	= $row[0]['HOUR'];
	$sminute= $row[0]['MIN'];
	
	if( empty($syear) ){
		$syear=date("Y");
		$smonth=date("m");
		$sday=date("d");
		$shour=date("H");
		$sminute=date("i");
	} ?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar tiempo de evento</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h1 align="center">Editar tiempo de <?php if( empty($event) ){ echo "inicio "; }else{ echo "fin "; } ?> de evento</h1>
			<form name='test' action='insert_time.php' method='post' target='_blank'>
				<h2>Bitacora <?php echo "$id"; ?></h2>
				<input type="hidden" name="id" value="<?php echo $id; ?>"> 
				<input type="hidden" name="event" value="<?php echo $event; ?>">
				
				<p>
					<table >
						<tr>
						<?php
							$fecha_i = date('Y');
							$nuevafecha_f = strtotime ( '+1 year' , strtotime ( $fecha_i ) ) ;
							$nuevafecha_f = date ( 'Y' , $nuevafecha_f ); ?>
							<td>Year: </td>
							<td><select name="year">
						<?php
							for( $i=2002; $i<$nuevafecha_f; $i++ ){
								if ($i==$syear){
									echo "<option value='$i' selected>$i</option>\n";
								}
								else{
									echo "<option value='$i'>$i</option>\n";
								}
						} ?>
							</select></td>
						</tr>
						<tr>
							<td>Month: </td>
							<td><select name="month">
					<?php
						for ($i=1; $i<13; $i++){
							if ($i==$smonth){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
							</select></td>
						</tr>
						<tr>
							<td>Day: </td>
							<td><select name="day">
					<?php
						for ($i=1; $i<32; $i++){
							if ($i==$sday){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
							</select></td>
						</tr>
						<tr>
							<td>Hour: </td>
							<td><select name="hour">
					<?php
						for ($i=0; $i<24; $i++){
							if ($i==$shour){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
							</select></td>
						</tr>
						<tr>
							<td>Minute: </td>
							<td><select name="minute">
					<?php
						for ($i=0; $i<60; $i++){
							if ($i==$sminute){
								echo "<option value='$i' selected>$i</option>\n";
							}
							else{
								echo "<option value='$i'>$i</option>\n";
							}
						} ?>
							</select></td>
						</tr>
						<tr>
							<td><input type="submit" value="Ingresar" /></td><td><input type="reset" value="Borrar" /> </td>
						</tr>
					</table>
				</p>
			</form>
		</body>
	</html>