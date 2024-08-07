var usuario = "";
var cliente = "";

$(function () {
	cliente = $("#superv_cliente").val();
	Cons_supervision("", "agregar");
});

function Cons_supervision(cod, metodo) {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = { "codigo": cod, "metodo": metodo, "cliente": cliente };
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_agendacontactos/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#superv_supervision").html(response);
				if (metodo == "modificar") {
					CargarDetalleSuperv(cod);
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

function save_supervision() {
	var error = 0;
	var errorMessage = ' ';
	var codigo = $("#superv_codigo").val();
	var status = Status($("#superv_status:checked").val());
	var descripcion = $("#superv_descripcion").val();
	var fecha_inicio = $("#superv_fec_inicio").val();
	var proced = "p_cl_supervision";
	var metodo = $("#superv_metodo").val();

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
			url: 'packages/cliente/cl_supervision/modelo/supervision.php',
			type: 'post',
			success: function (response) {
				var content = JSON.parse(response);
				if (content.error) {
					alert(content.mensaje);
				} else {
					Cons_supervision_inicio();
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

function getfcactividades(cod_ruta) {
	var usuario = $("#usuario").val();
	var parametros = { "cod_ruta": cod_ruta, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/cliente/cl_agendacontactos/views/option_fcactividades.php',
		type: 'post',
		success: function (response) {
			$("#superv_actividad").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}
function save_agenda_det(codigo_det, metodo) {

	var error = 0;
	var fecha = '';
	var errorMessage = ' ';
	var codigo = codigo_det;
	var supervision = $("#superv_codigo").val();
	var ubicacion = $("#superv_ubicacion" + codigo_det + "").val();
	var formacontacto = $("#superv_formacontacto" + codigo_det + "").val();
	var actividad = $("#superv_actividad" + codigo_det + "").val();
	var fecha = $("#agenda_fecha" + codigo_det + "").val();
	var hora = $("#hora" + codigo_det + "").val();
	var proced = "p_cl_agendarclientes";
	cliente = $("#superv_cliente").val();

	if (error == 0) {

		var parametros = {
			"codigo": codigo, "supervision": supervision,
			"ubicacion": ubicacion, "formacontacto": formacontacto, "actividad": actividad, "fecha": fecha,"hora": hora,
			"proced": proced, "usuario": usuario, "metodo": metodo
		};
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_agendacontactos/modelo/supervision_det.php',
			type: 'post',
			success: function (response) {
				var content = JSON.parse(response);
				if (content.error) {
					alert(content.mensaje);
				} else {
					CargarDetalleSuperv(cliente);
					alert('Actualizacion Exitosa...');
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

function CargarDetalleSuperv(cliente) {
	var parametros = {
		"cliente": cliente,
		"usuario": usuario
	};
	$.ajax({
		data: parametros,
		url: 'packages/cliente/cl_agendacontactos/views/Add_form.php',
		type: 'post',
		success: function (response) {
			$("#superv_supervision").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function Cargar_actividad(codigo, contenedor) {
	var parametros = { "codigo": codigo, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/cliente/cl_agendacontactos/views/select_actividad.php',
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
