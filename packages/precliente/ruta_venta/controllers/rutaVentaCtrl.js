var usuario = "";
var precliente = "";

$(function () {
	precliente = $("#precliente").val();
	Cons_rutaventa(precliente, "agregar");
});


function Cons_rutaventa(precliente) {
	var error = 0;
	var errorMessage = ' ';
	var usuario = $("#usuario").val();
	if (error == 0) {
		var parametros = {"precliente": precliente, "usuario": usuario };
		$.ajax({
			data: parametros,
			url: 'packages/precliente/ruta_venta/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#precliente_rutaventa").html(response);
				CargarDetalleRuta(precliente);
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

function save_rutaventa() {
	if(confirm("¿Estas seguro de que desaeas registrar este paso de venta?")) {
		var error = 0;
		var errorMessage = ' ';
		var cod_precliente = $("#precliente").val();
		var comentario = $("#ruta_comentario").val();
		var cod_rutaventa = $("#ruta_de_venta").val();
		var proced = "p_precl_rutaventa";
		var usuario = $("#usuario").val();

		if (error == 0) {
			var parametros = {
				"cod_precliente": cod_precliente, "comentario": comentario,
				"cod_rutaventa": cod_rutaventa,
				"proced": proced, "usuario": usuario,
				"codigo": "", "metodo": "agregar"
			};
			$.ajax({
				data: parametros,
				url: 'packages/precliente/ruta_venta/modelo/rutaventa.php',
				type: 'post',
				success: function (response) {
					var content = JSON.parse(response);
					if (content.error) {
						alert(content.mensaje);
					} else {
						Cons_rutaventa(cod_precliente);
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

function CargarDetalleRuta(precliente) {
	var usuario = $("#usuario").val();
	var parametros = {
		"precliente": precliente,
		"usuario": usuario
	};
	$.ajax({
		data: parametros,
		url: 'packages/precliente/ruta_venta/views/Add_form.php',
		type: 'post',
		success: function (response) {
			$("#precliente_rutaventa").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function Cargar_actividad(codigo, contenedor) {
	var usuario = $("#usuario").val();
	var parametros = { "codigo": codigo, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/precliente/ruta_venta/views/select_actividad.php',
		type: 'post',
		success: function (response) {
			$("#" + contenedor + "").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}
