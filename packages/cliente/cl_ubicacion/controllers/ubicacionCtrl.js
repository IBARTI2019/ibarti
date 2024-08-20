$(function () {
	Cons_ubicacion_inicio();
});

function Cons_ubicacion_inicio() {

	var cliente = $("#c_codigo").val();

	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = { "cliente": cliente };
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_ubicacion/views/Cons_inicio.php',
			type: 'post',
			success: function (response) {
				$("#Cont_ubicacion").html(response);
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

function Cons_ubic(cod, metodo, title) {
	ModalOpen();
	$("#modal_title").text(title);
	$("#contenido_modal").html("");
	var usuario = $("#usuario").val();
	var cliente = $("#c_codigo").val();

	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = {
			"codigo": cod, "cliente": cliente,
			"metodo": metodo, "usuario": usuario
		};
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_ubicacion/views/Add_form.php',
			type: 'post',
			success: function (response) {

				$("#contenido_modal").html(response);
				iniciar_tab(0);

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

function save_ubic() {
	var error = 0;
	var errorMessage = ' ';
	var cliente = $("#c_codigo").val();

	var codigo = $("#ub_codigo").val();
	var status = Status($("#ub_status:checked").val());
	var nombre = $("#ub_nombre").val();
	var region = $("#ub_region").val();
	var zona = $("#ub_zona").val();
	var estado = $("#ub_estado").val();
	var ciudad = $("#ub_ciudad").val();
	var calendario = $("#ub_calendario").val();
	var contacto = $("#ub_contacto").val();
	var cargo = $("#ub_cargo").val();
	var telefono = $("#ub_telefono").val();
	var email = $("#ub_email").val();
	var direccion = $("#ub_direccion").val();

	var latitud = $("#ub_latitud").val();
	var longitud = $("#ub_longitud").val();

	var observ = $("#ub_observ").val();
	var campo01 = $("#ub_campo01").val();
	var campo02 = $("#ub_campo02").val();
	var campo03 = $("#ub_campo03").val();
	var campo04 = $("#ub_campo04").val();

	var proced = "p_clientes_ubic";
	var usuario = $("#usuario").val();
	var metodo = $("#ub_metodo").val();

	if (error == 0) {
		var parametros = "X";
		var parametros = {
			"codigo": codigo, "status": status,
			"nombre": nombre, "region": region,
			"estado": estado, "ciudad": ciudad,
			"calendario": calendario, "zona": zona,
			"contacto": contacto,
			"cargo": cargo, "telefono": telefono,
			"email": email, "direccion": direccion,
			"latitud": latitud, "longitud": longitud,
			"observ": observ,
			"proced": proced, "usuario": usuario,
			"metodo": metodo, "cliente": cliente,
			"campo01": campo01, "campo02": campo02,
			"campo03": campo03, "campo04": campo04
		};
		console.log(parametros);
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_ubicacion/modelo/ubicacion.php',
			type: 'post',
			success: function (response) {
				CloseModal();
				var cliente = $("#c_codigo").val();
				var metodo = $("#c_metodo").val();
				Cons_cliente(cliente, metodo);
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

function Borrar_ubic(cod) {
	var usuario = $("#usuario").val();
	var parametros = {
		"codigo": cod, "tabla": "clientes_ubicacion",
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
				Cons_ubicacion_inicio();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}