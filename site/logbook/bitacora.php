<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$usr=$cCfn->getUser();
	$opcion=0;
	$filtro = $_GET["filtro"] ?? $_POST["filtro"] ?? 'on';
	$modo_bitacora = $_GET["modo"] ?? $_POST["modo"] ?? '';
	
	$date = new DateTime(); # clase DateTime global de PHP 
	$ahora=$date->format('Y-m-d H:i:s');
	$selfFile=basename(__FILE__);
	$base_path=BASE_URL . "/site/logbook";
	
	$bodyCss=$cCfn->bodyCss();
	$boostrapCss=$cCfn->boostrapCss();
	$functions=$cCfn->functions();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
	$favicon=$cCfn->favicon(); 
	?>
	
		<h3 align="CENTER">
			Bitacora al <?php echo $ahora ?> <a href="<?php echo $base_path; ?>/crear_bitacora.php">(Crear nueva)</a>
		</h3>
		<form id='filtroForm' action="<?php echo $base_path; ?>/bitacora.php" method="post">
			<p>
				<input type="submit" value="Refresh">
				<input type="radio" name="filtro" id="filtro_on"  value="on" <?php if ($filtro == "on") echo "checked"?>>Autom&aacute;tico
				<input type="radio" name="filtro" id="filtro_off" value="off" <?php if ($filtro == "off") echo "checked"?>>Manual
				<input type="radio" name="modo" id="modo"  <?php if (($modo_bitacora=="") or ($modo_bitacora==0)) echo "checked" ?> value="0">Expanded
				<input type="radio" name="modo"  <?php if ($modo_bitacora==1) echo "checked" ?> value="1">Compressed
			</p>
		</form>
		<?php
			$result = $cCfn->exeQuery("bitacoras",null,null,$cCfn->getLocal(),null,$modulo);
			$row = $result->fetch_all(MYSQLI_ASSOC);
			for( $r=0; $r<sizeof($row); $r++ ){
			// while($row = $result->fetch_assoc() ) {
				$anio_bit =  date("Y", strtotime($row[$r]["INICIO"]));
				$anio_bit = substr($anio_bit, -2);    
				$inicio=$row[$r]["INICIO"];
				$fin=$row[$r]["FIN"];
				$nodo=htmlspecialchars($row[$r]["TITULO"]);
				$bid=$row[$r]["ID"];
				$pid=$row[$r]["PROBLEMA_ID"];
				$tp=$row[$r]["PLANNED_ID"];
				$tarea=$row[$r]["TAREA_ID"];
				$sc=$row[$r]["SC_ID"];
				$csr=$row[$r]["CSR"];
				$boletaRed=$row[$r]["BoletaRED"];
				$tot=$row[$r]["TOT"];
				$ne=$row[$r]["NODO"];
				$owner=$row[$r]["usr"]; 
				$tipo=$row[$r]["TIPO"];
				$event_time=$row[$r]["EVENT_TIME"];
				$cease_time=$row[$r]["CEASE_TIME"];
				$creador=$row[$r]["OWNER"];
				
				if( $row[$r]["BIT_SEVERITY"] === 0){
					$severity="NINGUNA";
				}
				elseif( $row[$r]["BIT_SEVERITY"] === 1){
					$severity="BAJA";
				}
				elseif( $row[$r]["BIT_SEVERITY"] === 2){
					$severity="MEDIA";
				}	
				elseif( $row[$r]["BIT_SEVERITY"] === 3){
					$severity="ALTA";
				}
				elseif( $row[$r]["BIT_SEVERITY"] === 4){
					$severity="CRITICA";
				}
				elseif( $row[$r]["BIT_SEVERITY"] === 5){
					$severity="CATASTROFICA";
				} ?>
				
				<table border='1'>
					<tr>
						<th><a href='<?php echo $base_path; ?>/crear_accion.php?id=<?php echo $bid; ?>'>ID</a></th>
						<th>Inicio</th>
						<?php 
						$file="cerrar_bit"; # ARCHIVO CON LISTADO DE USUARIOS 
						$lista=[];
						if( file_exists($file) ){
							$lista=file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
						}
						if( empty($fin) ){
							if( $usr==$owner || in_array($usr,$lista) ){
								echo "<th><a href='$base_path/cerrar_bitacora.php?id=$bid'>Fin</a></th>";
							}
							else{
								echo "<th>Fin</th>";
							}
						}
						else{
							echo "<th><a href='$base_path/abrir_bitacora.php?id=$bid'>Fin</a></th>";
						} ?>
						<th><a href='<?php echo $base_path; ?>/editar_bitacora.php?id=<?php echo $bid; ?>'>T&iacute;tulo</a></th>
						<th><a href='<?php echo $base_path; ?>/add_node.php?id=<?php echo $bid; ?>'>Sitio/Nodo/Servicio</a></th>
						<th><a href='<?php echo $base_path; ?>/edit_time.php?id=<?php echo $bid; ?>&event=0'>Inicio Evento</a></th>
						<th><a href='<?php echo $base_path; ?>/edit_time.php?id=<?php echo $bid; ?>&event=1'>Fin Evento</a></th>
						<th><a href='<?php echo $base_path; ?>/edit_severity.php?id=<?php echo $bid; ?>'>Severidad</a></th>
						<th><a href='<?php echo $base_path; ?>/cambiar_tipo.php?id=<?php echo $bid; ?>&tipo=<?php echo $tipo; ?>'>Responsable</a></th>
						<th><a href='<?php echo $base_path; ?>/asociar_bitacora.php?id=<?php echo $bid; ?>'>Asociada con</a></th>
						<th>Creado Por</th>
						<th><a href='<?php echo $base_path; ?>/documentar_tp.php?bid=<?php echo $bid; ?>'>Documentar</a></th>
						<th><a href='<?php echo $base_path; ?>/asociar_sitios.php?bitacora=<?php echo $bid; ?>'>Sitio(s)</a></th>
					</tr>
					<tr>
						<?php 
						if( empty($opcion) ){
							echo "<td><a href='$base_path/search_bit_query.php?numero=$bid' target='_blank'>$bid</a></td>";
						}
						else{
							echo "<td>$bid</td>";
						} ?>
						
						<td><?php echo $inicio; ?></td>
						
						<?php 
						if( empty($fin) ){
							echo "<td bgcolor='#DC2300'>-</td>";
						}
						else{
							echo "<td>$fin</td>";
						} ?>
						
						<td><?php echo $nodo; ?></td>
						<td><?php echo $ne; ?></td>
						<td><?php echo $event_time; ?></td>
						<td><?php echo $cease_time; ?></td>
						<td><?php echo $severity; ?></td>
						
						<?php
						$file="transferir_bit"; # ARCHIVO CON LISTADO DE USUARIOS 
						$lista=[];
						if( file_exists($file) ){
							$lista=file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
						}
						if ($owner==$usr || in_array($usr,$lista) ){
							echo "<td bgcolor='#00FF00'><a href='$base_path/transferir.php?bid=$bid'>$owner</a></td>";
						}
						else{
							echo "<td bgcolor=\"#00FF00\">$owner</td>";
						} ?>
						
						<td>
							<table>
							<?php 
							# PROBLEMA ID -> PID 
							if ( !empty($pid) ){
								$params = [ $pid ];
								$result = $cCfn->exeQuery("problema_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
								$data = $result->fetch_assoc();
								$titulo=( !empty($data["TITULO"]) ) ? htmlspecialchars($data["TITULO"]) : ""; ?>
								<tr>
									<td>PID</td>
									<td><a href='../pid/ver_pid.php?id=<?php echo $pid; ?>' target='_blank'><?php echo $pid; ?></a></td>
									<td><?php echo $titulo; ?></td>
									<td><a href='$base_path/traspasar.php?bid=<?php echo $bid; ?>&tipo=1&id=<?php echo $pid; ?>' target='_blank'>(T)</a></td>
								</tr>
								<?php 
								$inicio="-";
								$nodo="-";
							}
							# PLANNED_ID -> TP 
							if ( !empty($tp) ){
								$params = [ $tp ];
								$result = $cCfn->exeQuery("planned_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
								$data = $result->fetch_assoc();
								$titulo=( !empty($data["TITULO"]) ) ? htmlspecialchars($data["TITULO"]) : ""; ?>
								<tr>
									<td>TP</td>
									<td><a href='../tp/ver_planned.php?id=<?php echo $tp; ?>' target='_blank'><?php echo $tp; ?></a></td>
									<td><?php echo $titulo; ?></td>
									<td><a href='$base_path/traspasar.php?bid=<?php echo $bid; ?>&tipo=2&id=<?php echo $tp; ?>' target='_blank'>(T)</a></td>
								</tr>
								<?php 
								$inicio="-";
								$nodo="-";
							}
							# TAREA 
							if ( !empty($tarea) ){
								$params = [ $tarea ];
								$result = $cCfn->exeQuery("tarea_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
								$data = $result->fetch_assoc();
								$titulo=( !empty($data["TITULO"]) ) ? htmlspecialchars($data["TITULO"]) : ""; ?>
								<tr>
									<td>TAR</td>
									<td><a href='../tasks/ver_tarea.php?id=<?php echo $tarea; ?>' target='_blank'><?php echo $tarea; ?></a></td>
									<td><?php echo $titulo; ?></td>
									<td><a href='$base_path/traspasar.php?bid=<?php echo $bid; ?>&tipo=3&id=<?php echo $tarea; ?>' target='_blank'>(T)</a></td>
								</tr>
								<?php 
								$inicio="-";
								$nodo="-";
							}
							
							if ( !empty($sc) ){
								$params = [ $sc ];
								$result = $cCfn->exeQuery("sc_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
								$data = $result->fetch_assoc();
								$titulo=( !empty($data["TITULO"]) ) ? htmlspecialchars($data["TITULO"]) : ""; ?>
								<tr>
									<td>SC</td>
									<td><a href='../sc/ver_sc.php?id=<?php echo $sc; ?>' target='_blank'><?php echo $sc; ?></a></td>
									<td><?php echo $titulo; ?></td>
									<td><a href='$base_path/traspasar.php?bid=<?php echo $bid; ?>&tipo=4&id=<?php echo $sc; ?>' target='_blank'>(T)</a></td>
								</tr>
								<?php 
								$inicio="-";
								$nodo="-";
							}
					
							if ( !empty($csr) ){
								$titulo="CSR";
								echo "<tr>";
								echo "<td>CSR</td>";
								echo "<td>$csr</td>";
								echo "<td>$titulo</td>";
								echo "</tr>";
								$inicio="-";
								$nodo="-";
							}
						
							if( !empty($BoletaRED) ){
								$id=$BoletaRED;
								$bred = "F".$anio_bit."0000".$id;
								$titulo="Boleta de RED";
								echo "<tr>";
								echo "<td>BR</td>";
								echo "<td><a href='http://portal-sgi/go/aplicacion/man/rptFalla.aspx?var=$bred' target='_blank'>$id</a></td>";
								echo "<td>$titulo</td>";
								echo "</tr>";
								$inicio="-";
								$nodo="-";
							}
							
							if ( !empty($tot) ){
								$id=$tot;
								$titulo = "Tarea Office Track";
								echo "<tr>";
								echo "<td>TOT</td>";
								echo "<td><a href='http://latam.officetrack.com/secure/GlobalOfficeTrackLogon.aspx?ReturnUrl=%2findex.aspx' target='_blank'>$id</a></td>";
								echo "<td>$titulo</td>";
								echo "</tr>";
								$inicio="-";
								$nodo="-";
							}
					
							/**Inicio nuevo campo INC Planta Externa**/
							/*if ($row[18] != 0){
							$id=$row[18];
							$incpe = "INC"."0000".$id;
							$titulo = "INC Planta externa";
							echo "<tr>";
							echo "<td>INC PE</td>\n";
							#echo "<td><a href=\"http://latam.officetrack.com/secure/GlobalOfficeTrackLogon.aspx?ReturnUrl=%2findex.aspx\" target=\"_blank\">$id</a></td>\n";
							echo "<td><a href=\"http://portal-sgi/go/aplicacion/man/rptFalla.aspx?var=$incpe\" target=\"_blank\">$id</a></td>\n";
							echo "<td>$titulo</td>\n";
							echo "</tr>";
							$inicio="-";
							$nodo="-";
							}*/
							/**Fin nuevo campo INC Planta Externa**/
					
							if( $inicio != "-" ){
								echo "<tr>";
								echo "<td>-</td>";
								echo "</tr>";
								$inicio="-";
								$nodo="-";
							} ?>
						
							</table>
							<td><?php echo $creador; ?></td>
							
							<?php 
							$params = [ $bid ];
							$result = $cCfn->exeQuery("doc_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
							$row_doc = $result->fetch_row();
							
							$docAsig = "";
							while( $row_doc ){
								$docAsig .= $row_doc[0] . " - ";
							}
							echo "<td>".substr($docAsig,0,-3)."</td>";
							
							$params = [ $bid ];
							$result = $cCfn->exeQuery("asig_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
							$row_tabla = $result->fetch_row();
							$sitiosAsig = "";
							while( $row_tabla ){
								$sitiosAsig .= $row_tabla[1] . " - ";
							}
							echo "<td>".substr($sitiosAsig,0,-3)."</td>"; ?>
							
						</td>
					</tr>
				</table>
				
				<?php
					$str_style_copiar='';

					if( $opcion == 1 ) {
						$str_style_copiar =" style='cursor:hand; color:blue' title='Haga click para copiar'";
					}
					
					if( $modo_bitacora == 0 ){ ?>
						
						<tr>
							<td>
							<table border='2'>
							<th>usr</th>
							<th>Fecha y hora</th>
							<th <?php echo $str_style_copiar; ?> ><div id='copiartexto'>Descripci&oacute;n</div></th>
							</td>
						</tr>
						
						<?php 
						$text_observaciones ='';
						$params = [ $bid ];
						$result = $cCfn->exeQuery("desc_bit",$params,'i',$cCfn->getLocal(),null,$modulo);
						while( $data = $result->fetch_row() ){
							$user_desc=htmlspecialchars($data[2]);
							$text_observaciones .= $user_desc . chr(10) . chr(13) ;	

							echo "<tr>";
							echo "<td>".$data[0]."</td>";
							echo "<td>".$data[1]."</td>";
							echo "<td><pre>".utf8_decode($user_desc)."</pre></td>";
							echo "</tr>";
						}
						echo "
						</table>
						<div style='visibility:hidden' > 
						<textarea id='tx_texto_$r' cols='1' rows='1' style='height:0px'>$text_observaciones</textarea>	
						</div>	
						";
						
					}
					else{
						echo "</table>";
					}
			}
		?>
		
		<script type="text/javascript">
			$(document).ready(function() {
				// Definir un objeto para encapsular nuestro código
				var AutoRefreshModule = {
					currentUrl: '<?php echo $base_path."/".$selfFile; ?>',
					autoRefreshInterval: null,
					tiempo: 60000, // 10 segundos para pruebas
					
					// Inicializar el módulo
					init: function() {
						// Delegar eventos de clic a los enlaces dentro de mainContent
						$('#mainContent').on('click', 'a', this.handleMainContentClick.bind(this));
						// Delegar eventos de clic a los enlaces de la barra lateral
						$('.sidebar').on('click', 'a', this.handleSidebarClick.bind(this));
						// Delegar eventos de cambio al filtro
						$('#filtroForm').on('change', 'input[name="filtro"]', this.handleFiltroChange.bind(this));
						
						if ($('#filtro_on').is(':checked') ) {
							$('#mainContent').attr('data-current-url', this.currentUrl);
							// if (this.currentUrl) {
								this.autoRefreshInterval = setInterval(this.reloadMainContent.bind(this), this.tiempo);
							// }
						}
					},
					// Función para recargar el contenido del mainContent
					reloadMainContent: function() {
						if (this.currentUrl) {
							$('#mainContent').load(this.currentUrl, function(response, status, xhr) {
								if (status === "error") {
									$('#mainContent').html("<p><br/><br/><br/>Error al cargar el contenido. Intente de nuevo más tarde.</p>");
									// console.error("Error al cargar la página: " + xhr.status + " " + xhr.statusText);
								}
							});
						}
					},

					// Función para manejar cambios en el filtro
					handleFiltroChange: function() {
						// console.log("handleFiltroChange called");
						if ($('#filtro_off').is(':checked')) {
							// Detener la recarga automática si el filtro ya no está en 'on'
							if (this.autoRefreshInterval) {
								clearInterval(this.autoRefreshInterval);
								this.autoRefreshInterval = null;
							}
						} else if ($('#filtro_on').is(':checked')) {
							// Si el filtro está en 'on', iniciar la recarga automática si es necesario
							if (this.currentUrl) {
								this.autoRefreshInterval = setInterval(this.reloadMainContent.bind(this), this.tiempo);
							}
						}
					},

					// Función para manejar clics en enlaces de la barra lateral
					handleSidebarClick: function(e) {
						// console.log("handleSidebarClick called");
						e.preventDefault();
						var url = $(this).data('url');
						if (url && url !== this.currentUrl) {
							this.loadUrl(url);
							this.currentUrl = url;
						}

						// Detener cualquier recarga automática anterior
						if (this.autoRefreshInterval) {
							clearInterval(this.autoRefreshInterval);
							this.autoRefreshInterval = null;
						}
					},

					// Función para manejar clics en enlaces dentro de mainContent
					handleMainContentClick: function(e) {
						// console.log("handleMainContentClick called");
						e.preventDefault();
						var url = $(this).attr('href');
						if (url && url !== this.currentUrl) {
							this.loadUrl(url);
							this.currentUrl = url;
						}
						
						// Detener cualquier recarga automática anterior
						if (this.autoRefreshInterval) {
							clearInterval(this.autoRefreshInterval);
							this.autoRefreshInterval = null;
						}
						
						// Si el enlace tiene la clase 'auto-refresh', iniciar la recarga automática
						if ($(this).hasClass('auto-refresh')) {
							// Verificar si el filtro está en 'on'
							if ($('#filtro_on').is(':checked')) {
								this.autoRefreshInterval = setInterval(this.reloadMainContent.bind(this), this.tiempo);
							}
						}
					},

					// Función para cargar contenido en el div principal
					loadUrl: function(url) {
						// console.log("loadUrl called");
						$('#mainContent').load(url, function(response, status, xhr) {
							if (status === "error") {
								$('#mainContent').html("<p><br/><br/><br/>Error al cargar el contenido. Intente de nuevo más tarde.</p>");
								// console.error("Error al cargar la página: " + xhr.status + " " + xhr.statusText);
							} else {
								// Actualizar la URL actual en el atributo data
								$('#mainContent').attr('data-current-url', url);
								// console.log("data-current-url actualizado a: " + url);
							}
						});
					},
					
				};
				// Inicializar el módulo
				AutoRefreshModule.init();
				
				$('#filtroForm').on('submit', function(e) {
					e.preventDefault(); // Evitar el envío estándar del formulario
					var self = AutoRefreshModule;
					// Obtener los datos del formulario
					var formData = $(this).serialize();
					
					// Enviar los datos mediante AJAX
					$.ajax({
						type: 'POST',
						url: '<?php echo $base_path; ?>/bitacora.php',
						data: formData,
						success: function(response) {
							if ($('#filtro_off').is(':checked')) {
								if (self.autoRefreshInterval) {
									clearInterval(self.autoRefreshInterval);
									self.autoRefreshInterval = null;
								}
							}
							// Actualizar el contenido de mainContent con la respuesta del servidor
							$('#mainContent').html(response);
							// console.log("Formulario enviado exitosamente");
						},
						error: function(xhr, status, error) {
							// Manejar errores
							$('#mainContent').html("<p><br/><br/><br/>Error al enviar el formulario. Intente de nuevo más tarde.</p>");
							console.error("Error al enviar el formulario: " + xhr.status + " " + xhr.statusText);
						}
					});
				});
			});
		</script>
		