
	$(document).ready(function() {
		
		$('#tbl button').on('click', function(e){
			let id = e.target.id;
			let arr = id.split('_');
			if( arr[0] == "editar" ){
				opcion=1;
			}
			if( arr[0] == "eliminar" ){
				opcion=2;
			}
			gestion=arr[0];
			registro=arr[1];
			let vars="case="+opcion+"&registro="+registro+"&accion="+gestion;
			// console.log("variables: "+vars);
			$.ajax({
				type: "POST",
				url: "./mantenedores_seguridad/lib/library.php",
				data: vars,
				dataType: "html",
				beforeSend: function() {
				},
				success: function(rspta){
					$("#modalContent").html(rspta);
					$("#modalClave").modal("show");
				},
				error: function(){
				}
			});
			
		});
		
		$('#nuevo').click(function(e){
			let gestion = e.target.id;
			let opcion=3;
			let vars="case="+opcion+"&accion="+gestion;
			// console.log("variables: "+vars);
			$.ajax({
				type: "POST",
				url: "./mantenedores_seguridad/lib/library.php",
				data: vars,
				dataType: "html",
				beforeSend: function() {
				},
				success: function(rspta){
					$("#modalContent").html(rspta);
					$("#modalClave").modal("show");
				},
				error: function(){
				}
			});
			
		});
		
		
		$('#tblBq button').on('click', function(e){
			let id = e.target.id;
			let arr = id.split('_');
			if( arr[0] == "editar" ){
				opcion=6;
			}
			if( arr[0] == "eliminar" ){
				opcion=7;
			}
			gestion=arr[0];
			registro=arr[1];
			let vars="case="+opcion+"&registro="+registro+"&accion="+gestion;
			// console.log("variables: "+vars);
			$.ajax({
				type: "POST",
				url: "./mantenedores_seguridad/lib/library.php",
				data: vars,
				dataType: "html",
				beforeSend: function() {
				},
				success: function(rspta){
					$("#modalContent").html(rspta);
					$("#modalClave").modal("show");
				},
				error: function(){
				}
			});
			
		});
		
		$('#nuevoBloq').click(function(e){
			let gestion = e.target.id;
			let opcion=7;
			let vars="case="+opcion+"&accion="+gestion;
			// console.log("variables: "+vars);
			$.ajax({
				type: "POST",
				url: "./mantenedores_seguridad/lib/library.php",
				data: vars,
				dataType: "html",
				beforeSend: function() {
				},
				success: function(rspta){
					$("#modalContent").html(rspta);
					$("#modalClave").modal("show");
				},
				error: function(){
				}
			});
			
		});
		
	});
	
	function actualizaRegistro(id){
		let opcion=4;
		let llave=$("#txtLlave_"+id).val();
		let desc=$("#txtDesc_"+id).val();
		let valor=encodeURIComponent($("#txtValor_"+id).val());
		let msje=$("#txtMsje_"+id).val();
		let activo=( $("#chkActivo_"+id+":checked").val() ) ? 1 : 0 ;
		let vars="case="+opcion+"&llave="+llave+"&desc="+desc+"&valor="+valor+"&msje="+msje+"&activo="+activo+"&id_registro="+id;
		// console.log(vars);
		$.ajax({
			type: "POST",
			url: "./mantenedores_seguridad/lib/library.php",
			data: vars,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				alert(rspta);
				$("#modalClave").modal("hide");
				location.reload();
			},
			error: function(){
			}
		});
		
	}
	
	function guardaRegistro(){
		let opcion=5;
		let llave=$("#txtLlave").val();
		let desc=$("#txtDesc").val();
		let valor=$("#txtValor").val();
		let msje=$("#txtMsje").val();
		let activo=( $("#chkActivo:checked").val() ) ? 1 : 0 ;
		let vars="case="+opcion+"&llave="+llave+"&desc="+desc+"&valor="+valor+"&msje="+msje+"&activo="+activo;
		
		$.ajax({
			type: "POST",
			url: "./mantenedores_seguridad/lib/library.php",
			data: vars,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				alert(rspta);
				$("#modalClave").modal("hide");
				location.reload();
			},
			error: function(){
			}
		});
		
	}
	
	
	function actualizaRegistroBloq(id){
		let opcion=8;
		let clave=$("#txtClave_"+id).val();
		let desc=$("#txtDesc_"+id).val();
		let detalle=$("#txtDetalle_"+id).val();
		let activo=( $("#chkActivo_"+id+":checked").val() ) ? 1 : 0 ;
		let vars="case="+opcion+"&clave="+clave+"&desc="+desc+"&detalle="+detalle+"&activo="+activo+"&id_registro="+id;
		
		$.ajax({
			type: "POST",
			url: "./mantenedores_seguridad/lib/library.php",
			data: vars,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				alert(rspta);
				$("#modalClave").modal("hide");
				location.reload();
			},
			error: function(){
			}
		});
		
	}
	
	function guardaRegistroBloq(){
		let opcion=9;
		let clave=$("#txtClave").val();
		let desc=$("#txtDesc").val();
		let detalle=$("#txtDetalle").val();
		let activo=( $("#chkActivo:checked").val() ) ? 1 : 0 ;
		let vars="case="+opcion+"&clave="+clave+"&desc="+desc+"&detalle="+detalle+"&activo="+activo;
		
		$.ajax({
			type: "POST",
			url: "./mantenedores_seguridad/lib/library.php",
			data: vars,
			dataType: "html",
			beforeSend: function() {
			},
			success: function(rspta){
				alert(rspta);
				$("#modalClave").modal("hide");
				location.reload();
			},
			error: function(){
			}
		});
		
	}
	
	