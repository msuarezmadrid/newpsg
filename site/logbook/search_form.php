<?php
	include_once("../../protected/classFunciones.php");
	session_start();
	$cCfn = new classFunciones();
	$cCfn->checkSession();
	$db="intradb"; ?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Subgerencia Operaciones y Mantenimiento de Red</title>
			<script src="../lib/jquery/jquery-3.6.0.min.js"></script>
			<link rel="stylesheet" href="../lib/jquery/jquery-ui.css">
			<script src="../lib/jquery/jquery-ui.min.js"></script>
			<script src="../lib/jquery/jquery-ui-i18n.min.js"></script>
			<script src="../lib/javascript/calendar_datepicker.js"></script>
			<?php include_once("../../protected/style.php") ?>
			<script>
				$.datepicker.setDefaults($.datepicker.regional['es']);
			</script>
		</head>
		<body>
			<h1 align="CENTER">Buscar en log</h1>
			<form name='test' action="search_query.php" method="post" >
				<table >
					<tr>
						<td><label for="descripcion">Descripci&oacute;n: </label></td>
						<td><input type="text" id="descripcion" name="descripcion"></td>
					</tr>
					<tr>
						<td><label for="user">Usuario: </label></td>
						<td><input type="text" id="user" name="user"></td>
					</tr>
					<tr>
						<td><label for="fecha1">Al o Desde: </label></td>
						<td><input type="text" id="fecha1" name="fecha1"></td>
					</tr>
					<tr>
						<td><label for="fecha2">Hasta: </label></td>
						<td><input type="text" id="fecha2" name="fecha2" ></td>
					</tr>
					<tr>
						<td colspan='2'><input type="submit" value="Buscar" /> <input type="reset" value="Borrar" /></td>
					</tr>
				<table/>
			</form>
		</body>
	</html>