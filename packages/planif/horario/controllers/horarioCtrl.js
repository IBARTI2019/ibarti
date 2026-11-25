$(function () {
	Cons_horario("", "agregar");
});

function Cons_horario(cod, metodo) {
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
			url: 'packages/planif/horario/views/Add_form.php',
			type: 'post',
			success: function (response) {
				$("#Cont_horario").html(response);
				if(metodo == "modificar"){
					//Desabilito el campo codigo, para evitar errores de INSERT
					$("#h_codigo").attr('disabled',true);
					//Si el metodo es modificar muestro los botones AGREGAR Y ELIMINAR
					$('#borrar_horario').show();
					$('#agregar_horario').show();
				}
			}
			,
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	} else {
		alert(errorMessage);
	}
}

function save_horario() {
	var error = 0;
	var errorMessage = ' ';
	var proced = "p_horario";

	var codigo = $("#h_codigo").val();
	var nombre = $("#h_nombre").val();
	var concepto = $("#h_concepto").val();
	var h_entrada = $("#h_entrada").val();
	var h_salida = $("#h_salida").val();
	var inicio_m_entrada = $("#inicio_m_entrada").val();
	var fin_m_entrada = $("#fin_m_entrada").val();
	var inicio_m_salida = $("#inicio_m_salida").val();
	var fin_m_salida = $("#fin_m_salida").val();
	var dia_trabajo = $("#dia_trabajo").val();
	var minutos_trabajo = $("#minutos_trabajo").val();
	var horas_trabajo = $("#horas_trabajo").val();
	var tipo_hora_extra = $("#tipo_hora_extra").val();
	var status;
	if( $('#activo').is(':checked') ) {
    	status = 'T';
	}else{
		status = 'F';
	}
	var usuario = $("#usuario").val();
	var metodo = $("#h_metodo").val();

	if (error == 0) {
		var parametros = "X";
		var parametros = {
			"codigo": codigo, "status": status,
			"nombre": nombre, "concepto": concepto,
			"h_entrada": h_entrada, "h_salida": h_salida,
			"inicio_m_entrada": inicio_m_entrada, "fin_m_entrada": fin_m_entrada,
			"inicio_m_salida": inicio_m_salida, "fin_m_salida": fin_m_salida,
			"dia_trabajo": dia_trabajo, "minutos_trabajo": minutos_trabajo,
			"horas_trabajo": horas_trabajo, "tipo_hora_extra": tipo_hora_extra,
			"proced": proced, "usuario": usuario,
			"metodo": metodo
		};

		$.ajax({
			data: parametros,
			url: 'packages/planif/horario/modelo/horario.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp.error) {
					alert(resp.mensaje);
				} else {
					if(metodo == "agregar") {
						if(confirm("Actualización Exitosa!.. \n Desea AGREGAR un NUEVO REGISTRO?")){
							Cons_horario("", "agregar");
						}else{
							//Cambio el metodo a 'modificar'
							$("#h_metodo").val('modificar');
							//Cambio el titulo de Agregar X a Modificar X
							var string = $("#title_horario").text().split("Agregar").join("Modificar");
							$("#title_horario").text(string);
							//Desabilito el campo Código hasta que el formulario sufra cambios
							$("#h_codigo").attr('disabled',true);
							//Ya que el metodo es modificar muestro los botones AGREGAR Y ELIMINAR
							$('#borrar_horario').show();
							$('#agregar_horario').show();
						}
					}else if(metodo == "modificar"){
						alert("Actualización Exitosa!..");
					}
					//Cambio la propiedad type del boton de RESTABLECER de reset a button
					//para Evitar errores de historial y a su evento click le una funcion que cargue
					//los datos de el registro que contenga el codigo actual y asi restablecer los valores
					$('#limpiar_horario').prop('type', 'button');
					$("#limpiar_horario").click(function(){ Cons_horario(codigo, 'modificar'); });
					//Paso a false el input que almacena el valor que indica si el formulario a sufrido cambios
					$("#h_cambios").val('false');
					//Desabilito el boton guardar hasta que el formulario sufra cambios
					$('#salvar_horario').attr('disabled',true);
					//Reinicio la tabla de busqueda para que se muestre con la modificacion,
					//con el parametro en true para no cambiar de vista.
					buscar_horario(true);
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

function Borrar_horario() {
	if(confirm('Esta seguro que desea BORRAR este Registro?..')){
		var usuario = $("#usuario").val();
		var cod = $("#h_codigo").val();
		var parametros = {
			"codigo": cod, "tabla": "horarios",
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
					//Paso el parametro false para que cambie de vista buscar_horario(isBuscar)->if(!isBuscar){...
						buscar_horario(false);
					alert('Registro Eliminado con exito!..');

					//cambio la funcion del evento click del boton Volver para evitar errores de historial
					//volver_horario -> Cons_horario('', 'agregar'), para asi no mostrar los datos del registro antes
					//eliminado y mostrar la vista Agregar X
					$("#volver_horario").click(function(){ Cons_horario('', 'agregar'); });
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
}

function buscar_horario(isBuscar) {
	var data = $('#data_buscar_horario').val() || '';
	var filtro = $('#filtro_buscar_horario').val() || '';
	$.ajax({
		data: {'data':data,'filtro':filtro},
		url: 'packages/planif/horario/views/Buscar_horarios.php',
		type: 'post',
		beforeSend: function(){
			//Deshabilito el submit del form Buscar X
			$('#buscarHorario').attr('disabled',true);
			//Deshabilito el boton(img) de form Buscar X
			$('#buscarH').attr('disabled',true);
			//Cambio la imagen del boton Buscar X
			$('#buscarH').prop('src',"imagenes/loading3.gif");
		},
		success: function (response) {
			// Oculto la vista Agregar X y Muestro la vista Buscar X
			//solo cuando estoy fuera de la vista Buscar X (lógico)
			if(!isBuscar){
				$('#add_horario').hide();
				$('#buscar_horario').show();
			}
			//Limpio el cuerpo de la tabla con los horarios y pinto el resultado de la busqueda
			$("#lista_horarios tbody tr").remove();
			$("#lista_horarios tbody").html(response);
			//habilito el boton(img) de form Buscar X
			$('#buscarHorario').attr('disabled',false);
			//Habilito el submit del form Buscar X
			$('#buscarH').attr('disabled',false);
			//Restablesco la imagen por defecto del boton buscar X
			$('#buscarH').prop('src',"imagenes/buscar.bmp");
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);

			$('#buscarHorario').attr('disabled',false);
			$('#buscarH').attr('disabled',false);
			$('#buscarH').prop('src',"imagenes/buscar.bmp");
		}
	});
}

//Funcion para ir a la vista Agregar, cuanto se esta en Modificar X
function irAAgregarHorario(){
	var msg = "Desea Agregar un NUEVO REGISTRO?.. ";
	//Obtengo el valor que define si el formulario a sufrido cambios, para definir el mensaje de confirmacion
	var cambiosSinGuardar =  $("#h_cambios").val();
	if(cambiosSinGuardar == "true") msg = 'Puede haber cambios sin guardar en el formulario, ¿Desea agregar un NUEVO Registro de todas formas?.';
	if(confirm(msg)) Cons_horario('', 'agregar');
}

////Funcion para ir a la vista Agregar, cuanto se esta en
//Buscar X y previamente no se ha modificado ningún registro
function volverHorario(){
	// Oculto la vista Buscar X y Muestro la vista Agregar X
	$('#add_horario').show();
	$('#buscar_horario').hide();
}

function irABuscarHorario(){
	// Oculto la vista Agregar X y Muestro la vista Buscar X
	$('#add_horario').hide();
	$('#buscar_horario').show();
}
