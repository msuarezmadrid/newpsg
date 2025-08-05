$(document).ready(function() {

	var oidform= $( '#id_falla' );
	
	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=1",
		dataType: "html",	
		success: function(respuesta){
			$('#div_severidad').html(respuesta);
		},
		error: function(errMsg){
			alert(JSON.stringify(errMsg));
		}
	});
	
	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=2",
		dataType: "html",
		success: function(respuesta){
			$('#div_falla').html(respuesta);
		}
	});
	
	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=3&idf=" + oidform.val() , 
		dataType: "html",
		success: function(respuesta){
			$('#div_falla_core').html(respuesta);
		}
	});

	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=4",
		dataType: "html",
		success: function(respuesta){
			$('#div_paises').html(respuesta);
		}
	});
	
	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=5",
		dataType: "html",
		success: function(respuesta){
			$('#div_regiones').html(respuesta);
		}
	});
	
	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=6",
		dataType: "html",
		success: function(respuesta){
			$('#div_zonas').html(respuesta);
		}
	});

	$.ajax({
		type: "POST",
		url: "php/lib_bitacora.php",
		data:"&caso=7",
		dataType: "html",
		success: function(respuesta){
			$('#div_lugares').html(respuesta);
		}
	});


	$(document).on('change','#id_paises',function(){
		var obj = document.getElementById('id_paises');
		
		$.ajax({
			type: "POST",
			async: false,
			url: "php/lib_bitacora.php",
			data:"&caso=8&idp="+obj.value,
			dataType: "html",
			beforeSend: function() {
				//$('#selectDominio').html("<img src='images/ajax-loader.gif' />");
			},
			success: function(respuesta){
				$('#div_regiones').html(respuesta);
			}
		});
		$.ajax({
			type: "POST",
			async: false,
			url: "php/lib_bitacora.php",
			data:"&caso=9&idp="+obj.value,
			dataType: "html",
			beforeSend: function() {
				//$('#selectDominio').html("<img src='images/ajax-loader.gif' />");
			},
			success: function(respuesta){
				$('#div_zonas').html(respuesta);
			}
		});
		return false;
	});


	$(document).on('change','#id_regiones',function(){

		var obj1= $( '#id_paises' ); 		// document.getElementById('id_paises');
		var obj2= $( this);			// document.getElementById('id_regiones');
		var ovalues = obj2.val().split(';');	// obj2.value.split(';');
		
		if (ovalues[1] === undefined){
			ovalues[1]  = -1;
		}
		
		$( "#id_paises option" ).each(function(index) {
			if ( $(this).val() == ovalues[0]){
				$("select#id_paises").prop('selectedIndex', index );
			}
		});
		
		$.ajax({
			type: "POST",
			async: false,
			url: "php/lib_bitacora.php",
			data:"&caso=10&idp="+ovalues[0]+"&idr="+ovalues[1] ,
			dataType: "html",
			beforeSend: function() {
				// $('#selectDominio').html("<img src='images/ajax-loader.gif' />");
			},
			success: function(respuesta){
				$('#div_zonas').html(respuesta);
			}
			
		});
		return false;
	});


	$(document).on('change','#id_zonas',function(){
		var obj1= $( '#id_paises' );
		var obj2= $( '#id_regiones' );
		var obj3= $( '#id_zonas' );
		var ovalues = obj3.val().split(';');    // obj2.value.split(';');
		
		$( "#id_paises option" ).each(function(index) {
			if ( $(this).val() == ovalues[0]){
				$("select#id_paises").prop('selectedIndex', index );
			}	
		});

		$( "#id_regiones option" ).each(function(index) {
			var oreg = $( this ).val().split(';');    // obj2.value.split(';');
			if (oreg[1] === undefined){
			   v_index = 0;		
			}
			else{
				if ( oreg[1]  == ovalues[1]){
					v_index = index;
				} 
			}
			$("select#id_regiones").prop('selectedIndex', v_index );
		});
		if ( ovalues == -1){
			ovalues[2] = -1;
		}
		$.ajax({
			type: "POST",
			async: false,
			url: "php/lib_bitacora.php",
			data:"&caso=11&idp="+obj1.val()+"&idr="+obj2.val()+"&idz="+ovalues[2],
			dataType: "html",
			beforeSend: function() {
				//$('#selectDominio').html("<img src='images/ajax-loader.gif' />");
			},
			success: function(respuesta){
				$('#div_lugares').html(respuesta);
			}
		});
		return false;
	});

	$(document).on('change','#id_lugar',function(){
		var obj1= $( '#id_paises' );
		var obj2= $( '#id_regiones' );
		var obj3= $( '#id_zonas' );
		var obj4= $( '#id_lugar' );
		var ovalues = obj4.val().split(';');    // obj2.value.split(';');

		$( "#id_paises option" ).each(function(index) {
			if ( $(this).val() == ovalues[0]){
				$("select#id_paises").prop('selectedIndex', index );
			}
		});

		$( "#id_regiones option" ).each(function(index) {
			var oreg = $( this ).val().split(';');    // obj2.value.split(';');
			if (oreg[1] === undefined){
			   v_index = 0;
			}
			else{
				if ( oreg[1]  == ovalues[1]){
					v_index = index;
				}
			}
			$("select#id_regiones").prop('selectedIndex', v_index );
		});

		$( "#id_zonas option" ).each(function(index) {
			var oreg = $( this ).val().split(';');    // obj2.value.split(';');
			if (oreg[2] === undefined){
			   l_index = 0;
			}
			else{
				if ( oreg[2]  == ovalues[2]){
					l_index = index;
				}
			}
			$("select#id_zonas").prop('selectedIndex', l_index );
		});
	});
	
	$('#id_accion').click(function(evt) {
		var oserv	= $('#id_severidad');
		var otitu   = $('#id_fallas_titulo');
		var oallas  = $('#id_fallas_telefonia');
		var olugar	= $('#id_lugar');
		var ozona	= $('#id_zonas')
		var oidform	= $('#id_falla');
		
		if ( oidform.val() == -1){
			window.alert('No es posible continuar con el ingreso de bitacora. la accion aplicada no es correcta.');
			return;
		}
		
		if ( oserv.val() == -1){
			window.alert('Debe seleccionar severidad');
			return;
		}
		
		if (otitu.val() == -1){
			window.alert('Debe seleccionar titulo falla.');
			return;
		}

		if (oallas.val() == -1){
			window.alert('Debe seleccionar fallas.');
			return;
		}

		if (ozona.val() == -1){
			window.alert('Debe seleccionar Zona.');
			return;
		}

		if (olugar.val() == -1){
			window.alert('Debe seleccionar lugar.');
			return;
		}
		
		$.ajax({
			cache: false,
			type: "POST",
			async: false,
			url: "php/crear_bitacora_save.php",
			beforeSend: function(){
				//$( this ).attr('disabled', true);
			},
			data:{
				id_serv: $('#id_severidad').val(),
				id_fal1: $('#id_fallas_titulo').val(),
				id_fal2: $('#id_fallas_telefonia').val(),
				id_pais: $('#id_paises').val(),
				id_regi: $('#id_regiones').val(),
				id_zona: $('#id_zonas').val(),
				id_luga: $('#id_lugar').val(),
				id_titu: $('#titulo').val(),
				id_com2: $('#comentario').val(),
				id_cerr: $('input[name="cerrar"]:checked').val(),
				id_tipo: $('input[name="tipo"]:checked').val(),
					id_iyear	:$('#iyear').val(),		
					id_imonth	:$('#imonth').val(),
					id_iday		:$('#iday').val(),
					id_ihour	:$('#ihour').val(),
					id_iminute	:$('#iminute').val(),
				id_fyear	:$('#fyear').val(),
				id_fmonth	:$('#fmonth').val(),
				id_fday		:$('#fday').val(),
				id_fhour	:$('#fhour').val(),
				id_fminute	:$('#fminute').val()
			},
			dataType: "html",
			success: function(respuesta)
			console.log(respuesta);
			window.alert(' Se ha creado la bitacora existosamente');
		},
		error: function(msg){
			//$(this).attr('disabled', false);
		}
	});
	evt.preventDefault();	
	$(location).attr('href', 'crear_bitacora.php'); 
});