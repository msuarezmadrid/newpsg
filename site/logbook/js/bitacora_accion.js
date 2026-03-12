$(document).ready(function() {
	// 
	$('#copiartexto').zclip({
		path:'js/ZeroClipboard.swf',
		copy:$('#tx_texto').val()
	});
	// 
	$('#cmd_enviar').click(function(evt) {
		var oid		= $('#id');
		// var omark   = $('#mark');
		var odesc	= $('#descripcion');
		var href        = '';
		$.ajax({
			cache: false,
			type: "POST",
			async: false,
			url: "php/crear_accion_save.php",
			beforeSend: function(){
				//$( this ).attr('disabled', true);
			},
			data:{
				id	: $('#id').val(),
				desc	: $('#descripcion').val(),
				// mark    : $('#mark').val()
			},
			dataType: "html",
			success: function(respuesta){
				console.log(respuesta);
				var arr = respuesta.split(';');
				numero  =   arr[0]
				estado  =   arr[1]
				href    =   arr[2];
				if ( estado  =='OK' ){
					window.alert(' Se ha agregado una accion exitosamente, a la Bitacora Nro :'+ numero);
				}
			},
			error: function(msg){
				window.alert(msg);
			}
		});
		evt.preventDefault();
		$(location).attr('href',href);
	});
	
});











