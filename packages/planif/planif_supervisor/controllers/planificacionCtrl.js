var cliente = '';
var usuario = '';
var apertura = '';
var region = '';
var cargo = '';
var supervision = '';
var ubicacion = '';
var calendar = null;
var calendarSuperv = null;
var calendarTrab = null;
var cerrar = true;
var eventActual = null;
var fecha_fin = null;
var actividades = [];
var metodo = "agregar";
var typeCalendar = "timeGridWeek";
var isafter = true;
var hoy = new Date();

$(function () {
	Cons_planificacion_inicio();
});

function Habilitar_supervision() {
	$('#supervision_texto').css("display", "block");
	$('#supervision_cont').css("display", "block");
}
function Ocultar_apertura() {
	$('#apertura_texto').css("display", "none");
	$('#apertura_cont').css("display", "none");
	$('#planf_apertura').val("");
}
function Habilitar_apertura() {
	$('#apertura_texto').css("display", "block");
	$('#apertura_cont').css("display", "block");
}
function Ocultar_region() {
	$('#region_texto').css("display", "none");
	$('#region_cont').css("display", "none");
}
function Habilitar_region() {
	$('#region_texto').css("display", "block");
	$('#region_cont').css("display", "block");
}

function Ocultar_ubicacion() {
	$('#ubicacion_texto').css("display", "none");
	$('#ubicacion_cont').css("display", "none");
	$('#planf_ubicacion').val("");
}
function Habilitar_ubicacion() {
	$('#ubicacion_texto').css("display", "block");
	$('#ubicacion_cont').css("display", "block");
}

function Ocultar_cargo() {
	$('#cargo_texto').css("display", "none");
	$('#cargo_cont').css("display", "none");
	$('#planf_cargo').val("");
}
function Habilitar_cargo() {
	$('#cargo_texto').css("display", "block");
	$('#cargo_cont').css("display", "block");
}

function Ocultar_all() {
	Ocultar_apertura();
	//Ocultar_region();
	Ocultar_cargo();
	Ocultar_ubicacion();
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
			url: 'packages/planif/planif_supervisor/views/Cons_inicio.php',
			type: 'post',
			beforeSend: function () {
				$("#Cont_planificacion").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
			},
			success: function (response) {
				$("#Cont_planificacion").html(response);
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

function ac_apertura_planif(parametros, callback) {
	parametros.metodo = "modificar_apertura";
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/modelo/planificacion_apertura.php',
		type: 'post',
		success: function (response) {
			var resp = JSON.parse(response);
			if (resp.error == false) {
				toastr.success("Guardado exitosamente!.");
				if (typeof (callback) == "function") {
					callback();
				}
			} else {
				toastr.error("Se ha detectado un error!.")
			}

		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function validarIngreso(apertura, cliente, ubic, actividades, fecha, hora_inicio, hora_fin, cod_ficha, callback) {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = { apertura, cliente, ubic, actividades, fecha, hora_inicio, hora_fin, cod_ficha };
		$.ajax({
			data: parametros,
			url: 'packages/planif/planif_supervisor/views/validarIngreso.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				callback(resp);
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

function validarFecha(fecha, cliente, apertura, ubicacion, cod_ficha, callback) {
	var error = 0;
	var errorMessage = ' ';
	if (error == 0) {
		var parametros = { fecha, cliente, apertura, ubicacion, cod_ficha, cargo };
		$.ajax({
			data: parametros,
			url: 'packages/planif/planif_supervisor/views/validarFecha.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				callback(resp);
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

function cl_apertura() {
	var parametros = { "ubic": ubicacion, "cargo": cargo };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_apertura.php',
		type: 'post',
		beforeSend: function () {
			$("#planf_apertura").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#planf_apertura").html(response);
			Habilitar_apertura();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_regiones() {
	var parametros = { "cliente": cliente };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_regiones.php',
		type: 'post',
		beforeSend: function () {
			$("#planf_region").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#planf_region").html(response);
			Habilitar_region();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_cargos(ubic) {
	var parametros = { "cliente": cliente, "ubic": ubic };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_cargos.php',
		type: 'post',
		beforeSend: function () {
			$("#planf_cargo").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#planf_cargo").html(response);
			Habilitar_cargo();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function verificar_cl(cl, modal) {
	usuario = $("#usuario").val();

	if (cl == '') {
		Ocultar_all();
		var errorMessage = 'Debe Seleccionar Un Cliente';
		alert(errorMessage);
		cliente = cl;
	} else {
		$("#cont_supervision_det").html("");
		Ocultar_apertura();
		//Ocultar_region();
		Ocultar_ubicacion();
		Ocultar_cargo();
		cliente = cl;
		var parametros = { "codigo": cliente };
		$.ajax({
			data: parametros,
			url: 'packages/planif/planif_supervisor/modelo/verificar_cl.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp[0]['contra'] == 0) {
					if (!modal) {
						Ocultar_all();
						B_supervision();
					}
				} else {
					cargar_ubicaciones(cliente);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
}

function cargar_actividades(proyecto, ficha, callback) {
	var parametros = { proyecto, ficha };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_actividades.php',
		type: 'post',
		success: function (response) {
			//$("#planf_actividadRP").html(response);
			var resp = JSON.parse(response);
			updateFecFin(eventActual);
			if (typeof callback == "function") {
				callback(resp);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_ubicaciones() {
	//var parametros = { "cliente": cliente, "region": region, "cargo": cargo };
	var parametros = { "cliente": cliente, "cargo": cargo};
	parametros["usuario"] = $("#usuario").val();
	parametros['r_cliente'] = $("#r_cliente").val();
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_ap_ubic.php',
		type: 'post',
		beforeSend: function () {
			$("#planf_ubicacion").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			Habilitar_ubicacion();
			$("#planf_ubicacion").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_planif_superv(ubic, c) {
	ubicacion = ubic;
	cliente = $("#planf_cliente").val();
	ap = $("#planf_apertura").val();
	//region = $("#planf_region").val();
	cargo = c;
	$("#cont_supervision_det").html("");
	$("#cont_planif_det").html("");
	if (ubic == '') {
		Ocultar_cargo();
		Ocultar_apertura();
		if (calendar) {
			calendar.destroy();
		}
	} else {
		if (cargo == '') {
			Ocultar_apertura();
			if (calendar) {
				calendar.destroy();
			}
		} else {
			/* 		if (ap == "" || ap == undefined) {
						if (ubic == "" || ubic == undefined) {
							cargar_ubicaciones();
						} else {
												if (region == "" || region == undefined) {
												$("#cont_planif_det").html("");
												Ocultar_cargo();
												Ocultar_apertura();
											} */
			if ((cargo == '' || cargo == undefined) && (ap == '' || ap == undefined)) {
				cargar_cargos(ubic);
			} else if (ap == '' || ap == undefined) {
				cl_apertura();
			}
			/* }
		}
				else {
					if (region == "" || region == undefined) {
						$("#cont_planif_det").html("");
						Ocultar_cargo();
						Ocultar_apertura();
					}
				} */
			//if (ap !== '' && ap !== undefined && region !== '' && region !== undefined) {
			if (ap !== '' && ap !== undefined) {
				apertura = ap;
				cargar_supervision_det(cliente, ubic, cargo);
				cargar_planif_superv_det(apertura);
			}
		}
	}
}

function cargar_supervision_det(cliente, ubic, cargo) {
	var parametros = {
		"cliente": cliente,
		"ubic": ubic,
		"cargo": cargo,
		"usuario": usuario
	};
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_supervision_det.php',
		type: 'post',
		beforeSend: function () {
			$("#cont_supervision_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#cont_supervision_det").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function trab_sin_planificar() {
	var cliente = $("#planf_cliente").val();
	if (cliente && apertura) {
		var parametros = {
			"cliente": cliente, 'apertura': apertura
		};
		$.ajax({
			data: parametros,
			url: 'packages/planif/planif_supervisor/views/Add_trab_sin_planif.php',
			type: 'post',
			success: function (response) {
				ModalOpen();
				$("#modal_titulo").text("Trabajadores sin Planificacion");
				$("#modal_contenido").html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	} else {
		toastr.warning('Debe seleccionar el cliente y la apertura.')
	}
}

function cantidad_trab_sin_planificar(cliente, apertura) {
	var parametros = {
		"cliente": cliente,
		'apertura': apertura
	};
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_cant_trab_sin_planif.php',
		type: 'post',
		success: function (response) {
			var resp = JSON.parse(response);
			$("#cantidad_sin_planif").text(resp.cantidad);
			//cargar_calendario();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_planif_superv_det(apertura) {
	if (calendar) {
		calendarSuperv.destroy();
		calendar.destroy();
	}
	//region = $("#planf_region").val();
	var parametros = { "codigo": apertura, "cliente": cliente, "usuario": usuario, "ubic": ubicacion, "cargo": cargo };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_det.php',
		type: 'post',
		beforeSend: function () {
			$("#cont_planif_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			var resp = JSON.parse(response);
			$("#cont_planif_det").html(resp["html"]);
			var fechas = resp["fechas"];
			cantidad_trab_sin_planificar(cliente, apertura);
			var containerEx = document.getElementById('external-events-list');
			calendarSuperv = new FullCalendar.Draggable(containerEx, {
				itemSelector: '.fc-event',
				eventData: function (eventEl) {
					return {
						title: eventEl.innerText.trim(),
						extendedProps: {
							codigo: "",
							cod_ficha: eventEl.getAttribute('cod_ficha'),
							cedula: eventEl.getAttribute('cedula')
						},
						allDay: false,
						stick: true
					}
				},
				create: false,
				droppable: false
			});

			var calendarEl = document.getElementById('calendar');
			calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: typeCalendar,
				headerToolbar: { center: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth' },
				views: {
					listMonth: {
						editable: false,
						selectable: true,
						eventContent: function (arg) {
							var result = "<label>En proceso</label>";
							if (arg.event.id) {
								result = "<div>(" + arg.event.extendedProps.codigo + ") " + moment(arg.event.start).format("HH: mm") + " - " + moment(arg.event.end).format("HH: mm") + '<br>';
								result += "<label>" + arg.event.title + "</label><br>";
								result += arg.event.extendedProps.ubicacion + "<br>";
								var codigo_proyecto = arg.event.extendedProps.actividades[0].cod_proyecto;
								var index = 1;
								arg.event.extendedProps.actividades.forEach((act, i) => {
									if ((i != 0) && (act.cod_proyecto != codigo_proyecto)) {
										index = 1;
										codigo_proyecto = act.cod_proyecto;
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									} else if (i == 0) {
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									}
									result += "<span>" + index + ": " + act.actividad + "  (" + moment(act.fecha_inicio_act).format('HH:mm:ss') + " - " + moment(act.fecha_fin_act).format('HH:mm:ss') + ") </span><br>";
									index++;
								});
							}
							result += "</div>";
							return {
								html: result
							}
						},
					},
					timeGridDay: {
						editable: false,
						slotDuration: '00:05:00',
						eventContent: function (arg) {
							var result = "<label>En proceso</label>";
							if (arg.event.id) {
								result = "<div>(" + arg.event.extendedProps.codigo + ") " + moment(arg.event.start).format("HH: mm") + " - " + moment(arg.event.end).format("HH: mm") + ' - ';
								result += "<label>" + arg.event.title + "</label> <br>";
								result += arg.event.extendedProps.ubicacion + " <br> ";
								var codigo_proyecto = arg.event.extendedProps.actividades[0].cod_proyecto;
								var index = 1;
								arg.event.extendedProps.actividades.forEach((act, i) => {
									if ((i != 0) && (act.cod_proyecto != codigo_proyecto)) {
										index = 1;
										codigo_proyecto = act.cod_proyecto;
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									} else if (i == 0) {
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									}
									result += "<span>" + index + ": " + act.actividad + "</span><br>";
									index++;
								});
							}
							result += "</div>";
							return {
								html: result
							}
						},
					},
					dayGridMonth: { // name of view
						titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' },
						showNonCurrentDates: false,
						editable: true,
						selectable: true,
						eventContent: function (arg) {
							var result = "<label>En proceso</label>";
							if (arg.event.id) {
								result = "<div class='fc-event-main'>(" + arg.event.extendedProps.codigo + ") " + moment(arg.event.start).format("HH: mm") + " - " + moment(arg.event.end).format("HH: mm") + '<br>';
								result += "<label>" + arg.event.title + "</label><br>";
								result += arg.event.extendedProps.ubicacion + "<br>";
								var codigo_proyecto = arg.event.extendedProps.actividades[0].cod_proyecto;
								var index = 1;
								arg.event.extendedProps.actividades.forEach((act, i) => {
									if ((i != 0) && (act.cod_proyecto != codigo_proyecto)) {
										index = 1;
										codigo_proyecto = act.cod_proyecto;
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									} else if (i == 0) {
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									}
									result += "<span>" + index + ": " + act.actividad + "</span><br>";
									index++;
								});
							}
							result += "</div>";
							return {
								html: result
							}
						},
					},
					timeGridWeek: {
						showNonCurrentDates: false,
						editable: false,
						selectable: true,
						eventContent: function (arg) {
							var result = "<label>En proceso</label>";
							if (arg.event.id) {
								result = "<div>(" + arg.event.extendedProps.codigo + ") " + arg.event.extendedProps.ubicacion + " ";
								result += "<label>" + arg.event.title + "</label>"
							}
							result += "</div>";
							return {
								html: result
							}
						},
					},
				},
				eventDidMount: function (info) {
					eventActual = info.event;
					if (!isafter) {
						eventActual.remove();
					}
				},
				initialDate: fechas.fecha_inicio,
				navLinks: true,
				dayMaxEvents: true,
				locale: 'es',
				validRange: {
					start: fechas.fecha_inicio,
					end: moment(fechas.fecha_fin).add(1, 'days').format('YYYY-MM-DD')
				},
				allDaySlot: false,
				slotEventOverlap: true,
				dateClick: function (info) {
					//calendar.formatIso(info.date)
				},
				eventClick: function (arg) {
					eventActual = arg.event;
					metodo = "modificar";
					var isafter = moment(arg.dateStr).isSameOrAfter(moment(hoy).format("YYYY-MM-DD"));
					if (isafter) {
						$("#guardarActividad").show();
					} else {
						$("#guardarActividad").hide();
					}
					editarActividad(eventActual);
					/* 		
						if (confirm('Are you sure you want to delete this event?')) {
							arg.event.remove()
						} 
					*/
				},
				eventResize: function (arg) {
					actualizarEvento(arg);
				},
				eventDrop: function (arg) {
					actualizarEvento(arg);
				},
				selectMirror: true,
				select: function (arg) {
					//("arg.start ", arg.start);
				},
				eventAllow: function (dropInfo, draggedEvent) {
					return moment(dropInfo.start).isSameOrAfter(moment(hoy).format("YYYY-MM-DD")) && ((draggedEvent.start && moment(draggedEvent.start).isSameOrAfter(moment(hoy).format("YYYY-MM-DD")) || (draggedEvent.start === null))) && moment(draggedEvent.start).format("YYYY-MM-DD") != moment(dropInfo.start).format("YYYY-MM-DD");
				},
				editable: true,
				nowIndicator: true,
				height: 'auto',
				drop: function (arg) {
					var cod_ficha = arg.draggedEl.getAttribute('cod_ficha');
					isafter = moment(arg.dateStr).isSameOrAfter(moment(hoy).format("YYYY-MM-DD"));
					if (isafter) {
						//region = $("#planf_region").val();
						validarFecha(moment(arg.date).format("YYYY-MM-DD"), cliente, apertura, ubicacion, cod_ficha, (fechas) => {
							if (fechas.data.length > 0) {
								$("#planf_ubicacionRP").html("");
								$("#planf_ubicacionRP").append('<option value="">Seleccione</option>');
								var hora_entrada = fechas.data[0].hora_entrada;
								var hora_salida = fechas.data[0].hora_salida;
								$("#planf_horaRP").prop("min", hora_entrada);
								$("#planf_hora_finRP").prop("min", hora_entrada);
								$("#planf_hora_finRP").prop("max", hora_salida);
								$("#dias_habilesRP").html(fechas.data[0].dias_habiles);
								fechas.data.forEach((f) => {
									if (f.hora_entrada < hora_entrada) {
										hora_entrada = f.hora_entrada;
										$("#planf_horaRP").prop("min", hora_entrada);
										$("#planf_hora_finRP").prop("min", hora_entrada);
									}
									if (f.hora_salida > hora_salida) {
										hora_salida = f.hora_salida;
										$("#planf_hora_finRP").prop("max", hora_salida);
									}
								});
								fechas.datacliente.forEach((f) => {
									$("#planf_ubicacionRP").append('<option value=' + f.cod_ubicacion + '>' + f.ubicacion + ' (' + hora_entrada + ' - ' + hora_salida + ')</option>');
								});
								metodo = "agregar";
								modalActividad();
							} else {
								toastr.error(fechas.msg);
								eventActual.remove();
							}
						});
						$("#guardarActividad").show();
					} else {
						toastr.info("No es posible planificar actividades sobre fechas pasadas.");
					}
				},
				dayMaxEvents: true,
				dayHeaderFormat: { weekday: 'short' },
			});
			res_eventos = d3.nest()
				.key((d) => d.codigo)
				.entries(resp["data"]);
			res_eventos.forEach(d => {
				calendar.addEvent({
					id: d.key,
					title: d.values[0].trabajador + " (" + d.values[0].cod_ficha + ")",
					start: d.values[0].fecha_inicio,
					end: d.values[0].fecha_fin,
					extendedProps: {
						codigo: d.key,
						cod_ficha: d.values[0].cod_ficha,
						cedula: d.values[0].cedula,
						trabajador: d.values[0].trabajador,
						cod_cargo: d.values[0].cod_cargo,
						cargo: d.values[0].cargo,
						proyecto: d.values[0].proyecto,
						cod_proyecto: d.values[0].cod_proyecto,
						abrev_proyecto: d.values[0].abrev_proyecto,
						actividades: d.values,
						cod_cliente: d.values[0].cod_cliente,
						cliente: d.values[0].cliente,
						ubicacion: d.values[0].ubicacion,
						cod_ubicacion: d.values[0].cod_ubicacion,
						completado: (d.values[0].completado === 'T')
					},
				});
			});
			calendar.render();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_planif_superv_trab_det(ficha) {
	if (calendarTrab) {
		calendarTrab.destroy();
	}
	var parametros = { "codigo": apertura, "ubic": ubicacion, "ficha": ficha, "cargo": cargo, "usuario": usuario };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_trab_det.php',
		type: 'post',
		success: function (response) {
			var resp = JSON.parse(response);
			if (resp["data"].length > 0) {
				var fechas = resp["fechas"];
				var calendarTrabEl = document.getElementById('calendarTrab');
				calendarTrab = new FullCalendar.Calendar(calendarTrabEl, {
					initialView: "listMonth",
					views: {
						listMonth: {
							eventContent: function (arg) {
								var result = "<div>(" + arg.event.extendedProps.codigo + ") " + moment(arg.event.start).format("HH: mm") + " - " + moment(arg.event.end).format("HH: mm") + '<br>';
								result += arg.event.title + "<br>";
								result += arg.event.extendedProps.cliente + " - " + arg.event.extendedProps.ubicacion + "<br>";
								var codigo_proyecto = arg.event.extendedProps.actividades[0].cod_proyecto;
								var index = 1;
								arg.event.extendedProps.actividades.forEach((act, i) => {
									if ((i != 0) && (act.cod_proyecto != codigo_proyecto)) {
										index = 1;
										codigo_proyecto = act.cod_proyecto;
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									} else if (i == 0) {
										result += "<label>" + act.proyecto + " (" + act.abrev_proyecto + ")</label><br>";
									}
									result += "<span>" + index + ": " + act.actividad + "  (" + moment(act.fecha_inicio_act).format('HH:mm:ss') + " - " + moment(act.fecha_fin_act).format('HH:mm:ss') + ") </span><br>";
									index++;
								});
								result += "</div>";
								return {
									html: result
								}
							},
						},
					},
					editable: false,
					selectable: false,
					initialDate: fechas.fecha_inicio,
					navLinks: true,
					dayMaxEvents: true,
					locale: 'es',
					validRange: {
						start: fechas.fecha_inicio,
						end: moment(fechas.fecha_fin).add(1, 'days').format('YYYY-MM-DD')
					},
					allDaySlot: false,
					slotEventOverlap: true,
					selectMirror: true,
					nowIndicator: true,
					height: 'auto',
					dayMaxEvents: true,
					dayHeaderFormat: { weekday: 'short' },
				});
				var res_eventos = d3.nest()
					.key((d) => d.codigo)
					.entries(resp["data"]);
				$("#titulo_detalle_trab").html("Agenda de " + res_eventos[0].values[0].trabajador);
				res_eventos.forEach(d => {
					calendarTrab.addEvent({
						id: d.key,
						title: d.values[0].trabajador,
						start: d.values[0].fecha_inicio,
						end: d.values[0].fecha_fin,
						extendedProps: {
							codigo: d.key,
							cod_ficha: d.values[0].cod_ficha,
							cedula: d.values[0].cedula,
							trabajador: d.values[0].trabajador,
							proyecto: d.values[0].proyecto,
							cod_proyecto: d.values[0].cod_proyecto,
							abrev_proyecto: d.values[0].abrev_proyecto,
							actividades: d.values,
							cod_cliente: d.values[0].cod_cliente,
							cliente: d.values[0].cliente,
							ubicacion: d.values[0].ubicacion,
							cod_ubicacion: d.values[0].cod_ubicacion,
							completado: (d.values[0].completado === 'T')
						},
					});
				});
				calendarTrab.render();
			} else {
				$("#titulo_detalle_trab").html("Sin Agenda");
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function B_supervision() {
	var error = 0;
	var errorMessage = ' ';
	var parametros = { "codigo": cliente, "usuario": usuario };

	if (error == 0) {
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_supervision/index.php',
			type: 'post',
			success: function (response) {
				ModalOpen();
				$("#modal_titulo").text("Supervision");
				$("#modal_contenido").html(response);
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

function B_planif_apertura() {
	var parametros = { "usuario": usuario, "ubic": ubicacion, "cargo": cargo, "supervision": supervision };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_apertura_ing.php',
		type: 'post',
		success: function (response) {
			ModalOpen();
			$("#modal_titulo").text("Ingresar Apertura");
			$("#modal_contenido").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cerrarModal() {
	$("#myModal").hide();
	var cliente = $("#planf_cliente").val();
	var cargo = $("#planf_cargo").val();
	if (cliente && cargo) {
		verificar_cl(cliente, true);
	}
}

function Cons_Apertura() {
	var parametros = { "cliente": cliente, "supervision": supervision };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Cons_planif_apertura.php',
		type: 'post',
		success: function (response) {
			$("#modal_contenido").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

// Planificacion de trabajo detalle por trabajador  Agregar Modificar Eliminar
function Cerrar_ap_planif(cod, status) {
	if (status == "T") {
		if (confirm("¿ Esta Seguro De Cerrar esta Planificación (" + cod + ") ?")) {
			var parametros = {
				"codigo": cod,
				"metodo": "cerrar", "usuario": usuario
			}
			$.ajax({
				data: parametros,
				url: 'packages/planif/planif_supervisor/modelo/planificacion_apertura.php',
				type: 'post',
				success: function (response) {
					var resp = JSON.parse(response);
					if (resp.error) {
						alert(resp.mensaje);
					} else {
						// Actualizo Datos De apertura
						Cons_Apertura();
						cl_apertura();
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


function B_planif_trab() {
	var ubic = $("#planf_ubicacion").val();
	var client = $("#planf_cliente").val();
	var parametros = {
		"codigo": apertura, "ubicacion": ubic, "cliente": client,
		"usuario": usuario
	};
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_planif_trab.php',
		type: 'post',
		success: function (response) {
			ModalOpen();
			$("#modal_titulo").text("Agregar Trabajador");
			$("#modal_contenido").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

// Planificacion de trabajo detalle por trabajador  Agregar Modificar Eliminar
function saveActividad() {
	$("#guardar_actividad").attr("disabled", true);
	props = eventActual.extendedProps;
	var error = 0;
	var errorMessage = "";
	if (!ubicacion) {
		error++;
		errorMessage += "La ubicación es obligatoria";
	}

	if (actividades.length == 0) {
		error++;
		errorMessage += "Debe agregar actividades al evento";
	}
	var hora_inicio = $("#planf_horaRP").val();
	var hora_fin = $("#planf_hora_finRP").val();
	var fechaQuery = moment(eventActual.start).format("YYYY-MM-DD");
	var fecha_inicio = fechaQuery + " " + hora_inicio;
	var fecha_fin = fechaQuery + " " + hora_fin;

	if (!fecha_inicio) {
		error++;
		errorMessage += "La fecha inicial es obligatoria";
	}
	if (error == 0) {
		if (metodo == "agregar") {
			validarIngreso(apertura, cliente, ubicacion, actividades, fechaQuery, hora_inicio, hora_fin, props.cod_ficha, (valid) => {
				if (valid.error) {
					toastr.error(valid.msg);
					$("#guardar_actividad").attr("disabled", false);
				} else {
					var parametros = {
						"codigo": props.codigo, "fecha_inicio": fecha_inicio, "fecha_fin": fecha_fin,
						"cliente": cliente, "ubicacion": ubicacion, "ficha": props.cod_ficha, 'apertura': apertura,
						"actividades": actividades, "metodo": metodo, "usuario": usuario
					}
					$.ajax({
						data: parametros,
						url: 'packages/planif/planif_supervisor/modelo/actividad_det.php',
						type: 'post',
						success: function (response) {
							var resp = JSON.parse(response);
							if (resp.error) {
								toastr.error(resp.mensaje);
							} else {
								toastr.success('Actualizacion Exitosa!..');
								$('#modalRP').hide();
								typeCalendar = calendar.view.type;
								cargar_planif_superv_det(apertura);
							}
							$("#guardar_actividad").attr("disabled", false);
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(xhr.status);
							alert(thrownError);
							$("#guardar_actividad").attr("disabled", false);
						}
					});
				}
			});
		} else {
			var parametros = {
				"codigo": props.codigo, "fecha_inicio": fecha_inicio, "fecha_fin": fecha_fin,
				"cliente": cliente, "ubicacion": ubicacion, "ficha": props.cod_ficha, 'apertura': apertura,
				"actividades": actividades, "metodo": metodo, "usuario": usuario
			};
			$.ajax({
				data: parametros,
				url: 'packages/planif/planif_supervisor/modelo/actividad_det.php',
				type: 'post',
				success: function (response) {
					var resp = JSON.parse(response);
					if (resp.error) {
						toastr.error(resp.mensaje);
					} else {
						eventActual.setStart(fecha_inicio);
						eventActual.setEnd(fecha_fin);
						toastr.success('Actualizacion Exitosa!..');
						$('#modalRP').hide();
						typeCalendar = calendar.view.type;
						cargar_planif_superv_det(apertura);
					}
					$("#guardar_actividad").attr("disabled", false);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
					$("#guardar_actividad").attr("disabled", false);
				}
			});
		}
	} else {
		toastr.warning(errorMessage);
		$("#guardar_actividad").attr("disabled", false);
	}
}

function mostrar_icono_apertura(valor) {
	if (valor != '') {
		$('#mod_ap_planif').show();

	} else {
		$('#mod_ap_planif').hide();
	}
}

function cancelarActividad() {
	if (metodo === "agregar") {
		eventActual.remove();
	}
	$('#modalRP').hide();
	$("#guardar_actividad").show();
}

function mostarOcultarActividades(proyecto, valor) {
	if (valor) {
		$("#panel" + proyecto).show();
	} else {
		$("#panel" + proyecto).hide();
	}
}

function parse_act_html(acts) {
	data = d3.nest()
		.key((d) => d.cod_proyecto)
		.entries(acts);
	var proyectos_html = "";
	var actividades_html = "";

	const existe = (element) => eventActual.extendedProps.actividades.findIndex((el) => el.cod_actividad === element.codigo) != -1;

	const event = (element) => element.obligatoria === 'T';
	data.forEach(d => {
		var checked = "";
		if (d.values.some(event)) {
			checked = 'checked disabled="disabled"';
		} else if (metodo === "modificar") {
			if (d.values.some(existe)) {
				checked = 'checked';
			}
		}
		proyectos_html += '<input type="checkbox" id="proyecto' + d.key + '"' + checked + ' onclick="mostarOcultarActividades(' + d.key + ', this.checked)">' + d.values[0].proyecto_descripcion;
		if (checked === "") {
			actividades_html += '<div id="panel' + d.key + '" hidden="hidden">';
		} else {
			actividades_html += '<div id="panel' + d.key + '">';
		}
		d.values.forEach(act => {
			checked = act.obligatoria === 'T' ? 'checked disabled="disabled"' : "";
			actividades_html += '(' + d.values[0].proyecto_descripcion + ') ' + act.descripcion + ' <input type="checkbox" name="actividades[]" value="' + act.codigo + '" ' + checked + ' id="actividad' + act.codigo + '" minutos="' + act.minutos + '" proyecto="' + act.cod_proyecto + '" onchange="updateFecFin(null)" style="width:auto"> ' + act.minutos + ' min.<br>';
		});
		actividades_html += '</div>';
	});

	$("#planf_proyectoRP").html(proyectos_html);
	$("#planf_actividadRP").html(actividades_html);
	updateFecFin(eventActual);
}
function modalActividad() {
	cargar_actividades(null, eventActual.extendedProps.cod_ficha, (acts) => {
		parse_act_html(acts);
	});
	$("#planf_ubicacionRP").attr("disabled", false);
	$("#planf_proyectoRP").attr("disabled", false);
	$("#planf_fechaRP").html(moment(eventActual.start).format('YYYY-MM-DD'));
	$("#cedulaRP").html(eventActual.extendedProps.cod_ficha + " - " + eventActual.extendedProps.cedula);
	$("#planf_actividadRP").html("");
	$("#planf_horaRP").val(moment(eventActual.start).format("HH:mm:00"));
	$("#planf_hora_finRP").val("");
	$("#fotoRP").attr("src", "imagenes/fotos/" + eventActual.extendedProps.cedula + ".jpg");
	$('#modalRP').show();
	cargar_planif_superv_trab_det(eventActual.extendedProps.cod_ficha);
}

function editarActividad(event) {
	//region = $("#planf_region").val();
	validarFecha(moment(event.start).format("YYYY-MM-DD"), cliente, apertura, ubicacion, event.extendedProps.cod_ficha, (fechas) => {
		if (fechas.data.length > 0) {
			$("#planf_ubicacionRP").html("");
			var hora_entrada = fechas.data[0].hora_entrada;
			var hora_salida = fechas.data[0].hora_salida;
			$("#planf_horaRP").prop("min", hora_entrada);
			$("#planf_hora_finRP").prop("min", hora_entrada);
			$("#planf_hora_finRP").prop("max", hora_salida);
			fechas.data.forEach((f) => {
				if (f.hora_entrada < hora_entrada) {
					var hora_entrada = f.hora_entrada;
					$("#planf_horaRP").prop("min", hora_entrada);
					$("#planf_hora_finRP").prop("min", hora_entrada);
				}
				if (f.hora_salida > hora_salida) {
					var hora_salida = fechas[0].hora_salida;
					$("#planf_hora_finRP").prop("max", hora_salida);
				}
			});
			fechas.datacliente.forEach((f) => {
				$("#planf_ubicacionRP").append('<option value=' + f.cod_ubicacion + '>' + f.ubicacion + '</option>');
			});
			$("#planf_fechaRP").html(moment(event.start).format('YYYY-MM-DD'));
			$("#planf_ubicacionRP").val(event.extendedProps.cod_ubicacion);
			$("#planf_horaRP").val(moment(event.start).format("HH:mm:00"));
			$("#cedulaRP").html(event.extendedProps.cod_ficha + " - " + event.extendedProps.cedula);
			$("#dias_habilesRP").html(fechas.data[0].dias_habiles);
			if (moment(moment(event.start).format("YYYY-MM-DD")).isSameOrAfter(moment(hoy).format("YYYY-MM-DD"))) {
				$("#guardar_actividad").show();
			} else {
				$("#guardar_actividad").hide();
			}
			cargar_actividades(null, event.extendedProps.cod_ficha, (acts) => {
				parse_act_html(acts);
				$('#modalRP').show();
				event.extendedProps.actividades.forEach(act => {
					$("#actividad" + act.cod_actividad).prop("checked", true);
				});
				updateFecFin(event);
			});
			cargar_planif_superv_trab_det(event.extendedProps.cod_ficha);
		} else {
			toastr.error(fechas.msg);
		}
	});
}

function updateFecFin(event) {
	if (event) {
		evt = event;
	} else {
		evt = eventActual;
	}
	var minutos = 0;
	var hora_inicio = $("#planf_horaRP").val();
	var fecha_inicio_temp = null;
	var fecha_fin_temp = null;
	actividades = $('[name="actividades[]"]:checked').map(function () {
		minutos += Number($("#actividad" + this.value).attr("minutos"));
		if (hora_inicio) {
			if (fecha_inicio_temp === null) {
				fecha_inicio_temp = moment(evt.start);
				fecha_inicio_temp = moment(fecha_inicio_temp.format('YYYY-MM-DD') + " " + hora_inicio).format('YYYY-MM-DD HH:mm:ss');
			} else {
				fecha_inicio_temp = moment(fecha_fin_temp).format('YYYY-MM-DD HH:mm:ss');
			}
			fecha_fin_temp = moment(fecha_inicio_temp).add($("#actividad" + this.value).attr("minutos") / 60, "hours").format('YYYY-MM-DD HH:mm:ss');
		}
		return {
			'codigo': this.value, 'cod_proyecto': $("#actividad" + this.value).attr("proyecto"),
			'fecha_inicio': fecha_inicio_temp, 'fecha_fin': fecha_fin_temp
		};
	}).get();
	if (hora_inicio) {
		var fec_start = moment(evt.start);
		fec_start = fec_start.format('YYYY-MM-DD') + " " + hora_inicio;
		fec_start = moment(fec_start);
		fecha_inicio = fec_start.format('YYYY-MM-DD HH:mm:ss');
		fecha_fin = fec_start.add(minutos / 60, "hours").format('YYYY-MM-DD HH:mm:ss');
		hora_fin = fec_start.format('HH:mm:00');
		$("#planf_hora_finRP").val(hora_fin);
	}
}


function save_planif_apertura() {
	var fec_inicio = $("#ap_fecha_inicio").val();
	var fec_fin = $("#ap_fecha_fin").val();
	var parametros = {
		"codigo": '', "ubic": ubicacion, "cargo": cargo,
		"fec_inicio": fec_inicio, "fec_fin": fec_fin,
		"metodo": "agregar", "usuario": usuario
	}
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/modelo/planificacion_apertura.php',
		type: 'post',
		beforeSend: function () {
			$("#planif_apertura_ing").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#planif_apertura_ing").html('<span class="art-button-wrapper" id="planif_apertura_ing"><span class="art-button-l"> </span><span class="art-button-r"> </span><input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" /> </span')
			var resp = JSON.parse(response);
			if (resp.error) {
				alert(resp.mensaje);
			} else {
				// actualiza la apertura
				Habilitar_apertura();
				cl_apertura();
				CloseModal();
				toastr.success("Actualizacion Exitosa!..");
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function filtrar_supervisores(filtro) {
	var parametros = { ubicacion, filtro, usuario, cargo };
	$.ajax({
		data: parametros,
		url: 'packages/planif/planif_supervisor/views/Add_supervisores.php',
		type: 'post',
		beforeSend: function () {
			$("#external-events-list").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
		},
		success: function (response) {
			$("#external-events-list").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function actualizarEvento(arg) {
	isafter = moment(arg.event.start).isSameOrAfter(moment(hoy).format("YYYY-MM-DD"));
	if (isafter) {
		//region = $("#planf_region").val();
		validarFecha(moment(arg.event.start).format("YYYY-MM-DD"), cliente, apertura, ubicacion, arg.oldEvent.extendedProps.cod_ficha, (fechas) => {
			if (fechas.data.length > 0) {
				var parametros = {
					"codigo": arg.oldEvent.id, "fecha_inicio": moment(arg.event.start).format("YYYY-MM-DD HH:mm:00"), "fecha_fin": moment(arg.event.end).format("YYYY-MM-DD HH:mm:00"),
					"cliente": arg.oldEvent.extendedProps.cod_cliente, "ubicacion": arg.oldEvent.extendedProps.cod_ubicacion,
					"ficha": arg.oldEvent.extendedProps.cod_ficha, 'apertura': apertura, "metodo": "modificar", "usuario": usuario
				};
				$.ajax({
					data: parametros,
					url: 'packages/planif/planif_supervisor/modelo/actividad_det.php',
					type: 'post',
					success: function (response) {
						var resp = JSON.parse(response);
						if (resp.error) {
							toastr.error(resp.mensaje);
						} else {
							toastr.success('Actualizacion Exitosa!..');
							$('#modalRP').hide();
							typeCalendar = calendar.view.type;
							cargar_planif_superv_det(apertura);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					}
				});
			} else {
				toastr.error(fechas.msg);
				cargar_planif_superv_det(apertura);
			}
		});
	} else {
		toastr.info("No es posible planificar actividades sobre fechas pasadas.");
		typeCalendar = calendar.view.type;
		if (apertura) {
			cargar_planif_superv_det(apertura);
		}
	}
}

function onChangeAp(ap) {
	mostrar_icono_apertura(ap);
	ubicacion = $('#planf_ubicacion').val();
	cargo = $('#planf_cargo').val();
	cargar_planif_superv(ubicacion, cargo);
}


function onChangeCargo(v_cargo){
	cargar_planif_superv($('#planf_ubicacion').val(), v_cargo);
	cl_apertura();
}