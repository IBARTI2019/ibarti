
$(function () {
	Cons_pasoventa();
});

function Cons_pasoventa() {
	var error = 0;
	var errorMessage = ' ';
	var usuario = $("#usuario").val();
	if (error == 0) {
		var parametros = {"usuario": usuario };
		$.ajax({
			data: parametros,
			url: 'packages/precliente/paso_venta/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#precliente_paso_venta").html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	} else {
		alert(errorMessage);
	}
}

function getSubRutas(cod_ruta) {
	var usuario = $("#usuario").val();
	var parametros = { "cod_ruta": cod_ruta, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/precliente/paso_venta/views/optionsSubRutas.php',
		type: 'post',
		success: function (response) {
			console.log(response);
			$("#paso_subruta").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function getRutas(precliente) {
	var usuario = $("#usuario").val();
	var parametros = { "precliente": precliente, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/precliente/paso_venta/views/optionsRutas.php',
		type: 'post',
		success: function (response) {
			$("#paso_ruta").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function save_paso_venta() {
	if(confirm("¿Estas seguro de que desaeas registrar este paso de venta?")) {
		var error = 0;
		var errorMessage = ' ';
		var cod_precliente = $("#paso_precliente").val();
		var cod_sub_ruta = $("#paso_subruta").val();
		var comentario = $("#paso_comentario").val();
		var proced = "p_precl_pasoventa";
		var usuario = $("#usuario").val();

		if (error == 0) {
			var parametros = {
				"cod_precliente": cod_precliente, "comentario": comentario,
				"cod_subrutaventa": cod_sub_ruta,
				"proced": proced, "usuario": usuario,
				"codigo": "", "metodo": "agregar"
			};
			$.ajax({
				data: parametros,
				url: 'packages/precliente/paso_venta/modelo/pasoventa.php',
				type: 'post',
				success: function (response) {
					var content = JSON.parse(response);
					if (content.error) {
						alert(content.mensaje);
					} else {
						Cons_pasoventa();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		} else {
			alert(errorMessage);
		}
	}
}
