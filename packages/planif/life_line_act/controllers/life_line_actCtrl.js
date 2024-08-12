$(function () {
	Cons_life_line_inicio();
});

function Cons_life_line_inicio() {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = {};
		$.ajax({
			data: parametros,
			url: 'packages/planif/life_line_act/views/Cons_inicio.php',
			type: 'post',
			success: function (response) {
				$("#Cont_life_line_act").html(response);
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

function Cons_life_line(cod, metodo) {
	var usuario = $("#usuario").val();
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = {
			"codigo": cod,
			"metodo": metodo, "usuario": usuario
		};
		$.ajax({
			data: parametros,
			url: 'packages/planif/life_line_act/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#Cont_life_line_act").html(response);
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

function save_life_line() {
	var error = 0;
	var errorMessage = ' ';
	var codigo = $("#r_codigo").val();
	var abrev = $("#r_abrev").val();
	var nombre = $("#r_nombre").val();
	var color = $("#r_color").val();
	var status = Status($("#r_status:checked").val());
	var propuesta = Status($("#r_propuesta:checked").val());
	var usuario = $("#usuario").val();
	var metodo = $("#h_metodo").val();

	if (error == 0) {
		var parametros = "X";
		var parametros = {
			"codigo": codigo, "status": status,
			"nombre": nombre, "abrev": abrev,
			"color": color, "propuesta": propuesta,
			"usuario": usuario, "metodo": metodo
		};
		$.ajax({
			data: parametros,
			url: 'packages/planif/life_line_act/modelo/life_line_act.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp.error) {
					alert(resp.mensaje);
				} else {
					toastr.success("GUARDADO CORRECTAMENTE");
					Cons_life_line_inicio();
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

function CargarDetalle(cod) {

	var usuario = $("#usuario").val();
	var parametros = { "codigo": cod, "usuario": usuario };

	$.ajax({
		data: parametros,
		url: 'packages/planif/life_line_act/views/Add_form_det.php',
		type: 'post',
		success: function (response) {
			$("#Cont_detalleR").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}


function Borrar_life_line(cod) {
	if(confirm("Estas seguro(a) de que deseas eliminar la actividade de linea de vida " + cod)){
		var usuario = $("#usuario").val();
		var parametros = {
			"codigo": cod, "tabla": "planif_life_line_actividades",
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
					Cons_life_line_inicio();
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
}


function actualizar(cargo) {
	var usuario = $("#usuario").val();
	var life_line_act = $("#r_codigo").val();
	var status = 'F';
	console.log('check' + cargo);
	if ($('#check' + cargo).is(':checked')) {
		status = 'T';
	}
	var parametros = {
		cargo: cargo, life_line_act: life_line_act, estatus: status, usuario: usuario
	};

	$.ajax({
		data: parametros,
		url: 'packages/planif/life_line_act/modelo/procesar.php',
		type: 'post',
		success: function (response) {
			var resp = JSON.parse(response);
			if (resp.error) {
				toastr.error(resp.mensaje);
			} else {
				toastr.success('Actualizacion Exitosa!..');
			}

		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});

}
