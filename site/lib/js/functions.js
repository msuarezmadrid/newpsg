var currentUrl = '';
$(document).ready(function() {
	// Limpiar el sessionStorage al cargar la página por primera vez (ejemplo de inicio de sesión)
    if (sessionStorage.getItem('modalShown') !== 'true' ) {
        $('#modalAviso').modal({
            show: 'true'
        });
        sessionStorage.setItem('modalShown', 'true'); // Guardar que el modal ya se ha mostrado
    }
	
    // Para los enlaces en la barra lateral
    $('#mySidebar a').on('click', handleSidebarClick);

    // Para los enlaces dentro de mainContent
    $('#mainContent').on('click', 'a', handleMainContentClick);
	
	
	$('#s_areaorigen').on('change', function(){
		$('#dvServicio').css( 'display', 'block' );
	});
	triggerAO();
	
	$("#modaltp").on("shown.bs.modal", function () {
		initializeFlatpickr("#date", { 
			dateFormat: "Y-m-d", 
			minDate: "today", // DESHABILITA DIAS PASADOS 
			locale: "es",
			onChange: function(selectedDates, dateStr, instance) { 
				if (dateStr === new Date().toISOString().split('T')[0]) { 
					// Si la fecha seleccionada es hoy 
					instance.set('minTime', new Date().getHours() + ":" + new Date().getMinutes()); 
				} else {
					instance.set('minTime', null); // Quitar restricción de tiempo para fechas futuras 
				}
			}
		});
		initializeFlatpickr("#time", {
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i",
			time_24hr: true,
			locale: "es",
			minTime: new Date().getHours() + ":" + new Date().getMinutes(), // Deshabilita las horas pasadas hoy 
			onOpen: function(selectedDates, dateStr, instance) { 
				var dateField = document.querySelector("#date").value; 
				if (dateField === new Date().toISOString().split('T')[0]) { // Si la fecha seleccionada es hoy 
					instance.set('minTime', new Date().getHours() + ":" + new Date().getMinutes());
				} else {
					instance.set('minTime', null); // Quitar restricción de tiempo para fechas futuras 
				}
			}
		});
	});
	
	$("#ingresarLogin").on("click", function(e){
		e.preventDefault();
		let txtUsuario=$("#txtUsuario").val();
		let usuario=btoa(txtUsuario);
		let txtPwd=$("#txtPwd").val();
		let ingresarLogin=$(this).val();
		let vars={
			txtUsuario: txtUsuario,
			txtPwd: txtPwd,
			ingresarLogin: ingresarLogin
		};
		// console.log(vars);
		$.ajax({
			type: "POST",
			url: "login.php",
			data: vars,
			dataType: "text",
			beforeSend: function(){},
			success: function(rspta){
				if (rspta.startsWith("redirect:")) {
					let redirectUrl = rspta.split(":")[1];
					window.location.href = redirectUrl;
				} else if (rspta === "UserNot") {
					alert("ERROR! Usuario inexistente!");
				} else if (rspta === "CharWrong") {
					alert("ERROR! Se han ingresado carácteres no permitidos, se deben cumplir las políticas de seguridad.");
				} else if (rspta === "BloqIntFall") {
					alert("ERROR! La cuenta se encuentra bloqueada por intentos fallidos.");
				} else if (rspta === "BloqInact") {
					alert("ERROR! La cuenta se encuentra bloqueada por inactividad.");
				} else if (rspta === "MaxIntent") {
					alert("ERROR! Ha alcanzado el máximo de intentos permitidos.");
				} else if (rspta === "CredenErr") {
					alert("Credenciales incorrectas. Por favor, inténtalo de nuevo.");
				} else if (rspta === "PassCad") {
					alert("ERROR! Su clave temporal ha caducado.");
				} else if (rspta === "PassCadCamb") {
					alert("ERROR! Su clave temporal ha caducado, debe cambiarla.");
					window.location.href = "change_form.php?usr="+usuario;
				}
				else {
					alert("Ocurrió un error inesperado. Por favor, inténtalo de nuevo.");
				}
			},
			error: function(){
				alert("Error en la solicitud. Por favor, inténtalo de nuevo.");
			}
		});
	});
});



// Función para cargar contenido en el div principal
function loadUrl(url) {
    $('#mainContent').load(url, function(response, status, xhr) {
        if (status === "error") {
            $('#mainContent').html("<p><br/><br/><br/>Error al cargar el contenido. Intente de nuevo más tarde.</p>");
            console.error("Error al cargar la página: " + xhr.status + " " + xhr.statusText);
        }
    });
}

// Manejar clics en enlaces de la barra lateral
function handleSidebarClick(e) {
    e.preventDefault();
    var url = $(this).data('url');
    if (url && url !== currentUrl) {
        loadUrl(url);
        currentUrl = url;
    }
}

// Manejar clics en enlaces dentro de mainContent
function handleMainContentClick(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    if (url && url !== currentUrl) {
        loadUrl(url);
        currentUrl = url;
    }
}

// Definir la función toggleSidebar para abrir/cerrar la barra lateral
function toggleSidebar() {
    $("#mySidebar").toggleClass("sidebar-hidden");
    $("#mainContent").toggleClass("main-content-expanded");
}

// Para inicializar Flatpickr solo cuando corresponda 
function initializeFlatpickr(selector, options) {
	if (!$(selector).hasClass("flatpickr-input")) {
		flatpickr(selector, options);
	}
}

function limpiar(){
	location.reload();
}

function triggerAO(){
	if ( $('#s_areaorigen').prop('disabled') === true ){
		$('#s_areaorigen').trigger('change');
	}
}

function update_elemento(){
	let opcion = '1';
	let path = $("#path").val();
	let areaOrigen=$("#s_areaorigen option:selected").text();
	let servicio=$("#s_servicios").val();
	let vars="s_areaorigen="+areaOrigen+"&servicio="+servicio+"&opcion="+opcion;
	// console.log(vars);
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){},
		success: function(rspta){
			$('#dvElementos').css( 'display', 'block' );
			$("#s_elementos").html(rspta);
			$('#s_servicios').prop( 'disabled', 'true' );
		},
		error: function(){
		}
	});	
}

function update_tipoTrabajo(){
	let opcion = '2';
	let path = $("#path").val();
	let areaOrigen=$("#s_areaorigen option:selected").text();
	let servicio=$("#s_servicios").val();
	let elemento=$("#s_elemento").val();
	let vars="s_areaorigen="+areaOrigen+"&servicio="+servicio+"&elemento="+elemento+"&opcion="+opcion;
	// console.log(vars);
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){},
		success: function(rspta){
			$('#dvTipoTrabajo').css( 'display', 'block' );
			$("#s_tipotrabajos").html(rspta);
			$('#s_elemento').prop( 'disabled', 'true' );
		},
		error: function(){
		}
	});	
}

function update_tipoTarea(){
	let opcion = '3';
	let path = $("#path").val();
	let vars="opcion="+opcion;
	// console.log(vars);
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){},
		success: function(rspta){
			$('#dvModalidad').css( 'display', 'block' );
			$("#s_modalidadtrabajos").html(rspta);
			$('#s_tipoTrabajo').prop( 'disabled', 'true' );
		},
		error: function(){
		}
	});	
}

function validaTitulo(){
	let opcion = '4';
	let path = $("#path").val();
	let areaOrigen=$("#s_areaorigen option:selected").text();
	let servicio=$("#s_servicios").val();
	let tipotrabajo=$("#s_tipoTrabajo").val();
	let tipoingreso=$("#s_tipoIngreso").val();
	let vars="s_areaorigen="+areaOrigen+"&servicio="+servicio+"&tipotrabajo="+tipotrabajo+"&tipoingreso="+tipoingreso+"&opcion="+opcion;
	// console.log(vars);
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){},
		success: function(rspta){
			$('#dvTitulo').css( 'display', 'block' );
			$("#titulo").html(rspta);
			$('#s_tipoIngreso').prop( 'disabled', 'true' );
		},
		error: function(){
		}
	});	
	
}

function update_region(){
	// alert("region");
	let preTit=$("#pre_titulo").val();
	let txtTit=$("#txtTitulo").val();
	$('#dvRegion').css( 'display', 'block' );
	$('#dvTituloFull').css( 'display', 'block' );
	$('#txtTitulo').prop( 'disabled', 'true' );
	$('#lblTitulo').html(preTit + " : " + txtTit);
}

function update_comunas(){
	// alert("comuna");
	let opcion = '5';
	let path = $("#path").val();
	let region=$("#s_regiones").val();
	let vars="region="+region+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$('#dvLugar').css( 'display', 'none' );
			$('#dvNomLugar').css( 'display', 'none' );
			$('#dvUbicacion').css( 'display', 'none' );
			detectarSitios(false);
		},
		success: function(rspta){
			$('#dvComuna').css( 'display', 'block' );
			$("#s_comuna").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_comunas_edit(){
	// alert("comuna");
	let opcion = '55';
	let path = $("#path").val();
	let region=$("#edits_regiones").val();
	let vars="region="+region+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$("#edit_lugar").html('');
			$("#edit_nomlugar").html('');
			$("#edit_ubicacion").html('');
		},
		success: function(rspta){
			//$('#dvComuna').css( 'display', 'block' );
			$("#edit_comunas").html('');
			$("#edit_comunas").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_lugar(){
	// alert("lugar");
	let opcion = '6';
	let path = $("#path").val();
	let region=$("#s_regiones").val();
	let comuna=$("#s_comunas").val();
	let vars="region="+region+"&comuna="+comuna+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$('#dvNomLugar').css( 'display', 'none' );
			$('#dvUbicacion').css( 'display', 'none' );
			detectarSitios(false);
		},
		success: function(rspta){
			$('#dvLugar').css( 'display', 'block' );
			$("#s_lugars").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_lugar_edit(){
	// alert("lugar");
	let opcion = '66';
	let path = $("#path").val();
	let region=$("#edits_regiones").val();
	let comuna=$("#edits_comunas").val();
	let vars="region="+region+"&comuna="+comuna+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$("#edit_nomlugar").html('');
			$("#edit_ubicacion").html('');
		},
		success: function(rspta){
			//$('#dvLugar').css( 'display', 'block' );
			$("#edit_lugar").html('');
			$("#edit_lugar").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_nomLugar(){
	// alert("lugar");
	let opcion = '7';
	let path = $("#path").val();
	let region=$("#s_regiones").val();
	let comuna=$("#s_comunas").val();
	let lugar=$("#s_lugar").val();
	let vars="region="+region+"&comuna="+comuna+"&lugar="+lugar+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$('#dvUbicacion').css( 'display', 'none' );
			detectarSitios(false);
		},
		success: function(rspta){
			$('#dvNomLugar').css( 'display', 'block' );
			$("#s_nomlugar").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_nomLugar_edit(){
	// alert("lugar");
	let opcion = '77';
	let path = $("#path").val();
	let region=$("#edits_regiones").val();
	let comuna=$("#edits_comunas").val();
	let lugar=$("#edits_lugar").val();
	let vars="region="+region+"&comuna="+comuna+"&lugar="+lugar+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$("#edit_ubicacion").html('');
		},
		success: function(rspta){
			// $('#dvNomLugar').css( 'display', 'block' );
			$("#edit_nomlugar").html('');
			$("#edit_nomlugar").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_ubicacion(){
	// alert("ubicacion");
	let opcion = '8';
	let path = $("#path").val();
	let region=$("#s_regiones").val();
	let comuna=$("#s_comunas").val();
	let lugar=$("#s_lugar").val();
	let elemento=$("#s_nombreelemento").val();
	let vars="region="+region+"&comuna="+comuna+"&lugar="+lugar+"&elemento="+elemento+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
		},
		success: function(rspta){
			$('#dvUbicacion').css( 'display', 'block' );
			$("#s_ubica").html(rspta);
		},
		error: function(){
		}
	});	
}

function update_ubicacion_edit(){
	// alert("ubicacion");
	let opcion = '88';
	let path = $("#path").val();
	let region=$("#edits_regiones").val();
	let comuna=$("#edits_comunas").val();
	let lugar=$("#edits_lugar").val();
	let elemento=$("#edits_nombreelemento").val();
	let vars="region="+region+"&comuna="+comuna+"&lugar="+lugar+"&elemento="+elemento+"&opcion="+opcion;
	
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){},
		success: function(rspta){
			// $('#dvUbicacion').css( 'display', 'block' );
			$("#edit_ubicacion").html('');
			$("#edit_ubicacion").html(rspta);
		},
		error: function(){
		}
	});	
}

function detectarSitios(mode){
	if(mode == true){
		$('#dvConfirmar').css( 'display', 'block' );
	}
	else{
		$('#dvConfirmar').css( 'display', 'none' );
	}
}

function confirmarBoton(){
	detectarSitios(true);
}

function ingresarTramo(){
	// alert("ingresarTramo");
	let opcion=9;
	let path = $("#path").val();
	let region=$("#s_regiones").val();
	let comuna=$("#s_comunas").val();
	let lugar=$("#s_lugar").val();
	let modalidad=$("#s_tipoIngreso").val();
	let planned_aux=$("#planned_aux").val();
	let nombreelemento = "";
	let ubicacion = "";
	
	if( lugar == "Sitios" ){
		$('input[name="SITIO[]"]:checked').each(function() {
			nombreelemento += $(this).val() + "|";
		});
	}
	else{
		nombreelemento=$("#s_nombreelemento").val();
		ubicacion=$("#s_sala").val();
	}
	
	let vars="region="+region+"&comuna="+comuna+"&lugar="+lugar+"&elemento="+nombreelemento+"&sala="+ubicacion+"&planned_aux="+planned_aux+"&modalidad="+modalidad+"&opcion="+opcion;
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			if( region == "any" ){
				alert("Debe seleccionar los datos necesarios");
				return false;
			}
		},
		success: function(rspta){
			$('#dvTramos').css( 'display', 'block' );
			$("#tbTramos tbody").html(rspta);
		},
		error: function(){
		}
	});	
	
	$("#s_regiones").prop('selectedIndex', 0);
	$('#dvComuna').css( 'display', 'none' );
	$("#s_comuna").html('');
	$('#dvLugar').css( 'display', 'none' );
	$("#s_lugars").html('');
	$('#dvNomLugar').css( 'display', 'none' );
	$("#s_nomlugar").html('');
	$('#dvUbicacion').css( 'display', 'none' );
	$("#s_ubica").html('');
	$('#btnConfirmar').html( "Agregar" ); /* CAMBIAMOS CONFIRMAR POR AGREGAR */
	
	showBtnIngresar();
}

function showBtnIngresar(){
	let titulo=$("#txtTitulo").val();
	if( titulo != "" ){
		$('#btnIngresar').prop( 'disabled', false );
	}
	return true;
}

function eliminaTramo(id,aux){
	let opcion=10;
	let path = $("#path").val();
	let vars="opcion="+opcion+"&id_reg="+id+"&planned_aux="+aux;
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			let result = confirm("¿Esta seguro que desea eliminar el regitro "+id+"?");
			if( !result ){
				return false;
			}
			detectarSitios(false);
		},
		success: function(rspta){
			if( rspta === "1" ){
				$('#dvTramos').css( 'display', 'none' );
				alert("Registro "+id+" eliminado.");
			}else{
				$('#dvTramos').css( 'display', 'block' );
				$('#tbTramos tbody').html('');
				$("#tbTramos tbody").html(rspta);
			}
		},
		error: function(){
		}
	});	
}

function editaTramo(id,aux){
	// alert("edita:"+id+" aux:"+aux);
	let opcion=11;
	let path = $("#path").val();
	let vars="opcion="+opcion+"&id_reg="+id+"&planned_aux="+aux;
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			// detectarSitios(false);
		},
		success: function(rspta){
			$("#modalContentTramo").html(rspta);
			$('#modalTramo').modal({
				show: 'true'
			});
		},
		error: function(){
		}
	});	
}

function guardarTramo(){
	let opcion=13;
	let path = $("#path").val();
	
	let id_registro = $("#id_registro").val();
	let region = $("#edits_regiones").val();
	let comuna = $("#edits_comunas").val();
	let lugar = $("#edits_lugar").val();
	let planned_aux = $("#planned_aux").val();
	let nombreelemento = "";
	let ubicacion = "";
	
	if( lugar == "Sitios" ){
		$('input[name="SITIO[]"]:checked').each(function() {
			nombreelemento += $(this).val() + "|";
		});
	}
	else{
		nombreelemento=$("#edits_nombreelemento").val();
		ubicacion=$("#edits_sala").val();
	}
	
	let vars="region="+region+"&comuna="+comuna+"&lugar="+lugar+"&elemento="+nombreelemento+"&sala="+ubicacion+"&id_reg="+id_registro+"&planned_aux="+planned_aux+"&opcion="+opcion;
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			$("#tbTramos tbody").html('');
		},
		success: function(rspta){
			// alert(rspta);
			$("#tbTramos tbody").html(rspta);
			$('#modalTramo').modal('hide');
		},
		error: function(){
		}
	});	
}

function ingresaTP(){
	
	let opcion=12;
	let path = $("#path").val();
	
	let tipotrabajo=$("#s_tipoTrabajo").val();
	let tipoingreso=$("#s_tipoIngreso").val();
	let planned_aux=$("#planned_aux").val();
	let fullTitulo=$("#lblTitulo").text();
	
	let vars="tipotrabajo="+tipotrabajo+"&tipoingreso="+tipoingreso+"&planned_aux="+planned_aux+"&fullTitulo="+fullTitulo+"&opcion="+opcion;
	// console.log("vars:"+vars);
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){},
		success: function(rspta){
			// alert(rspta);
			window.location.href = path + '/tp/ver_tp.php?tp=' + rspta.TP
		},
		error: function(){
		}
	});	
}

function fechaSolicEjecTP(adc,tp){
	if( adc == "SI" ){
		let opcion=14;
		let path = $("#path").val();
		let vars="opcion="+opcion+"&planned="+tp;
		$.ajax({
			type: "POST",
			url: path + "/lib/functions.php",
			data: vars,
			dataType: "html",
			beforeSend: function(){},
			success: function(rspta){
				$("#modalContentTP").html(rspta);
				$('#modaltp').modal({
					show: 'true'
				});
			},
			error: function(){
			}
		});	
	}
	else{
		alert("Usted no puede modificar esta fecha");
		return false;
	}
}

function saveFechaSolicEjecTP(){
	let opcion=15;
	let path = $("#path").val();
	let tp = $("#planned").val();
	let date = $("#date").val();
	let time = $("#time").val();
	let vars="opcion="+opcion+"&planned="+tp+"&date="+date+"&time="+time;
	$.ajax({
		type: "POST",
		url: path + "/lib/functions.php",
		data: vars,
		dataType: "html",
		beforeSend: function(){
			if( date == "" || time == "" ){
				alert("Debe ingresar datos válidos.");
				return false;
			}
		},
		success: function(rspta){
			if( rspta === "1" ){
				alert("Modificación éxitosa");
				$('#modaltp').modal('hide');
				location.reload();
			}
			else{
				alert("Existió un error al actualizar.");
			}
		},
		error: function(){
		}
	});	
}