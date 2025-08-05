	
	/* $(document).ready(function() {
		
		
	} */
	
	function ObtenerID(form){
		// Usar jQuery para seleccionar el input checkbox seleccionado dentro del formulario
		var var_id = $(form).find('input[name="sitios"]:checked').val();
		
		// Si no hay ningún checkbox seleccionado, var_id será undefined
		// Puedes manejar este caso según tus necesidades
		if (var_id === undefined) {
			var_id = 0; // o cualquier valor por defecto que desees
		}
		return var_id;
	}
	
	function ObtenerIDs(id){
		let variables="opc="+1+"&sitio="+id;
		// console.log(variables);
		$.ajax({
			type: "POST",
			url: "./proceso_ajax_bitacora.php",
			data: variables,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				opcion = rspta.split('|');
				$("#selec_sitio").html(opcion[0]);
				$("#selec_nodo").html(opcion[3]);
			},
			error: function(){
			}
		});
	}
	
	function Guardar_datos(){
		let id_tipo=null;
		let id_opt=null;
		let var_sigla = $("#selec_sitio");
		let var_siglas = [];
		let var_nodo = $("#selec_nodo");
		let var_nodos = [];
		
		if( $("#id_tipo").is(":checked") ){
			id_tipo=0;
		}
		else{
			id_tipo=1;
		}
		
		if( $("#id_opt").is(":checked") ){
			id_opt='A';
		}
		else{
			id_opt='C';
		}
		
		// Obtener los valores de las opciones seleccionadas en selec_sitio
		var_sigla.find('option:selected').each(function() {
			var_siglas.push($(this).val());
		});

		// Limpiar las selecciones después de obtener los valores
		var_sigla.find('option:selected').prop('selected', false);
		
		let variables="opc="+2+"&severidad=BAJA&titulo=FALLA&sel_sitio="+var_siglas+"&nodo="+var_nodos+"&tipo="+id_tipo+"&opt="+id_opt;
		// console.log(variables);
		$.ajax({
			type: "POST",
			url: "./proceso_ajax_bitacora.php",
			data: variables,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				window.location.href = "crear_bitacora_plran.php";
			},
			error: function(){
				alert("Ocurrió un error al guardar los datos.");
			}
		});
	}
	
	function Volver_menu(){
		// 
		let variables="opc="+4;
		// console.log(variables);
		$.ajax({
			type: "POST",
			url: "./proceso_ajax_bitacora.php",
			data: variables,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				window.location.href = rspta;
			},
			error: function(){
			}
		});
	}
	
	function GuardarDatosNew(){
		// 
		let var_id_tipo	= $('#sel_tarea').val();
		let var_txt_tarea   = $('#txt_tarea').val();
		let var_id_bitacora = $('#id_bitacora').val();
		
		if( var_id_tipo == "0" ){
			alert("Debe seleccionar, el tipo de tarea ");
			$('#sel_tarea').focus();
			return false;
		}	
		if( var_txt_tarea.trim() == "" ){
			alert("Debe ingresar, el id de la tarea");
			$('#txt_tarea').focus();
			return false;
		}
		if( isNaN(var_txt_tarea) ){
			alert("El ID debe ser numerico");
			$('#txt_tarea').focus();
			return false;
		}
		
		let variables = {
			opc: 2,
			id_tipo: var_id_tipo,
			txt_tarea: var_txt_tarea,
			id_bitacora: var_id_bitacora
		};
		// console.log(variables);
		$.ajax({
			type: "POST",
			url: "./documentar_tp_procesar.php",
			data: variables,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				if( rspta != "SI" ){
					mensaje = "Ya se documentó.";
				}
				else{
					mensaje="Documentación realizada.";
					TraerAsociaciones();
				}
			},
			error: function(){
				alert("Ocurrió un error al guardar los datos.");
			}
		});
		alert(mensaje);
	}
	
	function TraerAsociaciones(){
		// 
		let var_id_bitacora = $('#id_bitacora').val();
		let variables = {
			opc: 3,
			id_bitacora: var_id_bitacora
		};
		
		$.ajax({
			type: "POST",
			url: "./documentar_tp_procesar.php",
			data: variables,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				$('#capa_datos').html(rspta);
			},
			error: function(){
				alert("Ocurrió un error.");
			}
		});
	}
	
	function ValidarTarea(){
		// 
		let var_id_tipo	= $('#sel_tarea').val();
		let var_txt_tarea = $('#txt_tarea').val();
		let texto="";
		
		if( var_txt_tarea.trim() != "" ){
			if( var_id_tipo == "0" ){
				alert("Debe seleccionar, el tipo de tarea ");
				$('#sel_tarea').focus();
				return false;
			}	
			if( var_txt_tarea.trim() == "" ){
				alert("Debe ingresar, el id de la tarea");
				$('#txt_tarea').focus();
				return false;
			}
			if( isNaN(var_txt_tarea) ){
				alert("El ID debe ser numerico");
				$('#txt_tarea').focus();
				return false;
			}
			let variables = {
				opc: 1,
				id_tipo: var_id_tipo,
				txt_tarea: var_txt_tarea
			};
			
			$.ajax({
				type: "POST",
				url: "./documentar_tp_procesar.php",
				data: variables,
				dataType: "html",
				beforeSend: function() {
				},
				success: function(rspta){
					if( rspta == "NO" ){
						texto = $('#sel_tarea option:selected').text();
						alert(texto + ": " + var_txt_tarea + " no existe.");
						$('#txt_tarea').val('');
					}
					else return false;
				},
				error: function(){
					alert("Ocurrió un error.");
				}
			});
		}
	}
	
	function CambiaFoco(){
		$('#txt_tarea').focus();
	}
	
	function LimpiarCombos(){
		$('#sel_frecuencia').val(0);
		$('#txt_distribucion').val("");
	}
	
	var color = '#e6e6e6';
	function filaNormal(objx) {
        $('#' + objx).css('background-color', color);
    }
	
	function filaSelect(objx) {
        // Guardar el color de fondo actual antes de cambiarlo
        color = $('#' + objx).css('background-color');
        $('#' + objx).css('background-color', '#ccc');
    }
	
	function Volver() {
		window.history.back();
	}
	
	function SeleccionaTodos() {
		// 
		let reg = $('#registros').val();
		if( $('#chk_todos').is(':checked') ){
			for(i=1; i <= reg; i++){
				$('#chk_'+i).prop('checked', true);
			}
		}else{
			for(i=1; i <= reg; i++){
				$('#chk_'+i).prop('checked', false);
			}
		}
		
	}
	
	function Limpiar(){
		$('#sel_tarea').val(0);
		$('#txt_tarea').val("");
	}
	