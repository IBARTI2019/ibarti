var cliente = '';
var usuario = '';
var apertura = '';

var cerrar = true;
$(function () {
	Cons_planificacion_inicio();
});

function Ocultar_all() {
	$("#cont_planif_det").html("");
}

function Cons_planificacion_inicio() {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = {};
		parametros['usuario'] = $("#usuario").val();
		parametros['r_cliente'] = $("#r_cliente").val();
		$.ajax({
			data: parametros,
			url: 'packages/planif/life_line/views/Cons_inicio.php',
			type: 'post',
			beforeSend: function () {
				$("#Cont_life_line").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
			},
			success: function (response) {
				$("#Cont_life_line").html(response);
				setTimeout(function () {
					Ocultar_all();
				}, 500);
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

function Add_filtroX() {
	var ubicacion = $("#ubicacion").val();
	var usuario = $("#usuario").val();
	var parametros = { "ubicacion": ubicacion, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/planif/life_line/views/Add_cl_act.php',
		type: 'post',
		beforeSend: function () {
			$("#cont_planif_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#cont_planif_det").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function B_add_actividad() {
	var ubic = $("#ubicacion").val();
	var usuario = $("#usuario").val();
	var parametros = {
		"ubicacion": ubic,
		"usuario": usuario
	};
	$.ajax({
		data: parametros,
		url: 'packages/planif/life_line/views/Add_actividad.php',
		type: 'post',
		success: function (response) {
			$("#modal_titulo").text("Agregar Actividad");
			$("#modal_contenido").html(response);
			ModalOpen();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function save_act_det(cod) {
	var error = 0;
	var codigo = $("#det_codigo" + cod + "").val();
	var ubic = $("#ubicacion").val();
	var actividad = $("#det_act" + cod + "").val();
	var hora_inicio = $("#det_hora_inicio" + cod + "").val();
	var hora_fin = $("#det_hora_fin" + cod + "").val();
	var metodo = $("#det_metodo" + cod + "").val();
	var propuesta = $("#det_propuesta" + cod + "").val();
	var usuario = $("#usuario").val();
	
	if (error == 0) {
		var parametros = { 
			"codigo": codigo, "actividad": actividad,
			"ubicacion": ubic, "hora_inicio": hora_inicio,
			"hora_fin": hora_fin, "propuesta": propuesta,
			"metodo": metodo, "usuario": usuario
		}
		$.ajax({
			data: parametros,
			url: 'packages/planif/life_line/modelo/life_line.php',
			type: 'post',
			beforeSend: function () {
				$("#cont_planif_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="35px" height="35px"> Procesando...');
			},
			success: function (response) {
				Add_filtroX();
				var resp = JSON.parse(response);
				if (resp.error) {
					alert(resp.mensaje);
				} else {
					alert('Actualizacion Exitosa...');
					CloseModal();
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
}

function Borrar_act_det(cod) {
	if (confirm("�Esta Seguro De Borrar?")) {
		var error = 0;
		var codigo = $("#det_codigo" + cod + "").val();
	
		if (error == 0) {
			var parametros = { 
				"codigo": codigo,
				"metodo": "eliminar"
			}
			$.ajax({
				data: parametros,
				url: 'packages/planif/life_line/modelo/life_line.php',
				type: 'post',
				beforeSend: function () {
					$("#cont_planif_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="35px" height="35px"> Procesando...');
				},
				success: function (response) {
					Add_filtroX();
					var resp = JSON.parse(response);
					if (resp.error) {
						alert(resp.mensaje);
					} else {
						alert('Registro Eliminado con Exito...');
						CloseModal();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
	}
}

function B_reporte(detalle) {
	var errorMessage = '';
	var ubicacion = $("#planf_ubicacion").val();

	if (ubicacion == 'TODOS' || ubicacion == '' || apertura == '') {
		errorMessage = 'Necesita completar los campos para generar el reporte!..';
	}
	var parametros = {
		"cliente": cliente, "ubicacion": ubicacion, "usuario": usuario
	};
	if (errorMessage == '') {
		$.ajax({
			data: parametros,
			url: 'ajax/Add_planif_servicio_min.php',
			type: 'post',
			beforeSend: function () {
				$("#modal_contenidoRP").html('');
				$('#modalRP').show();
				$("#RP").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px"> Procesando...');
				$("#cod_contratacion_serv").val($("#planif_contratacion").val());
			},
			success: function (response) {
				var resp = JSON.parse(response);
				if (typeof resp['servicio'] == 'undefined') {
					$("#RP").html('Sin Resultados!..');
				} else {
					if (detalle == 'T') {
						rp_planif_trab_serv_detalle(resp, 'modal_contenidoRP', () => $('#body_planif').val($('#t_reporte').html()));
					} else if (detalle == 'F') {
						rp_planif_trab_serv(resp, 'modal_contenidoRP', () => $('#body_planif').val($('#t_reporte').html()));
					}
					$("#RP").html('');
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