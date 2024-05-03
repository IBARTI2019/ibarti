var usuario = "";
var cliente = "";
var codigo_det = "";
var metodo_cont_det = "";

$(function () {
	Cons_contratacion_inicio();
});

function Cons_contratacion_inicio() {

	usuario = $("#usuario").val();
	cliente = $("#cont_cliente").val();

	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = { "cliente": cliente };

		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_contratacion/views/Cons_inicio.php',
			type: 'post',
			success: function (response) {
				$("#Cont_contratacion").html(response);
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

function Cons_contratacion(cod, metodo) {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = { "codigo": cod, "metodo": metodo };
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_contratacion/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#Cont_contratacion").html(response);
				if (metodo == "modificar") {
					console.log(metodo, cod);
					CargarDetalleCont(cod);
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

function save_contratacion() {
	var error = 0;
	var errorMessage = ' ';
	var codigo = $("#cont_codigo").val();
	var status = Status($("#cont_status:checked").val());
	var descripcion = $("#cont_descripcion").val();
	var fecha_inicio = $("#cont_fec_inicio").val();
	var proced = "p_cl_contratacion";
	var metodo = $("#cont_metodo").val();

	if (error == 0) {
		var parametros = {
			"codigo": codigo, "status": status,
			"cliente": cliente, "descripcion": descripcion,
			"fecha_inicio": fecha_inicio,
			"proced": proced, "usuario": usuario,
			"metodo": metodo
		};
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_contratacion/modelo/contratacion.php',
			type: 'post',
			success: function (response) {
				var content = JSON.parse(response);
				if (content.error) {
					alert(content.mensaje);
				} else {
					Cons_contratacion_inicio();
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

function validar_contratacion_det(id, metodo) {
	codigo_det = id;
	metodo_cont_det = metodo;
	var contratacion = $("#cont_codigo").val();
	var errorMessage = '';

	if (cliente == '') errorMessage = 'Debe seleccionar un cliente..';
	if (errorMessage == '') {
		var parametros = { "cliente": cliente, "contratacion": contratacion };

		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_contratacion/modelo/verificar_cont_det.php',
			type: 'post',
			success: function (response) {
				var content = JSON.parse(response);
				if (content[0]['contra'] == 0) {
					save_contratacion_det(null);
				} else {
					$("#modal_cont_ap").show();
					$("#cont_ap_fecha").attr("min", content[0]['fecha_min']);
					$("#cont_ap_fecha").attr("max", content[0]['fecha_max']);
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

function save_contratacion_det(fecha_inicio) {

	if (confirm("Estas seguro(a) de que deseas aplicar esta ectualizacion?..")) {
		var error = 0;
		var fecha = '';
		var errorMessage = ' ';
		var codigo = codigo_det;
		var contratacion = $("#cont_codigo").val();
		var ubicacion = $("#cont_ubicacion" + codigo_det + "").val();
		var puesto = $("#cont_puesto" + codigo_det + "").val();
		var turno = $("#cont_turno" + codigo_det + "").val();
		var cargo = $("#cont_cargo" + codigo_det + "").val();
		var cantidad = $("#cont_cantidad" + codigo_det + "").val();
		var proced = "p_cl_contratacion_det";

		if (error == 0) {

			var parametros = {
				"codigo": codigo, "contratacion": contratacion,
				"ubicacion": ubicacion, "puesto": puesto,
				"turno": turno, "cargo": cargo,
				"cantidad": cantidad,
				"proced": proced, "usuario": usuario, "fecha_inicio": fecha_inicio,
				"metodo": metodo_cont_det
			};

			$.ajax({
				data: parametros,
				url: 'packages/cliente/cl_contratacion/modelo/contratacion_det.php',
				type: 'post',
				beforeSend: function(){
					//Deshabilito el submit del form X
					$('#close_act_cont').hide();
					$('#contrato_ap').attr('disabled',true);
					$('#cont_ap_form').hide();
					//Cambio la imagen del boton Buscar X
					$('#loading_save_cont').show();
				},
				success: function (response) {
					$('#close_act_cont').show();
					$('#loading_save_cont').hide();
					$('#cont_ap_form').show();
					$('#contrato_ap').attr('disabled',false);
					var content = JSON.parse(response);
					if (content.error) {
						alert(content.mensaje);
					} else {
						CargarDetalleCont(contratacion);
						alert('Actualizacion Exitosa...')
						$("#modal_cont_ap").hide();
					}

				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#close_act_cont').show();
					$('#loading_save_cont').hide();
					$('#cont_ap_form').show();
					$('#contrato_ap').attr('disabled',false);
					alert(xhr.status);
					alert(thrownError);
				}
			});
		} else {
			alert(errorMessage);
		}
	}
}


function Borrar_contratacion(cod) {
	if (confirm("¿ Esta Seguro De Borrar esta Contratación ?")) {
		var parametros = {
			"codigo": cod, "tabla": "clientes_contratacion",
			"usuario": usuario
		};
		$.ajax({
			data: parametros,
			url: 'packages/general/controllers/sc_borrar.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp.error) {
					alert(resp.mensaje);
				} else {
					Cons_contratacion_inicio();
				}

			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
}

function CargarDetalleCont(cod_cont) {
	var parametros = {
		"contratacion": cod_cont, "cliente": cliente,
		"usuario": usuario
	};
	$.ajax({
		data: parametros,
		url: 'packages/cliente/cl_contratacion/views/Add_form_contratacion_det.php',
		type: 'post',
		success: function (response) {
			$("#Cont_detalleCont").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}
function Cargar_puesto(codigo, contenedor) {
	var parametros = { "codigo": codigo, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/cliente/cl_contratacion/views/select_puesto.php',
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
