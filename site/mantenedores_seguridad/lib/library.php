<?php 
	require "../../../autoloader.php";
    use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	
	include("funciones.php");
		
	$case=( isset($_REQUEST["case"]) ) ? $_REQUEST["case"] : NULL ;
	$registro=( isset($_REQUEST["registro"]) ) ? $_REQUEST["registro"] : NULL ;
	$accion=( isset($_REQUEST["accion"]) ) ? $_REQUEST["accion"] : NULL ;
	
	if( $accion == "nuevoBloq" ) $accion="nuevo";
	
	$modal="<div class='modal-header'>
				<h4 class='modal-title'>$accion registro </h4>
			</div>";
	
	switch ($case){
		case 1:{
			# EDITAR MANTENEDOR CLAVE 
			$data=traeParamPass($registro);
			$check=( $data[0][4] == 1 ) ? "checked" : "" ;
			$id=$data[0][5];
			
			$modal.="<div class='modal-body'>
				<table  align='center' class='table-condensed ' > 
					<tr ><th >Llave</th><td><input readonly='true' size='50%' type='text' id='txtLlave_$id' value='".$data[0][0]."' /> </td></tr>
					<tr ><th >Descripci&oacute;n</th><td><textarea placeholder='Ingrese una breve descripci&oacute;n' id='txtDesc_$id' rows='3' cols='50' >".$data[0][1]."</textarea> </td></tr>
					<tr ><th >Valor</th><td><input placeholder='Ingresar un valor' size='50%' type='text' id='txtValor_$id' value='".$data[0][2]."' /> </td></tr>
					<tr ><th >Mensaje</th><td><textarea placeholder='Ingresar un mensaje de aviso para el usuario ' id='txtMsje_$id' rows='3' cols='50' >".$data[0][3]."</textarea> </td></tr> ";
			$modal.="<tr ><th >Activo</th><td><input type='checkbox' id='chkActivo_$id' $check /></td></tr> 
				</table>";
				
			$modal.="
			</div>
			<div class='modal-footer'>
				<button type='button' value='$id' class='btn btn-success btn-sm' onClick='actualizaRegistro(this.value)' >Actualizar</button>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			echo $modal;
			
			break;
		}
		case 2:{
			#ELIMINAR 
			# EN CONSTRU, SI ESQ 
			break;
		}
		case 3:{ # NUEVO 
			
			$modal.="<div class='modal-body'>
				<table align='center' class='table-condensed ' > 
					<tr ><th >Llave</th><td><input placeholder='Ingresar una identificaci&oacute;n sin espacios' size='50%' type='text' id='txtLlave' name='txtLlave' value='' /> </td></tr>
					<tr ><th >Descripci&oacute;n</th><td><textarea placeholder='Ingrese una breve descripci&oacute;n' id='txtDesc' rows='3' cols='50' name='txtDesc' ></textarea> </td></tr>
					<tr ><th >Valor</th><td><input placeholder='Ingresar un valor' size='50%' type='text' id='txtValor' name='txtValor' value='' /> </td></tr>
					<tr ><th >Mensaje</th><td><textarea placeholder='Ingresar un mensaje de aviso para el usuario ' id='txtMsje' rows='3' cols='50' name='txtMsje' ></textarea> </td></tr> ";
			$modal.="<tr ><th >Activo</th><td><input type='checkbox' id='chkActivo' name='chkActivo' /></td></tr> 
				</tbody>
			</table>";
				
			$modal.="
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-success btn-sm' onClick='guardaRegistro()' >Guardar</button>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			echo $modal;
			
			break;
		}
		case 4:{
			# ACTUALIZAR REGISTRO 
			$llave=( isset($_REQUEST["llave"]) ) ? $_REQUEST["llave"] : "" ;
			$descripcion=( isset($_REQUEST["desc"]) ) ? utf8_decode($_REQUEST["desc"]) : "" ;
			$valor=( isset($_REQUEST["valor"]) ) ? $_REQUEST["valor"] : "" ;
			$mensaje=( isset($_REQUEST["msje"]) ) ? utf8_decode($_REQUEST["msje"]) : "" ;
			$activo=( isset($_REQUEST["activo"]) ) ? $_REQUEST["activo"] : "" ;
			$id=( isset($_REQUEST["id_registro"]) ) ? $_REQUEST["id_registro"] : "" ;
			## EJECUTAMOS UPDATE 
			$affected=actualizarRegistro($llave,$descripcion,$valor,$mensaje,$activo,$id);
			## VALIDAMOS EJECUCION
			if( !empty($affected) && $affected != -1 ) echo "Datos actualizados";
			else echo "Error en flujo actualizar\nRevisar log.";
			
			break;
		}
		case 5:{
			# GUARDAR NUEVO REGISTRO  
			$llave=( isset($_REQUEST["llave"]) ) ? $_REQUEST["llave"] : "" ;
			$descripcion=( isset($_REQUEST["desc"]) ) ? utf8_decode($_REQUEST["desc"]) : "" ;
			$valor=( isset($_REQUEST["valor"]) ) ? $_REQUEST["valor"] : "" ;
			$mensaje=( isset($_REQUEST["msje"]) ) ? utf8_decode($_REQUEST["msje"]) : "" ;
			$activo=( isset($_REQUEST["activo"]) ) ? $_REQUEST["activo"] : "" ;
			
			## EJECUTAMOS UPDATE 
			$affected=guardarRegistro($llave,$descripcion,$valor,$mensaje,$activo);
			## VALIDAMOS EJECUCION
			if( !empty($affected) && $affected != -1 ) echo "Datos ingresados";
			else echo "Error en flujo de ingreso\\nRevisar log.";
			
			break;
		}
		case 6:{
			# EDITAR MANTENEDOR BLOQUEO 
			$data=traeParamBloq($registro);
			$check=( $data[0][3] == 1 ) ? "checked" : "" ;
			$id=$data[0][4];
			
			$modal.="<div class='modal-body'>
				<table  align='center' class='table-condensed table-hover table-bordered' > 
					<tr ><th >Clave</th><td><input readonly='true' size='50%' type='text' id='txtClave_$id' value='".$data[0][0]."' /> </td></tr> ";
			$readonly=( $data[0][0] == "estado" ) ? "readonly='true'" : "" ;
			$modal.="<tr ><th >Detalle</th><td><input $readonly placeholder='Ingresar un valor, como detalle' size='50%' type='text' id='txtDetalle_$id' value='".$data[0][1]."' /> </td></tr>
					<tr ><th >Descripci&oacute;n</th><td><textarea placeholder='Ingrese una breve descripci&oacute;n' id='txtDesc_$id' rows='3' cols='50' >".$data[0][2]."</textarea> </td></tr>
					<tr ><th >Activo</th><td><input type='checkbox' id='chkActivo_$id' $check /></td></tr> 
				</table>";
				
			$modal.="
			</div>
			<div class='modal-footer'>
				<button type='button' value='$id' class='btn btn-success btn-sm' onClick='actualizaRegistroBloq(this.value)' >Actualizar</button>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			echo $modal;
			
			break;
		}
		case 7:{
			# NUEVO MANTENEDOR BLOQUEO 
			
			$modal.="<div class='modal-body'>
				<table align='center' class='table-condensed ' > 
					<tr ><th >Clave</th><td><input placeholder='Ingresar una clave sin espacios' size='50%' type='text' id='txtClave' name='txtClave' value='' /> </td></tr>
					<tr ><th >Detalle</th><td><input placeholder='Ingresar un valor, como detalle' size='50%' type='text' id='txtDeetalle' name='txtDetalle' value='' /> </td></tr>
					<tr ><th >Descripci&oacute;n</th><td><textarea placeholder='Ingrese una breve descripci&oacute;n' id='txtDesc' rows='3' cols='50' name='txtDesc' ></textarea> </td></tr>
					<tr ><th >Activo</th><td><input type='checkbox' id='chkActivo' name='chkActivo' /></td></tr> 
				</tbody>
			</table>";
				
			$modal.="
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-success btn-sm' onClick='guardaRegistroBloq()' >Guardar</button>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			echo $modal;
			
			break;
		}
		case 8:{
			# ACTUALIZAR REGISTRO BLOQUEO 
			$clave=( isset($_REQUEST["clave"]) ) ? $_REQUEST["clave"] : "" ;
			$descripcion=( isset($_REQUEST["desc"]) ) ? utf8_decode($_REQUEST["desc"]) : "" ;
			$detalle=( isset($_REQUEST["detalle"]) ) ? $_REQUEST["detalle"] : "" ;
			$activo=( isset($_REQUEST["activo"]) ) ? $_REQUEST["activo"] : "" ;
			$id=( isset($_REQUEST["id_registro"]) ) ? $_REQUEST["id_registro"] : "" ;
			
			## DETALLE NOTIFICADO 
			$arrNotif=traeParamBloq(1);
			$detalle_notif=$arrNotif[0][1];
			## DETALLE BLOQUEO 
			$arrBloq=traeParamBloq(2);
			$detalle_bloq=$arrBloq[0][1];
			## DETALLE ELIMINADO 
			$arrElim=traeParamBloq(3);
			$detalle_elim=$arrElim[0][1];
			
			if( $clave=="notificado" ){
				if( $detalle >= $detalle_bloq || $detalle >= $detalle_elim ){
					echo "El detalle ingresado no puede ser mayor o igual que el detalle de bloqueado ni eliminado"; die;
				}
			}
			if( $clave=="bloqueado" ){
				if( $detalle <= $detalle_notif || $detalle >= $detalle_elim ){
					echo "El detalle ingresado no puede ser menor que el detalle de notificado ni mayor que el detalle de eliminado"; die;
				}
			}
			if( $clave=="eliminado" ){
				if( $detalle <= $detalle_notif || $detalle <= $detalle_bloq ){
					echo "El detalle ingresado no puede ser menor que el detalle de notificado ni mayor que el detalle de eliminado"; die;
				}
			}
			
			## EJECUTAMOS UPDATE 
			$affected=actualizarRegistroBloq($clave,$detalle,$descripcion,$activo,$id);
			## VALIDAMOS EJECUCION
			if( !empty($affected) && $affected != -1 ) echo "Datos actualizados";
			else echo "Error en flujo actualizar\\nRevisar log.";
			
			break;
		}
		case 9:{
			# GUARDAR NUEVO REGISTRO BLOQUEO 
			$clave=( isset($_REQUEST["clave"]) ) ? $_REQUEST["clave"] : "" ;
			$descripcion=( isset($_REQUEST["desc"]) ) ? utf8_decode($_REQUEST["desc"]) : "" ;
			$detalle=( isset($_REQUEST["detalle"]) ) ? $_REQUEST["detalle"] : "" ;
			$activo=( isset($_REQUEST["activo"]) ) ? $_REQUEST["activo"] : "" ;
			
			## EJECUTAMOS UPDATE 
			$affected=guardarRegistroBloq($clave,$descripcion,$detalle,$activo);
			## VALIDAMOS EJECUCION
			if( !empty($affected) && $affected != -1 ) echo "Datos ingresados";
			else echo "Error en flujo de ingreso\\nRevisar log.";
			
			break;
		}
		default:{
			
			break;
		}
	}
?>
