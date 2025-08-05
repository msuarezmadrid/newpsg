<?php 
	session_start();
    require "../../autoloader.php";
    use App\Componentes\DependencyContainer;
	
    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	$cCfn->checkSession();
	$file=basename(__FILE__);
	
	include("lib/funciones.php");
	
	$datos=traeParametrosClave();
?>

	<?php include("header.php"); ?> 

	<div class='container-fluid' >
		<div class='row' >
			<div class='col_md-12' >
				<h1 align='center' >Mantenedor de par&aacute;metros de clave</h1>
			</div>
		</div>
		<hr>
		<div class='row' >
			<div class='col_md-12' >
				<p align='right'><button type='button' id='nuevo' value='' class='btn btn-success btn-sm'>Nuevo Registro</button></p>
				<table class='table-condensed table-striped ' id='tbl' align='center' >
					<thead>
						<tr>
							<th>Llave</th>
							<th>Descripci&oacute;n</th>
							<th>Valor</th>
							<th>Mensaje</th>
							<th>Activo</th>
							<th colspan='2' style='text-align:center;' >Acciones</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						for( $a=0; $a<count($datos); $a++ ){
							echo "<tr>";
							for( $r=0; $r<6; $r++ ){
								## ACTIVO 
								if( $r == 4 ){
									$activo=( $datos[$a][$r] == 1 ) ? "Si" : "No" ;
									echo "<td align='center'>$activo</td>";
								}
								else if( $r == 5 ){
									$id=$datos[$a][$r];
									echo "<td><button type='button' id='editar_$id' value='$id' class='btn btn-info btn-sm'>Editar</button></td>";
								}
								else{
									echo "<td>".$datos[$a][$r]."</td>";
								}
							}
							echo "</tr>";
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
