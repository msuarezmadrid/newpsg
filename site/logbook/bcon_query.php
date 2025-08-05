<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$filtro = $_GET["filtro"] ?? $_POST["filtro"] ?? '';
	$descripcion = $_GET["descripcion"] ?? $_POST["descripcion"] ?? '';
	
	if( empty($descripcion) ){
		include_once("bcon_form.php");
		exit;
	} ?>
	
	<h3 align="CENTER"> Resultado de b&uacute;squeda</h3>
	<h3 align='CENTER'><?php echo $descripcion; ?></h3>
	<?php
	if ($filtro == "boolean"){
		$clauses=" IN BOOLEAN MODE ";
	}
	$params = [ $descripcion, $clauses , $descripcion, $clauses ];
	
	echo"<table border='1' align='CENTER'
		<tr>
			<th>ID</th>
			<th>Titulo</th>
			<th>Descripcion</th>
		</tr> ";
	$result = $cCfn->exeQuery("sel_bcon_search",$params,'sisi',$cCfn->getLocal(),null,$modulo);
	
	while( $row = $result->fetch_assoc() ){
		$titulo=htmlspecialchars($row['TITULO']);
		$desc=htmlspecialchars($row['DESCRIPCION']);
		$id=$row['ID'];
		echo "<tr>";
		
		if ( $row['TIPO'] == "PID" ){
			echo "<td><a href='../pid/ver_pid.php?id=$id' target='_blank'>PID $id</a></td>";
		}
		else if ($row['TIPO'] == "TP"){
			echo "<td><a href='../tp/ver_planned.php?id=$id' target='_blank'>TP $id</a></td>";
		}
		else if ($row['TIPO'] == "TAR"){
			echo "<td><a href='../tasks/ver_tarea.php?id=$id' target='_blank'>TAR $id</a></td>";
		}
		
		echo "<td>$titulo</td>";
		echo "<td>$desc</td>";
		echo "</tr>";
	}				
	echo "</table>"; ?>
	