var val, val2, trh, trb, servicio, contrato, res_ficha, res_concepto, res_horario, res_fecha_cont, map_res_fec_cont,
	res_horario_cont, map_res_horario_cont, map_res_horario, res_mes_anio, map_res_mes_anio, val_ubic, val_ubic_f, val_ubic_h,
	val_horarios, res_fecha_cont_keys, conceptos;
var cantidad = 0, horas = 0, resultado = 0, factor = 0, trab_neces = 0, trab_activos = 0, excepcion = 0, sum_dia = 0;

/* SIN PUESTO */
function rp_planif_trab_serv(data, id_contenedor, callback, formato) {
	if (d3.select('#' + id_contenedor).node()) {
		limpiarContenedor(id_contenedor);
		servicio = data['servicio'];
		contrato = data['contrato'];
		conceptos = data['conceptos'];

		res_ficha = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.ficha).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);

		res_concepto = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_concepto).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);

		res_horario = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);

		map_res_horario = d3.map(res_horario, (d) => d.key);

		res_horario_c = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);
		map_res_horario_c = d3.map(res_horario, (d) => d.key);


		res_fecha_cont = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_fec_cont = d3.map(res_fecha_cont, (d) => d.key);

		res_horario_cont = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(contrato);

		res_horario_contrato = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.cod_cargo).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_horario_cont = d3.map(res_horario_cont, (d) => d.key);
		map_res_horario_contrato = d3.map(res_horario_contrato, (d) => d.key);

		res_mes_anio = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.mes_anio).sortKeys(d3.descending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_mes_anio = d3.map(res_mes_anio, (d) => d.key);

		rp_planif_trab_serv_create_cabecera(id_contenedor, callback, formato);
	}
}



function rp_planif_trab_serv_create_cabecera(id_contenedor, callback, formato) {
	/////////////////Creacion de la tabla y cabecera
	var divs = d3.select('#' + id_contenedor).selectAll('div').data(res_fecha_cont).enter().append('div');
	if (formato == 'pdf') {
		divs.append('span').attr('align', 'center').html((d) =>
			'<img class="imgLink" title="imprimir planificacion(' +
			d.values[0].values[0].values[0].cliente + ' - ' + d.values[0].values[0].values[0].ubicacion +
			')" width="25px" src="imagenes/pdf.gif" border="0" onclick="rp_planif_serv_rp(\'pdf\',\'table' +
			d.key + '\')"> <select name="update" style="width:90px;" onchange="rp_planif_serv_update(' + d.key + ',this.value)">' +
			'<option value="C"> CANTIDADES</option><option value="H">HORAS</option></select>');
	} else {
		divs.append('span').attr('align', 'center').html((d) =>
			'<img class="imgLink" title="imprimir planificacion(' +
			d.values[0].values[0].values[0].cliente + ' - ' + d.values[0].values[0].values[0].ubicacion +
			')" width="25px" src="imagenes/excel.gif" border="0" onclick="rp_planif_serv_rp(\'excel\',\'table' +
			d.key + '\')"> <select name="update" style="width:90px;" onchange="rp_planif_serv_update(' + d.key + ',this.value)">' +
			'<option value="C"> CANTIDADES</option><option value="H">HORAS</option></select>');
		divs.append('span').style("float", 'right').html((d) =>
			'<b>DOBLE CLICK PARA MODIFICAR</b>');
	}

	var tablas = divs.append('table').attr('id', (d) => "table" + d.key).attr('width', '100%').attr('class', 'tabla_planif').attr('align', 'center');
	var theads = tablas.append('thead').attr('id', (d) => "thead" + d.key);
	var tbodys = tablas.append('tbody').attr("id", (d) => "tbody" + d.key);

	trh = theads.append('tr');
	trh.append('th').attr('colspan', (d) => 3 + Number(d.values.length)).attr('class', 'etiqueta titulo')
		.text((d) => d.values[0].values[0].values[0].cliente + " - " + d.values[0].values[0].values[0].ubicacion);

	trh = theads.append('tr');
	trh.append('th').attr('colspan', 2).attr('rowspan', 4).attr('class', 'etiqueta').text('Horario');
	trh.append('th').attr('colspan', 1).attr('rowspan', 4).attr('class', 'etiqueta').text('Cargo');
	trh.append('th').attr('colspan', (d) => d.values.length).attr('class', 'etiqueta').text('Dias del Mes');

	res_fecha_cont.forEach((d) => {
		trh = d3.select("#thead" + d.key).append("tr");
		if (map_res_mes_anio.has(d.key)) {
			map_res_mes_anio.get(d.key).values.forEach((d) => {
				trh.append('th').attr('class', d.key).attr('colspan', d.values.length)
					.on("mouseover", () => d3.selectAll('.' + d.key).classed('yellow', true)).on("mouseout", () => d3.selectAll('.' + d.key).classed('yellow', false)).text(d.key);
			})
		}
	});
	res_fecha_cont.forEach((d) => {
		d3.select("#thead" + d.key).append("tr").selectAll("th").data(d.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].dia_semana).attr('class', (d) => 'etiqueta ' + d.values[0].values[0].mes_anio +
				' ' + d.values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.values[0].values[0].dia_semana[0])
	});
	res_fecha_cont.forEach((d) => {
		d3.select("#thead" + d.key).append("tr").selectAll("th").data(d.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].dia_semana).attr('class', (d) => 'etiqueta ' + d.values[0].values[0].mes_anio +
				' ' + d.values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.key.substring(8))
	});

	rp_planif_trab_serv_contrato(tablas);
}
function rp_planif_trab_serv_contrato(tablas, callback) {

	//////////////Creacion de necesidad dias por horario
	res_fecha_cont_keys = d3.map(res_fecha_cont[0].values, (d) => d.key).keys();
	val_ubic = d3.map(res_fecha_cont, (d) => d.key);

	res_horario_contrato.forEach((ubic) => {
		val_horarios = d3.map(ubic.values, (d) => d.key);
		ubic.values.forEach((horario) => {
			let arreglo_dia = res_fecha_cont_keys.map(() => 0);
			val_horarios.get(horario.key).values.forEach((cargo, i) => {

				val_fechas = d3.map(cargo.values, (d) => d.key);

				trb = d3.select("#thead" + ubic.key).append("tr").attr('id', (d) => 'tbody_contrato_' + ubic.key + '_' + horario.key + "_" + cargo.key).attr('class', 'color');
				if (i == 0) {
					trb.append('td').attr('colspan', 2).attr("rowspan", horario.values.length).text((d) => horario.values[0].values[0].values[0].horario);
					trb.append('td').attr('colspan', 1).text((d) => cargo.values[0].values[0].cargo);
				} else {
					trb.append('td').attr('colspan', 1).text((d) => cargo.values[0].values[0].cargo);
				}

				res_fecha_cont_keys.forEach((res, i) => {

					if (val_fechas.has(res)) {
						val_fechas.get(res).values.forEach((d) => {
							cantidad += Number(d.cantidad);
							arreglo_dia[i] += Number(d.cantidad);
						});
						d3.select('#tbody_contrato_' + ubic.key + '_' + horario.key + "_" + cargo.key).append('td').attr("id", 'td_' + ubic.key + '_' + horario.key + "_" + cargo.key + "_" + res.substring(8)).attr("rowspan", 1)
							.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].values[0].horario + ' ' + res)
							.attr('class', 'cantidad ' + val_fechas.get(res).values[0].mes_anio + ' ' + val_fechas.get(res).values[0].mes_anio + res.substring(8))
							.text(cantidad);
					} else {
						d3.select('#tbody_contrato_' + ubic.key + '_' + horario.key + "_" + cargo.key).append('td').attr("id", 'td_' + ubic.key + '_' + horario.key + "_" + cargo.key + "_" + res.substring(8)).attr("rowspan", 1)
							.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].values[0].horario + ' ' + res)
							.text(cantidad);
					}

					cantidad = 0;
				});
			});
			trb = d3.select("#thead" + ubic.key).append("tr").attr('id', (d) => 'tbody_contrato_' + ubic.key + '_' + horario.key + "_total").attr('class', 'color');
			trb.append('td').attr('colspan', 3).text((d) => `TOTAL: ${val_horarios.get(horario.key).values[0].values[0].values[0].horario}`);
			arreglo_dia.forEach((cantidad) => {
				trb.append('td')
					.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].horario)
					.attr('class', 'cantidad ')
					.text(cantidad);
			});
		});

		////////////////////////////////// Resumen de Totales por dia y horario
		// trb = d3.select("#thead" + ubic.key).append("tr").attr('class', 'color');
		// res_horario_cont.forEach((ubic) => {
		// 	val_horarios = d3.map(ubic.values, (d) => d.key);
		// 	ubic.values.forEach((horario) => {
		// 		val_fechas = d3.map(horario.values, (d) => d.key);
		// 		trb = d3.select("#thead" + ubic.key).append("tr").attr('id', (d) => 'tbody_contrato_' + ubic.key + '_' + horario.key + "_total").attr('class', 'color');
		// 		trb.append('td').attr('colspan', 3).text((d) => `TOTAL: ${horario.values[0].values[0].horario}`);
		// 		val_ubic.get(ubic.key).values.forEach((f) => {

		// 			if (val_fechas.has(f.key)) {
		// 				val_fechas.get(f.key).values.forEach((d) => {
		// 					cantidad += Number(d.cantidad);
		// 				});
		// 			}

		// 			d3.select('#tbody_contrato_' + ubic.key + '_' + horario.key + "_total").append('td')
		// 				.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].horario + ' ' + f.key)
		// 				.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
		// 				.text(cantidad);
		// 			cantidad = 0;
		// 		});
		// 	});
		// });
	});

	rp_planif_trab_serv_trabajador(tablas);
}

function rp_planif_trab_serv_trabajador(tablas, callback) {
	///////////////Creaccion de planificacion a trabajador
	var theads = tablas.append('thead').attr('id', (d) => "thead_servicio_" + d.key);
	var tbodys = tablas.append('tbody').attr("id", (d) => "tbody_servicio_" + d.key);
	trh = theads.append('tr').attr('id', (d) => 'tr_servicio_fechas' + d.key);
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('Ficha');
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('CI');
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('Nombre');
	val_ubic_f = d3.map(res_ficha, (d) => d.key);

	res_fecha_cont.forEach((ubic, i) => {

		d3.select("#tr_servicio_fechas" + ubic.key).selectAll(".fechas").data(ubic.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].dia_semana).attr('class', (d) => 'etiqueta fechas' +
				d.values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.key.substring(8));
		if (val_ubic_f.has(ubic.key)) {
			val_ubic_f.get(ubic.key).values.forEach((d) => {
				val_fechas = d3.map(d.values, (d) => d.key);
				trb = d3.select('#tbody_servicio_' + ubic.key).append('tr').attr("id", "tr_ficha_" + ubic.key + "_" + d.key);
				trb.append('td').text(d.key);
				trb.append('td').text(d.values[0].values[0].cedula);
				trb.append('td').text(d.values[0].values[0].ap_nombre);
				ubic.values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						d3.select('#tr_ficha_' + ubic.key + '_' + d.key).append('td').attr('id', `cod_${val_fechas.get(f.key).values[0].cod_planif}`)
							.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
							.on("mouseover", (d) => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
							.on("mouseout", () => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
							.on("dblclick", () => {
								if (conceptos) {
									let datos_map = d3.map(d.values, (d) => d.key);

									if (document.getElementById(`select_${datos_map.get(f.key).values[0].cod_planif}`) != null) {
										d3.select(`#cod_${datos_map.get(f.key).values[0].cod_planif}`).text(`${datos_map.get(f.key).values[0].concepto}`);
									} else {
										let select = d3.select(`#cod_${datos_map.get(f.key).values[0].cod_planif}`)
											.text("").append("select").attr("id", `select_${datos_map.get(f.key).values[0].cod_planif}`)
											.style("width", "40px").on("change", () => {
												let index = document.getElementById(`select_${datos_map.get(f.key).values[0].cod_planif}`).selectedIndex;
												guardar_cambio_concepto(datos_map.get(f.key).values[0].cod_planif, conceptos[index].turno, () => {
													B_reporte("F");
												});
											});
										conceptos.forEach((d) => {
											if (datos_map.get(f.key).values[0].cod_turno == d.turno) {
												select.append("option").attr("value", d.turno).attr("selected", "selected").text(`${d.descripcion}(${d.conceptos})`);
											} else {
												select.append("option").attr("value", d.turno).text(`${d.descripcion}(${d.conceptos})`);
											}

										});
									}
								}


							})
							.text(`${val_fechas.get(f.key).values[0].concepto}`);
					} else {

						d3.select('#tr_ficha_' + ubic.key + '_' + d.key).append('td').attr("id", `cod_${d.values[0].values[0].ficha}_${f.key}`)
							.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text('')
							.on("dblclick", () => {
								if (conceptos) {
									if (document.getElementById(`select_${d.values[0].values[0].ficha}_${f.key}`) != null) {
										d3.select(`#cod_${d.values[0].values[0].ficha}_${f.key}`).text('');
									} else {
										let parametros = {
											apertura: d.values[0].values[0].planif_cl,
											planif_cl_trab: d.values[0].values[0].planif_cl_trab,
											cliente: d.values[0].values[0].cod_cliente,
											ubicacion: d.values[0].values[0].cod_ubicacion,
											puesto_trab: d.values[0].values[0].cod_puesto,
											turno: 0,
											ficha: d.values[0].values[0].ficha,
											fecha: f.key,
											usuario: $("#usuario").val()

										}

										let select = d3.select(`#cod_${d.values[0].values[0].ficha}_${f.key}`)
											.text("").append("select").attr("id", `select_${d.values[0].values[0].ficha}_${f.key}`)
											.style("width", "40px").on("change", () => {
												let index = document.getElementById(`select_${d.values[0].values[0].ficha}_${f.key}`).selectedIndex;
												parametros.turno = conceptos[index].turno;

												guardar_cambio_concepto("", parametros, () => {

													B_reporte("F");
												});
											});
										conceptos.forEach((d) => {
											select.append("option").attr("value", d.turno).text(`${d.descripcion}(${d.conceptos})`);
										});
									}
								}
							});
					}
				});
			});
		} else {
			d3.select('#tbody_servicio_' + ubic.key).append('tr').append('td').text('Sin Planificacion de Trabajadores!..')
				.attr('colspan', (d) => 3 + Number(ubic.values.length));
		}
	});
	rp_planif_trab_serv_conceptos(tablas);
}


function rp_planif_trab_serv_conceptos(tablas, callback) {

	///////////////////////////////////Creacion de resumen de conceptos
	tablas.append('tbody').attr('id', (d) => "thead_conceptos_" + d.key);

	res_concepto.forEach((ubic, i) => {
		d3.select('#thead_conceptos_' + ubic.key).selectAll('tr').data(ubic.values).enter()
			.append("tr").attr("id", (d) => "tr_conceptos_" + ubic.key + "_" + d.key).attr('class', 'color');
		ubic.values.forEach((d, i) => {
			if (i == 0) d3.select("#tr_conceptos_" + ubic.key + "_" + d.key).append('th').attr('class', 'etiqueta').attr('colspan', 2)
				.attr('rowspan', ubic.values.length).text((d) => 'Resumen Conceptos');
			d3.select("#tr_conceptos_" + ubic.key + "_" + d.key).append('td').text(d.values[0].values[0].concepto);
			val_fechas = d3.map(d.values, (d) => d.key);
			if (val_ubic.has(ubic.key)) {
				val_ubic.get(ubic.key).values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						d3.select("#tr_conceptos_" + ubic.key + "_" + d.key).append('td')
							.attr('title', 'RESUMEN CONCEPTO (' + d.key + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8))
							.text(val_fechas.get(f.key).values.length);
					} else {
						d3.select("#tr_conceptos_" + ubic.key + "_" + d.key).append('td')
							.attr('title', 'RESUMEN CONCEPTO (' + d.key + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text(0);
					}
				});
			}
		});
	});
	rp_planif_trab_serv_horario(tablas);
}

function rp_planif_trab_serv_horario(tablas, callback) {

	//////////////////////Creacion de resumen de Horarios
	tablas.append('tbody').attr("id", (d) => "thead_horarios_" + d.key);

	res_horario.forEach((ubic, i) => {
		d3.select('#thead_horarios_' + ubic.key).selectAll('tr').data(ubic.values).enter()
			.append("tr").attr("id", (d) => "tr_horarios_" + ubic.key + "_" + d.key).attr('class', 'color');
		ubic.values.forEach((d, i) => {
			if (i == 0) d3.select("#tr_horarios_" + ubic.key + "_" + d.key).append('th').attr('class', 'etiqueta').attr('colspan', 2)
				.attr('rowspan', ubic.values.length).text((d) => 'Resumen Horarios');
			d3.select("#tr_horarios_" + ubic.key + "_" + d.key).append('td').text(d.values[0].values[0].concepto_horario);
			val_fechas = d3.map(d.values, (d) => d.key);
			if (val_ubic.has(ubic.key)) {
				val_ubic.get(ubic.key).values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						d3.select("#tr_horarios_" + ubic.key + "_" + d.key).append('td')
							.attr('title', 'RESUMEN HORARIO (' + d.values[0].values[0].concepto_horario + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
							.text(val_fechas.get(f.key).values.length);
					} else {
						d3.select("#tr_horarios_" + ubic.key + "_" + d.key).append('td')
							.attr('title', 'RESUMEN HORARIO (' + d.values[0].values[0].concepto_horario + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text(0);
					}
				});
			}
		});
	});
	rp_planif_trab_serv_diferencia(tablas);
}
function rp_planif_trab_serv_diferencia(tablas, callback) {
	////////////////// Creacion de tabla de diferencias
	var theads = tablas.append('thead').attr('id', (d) => "thead_diferencia_" + d.key);

	trh = theads.append('tr').attr("id", (d) => "tr_diferencia_" + d.key);
	trh.append('th').attr('class', 'etiqueta').attr('colspan', 3).text('Diferencia');

	res_fecha_cont.forEach((d) => {
		d3.select("#tr_diferencia_" + d.key).selectAll(".fechas").data(d.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].dia_semana).attr('class', 'etiqueta fechas' +
				' ' + d.values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.key.substring(8))
	});

	tablas.append('thead').attr("id", (d) => "tbody_diferencia_" + d.key);
	val_ubic_h = d3.map(res_horario, (d) => d.key);
	res_horario_cont.forEach((ubic, i) => {
		if (val_ubic_f.has(ubic.key)) {
			val_horarios = d3.map(ubic.values, (d) => d.key);
			ubic.values.forEach((horario, i) => {
				val_fechas = d3.map(horario.values, (d) => d.key);
				trb = d3.select("#tbody_diferencia_" + ubic.key).append("tr").attr('id', (d) => 'tr_diferencia_' + ubic.key + '_' + horario.key)
					.attr('class', 'color');
				trb.append('td').attr('colspan', 3).text((d) => horario.values[0].values[0].horario);
				val_ubic.get(ubic.key).values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						val_fechas.get(f.key).values.forEach((d) => {
							cantidad += Number(d.cantidad);
						});
					}
					var cantidad_horario = 0, diff = 0;
					if (val_ubic_h.has(ubic.key)) {
						val_horarios_h = d3.map(val_ubic_h.get(ubic.key).values, (d) => d.key);
						if (val_horarios_h.has(horario.key)) {
							val_fechas_h = d3.map(val_horarios_h.get(horario.key).values, (d) => d.key);
							if (val_fechas_h.has(f.key)) {
								cantidad_horario += val_fechas_h.get(f.key).values.length;
							} else {
								cantidad_horario = 0;
							}
						}
						diff = cantidad_horario - cantidad;
					}
					d3.select('#tr_diferencia_' + ubic.key + '_' + horario.key).append('td').attr('title', 'DIFERENCIA ' + horario.values[0].values[0].horario + ' ' + f.key)
						.on("mouseover", (d) => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
						.on("mouseout", () => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
						.text(() => {
							if (diff == 0) return 'OK'; else return diff;
						}).attr('class', 'cantidad ' + validarFondo(diff));
					cantidad = 0;
				});
			});
		}
	});
	////////////777777777
}

function rp_planif_serv_update(cod_ubicacion, tipo) {
	map_res_horario_contrato.get(cod_ubicacion).values.forEach((horario) => {
		horario.values.forEach((cargo) => {
			val_fechas = d3.map(cargo.values, (d) => d.key);
			res_fecha_cont_keys.forEach((fec) => {

				if (val_fechas.has(fec)) {
					val_fechas.get(fec).values.forEach((d) => {
						if (tipo == 'H') {
							cantidad += Number(d.horas);
						} else if (tipo == 'C') {
							cantidad += Number(d.cantidad);
						}
					});
				}
				d3.select('#td_' + cod_ubicacion + '_' + horario.key + "_" + cargo.key + "_" + fec.substring(8)).text(cantidad);
				cantidad = 0;
			});
		});
	});

	map_res_horario_cont.get(cod_ubicacion).values.forEach((horario) => {
		val_fechas = d3.map(horario.values, (d) => d.key);
		trb = d3.select('#tbody_contrato_' + cod_ubicacion + '_' + horario.key + "_total").selectAll('.cantidad').remove();
		trb.append('td').attr('colspan', 3).text((d) => horario.values[0].values[0].horario);
		val_ubic.get(cod_ubicacion).values.forEach((f) => {

			if (val_fechas.has(f.key)) {
				val_fechas.get(f.key).values.forEach((d) => {
					if (tipo == 'H') {
						cantidad += Number(d.horas);
					} else if (tipo == 'C') {
						cantidad += Number(d.cantidad);
					}
				});
			}

			d3.select('#tbody_contrato_' + cod_ubicacion + '_' + horario.key + "_total").append('td')
				.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].horario + ' ' + f.key)
				.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
				.text(cantidad);
			cantidad = 0;
		});
	});

	val_ubic_f.get(cod_ubicacion).values.forEach((d) => {
		val_fechas = d3.map(d.values, (d) => d.key);
		trb = d3.select("#tr_ficha_" + cod_ubicacion + "_" + d.key).selectAll('.cantidad').remove();
		map_res_fec_cont.get(cod_ubicacion).values.forEach((f) => {
			if (val_fechas.has(f.key)) {
				horas = 0;
				if (tipo == 'H') {
					d3.select('#tr_ficha_' + cod_ubicacion + '_' + d.key).append('td')
						.attr('title', '(' + d.key + ') ' + f.values[0].ap_nombre + ' ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].mes_anio + ' ' + f.values[0].mes_anio + f.key.substring(8))
						.on("mouseover", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
						.on("mouseout", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
						.text(Number(val_fechas.get(f.key).values[0].horas));
				} else if (tipo == 'C') {
					d3.select('#tr_ficha_' + cod_ubicacion + '_' + d.key).append('td')
						.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].mes_anio + ' ' + f.values[0].mes_anio + f.key.substring(8))
						.on("mouseover", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
						.on("mouseout", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
						.text(val_fechas.get(f.key).values[0].concepto);
				}
			} else {
				d3.select('#tr_ficha_' + cod_ubicacion + '_' + d.key).append('td')
					.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' ' + f.key)
					.attr('class', 'cantidad ' + f.values[0].mes_anio + ' ' + f.values[0].mes_anio + f.key.substring(8)).text('');
			}
		});
	});

	map_res_horario.get(cod_ubicacion).values.forEach((d, i) => {
		d3.select("#tr_horarios_" + cod_ubicacion + "_" + d.key).selectAll('.cantidad').remove();
		val_fechas = d3.map(d.values, (d) => d.key);
		val_ubic.get(cod_ubicacion).values.forEach((f) => {
			horas = 0;
			if (val_fechas.has(f.key)) {
				if (tipo == 'H') {
					d3.select("#tr_horarios_" + cod_ubicacion + "_" + d.key).append('td')
						.attr('title', 'RESUMEN HORARIO (' + d.values[0].values[0].concepto_horario + ') ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
						.text(() => {
							val_fechas.get(f.key).values.forEach((d) => {
								horas += Number(d.horas);
							});
							return horas;
						});
				} else if (tipo == 'C') {
					d3.select("#tr_horarios_" + cod_ubicacion + "_" + d.key).append('td')
						.attr('title', 'RESUMEN HORARIO (' + d.values[0].values[0].concepto_horario + ') ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
						.text(val_fechas.get(f.key).values.length);
				}
			} else {
				d3.select("#tr_horarios_" + cod_ubicacion + "_" + d.key).append('td')
					.attr('title', 'RESUMEN HORARIO (' + d.values[0].values[0].concepto_horario + ') ' + f.key)
					.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text(0);
			}
		});
	});

	map_res_horario_cont.get(cod_ubicacion).values.forEach((horario, i) => {
		val_fechas = d3.map(horario.values, (d) => d.key);
		d3.select('#tr_diferencia_' + cod_ubicacion + '_' + horario.key).selectAll('.cantidad').remove();
		val_ubic.get(cod_ubicacion).values.forEach((f) => {
			if (val_fechas.has(f.key)) {
				if (tipo == 'H') {
					val_fechas.get(f.key).values.forEach((d) => {
						cantidad += Number(d.horas);
					});
				} else if (tipo == 'C') {
					val_fechas.get(f.key).values.forEach((d) => {
						cantidad += Number(d.cantidad);
					});
				}
			}
			var cantidad_horario = 0, diff = 0;
			if (val_ubic_h.has(cod_ubicacion)) {
				val_horarios_h = d3.map(val_ubic_h.get(cod_ubicacion).values, (d) => d.key);
				if (val_horarios_h.has(horario.key)) {
					val_fechas_h = d3.map(val_horarios_h.get(horario.key).values, (d) => d.key);
					if (val_fechas_h.has(f.key)) {
						if (tipo == 'H') {
							val_fechas_h.get(f.key).values.forEach(d => {
								cantidad_horario += Number(d.horas);
							});
						} else if (tipo == 'C') {
							cantidad_horario += val_fechas_h.get(f.key).values.length;
						}
					} else {
						cantidad_horario = 0;
					}
				}
				diff = cantidad_horario - cantidad;
			}
			d3.select('#tr_diferencia_' + cod_ubicacion + '_' + horario.key).append('td').attr('title', 'DIFERENCIA ' + horario.values[0].values[0].horario + ' ' + f.key)
				.on("mouseover", (d) => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
				.on("mouseout", () => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
				.text(() => {
					if (diff == 0) return 'OK'; else return diff;
				}).attr('class', 'cantidad ' + validarFondo(diff));
			cantidad = 0;
		});
	});
}

/*CON PUESTOS*/
function rp_planif_trab_serv_detalle(data, id_contenedor, callback) {
	if (d3.select('#' + id_contenedor).node()) {
		limpiarContenedor(id_contenedor);
		servicio = data['servicio'];
		contrato = data['contrato'];
		conceptos = data['conceptos'];
		res_ficha = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.ficha).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);

		res_concepto = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_puesto).sortKeys(d3.ascending)
			.key((d) => d.cod_concepto).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);

		res_horario = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_puesto).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(servicio);

		map_res_horario = d3.map(res_horario, (d) => d.key);

		res_fecha_cont = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.key((d) => d.cod_puesto).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_fec_cont = d3.map(res_fecha_cont, (d) => d.key);

		res_horario_cont = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_puesto).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_horario_cont = d3.map(res_horario_cont, (d) => d.key);

		res_horario_contrato = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_puesto).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.cod_cargo).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_horario_contrato = d3.map(res_horario_contrato, (d) => d.key);
		res_mes_anio = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.mes_anio).sortKeys(d3.descending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(contrato);

		map_res_mes_anio = d3.map(res_mes_anio, (d) => d.key);

		rp_planif_trab_serv_create_detalle(id_contenedor, callback);
	}
}

function rp_planif_trab_serv_create_detalle(id_contenedor, callback) {
	var divs = d3.select('#' + id_contenedor).selectAll('div').data(res_fecha_cont).enter().append('div');

	divs.append('span').attr('align', 'center').html((d) =>
		'<img class="imgLink" title="imprimir planificacion(' +
		d.values[0].values[0].values[0].cliente + ' - ' + d.values[0].values[0].values[0].ubicacion +
		')" width="25px" src="imagenes/excel.gif" border="0" onclick="rp_planif_serv_rp(\'excel\',\'table' +
		d.key + '\')"> <select name="region" style="width:90px;" onchange="rp_planif_serv_update_detalle(' + d.key + ',this.value)">' +
		'<option value="C"> CANTIDADES</option><option value="H">HORAS</option></select>')

	var tablas = divs.append('table').attr('id', (d) => "table" + d.key).attr('width', '100%').attr('class', 'tabla_planif').attr('align', 'center');
	var theads = tablas.append('thead').attr('id', (d) => "thead" + d.key);
	var tbodys = tablas.append('tbody').attr("id", (d) => "tbody" + d.key);

	trh = theads.append('tr');
	trh.append('th').attr('colspan', (d) => 4 + Number(d.values.length)).attr('class', 'etiqueta titulo')
		.text((d) => d.values[0].values[0].values[0].values[0].cliente + " - " + d.values[0].values[0].values[0].values[0].ubicacion);

	trh = theads.append('tr');
	trh.append('th').attr('colspan', 2).attr('rowspan', 4).attr('class', 'etiqueta').text('Puesto de Trabajo');
	trh.append('th').attr('colspan', 1).attr('rowspan', 4).attr('class', 'etiqueta').text("Horario");
	trh.append('th').attr('colspan', 1).attr('rowspan', 4).attr('class', 'etiqueta').text("Cargo");
	trh.append('th').attr('colspan', (d) => d.values.length).attr('class', 'etiqueta').text('Dias del Mes');


	res_fecha_cont.forEach((d) => {
		trh = d3.select("#thead" + d.key).append('tr');
		if (map_res_mes_anio.has(d.key)) {
			map_res_mes_anio.get(d.key).values.forEach((d, i) => {
				trh.append('th').attr('class', d.key).attr('colspan', d.values.length)
					.on("mouseover", () => d3.selectAll('.' + d.key).classed('yellow', true)).on("mouseout", () => d3.selectAll('.' + d.key).classed('yellow', false)).text(d.key);
			});
		}
	});
	res_fecha_cont.forEach((d, i) => {
		d3.select("#thead" + d.key).append("tr").selectAll("th").data(d.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].values[0].dia_semana).attr('class', (d) => 'etiqueta ' + d.values[0].values[0].values[0].mes_anio +
				' ' + d.values[0].values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.values[0].values[0].values[0].dia_semana[0])
	});
	res_fecha_cont.forEach((d, i) => {
		d3.select("#thead" + d.key).append("tr").selectAll("th").data(d.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].values[0].dia_semana).attr('class', (d) => 'etiqueta ' + d.values[0].values[0].values[0].mes_anio +
				' ' + d.values[0].values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.key.substring(8))
	});
	res_fecha_cont_keys = d3.map(res_fecha_cont[0].values, (d) => d.key).keys();
	val_ubic = d3.map(res_fecha_cont, (d) => d.key);

	res_horario_contrato.forEach((ubic) => {
		val_puestos = d3.map(ubic.values, (d) => d.key);
		ubic.values.forEach((puesto) => {
			val_horarios = d3.map(puesto.values, (d) => d.key);
			puesto.values.forEach((horario, j) => {
				horario.values.forEach((cargo, i) => {
					val_fechas = d3.map(cargo.values, (d) => d.key);
					trb = d3.select("#tbody" + ubic.key).append("tr").attr('id', 'tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + horario.key + "_" + cargo.key).attr('class', 'color');
					if (i == 0) {
						if (j == 0) {
							trb.append('td').attr('colspan', 2)
								.attr("rowspan", () => {
									let suma = 0;
									puesto.values.forEach((res) => {
										suma += res.values.length;
									});
									return suma;
								}).text((d) => cargo.values[0].values[0].puesto);
						}

						trb.append('td').attr('colspan', 1).attr("rowspan", horario.values.length).text((d) => horario.values[0].values[0].values[0].horario);
					}
					trb.append('td').attr('colspan', 1).text((d) => cargo.values[0].values[0].cargo);

					res_fecha_cont_keys.forEach((res) => {
						if (val_fechas.has(res)) {
							val_fechas.get(res).values.forEach((d) => {
								cantidad += Number(d.cantidad);
							});
							d3.select('#tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + horario.key + "_" + cargo.key).append('td').attr('id', 'td_' + ubic.key + '_' + puesto.key + '_' + horario.key + "_" + cargo.key + "_" + res.substring(8)).attr("rowspan", 1)
								.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].values[0].horario + ' ' + res)
								.attr('class', 'cantidad ' + val_fechas.get(res).values[0].mes_anio + ' ' + val_fechas.get(res).values[0].mes_anio + res.substring(8))
								.text(cantidad);
						} else {
							d3.select('#tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + horario.key + "_" + cargo.key).append('td').attr('id', 'td_' + ubic.key + '_' + puesto.key + '_' + horario.key + "_" + cargo.key + "_" + res.substring(8)).attr("rowspan", 1)
								.attr('title', 'CANTIDAD DEMANDA ' + horario.values[0].values[0].values[0].horario + ' ' + res)
								.text(cantidad);
						}
						cantidad = 0;
					});
				});

			});
		});
	});


	///////////////////
	res_horario_cont.forEach((ubic) => {
		val_puestos = d3.map(ubic.values, (d) => d.key);
		ubic.values.forEach((puesto, i) => {
			val_horarios = d3.map(puesto.values, (d) => d.key);
			puesto.values.forEach((d) => {
				d3.select("#tbody" + ubic.key).append("tr").attr('id', 'tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + d.key + "_total").attr('class', 'color');
			});

			d3.select('#tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + puesto.values[0].key + "_total").append('td').attr('rowspan', puesto.values.length)
				.attr('colspan', 2).text(puesto.values[0].values[0].values[0].puesto);

			puesto.values.forEach((d) => {
				val_fechas = d3.map(d.values, (d) => d.key);
				d3.select('#tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + d.key + "_total").append('td').attr('colspan', 2).text(`TOTAL: ${d.values[0].values[0].horario}`);
				val_ubic.get(ubic.key).values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						val_fechas.get(f.key).values.forEach((d, i) => {
							cantidad += Number(d.cantidad);
						});
					}

					d3.select('#tbody_contrato_' + ubic.key + '_' + puesto.key + '_' + d.key + "_total").append('td')
						.attr('title', 'CANTIDAD DEMANDA ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].horario + ') ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].values[0].values[0].mes_anio + ' ' + f.values[0].values[0].values[0].mes_anio + f.key.substring(8))
						.text(cantidad);
					cantidad = 0;
				});
			});
		});
	});

	var theads = tablas.append('thead').attr('id', (d) => "thead_servicio_" + d.key);
	var tbodys = tablas.append('tbody').attr("id", (d) => "tbody_servicio_" + d.key);
	trh = theads.append('tr').attr('id', (d) => 'tr_servicio_fechas_' + d.key);
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('Ficha');
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('CI');
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('Nombre');
	trh.append('th').attr("colspan", 1).attr('class', 'etiqueta').text('Puesto de Trabajo');
	val_ubic_f = d3.map(res_ficha, (d) => d.key);
	res_fecha_cont.forEach((ubic, i) => {
		d3.select("#tr_servicio_fechas_" + ubic.key).selectAll(".fechas").data(ubic.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].dia_semana).attr('class', (d) => 'etiqueta fechas' +
				d.values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.key.substring(8));
		if (val_ubic_f.has(ubic.key)) {
			val_ubic_f.get(ubic.key).values.forEach((d) => {
				val_fechas = d3.map(d.values, (d) => d.key);
				trb = d3.select('#tbody_servicio_' + ubic.key).append('tr').attr("id", "tr_ficha_" + ubic.key + "_" + d.key);
				trb.append('td').text(d.key);
				trb.append('td').text(d.values[0].values[0].cedula);
				trb.append('td').text(d.values[0].values[0].ap_nombre);
				trb.append('td').text(d.values[0].values[0].puesto);
				ubic.values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						d3.select('#tr_ficha_' + ubic.key + '_' + d.key).append('td').attr('id', `cod_${val_fechas.get(f.key).values[0].cod_planif}`)
							.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' (' + d.values[0].values[0].puesto + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))
							.on("mouseover", (d) => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
							.on("mouseout", () => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
							.on("dblclick", () => {
								if (conceptos) {
									let datos_map = d3.map(d.values, (d) => d.key);

									if (document.getElementById(`select_${datos_map.get(f.key).values[0].cod_planif}`) != null) {
										d3.select(`#cod_${datos_map.get(f.key).values[0].cod_planif}`).text(`${datos_map.get(f.key).values[0].concepto}`);
									} else {
										let select = d3.select(`#cod_${datos_map.get(f.key).values[0].cod_planif}`)
											.text("").append("select").attr("id", `select_${datos_map.get(f.key).values[0].cod_planif}`)
											.style("width", "40px").on("change", () => {
												let index = document.getElementById(`select_${datos_map.get(f.key).values[0].cod_planif}`).selectedIndex;
												guardar_cambio_concepto(datos_map.get(f.key).values[0].cod_planif, conceptos[index].turno, () => {
													B_reporte("T");
												});
											});
										conceptos.forEach((d) => {
											if (datos_map.get(f.key).values[0].cod_turno == d.turno) {
												select.append("option").attr("value", d.turno).attr("selected", "selected").text(`${d.descripcion}(${d.conceptos})`);
											} else {
												select.append("option").attr("value", d.turno).text(`${d.descripcion}(${d.conceptos})`);
											}

										});
									}
								}

							})
							.text(val_fechas.get(f.key).values[0].concepto);
					} else {
						d3.select('#tr_ficha_' + ubic.key + '_' + d.key).append('td').attr("id", `cod_${d.values[0].values[0].ficha}_${f.key}`)
							.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' (' + d.values[0].values[0].puesto + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + ' ' + f.values[0].values[0].mes_anio + f.key.substring(8))

							.on("dblclick", () => {
								if (conceptos) {
									if (document.getElementById(`select_${d.values[0].values[0].ficha}_${f.key}`) != null) {
										d3.select(`#cod_${d.values[0].values[0].ficha}_${f.key}`).text('');
									} else {
										let parametros = {
											apertura: d.values[0].values[0].planif_cl,
											planif_cl_trab: d.values[0].values[0].planif_cl_trab,
											cliente: d.values[0].values[0].cod_cliente,
											ubicacion: d.values[0].values[0].cod_ubicacion,
											puesto_trab: d.values[0].values[0].cod_puesto,
											turno: 0,
											ficha: d.values[0].values[0].ficha,
											fecha: f.key,
											usuario: $("#usuario").val()

										}

										let select = d3.select(`#cod_${d.values[0].values[0].ficha}_${f.key}`)
											.text("").append("select").attr("id", `select_${d.values[0].values[0].ficha}_${f.key}`)
											.style("width", "40px").on("change", () => {
												let index = document.getElementById(`select_${d.values[0].values[0].ficha}_${f.key}`).selectedIndex;
												parametros.turno = conceptos[index].turno;

												guardar_cambio_concepto("", parametros, () => {

													B_reporte("F");
												});
											});
										conceptos.forEach((d) => {
											select.append("option").attr("value", d.turno).text(`${d.descripcion}(${d.conceptos})`);
										});
									}
								}
							})
							.text('');
					}
				});
			});
		} else {
			d3.select('#tbody_servicio_' + ubic.key).append('tr').append('td').text('Sin Planificacion de Trabajadores!..')
				.attr('colspan', (d) => 3 + Number(ubic.values.length));
		}
	});

	tablas.append('tbody').attr('id', (d) => "thead_conceptos_" + d.key);

	res_concepto.forEach((ubic, i) => {

		ubic.values.forEach((puesto, i) => {
			puesto.values.forEach((d) => {
				d3.select('#thead_conceptos_' + ubic.key).append("tr").attr("id", "tr_conceptos_" + ubic.key + "_" + puesto.key + '_' + d.key).attr('class', 'color');
			});

			if (i == 0) d3.select("#tr_conceptos_" + ubic.key + "_" + puesto.key + "_" + puesto.values[0].key).append('th').attr('class', 'etiqueta')
				.attr('colspan', 2).attr('rowspan', () => {
					var rows = 0;
					ubic.values.forEach((d) => {
						rows += Number(d.values.length);
					});
					return rows;
				}).text((d) => 'Resumen Conceptos');

			d3.select("#tr_conceptos_" + ubic.key + "_" + puesto.key + "_" + puesto.values[0].key).append('td')
				.attr('rowspan', puesto.values.length).text(puesto.values[0].values[0].values[0].puesto);

			puesto.values.forEach((d) => {
				d3.select("#tr_conceptos_" + ubic.key + "_" + puesto.key + "_" + d.key).append('td').text(d.values[0].values[0].concepto);
				val_fechas = d3.map(d.values, (d) => d.key);
				if (val_ubic.has(ubic.key)) {
					val_ubic.get(ubic.key).values.forEach((f) => {
						if (val_fechas.has(f.key)) {
							d3.select("#tr_conceptos_" + ubic.key + "_" + puesto.key + "_" + d.key).append('td')
								.attr('title', 'RESUMEN CONCEPTO ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].concepto + ') ' + f.key)
								.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8))
								.text(val_fechas.get(f.key).values.length);
						} else {
							d3.select("#tr_conceptos_" + ubic.key + "_" + puesto.key + "_" + d.key).append('td')
								.attr('title', 'RESUMEN CONCEPTO ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].concepto + ') ' + f.key)
								.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text(0);
						}
					});
				}
			});
		});
	});
	tablas.append('tbody').attr("id", (d) => "thead_horarios_" + d.key);

	res_horario.forEach((ubic, i) => {
		ubic.values.forEach((puesto, i) => {
			puesto.values.forEach((d) => {
				d3.select('#thead_horarios_' + ubic.key).append("tr").attr("id", "tr_horarios_" + ubic.key + "_" + puesto.key + '_' + d.key).attr('class', 'color');
			});

			if (i == 0) d3.select("#tr_horarios_" + ubic.key + "_" + puesto.key + "_" + puesto.values[0].key).append('th').attr('class', 'etiqueta')
				.attr('colspan', 2).attr('rowspan', () => {
					var rows = 0;
					ubic.values.forEach((d) => {
						rows += Number(d.values.length);
					});
					return rows;
				}).text((d) => 'Resumen Horarios');

			d3.select("#tr_horarios_" + ubic.key + "_" + puesto.key + "_" + puesto.values[0].key).append('td')
				.attr('rowspan', puesto.values.length).text(puesto.values[0].values[0].values[0].puesto);

			puesto.values.forEach((d) => {
				d3.select("#tr_horarios_" + ubic.key + "_" + puesto.key + "_" + d.key).append('td').text(d.values[0].values[0].horario);
				val_fechas = d3.map(d.values, (d) => d.key);
				if (val_ubic.has(ubic.key)) {
					val_ubic.get(ubic.key).values.forEach((f) => {
						if (val_fechas.has(f.key)) {
							d3.select("#tr_horarios_" + ubic.key + "_" + puesto.key + "_" + d.key).append('td')
								.attr('title', 'RESUMEN HORARIOS ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].horario + ') ' + f.key)
								.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8))
								.text(val_fechas.get(f.key).values.length);
						} else {
							d3.select("#tr_horarios_" + ubic.key + "_" + puesto.key + "_" + d.key).append('td')
								.attr('title', 'RESUMEN HORARIOS ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].horario + ') ' + f.key)
								.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text(0);
						}
					});
				}
			});
		});
	});


	var theads = tablas.append('thead').attr('id', (d) => "thead_diferencia_" + d.key);

	trh = theads.append('tr').attr("id", (d) => "tr_diferencia_" + d.key);
	trh.append('th').attr('class', 'etiqueta').attr('colspan', 4).text('Diferencia');

	res_fecha_cont.forEach((d) => {
		d3.select("#tr_diferencia_" + d.key).selectAll(".fechas").data(d.values).enter().append("th")
			.attr('title', (d) => d.values[0].values[0].dia_semana).attr('class', 'etiqueta fechas' +
				' ' + d.values[0].values[0].mes_anio + d.key.substring(8)).text((d) => d.key.substring(8))
	});

	tablas.append('thead').attr("id", (d) => "tbody_diferencia_" + d.key);
	val_ubic_h = d3.map(res_horario, (d) => d.key);

	res_horario_cont.forEach((ubic) => {
		val_puestos = d3.map(ubic.values, (d) => d.key);
		ubic.values.forEach((puesto, i) => {
			val_horarios = d3.map(puesto.values, (d) => d.key);
			puesto.values.forEach((d) => {
				d3.select("#tbody_diferencia_" + ubic.key).append("tr").attr('id', 'tr_diferencia_' + ubic.key + '_' + puesto.key + '_' + d.key).attr('class', 'color');
			});

			d3.select('#tr_diferencia_' + ubic.key + '_' + puesto.key + '_' + puesto.values[0].key).append('th').attr('rowspan', puesto.values.length)
				.attr('colspan', 2).text(puesto.values[0].values[0].values[0].puesto);

			puesto.values.forEach((d) => {
				val_fechas = d3.map(d.values, (d) => d.key);
				d3.select('#tr_diferencia_' + ubic.key + '_' + puesto.key + '_' + d.key).append('td').attr('colspan', 2).text(d.values[0].values[0].horario);
				val_ubic.get(ubic.key).values.forEach((f) => {
					if (val_fechas.has(f.key)) {
						val_fechas.get(f.key).values.forEach((d, i) => {
							cantidad += Number(d.cantidad);
						});
					}

					var cantidad_horario = 0, diff = 0;
					if (val_ubic_h.has(ubic.key)) {
						val_puestos_h = d3.map(val_ubic_h.get(ubic.key).values, (d) => d.key);
						if (val_puestos_h.has(puesto.key)) {
							val_horarios_h = d3.map(val_puestos_h.get(puesto.key).values, (d) => d.key);
							if (val_horarios_h.has(d.key)) {
								val_fechas_h = d3.map(val_horarios_h.get(d.key).values, (d) => d.key);
								if (val_fechas_h.has(f.key)) {
									cantidad_horario += val_fechas_h.get(f.key).values.length;
								} else {
									cantidad_horario = 0;
								}
							}
						}
						diff = cantidad_horario - cantidad;
					} else {
						diff = 0 - cantidad;
					}

					d3.select('#tr_diferencia_' + ubic.key + '_' + puesto.key + "_" + d.key).append('td').attr('title', 'DIFERENCIA ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].horario + ') ' + f.key)
						.on("mouseover", (d) => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
						.on("mouseout", () => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
						.text(() => {
							if (diff == 0) return 'OK'; else return diff;
						}).attr('class', 'cantidad ' + validarFondo(diff));
					cantidad = 0;
				});
			});
		});
	});
}

function rp_planif_serv_update_detalle(cod_ubicacion, tipo) {

	map_res_horario_contrato.get(cod_ubicacion).values.forEach((puesto) => {

		puesto.values.forEach((horario) => {

			horario.values.forEach((cargo) => {

				val_fechas = d3.map(cargo.values, (d) => d.key);
				res_fecha_cont_keys.forEach((fec) => {

					if (val_fechas.has(fec)) {
						val_fechas.get(fec).values.forEach((d) => {
							if (tipo == 'H') {
								cantidad += Number(d.horas);
							} else if (tipo == 'C') {
								cantidad += Number(d.cantidad);
							}
						});
					}
					d3.select('#td_' + cod_ubicacion + '_' + puesto.key + '_' + horario.key + "_" + cargo.key + "_" + fec.substring(8)).text(cantidad);
					cantidad = 0;
				});
			});
		});

	});

	map_res_horario_cont.get(cod_ubicacion).values.forEach((puesto, i) => {
		val_horarios = d3.map(puesto.values, (d) => d.key);
		puesto.values.forEach((d) => {
			trb = d3.select('#tbody_contrato_' + cod_ubicacion + '_' + puesto.key + '_' + d.key + "_total").selectAll('.cantidad').remove();
		});

		puesto.values.forEach((d) => {
			val_fechas = d3.map(d.values, (d) => d.key);
			val_ubic.get(cod_ubicacion).values.forEach((f) => {
				if (val_fechas.has(f.key)) {
					val_fechas.get(f.key).values.forEach((d, i) => {
						if (tipo == 'H') {
							cantidad += Number(d.horas);
						} else if (tipo == 'C') {
							cantidad += Number(d.cantidad);
						}
					});
				}

				d3.select('#tbody_contrato_' + cod_ubicacion + '_' + puesto.key + '_' + d.key + "_total").append('td')
					.attr('title', 'CANTIDAD DEMANDA ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].horario + ') ' + f.key)
					.attr('class', 'cantidad ' + f.values[0].values[0].values[0].mes_anio + ' ' + f.values[0].values[0].values[0].mes_anio + f.key.substring(8))
					.text(cantidad);
				cantidad = 0;
			});
		});
	});

	val_ubic_f.get(cod_ubicacion).values.forEach((d) => {
		val_fechas = d3.map(d.values, (d) => d.key);
		trb = d3.select("#tr_ficha_" + cod_ubicacion + "_" + d.key).selectAll('.cantidad').remove();
		map_res_fec_cont.get(cod_ubicacion).values.forEach((f) => {
			if (val_fechas.has(f.key)) {
				if (tipo == 'H') {
					d3.select('#tr_ficha_' + cod_ubicacion + '_' + d.key).append('td')
						.attr('title', '(' + d.key + ') ' + f.values[0].ap_nombre + ' (' + f.values[0].puesto + ') ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].mes_anio + ' ' + f.values[0].mes_anio + f.key.substring(8))
						.on("mouseover", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
						.on("mouseout", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
						.text(Number(val_fechas.get(f.key).values[0].horas));
				} else if (tipo == 'C') {
					d3.select('#tr_ficha_' + cod_ubicacion + '_' + d.key).append('td')
						.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' (' + f.values[0].puesto + ') ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].mes_anio + ' ' + f.values[0].mes_anio + f.key.substring(8))
						.on("mouseover", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
						.on("mouseout", () => d3.selectAll('.' + f.values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
						.text(val_fechas.get(f.key).values[0].concepto);
				}
			} else {
				d3.select('#tr_ficha_' + cod_ubicacion + '_' + d.key).append('td')
					.attr('title', '(' + d.key + ') ' + d.values[0].values[0].ap_nombre + ' (' + f.values[0].puesto + ') ' + f.key)
					.attr('class', 'cantidad ' + f.values[0].mes_anio + ' ' + f.values[0].mes_anio + f.key.substring(8)).text('');
			}
		});
	});

	map_res_horario.get(cod_ubicacion).values.forEach((puesto, i) => {
		puesto.values.forEach((d) => {
			d3.select("#tr_horarios_" + cod_ubicacion + "_" + puesto.key + '_' + d.key).selectAll(".cantidad").remove();
		});

		puesto.values.forEach((d) => {
			val_fechas = d3.map(d.values, (d) => d.key);
			val_ubic.get(cod_ubicacion).values.forEach((f) => {
				horas = 0;
				if (val_fechas.has(f.key)) {
					if (tipo == 'H') {
						d3.select("#tr_horarios_" + cod_ubicacion + "_" + puesto.key + "_" + d.key).append('td')
							.attr('title', 'RESUMEN HORARIOS (' + d.key + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8))
							.text(() => {
								val_fechas.get(f.key).values.forEach((d) => {
									horas += Number(d.horas);
								});
								return horas;
							});
					} else if (tipo == 'C') {
						d3.select("#tr_horarios_" + cod_ubicacion + "_" + puesto.key + "_" + d.key).append('td')
							.attr('title', 'RESUMEN HORARIOS (' + d.key + ') ' + f.key)
							.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8))
							.text(val_fechas.get(f.key).values.length);
					}

				} else {
					d3.select("#tr_horarios_" + cod_ubicacion + "_" + puesto.key + "_" + d.key).append('td')
						.attr('title', 'RESUMEN HORARIOS (' + d.key + ') ' + f.key)
						.attr('class', 'cantidad ' + f.values[0].values[0].mes_anio + f.key.substring(8)).text(0);
				}
			});
		});
	});

	map_res_horario_cont.get(cod_ubicacion).values.forEach((puesto, i) => {
		val_horarios = d3.map(puesto.values, (d) => d.key);
		puesto.values.forEach((d) => {
			d3.select('#tr_diferencia_' + cod_ubicacion + '_' + puesto.key + '_' + d.key).selectAll('.cantidad').remove();
		});

		puesto.values.forEach((d) => {
			val_fechas = d3.map(d.values, (d) => d.key);
			val_ubic.get(cod_ubicacion).values.forEach((f) => {
				if (val_fechas.has(f.key)) {
					if (tipo == 'H') {
						val_fechas.get(f.key).values.forEach((d, i) => {
							cantidad += Number(d.horas);
						});
					} else if (tipo == 'C') {
						val_fechas.get(f.key).values.forEach((d, i) => {
							cantidad += Number(d.cantidad);
						});
					}
				}

				var cantidad_horario = 0, diff = 0;
				val_puestos_h = d3.map(val_ubic_h.get(cod_ubicacion).values, (d) => d.key);
				if (val_puestos_h.has(puesto.key)) {
					val_horarios_h = d3.map(val_puestos_h.get(puesto.key).values, (d) => d.key);
					if (val_horarios_h.has(d.key)) {
						val_fechas_h = d3.map(val_horarios_h.get(d.key).values, (d) => d.key);
						if (val_fechas_h.has(f.key)) {
							if (tipo == 'H') {
								val_fechas_h.get(f.key).values.forEach(d => {
									cantidad_horario += Number(d.horas);
								});
							} else if (tipo == 'C') {
								cantidad_horario += val_fechas_h.get(f.key).values.length;
							}
						} else {
							cantidad_horario = 0;
						}
					}
				}
				diff = cantidad_horario - cantidad;

				d3.select('#tr_diferencia_' + cod_ubicacion + '_' + puesto.key + "_" + d.key).append('td').attr('title', 'DIFERENCIA ' + d.values[0].values[0].puesto + ' (' + d.values[0].values[0].horario + ') ' + f.key)
					.on("mouseover", (d) => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', true))
					.on("mouseout", () => d3.selectAll('.' + f.values[0].values[0].mes_anio + f.key.substring(8)).classed('yellow', false))
					.text(() => {
						if (diff == 0) return 'OK'; else return diff;
					}).attr('class', 'cantidad ' + validarFondo(diff));
				cantidad = 0;
			});
		});
	});
}

function limpiarContenedor(id_contenedor) {
	d3.select('#' + id_contenedor).selectAll('div').remove();
	d3.select('#' + id_contenedor).selectAll('table').remove();
}

function validarColor(num) {
	if (num > 0) {
		color = "#4CAF50";
	} else if (num < 0) {
		color = "#ff3c1e";
	} else {
		color = "#EAFFEA";
	}
	return color;
}

function validarFondo(num) {
	if (num > 0) {
		clase = "fondo02";
	} else if (num < 0) {
		clase = "fondo03";
	} else {
		clase = "fondo01";
	}
	return clase;
}

function validarFondoTr(num) {
	if ((num % 2) == 0) {
		clase = 'fondo01'
	} else {
		clase = 'fondo02';
	}
	return clase;
}

/*SE USA PARA TRABAJADORES NECESARIOS PARA CUBRIR SERVICIOS (REPORTES OPERACIONALES)*/

function rp_planif_contratacion_vs_trab_cubrir(data, id_contenedor, callback) {
	if (d3.select('#' + id_contenedor).node()) {
		limpiarContenedor('id_contenedor');

		res_contratacion = d3.nest()
			.key((d) => d.cod_cliente).sortKeys(d3.ascending)
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.entries(data['contrato']);

		res_activos = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.entries(data['trab_activos']);

		if (data['excepcion'] !== undefined) {
			res_excepcion = d3.nest()
				.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
				.entries(data['excepcion']);
			var map_res_excepcion = d3.map(res_excepcion, (d) => d.key);
		}

		var map_res_activos = d3.map(res_activos, (d) => d.key);

		d3.select('#' + id_contenedor).append('table').attr('id', 't_reporte').attr('width', '100%').attr('border', 0).attr('align', 'center');
		d3.select('#t_reporte').append('thead').attr('id', 'thead');
		d3.select('#t_reporte').append('tbody').attr('id', 'tbody');
		d3.select('#thead').append('tr').attr('class', 'fondo00')
			.html('<th width="15%" class="etiqueta">Fecha</th>' +
				'<th width="15%" class="etiqueta">Region</th>' +
				'<th width="15%" class="etiqueta">Estado</th>' +
				'<th width="10%" class="etiqueta">Cod. Empresa</th>' +
				'<th width="15%" class="etiqueta">Empresa</th>' +
				'<th width="10%" class="etiqueta">Cod. ubicacion</th>' +
				'<th width="15%" class="etiqueta">Ubicacion</th>' +
				'<th width="5%" class="etiqueta">Pl. Cantidad </th>' +
				'<th width="5%" class="etiqueta">Trab. Necs.</th>' +
				'<th width="5%" class="etiqueta">Hombres Activos</th>' +
				'<th width="5%" class="etiqueta">Pl. Excepcion</th>' +
				'<th width="5%" class="etiqueta">Diferencia</th>');
		d3.select('#t_reporte').selectAll('.tbody2').data(res_contratacion).enter().append('tbody').attr('class', 'tbody2')
			.attr('id', (d) => {
				return 'body_' + d.key;
			});

		res_contratacion.forEach((d) => {
			d3.select('#body_' + d.key).selectAll('tr').data(d.values).enter().append('tr')
				.attr('class', (e) => {
					factor = 0, trab_neces = 0, trab_activos = 0, excepcion = 0, color = ''; cantidad = 0;
					
					if (data['excepcion'] !== undefined) {
						if (map_res_excepcion.has(e.key)) {
							excepcion = Number(map_res_excepcion.get(e.key).values[0].cantidad);
						}
					}

					let valorMax  = e.values.reduce((previous, current) => {
						return Number(current.trab_neces) > Number(previous.trab_neces) ? current : previous;
					});
					e.values = [valorMax];
					e.values.forEach((f, i) => {
						cantidad += Number(f.cantidad);
						trab_neces += Number(f.trab_neces);
						if (i == 0) {
							if (map_res_activos.has(f.cod_ubicacion)) {
								map_res_activos.get(f.cod_ubicacion).values.forEach((g) => {
									trab_activos += Number(g.cantidad);
								});
							}
						}
					});
					factor = (trab_activos - excepcion) - trab_neces;
					color = 'color ' + validarFondo(factor);
					return color;
				}).html((e) => {
					factor = 0, trab_neces = 0, trab_activos = 0, excepcion = 0; cantidad = 0;

					if (data['excepcion'] !== undefined) {
						if (map_res_excepcion.has(e.key)) {
							excepcion = Number(map_res_excepcion.get(e.key).values[0].cantidad);
						}
					}

					let valorMax  = e.values.reduce((previous, current) => {
						return Number(current.trab_neces) > Number(previous.trab_neces) ? current : previous;
					});
					e.values = [valorMax];
					e.values.forEach((f, i) => {
						cantidad += Number(f.cantidad);
						trab_neces += Number(f.trab_neces);
						if (i == 0) {
							if (map_res_activos.has(f.cod_ubicacion)) {
								map_res_activos.get(f.cod_ubicacion).values.forEach((g) => {
									trab_activos += Number(g.cantidad);
								});
							}
						}
					});
					factor = Math.floor((trab_activos - excepcion) - trab_neces);
					if (factor == 0) factor = 'OK';
					
					return '<td class="texto" id="center" >' + e.values[0].fecha + '</td><td class="texto" id="center" >' + e.values[0].region 
					 + '</td><td class="texto" id="center" >' + e.values[0].estado
					 + '</td><td class="texto" id="center" >' + e.values[0].cod_cliente + '</td><td class="texto" id="center" >' + e.values[0].cliente 
					 + '</td><td class="texto" id="center" >' + e.values[0].cod_ubicacion + '</td><td class="texto" id="center" >' + e.values[0].ubicacion 
					 + '</td><td class="texto" id="center" >' + cantidad + '</td><td class="texto" id="center" >' + trab_neces 
					 + '</td><td class="texto" id="center" >' + trab_activos + '</td><td class="texto" id="center" >' + excepcion 
					 + '</td><td class="texto" id="center" >' + factor + '</td>';
				});
		});
		if (typeof (callback) == 'function') callback();
	}
}

function rp_planif_serv_vs_contratacion_horario(data, id_contenedor, callback) {
	if (d3.select('#' + id_contenedor).node()) {
		limpiarContenedor('id_contenedor');

		res_horario = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(data['asistencia']);

		map_res_horario = d3.map(res_horario, (d) => d.key);

		res_horario_cont = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(data['contrato']);

		map_res_horario_cont = d3.map(res_horario_cont, (d) => d.key);

		d3.select('#' + id_contenedor).append('table').attr('id', 't_reporte').attr('width', '100%').attr('border', 0).attr('align', 'center');
		d3.select('#t_reporte').append('thead').attr('id', 'thead');
		d3.select('#t_reporte').append('thead').attr('id', 'tbody_pl_vs_as');
		d3.select('#thead').append('tr').attr('class', 'fondo00').html('<th width="8%" class="etiqueta">Fecha</th><th width="20%" class="etiqueta">Horario</th><th width="20%" class="etiqueta">Estado</th><th width="22%" class="etiqueta">Cliente<th width="22%" class="etiqueta">Ubicacion<th width="8%" class="etiqueta">Factor</th>');
		var horarios = [];
		var ubicaciones = [];
		var fechas = [];
		data['contrato'].forEach((a) => {
			var sum_dia = 0;
			var color = '';
			var clases = '';
			var factor = 0;

			if (map_res_horario.has(a.cod_ubicacion)) {
				val_ubic_h = d3.map(map_res_horario.get(a.cod_ubicacion).values, (d) => d.key);
				if (val_ubic_h.has(a.cod_horario)) {
					val_ubic_f = d3.map(val_ubic_h.get(a.cod_horario).values, (d) => d.key);
					if (val_ubic_f.has(a.fecha)) {
						val_ubic_f.get(a.fecha).values.forEach((d) => { sum_dia += Number(d.valor) });
					}
					factor = sum_dia - a.cantidad;
					color = validarFondo(factor);
					clases = 'color ' + color;
					if (factor == 0) factor = 'OK';
					$('#tbody_pl_vs_as').append('<tr class="' + clases + '"><td class="texto" id="center" >' + a.fecha + '</td><td class="texto" id="center" >' + a.horario + '</td><td class="texto" id="center" >' + a.estado + '</td><td class="texto" id="center" >' + a.cliente + '</td><td class="texto" id="center" >' + a.ubicacion + '</td><td class="texto" id="center" >' + factor + '</td></tr>');
				} else {
					color = validarFondo(factor);
					clases = 'color ' + color;
					$('#tbody_pl_vs_as').append('<tr class="' + clases + '"><td class="texto" id="center" >' + a.fecha + '</td><td class="texto" id="center" >' + a.horario + '</td><td class="texto" id="center" >' + a.estado + '</td><td class="texto" id="center" >' + a.cliente + '</td><td class="texto" id="center" >' + a.ubicacion + '</td><td class="texto" id="center" >' + (0 - Number(a.cantidad)) + '</td></tr>');
				}
			}

			res_horario.forEach((b) => {
				sum_dia = 0;
				color = '';
				clases = '';
				if (b.key === a.cod_ubicacion) {
					b.values.forEach(c => {
						val_ubic_h = d3.map(map_res_horario_cont.get(a.cod_ubicacion).values, (d) => d.key);
						if (!val_ubic_h.has(c.key) && (ubicaciones.indexOf(a.cod_ubicacion) === -1 || fechas.indexOf(a.fecha) === -1 || horarios.indexOf(c.key) === -1)) {
							ubicaciones.push(a.cod_ubicacion);
							fechas.push(a.fecha);
							horarios.push(c.key);
							val_ubic_f = d3.map(c.values, (f) => f.key);
							if (val_ubic_f.has(a.fecha)) {
								sum_dia = 0;
								val_ubic_f.get(a.fecha).values.forEach(e => { sum_dia += Number(e.valor) });
								color = validarFondo(sum_dia);
								clases = 'color ' + color;
								$('#tbody_pl_vs_as').append('<tr class="' + clases + '"> <td class="texto" id="center" >' + a.fecha + '</td><td class="texto" id="center" >' + val_ubic_f.get(a.fecha).values[0].horario + '</td><td class="texto" id="center" >' + a.estado + '</td><td class="texto" id="center" >' + a.cliente + '</td><td class="texto" id="center" >' + a.ubicacion + '</td><td class="texto" id="center" >' + sum_dia + '</td>');
							}
						}
					})

				}
			});
		});

		if (typeof (callback) == 'function') callback();
	}
}

function rp_planif_trab_vs_asistencia(data, id_contenedor, callback) {
	if (d3.select('#' + id_contenedor).node()) {
		limpiarContenedor('id_contenedor');

		res_horario = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(data['asistencia']);

		map_res_horario = d3.map(res_horario, (d) => d.key);

		res_horario_cont = d3.nest()
			.key((d) => d.cod_ubicacion).sortKeys(d3.ascending)
			.key((d) => d.cod_horario).sortKeys(d3.ascending)
			.key((d) => d.fecha).sortKeys(d3.ascending)
			.entries(data['servicio']);

		d3.select('#' + id_contenedor).append('table').attr('id', 't_reporte').attr('width', '100%').attr('border', 0).attr('align', 'center');
		d3.select('#t_reporte').append('thead').attr('id', 'thead');
		d3.select('#t_reporte').append('thead').attr('id', 'tbody_pl_vs_as');
		d3.select('#thead').append('tr').attr('class', 'fondo00').html('<th width="8%" class="etiqueta">Fecha</th><th width="20%" class="etiqueta">Horario</th><th width="20%" class="etiqueta">Estado</th><th width="22%" class="etiqueta">Cliente<th width="22%" class="etiqueta">Ubicacion<th width="8%" class="etiqueta">Factor</th>');

		d3.select('#tbody_pl_vs_as').selectAll('tr').data(data['servicio']).enter().append('tr')
			.attr('title', (d) => 'Click para ver Detalles \n ' + d.cliente + ' \n ' + d.ubicacion + ' \n ' + d.horario + ' \n ' + d.fecha)
			.attr('class', (a) => {
				sum_dia = 0; color = '';
				if (map_res_horario.has(a.cod_ubicacion)) {
					val_ubic_h = d3.map(map_res_horario.get(a.cod_ubicacion).values, (d) => d.key);
					if (val_ubic_h.has(a.cod_horario)) {
						val_ubic_f = d3.map(val_ubic_h.get(a.cod_horario).values, (d) => d.key);
						if (val_ubic_f.has(a.fecha)) {
							val_ubic_f.get(a.fecha).values.forEach((d) => { sum_dia += Number(d.valor) });
						}
						factor = sum_dia - a.cantidad;
						color = validarFondo(factor);
						return 'color ' + color;
					} else {
						factor = 0 - Number(a.cantidad);
						color = validarFondo(factor);
						return 'color ' + color;
					}
				} else {
					factor = 0 - Number(a.cantidad);
					color = validarFondo(factor);
					return 'color ' + color;
				}
			}).html((a) => {
				sum_dia = 0;
				if (map_res_horario.has(a.cod_ubicacion)) {
					val_ubic_h = d3.map(map_res_horario.get(a.cod_ubicacion).values, (d) => d.key);
					if (val_ubic_h.has(a.cod_horario)) {
						val_ubic_f = d3.map(val_ubic_h.get(a.cod_horario).values, (d) => d.key);
						if (val_ubic_f.has(a.fecha)) {
							val_ubic_f.get(a.fecha).values.forEach((d) => { sum_dia += Number(d.valor) });
						}
						factor = sum_dia - a.cantidad;
						if (factor == 0) factor = 'OK';
						return '<td class="texto" id="center" >' + a.fecha + '</td><td class="texto" id="center" >' + a.horario + '</td><td class="texto" id="center" >' + a.estado + '</td><td class="texto" id="center" >' + a.cliente + '</td><td class="texto" id="center" >' + a.ubicacion + '</td><td class="texto" id="center" >' + factor + '</td>';
					} else {
						return '<td class="texto" id="center" >' + a.fecha + '</td><td class="texto" id="center" >' + a.horario + '</td><td class="texto" id="center" >' + a.estado + '</td><td class="texto" id="center" >' + a.cliente + '</td><td class="texto" id="center" >' + a.ubicacion + '</td><td class="texto" id="center" >' + (0 - Number(a.cantidad)) + '</td>';
					}
				} else {
					return '<td class="texto" id="center" >' + a.fecha + '</td><td class="texto" id="center" >' + a.horario + '</td><td class="texto" id="center" >' + a.estado + '</td><td class="texto" id="center" >' + a.cliente + '</td><td class="texto" id="center" >' + a.ubicacion + '</td><td class="texto" id="center" >' + (0 - Number(a.cantidad)) + '</td>';
				}
			}).on("click", (d) => B_reporte(d));

		if (typeof (callback) == 'function') callback();
	}
}