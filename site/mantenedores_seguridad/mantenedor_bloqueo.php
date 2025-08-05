<?php 
	session_start();
    require "../../autoloader.php";
    use App\Componentes\DependencyContainer;
	
    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	$cCfn->checkSession();
	include("lib/funciones.php");
	
	$file=basename($_SERVER['PHP_SELF']);
	
	$datos=traeParametrosBloqueo();
?>
	<?php
		include("header.php");
	?>
	<div class='container-fluid' >
		<div class='row' >
			<div class='col_md-12' >
				<h1 align='center' >Mantenedor de configuraci&oacute;n para el bloqueo de cuentas</h1>
			</div>
		</div>
		<hr>
		<div class='row' >
			<div class='col_md-12' >
				<p align='right'><button type='button' id='nuevoBloq' value='' class='btn btn-success btn-sm'>Nuevo Registro</button></p>
				<table class='table-condensed table-striped ' id='tblBq' align='center' >
					<thead>
						<tr>
							<th>Clave</th>
							<th>Detalle</th>
							<th>Descripci&oacute;n</th>
							<th>Activo</th>
							<th colspan='2' style='text-align:center;' >Acciones</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if( empty($datos) ){
							echo "<tr><td colspan='5' align='center'>Sin datos que mostrar</td></tr>";
						}
						else{
							for( $a=0; $a<count($datos); $a++ ){
								echo "<tr>";
								for( $r=0; $r<5; $r++ ){
									## ACTIVO 
									if( $r == 3 ){
										$activo=( $datos[$a][$r] == 1 ) ? "Si" : "No" ;
										echo "<td align='center'>$activo</td>";
									}
									else if( $r == 4 ){
										$id=$datos[$a][$r];
										echo "<td><button type='button' id='editar_$id' value='$id' class='btn btn-info btn-sm'>Editar</button></td>";
									}
									else{
										echo "<td>".$datos[$a][$r]."</td>";
									}
								}
								echo "</tr>";
							}
						}
					?>
					</tbody>
				</table>
				
				<div id='modalClave' tabindex='-1' class='modal fade' role='dialog' >
					<div class='modal-dialog ' id='modalDialog' role='document'>
						<div class='modal-content' id='modalContent' ></div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	