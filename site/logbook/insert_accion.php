<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$db="intradb";
	$date = new DateTime();
	
	$descripcion = $_GET["descripcion"] ?? $_POST["descripcion"] ?? '';
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	
	if( empty($descripcion) ){
		include("crear_accion.php");
		exit;
	}
	
	$usr=$cCfn->getUser();
	$ahora=$date->format('Y-m-d H:i:s');
	
	$params = [ $id ];
	$result = $cCfn->exeQuery("sel_bitacora",$params,'i',$cCfn->getLocal(),null,$modulo);
	// echo "<br/>sql:".$sql."<br/>"; 
	$row=$result->fetch_assoc();
	
	$pid=$row['PROBLEMA_ID'];
	$tp	=$row['PLANNED_ID'];
	$tar=$row['TAREA_ID'];
	$sc	=$row['SC_ID'];
	
	$params = [	$usr, addslashes($descripcion), $id, $pid, $tp, $tar, $sc ];
	$types="ssiiiii";
	$result = $cCfn->exeQuery("insert_bit_conso",$params,$types,$cCfn->getLocal(),null,$modulo);
	// echo "<br/>sql:".$sql."<br/>"; 
	$insert_id=$result['insert_id']; ## ID DEL REGISTRO INSERTADO 
	
	$params = [ $id	];
	$types="i";
	$result = $cCfn->exeQuery("sel_bitacora_asoc",$params,$types,$cCfn->getLocal(),null,$modulo);
	
	$params = [$usr, addslashes($descripcion)];
	$types="ss";
	foreach( $result as $row ) {
		$sql_insert = $cCfn->loadQueries($modulo, "insert_bit_conso2");
		if($row['ASOC_ID'] == 1) $sql_insert .= "0,'".$row['ASOC_ID']."',0,0)";
		if($row['ASOC_ID'] == 2) $sql_insert .= "'".$row['ASOC_ID']."',0,0,0)";
		if($row['ASOC_ID'] == 3) $sql_insert .= "0,0,'".$row['ASOC_ID']."',0)";
		if($row['ASOC_ID'] == 4) $sql_insert .= "0,0,0,'".$row['ASOC_ID']."')";
		
		$result = $cCfn->exeQuery($sql_insert,$params,$types,$cCfn->getLocal(),null,$modulo);
	}
?>
	<title>Documentar acci&oacute;n</title>
	<h2 align='center'>Bitacora <?php echo "$id"; ?></h2>
	<h3 align='center' >Acci&oacute;n Ingresada.</h3>
