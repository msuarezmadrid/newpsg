<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	$base_path=BASE_URL . "/site/logbook";
	
	$id = $_GET["id"] ?? $_POST["id"] ?? '';
	$sel = $_GET["sel"] ?? $_POST["sel"] ?? ''; ?>
	
	<h1 align="CENTER">Asociar bit&aacute;cora</h1>
	<form name='test' action="insert_asociacion.php" method="post">
		<table border="1" class="sample" align="center">
			<tr>
				<th colspan="2" align="center">Bit&aacute;cora: <?php echo $id; ?></th>
			</tr>
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<tr>
				<td>Tipo :</td>
				<td><select name="sel" onchange="this.form.submit()">
					<?php
						$result = $cCfn->exeQuery("asoc_bit_btipo",null,null,$cCfn->getLocal(),null,$modulo);
						while( $row = $result->fetch_assoc() ){
							if( $sel==$row['TIPO'] ){
								echo "<option value='".$row['TIPO']."' selected>".$row['DESCRIPCION']."</option>";
							}else{
								echo "<option value='".$row['TIPO']."' >".$row['DESCRIPCION']."</option>";
							}
						} ?>
				</select></td>
			</tr>
		<?php 
			if( $sel=="BRED" ){ ?>
			<tr>
				<td>Nro Ticket: </td>
				<td><input type="text" name="assoc_id"></td>
			</tr>
		<?php
			}
			else{ ?>
			<tr>
				<td>ID: </td>
				<td><input type="text" name="assoc_id"></td>
			</tr>
		<?php 
			} ?>
			<tr>
				<td>Acci&oacute;n: </td>
				<td><input type="radio" name="accion" checked value="C"/ >Asociar
					<input type="radio" name="accion" value="Q" />Quitar asociaci&oacute;n
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Ingresar">
					<input type="reset" value="Borrar">
				</td>
			</tr>
		</table>
	</form>
	