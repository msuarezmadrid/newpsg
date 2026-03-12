<?php
	function ver_bitacora($sql,$usr,$modo_bitacora,$opcion=0){
		global $cCfn,$params,$modulo,$types,$base_path;
		
		$result = $cCfn->exeQuery($sql,$params,$types,$cCfn->getLocal(),null,$modulo);
		$r=0;
		while ($row = $result->fetch_assoc()) {
			$anio_bit =  date("Y", strtotime($row['INICIO']));
			$anio_bit = substr($anio_bit, -2);    
			$inicio=$row['INICIO'];
			$fin=$row['FIN'];
			$nodo=htmlspecialchars($row['TITULO']);
			$bid=$row['ID'];
			$ne=$row['NODO'];
			$owner=$row['usr']; 
			$tipo=$row['TIPO'];
			$event_time=$row['EVENT_TIME'];
			$cease_time=$row['CEASE_TIME'];
			$creador=$row['OWNER'];
			
			if ($row['BIT_SEVERITY'] == 0 ){
				$severity="NINGUNA";
			}
			else if ($row['BIT_SEVERITY'] == 1 ){
				$severity="BAJA";
			}
			else if ($row['BIT_SEVERITY'] == 2 ){
				$severity="MEDIA";
			}
			else if ($row['BIT_SEVERITY'] == 3 ){
				$severity="ALTA";
			}
			else if ($row['BIT_SEVERITY'] == 4 ){
				$severity="CRITICA";
			}
			else if ($row['BIT_SEVERITY'] == 5 ){
				$severity="CATASTROFICA";
			}
			echo "
			<table border=1>
				<tr>
					<td>
						<table border=2>
							<tr>
								<th><a href='$base_path/crear_accion.php?id=$bid'>ID</a></th>
								<th>Inicio</th> ";
			if( empty($fin) ){
				$file="cerrar_bit"; # ARCHIVO CON LISTADO DE USUARIOS
				$lista=[];
				if( file_exists($file) ){
						$lista=file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
				}
				if( $usr==$owner || in_array($usr,$lista) ){
					echo "<th><a href='$base_path/cerrar_bitacora.php?id=$bid'>Fin</a></th>";
				}
				else{
					echo "<th>Fin</th>";
				}
			}
			else{
				echo "<th><a href='$base_path/abrir_bitacora.php?id=$bid'>Fin</a></th>";
			}
			echo "
					<th><a href='$base_path/editar_bitacora.php?id=$bid'>Titulo</a></th>
					<th><a href='$base_path/add_node.php?id=$bid'>Sitio/Nodo/Servicio</a></th>
					<th><a href='$base_path/edit_time.php?id=$bid&event=0'>Inicio Evento</a></th>
					<th><a href='$base_path/edit_time.php?id=$bid&event=1'>Fin Evento</a></th>
					<th><a href='$base_path/edit_severity.php?id=$bid'>Severidad</a></th>
					<th><a href='$base_path/cambiar_tipo.php?id=$bid&tipo=$tipo'>Responsable</a></th>
					<th><a href='$base_path/asociar_bitacora.php?id=$bid'>Asociada con</a></th>
					<th>Creado Por</th>
					<th><a href='$base_path/documentar_tp.php?bid=$bid'>Documentar</a></th>
					<th><a href='$base_path/asociar_sitios.php?bitacora=$bid'>Sitio(s)</a></th>
				</tr>
				<tr> ";
			if( empty($opcion) ){
				echo "<td><a href='$base_path/search_bit_query.php?numero=$bid' target='_blank'>$bid</a></td>";
			}
			else{
				echo "<td>$bid</td>";
			}
			
			echo "<td>$inicio</td>";
			if( empty($fin) ){
				echo "<td bgcolor='#DC2300'>-</td>";
			}
			else{
				echo "<td>$fin</td>";
			}

			echo "  <td>$nodo</td>
					<td>$ne</td>
					<td>$event_time</td>
					<td>$cease_time</td>
					<td>$severity</td>";
			
			$file="transferir_bit"; # ARCHIVO CON LISTADO DE USUARIOS
			$lista=[];
			if( file_exists($file) ){
					$lista=file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
			}
			
			if( !empty($tipo) ){
				if ($owner==$usr || in_array($usr,$lista) ){
					echo "<td bgcolor='#00FF00'><a href='$base_path/transferir.php?bid=$bid'>$owner</a></td>";
				}
				else{
					echo "<td bgcolor='#00FF00'>$owner</td>";
				}
			}
			else{
				if ($owner==$usr || in_array($usr,$lista) ){
					echo "<td><a href='$base_path/transferir.php?bid=$bid'>$owner</a></td>";
				}
				else{
					echo "<td>$owner</td>";
				}
			}

			echo "<td><table>";
			
			if ( !empty($row['PROBLEMA_ID']) ){
				$id=$row['PROBLEMA_ID'];
				$params = [ $id ];
				$result = $cCfn->exeQuery("problema_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
				$dt = $result->fetch_assoc();
				$data = $dt['TITULO'];
				$titulo=htmlspecialchars($data);
				echo "<tr>";
				echo "<td>PID</td>";
				echo "<td><a href='../pid/ver_pid.php?id=$id' target='_blank'>$id</a></td>";
				echo "<td>$titulo</td>";
				echo "<td><a href='$base_path/traspasar.php?bid=$bid&tipo=1&id=$id' target='_blank'>(T)</a></td>";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			if( !empty($row['PLANNED_ID']) ){
				$id=$row['PLANNED_ID'];
				$params = [ $id ];
				$result = $cCfn->exeQuery("planned_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
				$dt = $result->fetch_assoc();
				$data = $dt['TITULO'];
				$titulo=htmlspecialchars($data);
				echo "<tr>";
				echo "<td>TP</td>\n";
				echo "<td><a href='../tp/ver_planned.php?id=$id' target='_blank'>$id</a></td>\n";
				echo "<td>$titulo</td>\n";
				echo "<td><a href='$base_path/traspasar.php?bid=$bid&tipo=2&id=$id' target='_blank'>(T)</a></td>\n";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			if( !empty($row['TAREA_ID']) ){
				$id=$row['TAREA_ID'];
				$params = [ $id ];
				$result = $cCfn->exeQuery("tarea_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
				$dt = $result->fetch_assoc();
				$data = $dt['TITULO'];
				$titulo=htmlspecialchars($data);
				echo "<tr>";
				echo "<td>TAR</td>\n";
				echo "<td><a href='../tasks/ver_tarea.php?id=$id' target='_blank'>$id</a></td>\n";
				echo "<td>$titulo</td>\n";
				echo "<td><a href='$base_path/traspasar.php?bid=$bid&tipo=3&id=$id' target='_blank'>(T)</a></td>\n";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			if( !empty($row['SC_ID']) ){
				$id=$row['SC_ID'];
				$params = [ $id ];
				$result = $cCfn->exeQuery("sc_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
				$dt = $result->fetch_assoc();
				$data = $dt['TITULO'];
				$titulo=htmlspecialchars($data);
				echo "<tr>";
				echo "<td>SC</td>\n";
				echo "<td><a href='../sc/ver_sc.php?id=$id' target='_blank'>$id</a></td>\n";
				echo "<td>$titulo</td>\n";
				echo "<td><a href='$base_path/traspasar.php?bid=$bid&tipo=4&id=$id' target='_blank'>(T)</a></td>\n";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			if( !empty($row['CSR']) ){
				$id=$row['CSR'];
				$titulo="CSR";
				echo "<tr>";
				echo "<td>CSR</td>\n";
				echo "<td>$id</td>\n";
				echo "<td>$titulo</td>\n";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			if( !empty($row['BoletaRED']) ){
				$id=$row['BoletaRED'];
				$bred = "F".$anio_bit."0000".$id;
				$titulo="Boleta de RED";
				echo "<tr>";
				echo "<td>BR</td>";
				//echo "<td><a href='http://192.168.185.181/go/aplicacion/man/rptFalla.aspx?var=$bred' target='_blank'>$id</a></td>";
				echo "<td><a href='http://portal-sgi/go/aplicacion/man/rptFalla.aspx?var=$bred' target='_blank'>$id</a></td>";
				echo "<td>$titulo</td>";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			
			if( !empty($row['TOT']) ){
				$id=$row['TOT'];
				$titulo = "Tarea Office Track";
				echo "<tr>";
				echo "<td>TOT</td>";
				echo "<td><a href='http://latam.officetrack.com/secure/GlobalOfficeTrackLogon.aspx?ReturnUrl=%2findex.aspx' target='_blank'>$id</a></td>";
				echo "<td>$titulo</td>";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			## Inicio nuevo campo INC Planta Externa
			if( !empty($row['INC_PE']) ){
				$id=$row['INC_PE'];
				$incpe = "INC"."0000".$id;
				$titulo = "INC Planta externa";
				echo "<tr>";
				echo "<td>INC PE</td>";
				#echo "<td><a href='http://latam.officetrack.com/secure/GlobalOfficeTrackLogon.aspx?ReturnUrl=%2findex.aspx' target='_blank'>$id</a></td>";
				echo "<td><a href='http://portal-sgi/go/aplicacion/man/rptFalla.aspx?var=$incpe' target='_blank'>$id</a></td>";
				echo "<td>$titulo</td>";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			## Fin nuevo campo INC Planta Externa 
			
			if( $inicio != "-" ){
				echo "<tr>";
				echo "<td>-</td>";
				echo "</tr>";
				$inicio="-";
				$nodo="-";
			}
			
			echo "</table><td>$creador</td>";
			$params = [ $bid ];
			$result = $cCfn->exeQuery("doc_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
			$docAsig = null;
			while ($row_doc = $result->fetch_assoc()) { $docAsig .= $row_doc['DOC'] . " - "; }
			echo "<td>".substr($docAsig,0,-3)."</td>";
			
			$result = $cCfn->exeQuery("asig_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
			$sitiosAsig = null;
			while ($row_tabla = $result->fetch_assoc()) { $sitiosAsig .= $row_tabla['SITIO'] . " - "; }
			echo "<td>".substr($sitiosAsig,0,-3)."</td>";
			
			echo "</td></tr></table></td></tr>";
			$str_style_copiar=null;

			if( $opcion == 1 ) $str_style_copiar =" style='cursor:hand; color:blue' title='Haga click para copiar'";
			
			if( empty($modo_bitacora) ){
				echo "<tr>
						<td>
							<table border=2>
								<th>usr</th>
								<th>Fecha y hora</th>
								<th $str_style_copiar ><div id='copiartexto'>Descripcion</div></th>
					</tr>";
				$text_observaciones = null;
				
				$params = [ $bid ];
				$result = $cCfn->exeQuery("desc_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
				while ($data = $result->fetch_assoc()) {
					$user_desc=htmlspecialchars($data['DESCRIPCION']);
					$text_observaciones .= $user_desc . chr(10) . chr(13) ;
					echo "<tr>";
					echo "	<td>".$data['usr']."</td>";
					echo "	<td>".$data['INGRESO']."</td>";
					echo "	<td><pre>".utf8_decode($user_desc)."</pre></td>";
					echo "</tr>";
				}
				
				echo "</table>
					<div style='visibility:hidden' > 
						<textarea id='tx_texto_$r' cols='1' rows='1' style='height:0px'>".$text_observaciones."</textarea>
					</div>
				</td>
				</tr>
				</table>";
			}
			else{
				echo "</table>";
			}
			$r++;
		}
	} // FIN FUNCION 