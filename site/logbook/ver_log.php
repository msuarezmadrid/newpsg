<?php
	function ver_log($link,$sql){
		global $cCfn, $db;
		echo"
			<table border='1' align='center' >
				<tr>
					<th>Usuario</th>
					<th>Fecha y Hora</th>
					<th>Descripcion</th>
					<th>Asociada a</th>
				</tr> ";
		
		$result = $cCfn->exeQuery($sql,$db);
		
		if( empty($result) ){
			echo "<tr>";
			echo "	<td align='center' colspan='4'>Sin resultados que mostrar.</td> ";
			echo "</tr>";
			die;
		}
		
		foreach( $result as $row ){
			$user_desc=htmlspecialchars($row['DESCRIPCION']);
			echo "<tr>";
			echo "	<td>".$row['usr']."</td>";
			echo "	<td>".$row['INGRESO']."</td>";
			echo "	<td>$user_desc</td>";
			echo "	<td>";
			echo "<table border='3'>";
			
			if ( !empty($row['BITACORA_ID']) ){
				$id=$row['BITACORA_ID'];
				$params = [
					":id" => $id
				];
				$sql=$cCfn->getQuery("sel_bit", $params);
				$data = $cCfn->exeQuery($sql,$db);
				$titulo=htmlspecialchars($data[0]['TITULO']);
				$nodo=htmlspecialchars($data[0]['NODO']);
				echo "<tr>";
				echo "	<td>BIT</td>";
				echo "	<td><a href='search_bit_query.php?numero=$id' target='_blank'>$id</a></td>";
				echo "	<td>$titulo ($nodo)</td>";
				echo "</tr>";
			}
			
			if ( !empty($row['PROBLEMA_ID']) ){
				$id=$row['PROBLEMA_ID'];
				$params = [
					":id" => $id
				];
				$sql=$cCfn->getQuery("problema_bit", $params);
				$data = $cCfn->exeQuery($sql,$db);
				$titulo=htmlspecialchars($data[0]['TITULO']);
				echo "<tr>";
				echo "	<td>PID</td>";
				echo "	<td><a href='../pid/ver_pid.php?id=$id' target='_blank'>$id</a></td>";
				echo "	<td>$titulo</td>";
				echo "</tr>";
			}
			
			if ( !empty($row['PLANNED_ID']) ){
				$id=$row['PLANNED_ID'];
				$params = [
					":tp" => $id
				];
				$sql=$cCfn->getQuery("planned_bit", $params);
				$data = $cCfn->exeQuery($sql,$db);
				$titulo=htmlspecialchars($data[0]['TITULO']);
				echo "<tr>";
				echo "	<td>TP</td>";
				echo "	<td><a href='../tp/ver_planned.php?id=$id' target='_blank'>$id</a></td>";
				echo "	<td>$titulo</td>";
				echo "</tr>";
			}
			
			if ( !empty($row['TAREA_ID']) ){
				$id=$row['TAREA_ID'];
				$params = [
					":tarea" => $id
				];
				$sql=$cCfn->getQuery("tarea_bit", $params);
				$data = $cCfn->exeQuery($sql,$db);
				$titulo=htmlspecialchars($data[0]['TITULO']);
				echo "<tr>";
				echo "	<td>TAR</td>";
				echo "	<td><a href='../tasks/ver_tarea.php?id=$id' target='_blank'>$id</a></td>";
				echo "	<td>$titulo</td>";
				echo "</tr>";
			}
			
			if ( !empty($row['SC_ID']) ){
				$id=$row['SC_ID'];
				$params = [
					":sc" => $id
				];
				$sql=$cCfn->getQuery("sc_bit", $params);
				$data = $cCfn->exeQuery($sql,$db);
				$titulo=htmlspecialchars($data[0]['TITULO']);
				echo "<tr>";
				echo "	<td>SC</td>";
				echo "	<td><a href='../sc/ver_sc.php?id=$id' target='_blank'>$id</a></td>";
				echo "	<td>$titulo</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</td>";
			echo "</tr>";
		}
		// 
		echo "</table>";
	}
?>