$(function () {
	Cons_proyecto_inicio();
});

function Cons_proyecto_inicio() {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = {};
		$.ajax({
			data: parametros,
			url: 'packages/formas_de_contactos/form_cont/views/Cons_inicio.php',
			type: 'post',
			success: function (response) {
				$("#Cont_proyecto").html(response);
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

function Cons_proyecto(cod, metodo) {
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
			url: 'packages/formas_de_contactos/form_cont/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#Cont_proyecto").html(response);
				if (metodo == "modificar") {
					CargarDetalle(cod);
					
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

function save_proyecto() {
	var error = 0;
	var errorMessage = ' ';
	var proced = "p_proyecto";
	var codigo = $("#r_codigo").val();
	var abrev = $("#r_abrev").val();
	var nombre = $("#r_nombre").val();
	var status = Status($("#r_status:checked").val());
	var usuario = $("#usuario").val();
	var metodo = $("#h_metodo").val();

	if (error == 0) {
		var parametros = "X";
		var parametros = {
			"codigo": codigo, "status": status,
			"nombre": nombre, "abrev": abrev,
			"proced": proced, "usuario": usuario,
			"metodo": metodo
		};
		$.ajax({
			data: parametros,
			url: 'packages/formas_de_contactos/form_cont/modelo/proyecto.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp.error) {
					alert(resp.mensaje);
				} else {
					toastr.success("GUARDADO CORRECTAMENTE");
					Cons_proyecto_inicio();
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
		url: 'packages/formas_de_contactos/form_cont/views/Add_form_det.php',
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

function CargarCargos(cod) {

	var usuario = $("#usuario").val();
	var parametros = { "codigo": cod, "usuario": usuario };

	$.ajax({
		data: parametros,
		url: 'packages/formas_de_contactos/form_cont/views/Add_cargos.php',
		type: 'post',
		success: function (response) {
			$("#Cont_cargosR").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function save_det(cod, metodo) {

	var error = 0;
	var errorMessage = ' ';
	var proced = "p_fcactividades";
	var proyecto = $("#r_codigo").val();
	var descripcion = $("#descripcion" + cod + "").val();
	var usuario = $("#usuario").val();
	if ((descripcion != "" && descripcion != null) ) {
		if (error == 0) {
			if (cod == '') {
				cod = 0;
			}
			var parametros = {
				"codigo": cod, "proyecto": proyecto,
				"descripcion": descripcion,
				"proced": proced, "usuario": usuario,
				"metodo": metodo
			};
			$.ajax({
				data: parametros,
				url: 'packages/formas_de_contactos/form_cont/modelo/proyecto_det.php',
				type: 'post',
				success: function (response) {
					var resp = JSON.parse(response);
					if (resp.error) {
						alert(resp.mensaje);
					} else {
						CargarDetalle(proyecto);
						toastr.success("Actualizado correctamente");
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
	} else {
		alert("La actividad es obligatoria");
	}
}

function Borrar_proyecto(cod) {
	var usuario = $("#usuario").val();
	var parametros = {
		"codigo": cod, "tabla": "formas_de_contactos",
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
				Cons_proyecto_inicio();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}


function actualizar(cargo) {
	var usuario = $("#usuario").val();
	var proyecto = $("#r_codigo").val();
	var status = 'F';
	console.log('check' + cargo);
	if ($('#check' + cargo).is(':checked')) {
		status = 'T';
	}
	var parametros = {
		cargo: cargo, proyecto: proyecto, estatus: status, usuario: usuario
	};

	$.ajax({
		data: parametros,
		url: 'packages/formas_de_contactos/form_cont/modelo/procesar.php',
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
