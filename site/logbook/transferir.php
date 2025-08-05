<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb";
	
	$bid = $_GET["bid"] ?? $_POST["bid"] ?? '';
	$owner = $_GET["owner"] ?? $_POST["owner"] ?? ''; ?>
	<html>
		<head>
			<title>Transferir</title>
			<?php include_once("../../protected/style.php") ?>
		</head>
		<body>
			<h1 align="CENTER">Transferir bitacora</h1>
			<h2>Bitacora <?php echo $bid; ?></h2>
			<h3>Confirmar:</h3>
			<p>Con esta acci&oacute;n se transferir&aacute; la bitacora a otro usuario:</p>
			<form name='test' action="give_away.php" method="post">
				<input type="hidden" name="bid" value="<?php echo $bid; ?>" />
				<p>
					<select name="owner" >
					<?php
						if( empty($owner) ){
							echo "<option value='' selected>Seleccionar</option>";
						}
						$sql=$cCfn->getQuery("lista_user", NULL);
						$result = $cCfn->exeQuery($sql,$db);
						foreach( $result as $row ){
							if( $owner == $row['usr'] ){
								echo "<option value='".$row['usr']."' selected>".$row['P_APELLIDO'].", ".$row['P_NOMBRE']." (".$row['usr'].")</option>";
							}
							else{
								echo "<option value='".$row['usr']."' >".$row['P_APELLIDO'].", ".$row['P_NOMBRE']." (".$row['usr'].")</option>";
							}
						} ?>
					</select>
				</p>
				
				<input name="accion" type="submit" value="Volver" />&nbsp;&nbsp;<input name="accion" type="submit" value="Transferir" />
				
			</form>
		</body>
	</html>