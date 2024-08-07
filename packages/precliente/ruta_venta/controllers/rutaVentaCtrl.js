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


function cerrar_venta() {
	if(confirm(
		`	Esta seguro de que desea cerrar la ruta de venta de este precliente.
	Al hacer este se registrará automáticamente un cliente con los datos de este precliente.
	Este proceso no se puede revertir.`
	)){
		var error = 0;
		var errorMessage = " ";
		var proced = "p_clientes";

		var codigo = $("#c_codigo").val();
		var abrev = $("#c_abrev").val();
		var juridico = Status($("#c_juridico:checked").val());
		var rif = $("#c_rif").val();
		var nit = $("#c_nit").val();
		var contrib = Status($("#c_contrib:checked").val());
		var nombre = $("#c_nombre").val();
		var telefono = $("#c_telefono").val();
		var fax = $("#c_fax").val();
		var cl_tipo = $("#c_cl_tipo").val();
		var region = $("#c_region").val();
		var vendedor = $("#c_vendedor").val();
		var email = $("#c_email").val();
		var website = $("#c_website").val();
		var contacto = $("#c_contacto").val();
		var direccion = $("#c_direccion").val();
		var observ = $("#c_observ").val();

		var limite_cred = $("#c_limite_cred").val();
		var plazo_pago = $("#c_plazo_pago").val();
		var desc_p_pago = $("#c_desc_p_pago").val();
		var desc_global = $("#c_desc_global").val();
		var dir_entrega = $("#c_dir_entrega").val();

		var campo01 = $("#c_campo01").val();
		var campo02 = $("#c_campo02").val();
		var campo03 = $("#c_campo03").val();
		var campo04 = $("#c_campo04").val();

		var latitud = $("#c_latitud").val();
		var longitud = $("#c_longitud").val();
		var direccion_google = $("#c_direccion_google").val();

		var lunes = Status($("#c_lunes:checked").val());
		var martes = Status($("#c_martes:checked").val());
		var miercoles = Status($("#c_miercoles:checked").val());
		var jueves = Status($("#c_jueves:checked").val());
		var viernes = Status($("#c_viernes:checked").val());
		var sabado = Status($("#c_sabado:checked").val());
		var domingo = Status($("#c_domingo:checked").val());

		var activo = Status($("#c_activo:checked").val());
		var usuario = $("#usuario").val();

		if (error == 0) {
		var parametros = {
			codigo: codigo,
			activo: activo,
			rif: rif,
			nit: nit,
			juridico: juridico,
			contrib: contrib,
			abrev: abrev,
			nombre: nombre,
			telefono: telefono,
			fax: fax,
			cl_tipo: cl_tipo,
			region: region,
			vendedor: vendedor,
			email: email,
			website: website,
			contacto: contacto,
			direccion: direccion,
			observ: observ,
			limite_cred: limite_cred,
			plazo_pago: plazo_pago,
			desc_p_pago: desc_p_pago,
			desc_global: desc_global,
			dir_entrega: dir_entrega,
			lunes: lunes,
			martes: martes,
			miercoles: miercoles,
			jueves: jueves,
			viernes: viernes,
			sabado: sabado,
			domingo: domingo,
			campo01: campo01,
			campo02: campo02,
			campo03: campo03,
			campo04: campo04,
			proced: proced,
			usuario: usuario,
			metodo: "agregar",
			latitud: latitud,
			longitud: longitud,
			direccion_google: direccion_google
		};

		$.ajax({
			data: parametros,
			url: "packages/precliente/cliente/modelo/cerrar_ruta_venta.php",
			type: "post",
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp.error) {
				alert(resp.mensaje);
				} else {
					$("#codPDF").val(codigo);
					alert("Actualización Exitosa!..");
					$("#limpiar_precliente").prop("type", "button");
					$("#limpiar_precliente").click(function () {
						Cons_precliente(codigo, "modificar");
					});
					buscar_precliente(true);
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
