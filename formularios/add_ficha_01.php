<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<!-- Styles Google maps searchs autocomplete list -->
<style>
	/* Google Maps */

	.label {
		box-sizing: border-box;
		background: #05F24C;
		box-shadow: 2px 2px 4px #333;
		border: 5px solid #346FF7;
		height: 20px;
		width: 20px;
		border-radius: 10px;
		-webkit-animation: pulse 1s ease 1s 3;
		-moz-animation: pulse 1s ease 1s 3;
		animation: pulse 1s ease 1s 3;
	}

	.autocomplete-input-container {
		position: absolute;
		z-index: 1;
		width: 100%;
	}

	.autocomplete-input {
		text-align: center;
	}

	#my-input-autocomplete {
		box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
		font-size: 15px;
		border-radius: 3px;
		border: 0;
		margin-top: 10px;
		width: 290px;
		height: 40px;
		text-overflow: ellipsis;
		padding: 0 1em;
	}

	#my-input-autocomplete:focus {
		outline: none;
	}

	.autocomplete-results {
		margin: 0 auto;
		right: 0;
		left: 0;
		position: absolute;
		display: none;
		background-color: white;
		width: 80%;
		padding: 0;
		list-style-type: none;
		margin: 0 auto;
		border: 1px solid #d2d2d2;
		border-top: 0;
		box-sizing: border-box;
	}

	.autocomplete-item {
		padding: 5px 5px 5px 35px;
		height: 26px;
		line-height: 26px;
		border-top: 1px solid #d9d9d9;
		position: relative;
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
	}

	.autocomplete-icon {
		display: block;
		position: absolute;
		top: 7px;
		bottom: 0;
		left: 8px;
		width: 20px;
		height: 20px;
		background-repeat: no-repeat;
		background-position: center center;
	}

	.autocomplete-icon.icon-localities {
		background-image: url(https://images.woosmap.com/icons/locality.svg);
	}

	.autocomplete-item:hover .autocomplete-icon.icon-localities {
		background-image: url(https://images.woosmap.com/icons/locality-selected.svg);
	}

	.autocomplete-item:hover {
		background-color: #f2f2f2;
		cursor: pointer;
	}

	.autocomplete-results::after {
		content: "";
		padding: 1px 1px 1px 0;
		height: 18px;
		box-sizing: border-box;
		text-align: right;
		display: block;
		background-image: url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3_hdpi.png);
		background-position: right;
		background-repeat: no-repeat;
		background-size: 120px 14px
	}
</style>
<script type="text/javascript">
	function Pdf() {
		$('#pdf').attr('action', "reportes/rp_ficha.php");
		$('#pdf').submit();
	}

	function validar_militar() {
		if ($('#servicio_militar').prop('checked')) {
			$('#seccion_militar').show();
		} else {
			$('#seccion_militar').hide();
		}
	}

	var map;
	var marker;
function validarfechaingreso(fecha){
	var Hoy = new Date();//Fecha actual del sistema
    var fechaactual= new Date(fecha);
var AnyoFecha = fechaactual.getFullYear();
var MesFecha = fechaactual.getMonth();
var DiaFecha = fechaactual.getDate();
 
var AnyoHoy = Hoy.getFullYear();
var MesHoy = Hoy.getMonth();
var DiaHoy = Hoy.getDate();
 
if (AnyoFecha < AnyoHoy){
    alert ("Error, La fecha de ingreso no debe ser mayor a la fecha actual");
}
else{
    if (AnyoFecha == AnyoHoy && MesFecha < MesHoy){
        alert ("La fecha introducida es anterior a Hoy");			
    }
    else{
        if (AnyoFecha == AnyoHoy && MesFecha == MesHoy && DiaFecha < DiaHoy){
            alert ("La fecha introducida es anterior a Hoy");
        }
        else{
            if (AnyoFecha == AnyoHoy && MesFecha == MesHoy && DiaFecha == DiaHoy){
                 alert ("Has introducido la fecha de Hoy");
            }
            else{
                alert ("Error, La fecha de ingreso no debe ser mayor a la fecha actual");
            }
        }
    }
}

    
}
	function closeModalMap() {
		$("#myModalMap").hide();
	}

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this,
				args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

	function displaySuggestions(predictions, status) {
		var inputContainer = document.querySelector('autocomplete-input-container');
		var serviceDetails = new google.maps.places.PlacesService(map);
		var autocomplete_input = document.getElementById('my-input-autocomplete');
		var autocomplete_results = document.querySelector('.autocomplete-results');
		if (status != google.maps.places.PlacesServiceStatus.OK) {
			toastr.error(status);
			return;
		}
		var results_html = [];
		predictions.forEach(function(prediction) {
			results_html.push(`<li class="autocomplete-item" data-type="place" data-place-id=${prediction.place_id}><span class="autocomplete-icon icon-localities"></span>      			    <span class="autocomplete-text">${prediction.description}</span></li>`);
		});
		autocomplete_results.innerHTML = results_html.join("");
		autocomplete_results.style.display = 'block';
		var autocomplete_items = autocomplete_results.querySelectorAll('.autocomplete-item');
		for (var autocomplete_item of autocomplete_items) {
			autocomplete_item.addEventListener('click', function() {
				var prediction = {};
				const selected_text = this.querySelector('.autocomplete-text').textContent;
				const place_id = this.getAttribute('data-place-id');
				var request = {
					placeId: place_id,
					fields: ['name', 'geometry']
				};

				serviceDetails.getDetails(request, function(place, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						if (!place.geometry) {
							console.log("Returned place contains no geometry");
							return;
						}
						var bounds = new google.maps.LatLngBounds();
						marker.setPosition(place.geometry.location);
						if (place.geometry.viewport) {
							bounds.union(place.geometry.viewport);
						} else {
							bounds.extend(place.geometry.location);
						}
						map.fitBounds(bounds);
					}
					autocomplete_input.value = selected_text;
					autocomplete_results.style.display = 'none';
				});
			})
		}
	};

	function initMap() {
		var inputContainer = document.querySelector('autocomplete-input-container');
		var autocomplete_input = document.getElementById('my-input-autocomplete');
		var direccion_input = $("#direccion_google").val();
		autocomplete_input.value = direccion_input;
		var autocomplete_results = document.querySelector('.autocomplete-results');
		var buttonSave = document.getElementById('buttonSave');
		var options = {
			types: ['address']
		}
		var lat = Number($("#latitud").val());
		var lng = Number($("#longitud").val());

		var positionInitial = {
			lat: lat === 0 || lat === undefined ? 10.1675248 : lat,
			lng: lng === 0 || lng === undefined ? -67.9637274 : lng
		}

		map = new google.maps.Map(document.getElementById("mapG"), {
			center: positionInitial,
			zoom: 18,
			streetViewControl: false,
			fullscreenControl: false,
			mapTypeControl: false
			//disableDefaultUI: true
		});

		/* 		map.controls[google.maps.ControlPosition.TOP_LEFT].push(autocomplete_input);
				map.controls[google.maps.ControlPosition.TOP_LEFT].push(buttonSave); */

		var service = new google.maps.places.AutocompleteService();

		autocomplete_input.addEventListener('input', debounce(function() {
			var value = this.value;
			value.replace('"', '\\"').replace(/^\s+|\s+$/g, '');
			if (value !== "" && this.value.length > 2) {
				service.getPlacePredictions({
					input: value,
					fields: ['name', 'geometry']
				}, (predictions, status) => {
					displaySuggestions(predictions, status);
				});
			} else {
				autocomplete_results.innerHTML = '';
				autocomplete_results.style.display = 'none';
			}
		}, 1500));

		/* 		
		var image = {
					url: $("#foto").attr("src"),
					scaledSize: new google.maps.Size(40, 40), // scaled size
					origin: new google.maps.Point(0, 0), // Origin
					anchor: new google.maps.Point(0, 0) // anchor
				}; 
		*/

		marker = new google.maps.Marker({
			map: map,
			draggable: true,
			position: positionInitial,
			anchorPoint: new google.maps.Point(0, -29),
			//icon: image
		});

		google.maps.event.addListener(marker, "dragend", function() {
			var point = marker.getPosition();
			map.panTo(point);
		});

		$("#myModalMap").show();
	}

	function saveLatLng() {
		var point_current = marker.getPosition();
		var lat = point_current.lat();
		var lng = point_current.lng();
		var error = 0;
		var errorMessage = ' ';
		if (error == 0) {
			var parametros = {
				"lat": point_current.lat(),
				"lng": point_current.lng(),
				"ficha": $("#cod_ficha").val(),
				"address": $('#my-input-autocomplete').val()
			};
			$.ajax({
				data: parametros,
				url: 'scripts/sc_lat_lng.php',
				type: 'post',
				success: (response) => {
					$("#latitud").val(lat);
					$("#longitud").val(lng);
					toastr.success('Guardado con éxito.');
				},
				error: function(xhr, ajaxOptions, thrownError) {
					toastr.error(xhr.status);
					toastr.error(thrownError);
				}
			});

		} else {
			toastr.error(errorMessage);
		}
	}
</script>
<?php
// require_once('autentificacion/aut_verifica_menu.php');
$archivo = "$area&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=0&metodo=modificar";
$proced    = "p_fichas";
if ($metodo == 'modificar' or $metodo == 'consultar') {

	$bd = new DataBase();

	$sql01 = "SELECT v_ficha.cod_ficha, v_ficha.cod_turno, v_ficha.cod_rol, v_ficha.rol,
	v_ficha.cod_cliente, v_ficha.cod_ubicacion,
	v_ficha.cod_contracto, v_ficha.contracto,
	v_ficha.cod_region,   v_ficha.region,
	v_ficha.cta_banco,
	v_ficha.carnet,  v_ficha.fec_carnet,
	v_ficha.fec_ingreso, v_ficha.fec_profit,
	v_ficha.fec_contracto, v_ficha.cod_us_ing,
	v_ficha.fec_us_ing,  CONCAT(men_usuarios.apellido,' ', men_usuarios.nombre) AS us_ing,
	v_ficha.cod_us_mod, v_ficha.fec_us_mod, v_ficha.cod_ficha_status,
	v_ficha.`status`, v_ficha.cedula,
	v_ficha.cod_cargo, v_ficha.cargo,
	v_ficha.cod_estado, v_ficha.estado,
	v_ficha.cod_ciudad, v_ficha.ciudad,
	v_ficha.nombres, v_ficha.apellidos,
	v_ficha.fec_nacimiento, v_ficha.lugar_nac,
	v_ficha.celular,
	v_ficha.correo, v_ficha.experiencia,
	v_ficha.sexo, v_ficha.telefono,
	v_ficha.direccion, v_ficha.direccion_google, v_ficha.observacion,
	v_ficha.cod_ficha_status_militar, v_ficha.status_militar_obs, v_ficha.servicio_militar
	FROM v_ficha LEFT JOIN men_usuarios ON v_ficha.cod_us_ing = men_usuarios.codigo
	WHERE v_ficha.cod_ficha = '$codigo' ";

	$query  = $bd->consultar($sql01);
	$result = $bd->obtener_fila($query, 0);

	$ced_read = 'readonly="readonly"';
	$cedula         = $result['cedula'];
	$cod_ficha      = $codigo;
	$nombres        = $result['nombres'];
	$apellidos        = $result['apellidos'];
	$fec_nacimiento = conversion($result['fec_nacimiento']);
	$lugar_nac      = $result['lugar_nac'];
	$carnet         = $result['carnet'];
	$fec_venc_carnet = conversion($result['fec_carnet']);
	$fec_ingreso    = conversion($result['fec_ingreso']);
	$sexo           = $result['sexo'];
	$telefono       = $result['telefono'];
	$celular        = $result['celular'];
	$experiencia    = $result['experiencia'];
	$correo         = $result['correo'];
	$direccion      = $result['direccion'];
	$direccion_google = $result['direccion_google'];
	$cod_estado     = $result['cod_estado'];
	$estado         = $result['estado'];
	$cod_ciudad     = $result['cod_ciudad'];
	$ciudad         = $result['ciudad'];
	$cod_cargo      = $result['cod_cargo'];
	$cargo          = $result['cargo'];
	$cod_contracto  = $result['cod_contracto'];
	$contracto      = $result['contracto'];
	$dias_venc_contrato  = $result['contracto'];
	$cod_rol        = $result['cod_rol'];
	$rol            = $result['rol'];
	$cod_region     = $result['cod_region'];
	$region         = $result['region'];
	$cta_banco      = $result['cta_banco'];
	$observacion    = $result["observacion"];
	$cod_status     = $result['cod_ficha_status'];
	$status         = $result['status'];
	$fec_profit     = conversion($result['fec_profit']);

	$fec_us_ing     = conversion($result['fec_us_ing']);
	$us_ing  	    = $result['us_ing'];

	$servicio_militar 		  = $result['servicio_militar'];
	$status_militar_obs 	  = $result['status_militar_obs'];
	$cod_ficha_status_militar = $result['cod_ficha_status_militar'];

	if ($servicio_militar == 'T') {
		$seccion_militar = 'display:block;';
	} else {
		$seccion_militar = 'display:none;';
	}

	$sql02 = "SELECT
				ficha.cod_nacionalidad,
				nacionalidad.descripcion AS nacionalidad,
				ficha.cod_estado_civil,
				estado_civil.descripcion AS estado_civil,
				ficha.cod_ocupacion,
				ocupacion.descripcion AS ocupacion,
				ficha.cod_nivel_academico,
				nivel_academico.descripcion AS nivel_academico,
				ficha.cod_turno,
				turno.descripcion AS turno,
				ficha.cod_cliente,
				clientes.nombre AS cliente,
				ficha.cod_ubicacion,
				clientes_ubicacion.descripcion AS ubicacion,
				ficha.cod_banco,
				bancos.descripcion AS banco,
				ficha.cod_n_contracto,
				ficha_n_contracto.vencimiento,
				(
					(ficha_n_contracto.dias) - (
						DATEDIFF(
							CURDATE(),
							MAX(ficha_historial.fec_inicio)
						)
					)
				) AS dias_venc,
				ficha_n_contracto.descripcion AS n_contracto,
				ficha.cod_dosis_covid19,
				ficha_dosis_covid19.vencimiento venc_dosis_covid19,
				(
					(ficha_dosis_covid19.dias) - (
						DATEDIFF(
							CURDATE(),
							MAX(
								ficha_historial_covid19.fec_inicio
							)
						)
					)
				) AS dias_venc_dosis_covid19,
				ficha_dosis_covid19.descripcion AS n_dosis_covid19,
				ficha.cod_n_zapatos,
				preing_zapatos.descripcion AS n_zapatos,
				ficha.cod_t_camisas,
				preing_camisas.descripcion AS t_camisas,
				ficha.cod_t_pantalon,
				preing_pantalon.descripcion AS t_pantalon,
				ficha.campo03,
				ficha.campo04
			FROM
				ficha
			LEFT JOIN ficha_dosis_covid19 ON ficha.cod_dosis_covid19 = ficha_dosis_covid19.codigo
			LEFT JOIN ficha_historial_covid19 ON ficha_dosis_covid19.codigo = ficha_historial_covid19.cod_dosis
			AND ficha_historial_covid19.cod_ficha = ficha.cod_ficha,
			ficha_n_contracto
			LEFT JOIN ficha_historial ON ficha_historial.cod_ficha = '$codigo'
			AND ficha_historial.cod_n_contrato = ficha_n_contracto.codigo,
			nacionalidad,
			estado_civil,
			ocupacion,
			nivel_academico,
			turno,
			clientes,
			clientes_ubicacion,
			bancos,
			preing_camisas,
			preing_pantalon,
			preing_zapatos
			WHERE
				ficha.cod_ficha = '$codigo'
			AND ficha.cod_nacionalidad = nacionalidad.codigo
			AND ficha.cod_estado_civil = estado_civil.codigo
			AND ficha.cod_ocupacion = ocupacion.codigo
			AND ficha.cod_nivel_academico = nivel_academico.codigo
			AND ficha.cod_turno = turno.codigo
			AND ficha.cod_cliente = clientes.codigo
			AND ficha.cod_ubicacion = clientes_ubicacion.codigo
			AND ficha.cod_banco = bancos.codigo
			AND ficha.cod_n_contracto = ficha_n_contracto.codigo
			AND ficha.cod_n_zapatos = preing_zapatos.codigo
			AND ficha.cod_t_camisas = preing_camisas.codigo
			AND ficha.cod_t_pantalon = preing_pantalon.codigo";

	$query  = $bd->consultar($sql02);
	$result = $bd->obtener_fila($query, 0);

	$cod_nacionalidad = $result['cod_nacionalidad'];
	$nacionalidad   = $result['nacionalidad'];
	$cod_estado_civil = $result['cod_estado_civil'];
	$estado_civil   = $result['estado_civil'];
	$cod_cliente    = $result['cod_cliente'];
	$cliente        = $result['cliente'];
	$cod_ubicacion  = $result['cod_ubicacion'];
	$ubicacion      = $result['ubicacion'];
	$cod_turno      = $result['cod_turno'];
	$turno          = $result['turno'];
	$cod_banco      = $result['cod_banco'];
	$banco          = $result['banco'];
	$cod_ocupacion  = $result['cod_ocupacion'];
	$ocupacion      = $result['ocupacion'];
	$cod_nivel_academico = $result['cod_nivel_academico'];
	$nivel_academico = $result['nivel_academico'];
	$vencimiento     = $result['vencimiento'];
	$dias_venc       = $result['dias_venc'];
	$cod_n_contracto = $result['cod_n_contracto'];
	$n_contracto     = $result['n_contracto'];

	//DOSIS COVID-19
	$venc_dosis_covid19 = $result['venc_dosis_covid19'];
	$dias_venc_dosis_covid19  = $result['dias_venc_dosis_covid19'];
	$cod_dosis_covid19 = $result['cod_dosis_covid19'];
	$n_dosis_covid19     = $result['n_dosis_covid19'];

	$cod_t_camisa    = $result['cod_t_camisas'];
	$t_camisa        = $result['t_camisas'];
	$cod_t_pantalon  = $result['cod_t_pantalon'];
	$t_pantalon      = $result['t_pantalon'];
	$cod_n_zapato    = $result['cod_n_zapatos'];
	$n_zapato        = $result['n_zapatos'];

	$latitud = $result['campo03'];
	$longitud = $result['campo04'];

	if ($vencimiento == "T") {
		$dias_venc_contrato = $dias_venc;
	} else {
		$dias_venc_contrato = 0;
	}

	if ($venc_dosis_covid19 == "T") {
		$dias_venc_dosis_covid19 = $dias_venc_dosis_covid19;
	} else {
		$dias_venc_dosis_covid19 = 0;
	}
	// Dotacion

	$sql    = " SELECT ficha.fec_us_mod, CONCAT(men_usuarios.nombre,' ',men_usuarios.apellido) AS us_mod
	FROM ficha LEFT JOIN  men_usuarios ON ficha.cod_us_mod =  men_usuarios.codigo
	WHERE ficha.cod_ficha = '$cod_ficha' ";
	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);

	$fec_us_mod    = conversion($result['fec_us_mod']);
	$us_mod        = $result['us_mod'];
	$fic_read = ' readonly="readonly" ';

	// Agregar
} elseif ($metodo == "agregar") {
	$archivo = "formularios/Cons_ficha2&Nmenu=410&mod=$mod";

	$cod_ficha      = '';
	$cod_contracto  = '';
	$contracto      = 'Seleccione...';
	$dias_venc_contrato  = 0;
	$dias_venc_dosis_covid19 = 0;
	$cod_n_contracto  = '9999';
	$n_contracto    = 'General';
	$cod_dosis_covid19 = '9999';
	$n_dosis_covid19     = 'N/A';
	$cod_rol        = '';
	$rol            = 'Seleccione...';
	$cod_region     = '';
	$region         = 'Seleccione...';
	$cod_cliente    = '';
	$cliente        = 'Seleccione...';
	$cod_ubicacion  = '';
	$ubicacion      = 'Seleccione...';
	$cod_turno      = '';
	$turno          = 'Seleccione...';
	$cod_banco      = '';
	$banco          = 'Seleccione...';
	$cta_banco      = '';

	$fec_profit     = '';

	$fec_us_ing     = '';
	$us_ing  	    = '';
	$fec_us_mod     = '';
	$us_mod         = '';
	$carnet         = '';
	$fec_venc_carnet = '';
	$fec_ingreso    = '';

	$fic_read = ' ';
	$latitud = ' ';
	$longitud = ' ';

	$sql = " SELECT ficha_status.codigo AS cod_status, ficha_status.descripcion AS status
	FROM ficha_status , control
	WHERE ficha_status.codigo = control.ficha_activo ";

	$query  = $bd->consultar($sql);
	$result02 = $bd->obtener_fila($query, 0);

	$cod_status     = $result02["cod_status"];
	$status         = $result02["status"];

	if ($_SESSION['ficha_preingreso'] == "S") {

		$sql = " SELECT preingreso.cod_cargo, cargos.descripcion AS cargo,
		preingreso.cod_estado, estados.descripcion AS estado,
		preingreso.cod_ciudad, ciudades.descripcion AS ciudad,
		preingreso.cod_nivel_academico, nivel_academico.descripcion AS nivel_academico,
		preingreso.apellidos, preingreso.nombres,
		preingreso.fec_nacimiento, preingreso.lugar_nac,
		preingreso.sexo, preingreso.telefono,
		preingreso.celular,
		preingreso.cod_nacionalidad, nacionalidad.descripcion AS nacionalidad,
		preingreso.cod_estado_civil, estado_civil.descripcion AS estado_civil,
		preingreso.experiencia, preingreso.correo,
		preingreso.cod_ocupacion, ocupacion.descripcion AS ocupacion,
		preingreso.direccion, preingreso.fec_preingreso,
		preingreso.fec_psic,
		preingreso.psic_apto, preingreso.psic_observacion,
		preingreso.fec_pol, preingreso.pol_apto,
		preingreso.pol_observacion,
		preingreso.cod_t_camisas, preing_camisas.descripcion AS t_camisas,
		preingreso.cod_t_pantalon, preing_pantalon.descripcion AS t_pantalon,
		preingreso.cod_n_zapatos, preing_zapatos.descripcion AS n_zapatos,
		preingreso.cod_us_ing, CONCAT(men_usuarios.apellido,' ', men_usuarios.nombre) AS us_ing ,
		preingreso.fec_us_ing,
		preingreso.cod_us_mod, preingreso.fec_us_mod,
		preingreso.observacion,
		preingreso.`status` AS cod_status, 	preing_status.descripcion AS status
		FROM preingreso LEFT JOIN men_usuarios ON preingreso.cod_us_ing = men_usuarios.codigo,
		cargos, preing_status, estados, nacionalidad, estado_civil,
		ciudades, preing_camisas, preing_pantalon, preing_zapatos,
		nivel_academico, ocupacion
		WHERE preingreso.cod_cargo = cargos.codigo
		AND preingreso.`status` = preing_status.codigo
		AND preingreso.cod_estado = estados.codigo
		AND preingreso.cod_nacionalidad = nacionalidad.codigo
		AND	preingreso.cod_estado_civil = estado_civil.codigo
		AND preingreso.cod_ciudad = ciudades.codigo
		AND preingreso.cod_nivel_academico = nivel_academico.codigo
		AND preingreso.cod_t_camisas = preing_camisas.codigo
		AND preingreso.cod_t_pantalon  = preing_pantalon.codigo
		AND preingreso.cod_n_zapatos = preing_zapatos.codigo
		AND preingreso.cod_ocupacion = ocupacion.codigo
		AND preingreso.cedula = '$codigo' ";

		$query  = $bd->consultar($sql);
		$result = $bd->obtener_fila($query, 0);


		$ced_read = 'readonly="readonly"';
		$cedula         = $codigo;

		$cod_nacionalidad = $result['cod_nacionalidad'];
		$nacionalidad   = $result['nacionalidad'];
		$cod_estado_civil = $result['cod_estado_civil'];
		$estado_civil   = $result['estado_civil'];
		$nombres        = $result['nombres'];
		$apellidos        = $result['apellidos'];
		$fec_nacimiento = conversion($result['fec_nacimiento']);
		$lugar_nac      = $result['lugar_nac'];
		$observacion    = $result["observacion"];
		$fec_ingreso    = conversion($result['fec_preingreso']);

		$sexo           = $result['sexo'];
		$telefono       = $result['telefono'];
		$celular        = $result['celular'];
		$experiencia    = $result['experiencia'];
		$correo         = $result['correo'];
		$direccion      = $result['direccion'];
		$direccion_google = '';
		$cod_ocupacion  = $result['cod_ocupacion'];
		$ocupacion      = $result['ocupacion'];
		$cod_estado     = $result['cod_estado'];
		$estado         = $result['estado'];
		$cod_ciudad     = $result['cod_ciudad'];
		$ciudad         = $result['ciudad'];
		$cod_cargo      = $result['cod_cargo'];
		$cargo          = $result['cargo'];
		$cod_nivel_academico      = $result['cod_nivel_academico'];
		$nivel_academico = $result['nivel_academico'];

		$cod_t_camisa    = $result['cod_t_camisas'];
		$t_camisa        = $result['t_camisas'];
		$cod_t_pantalon  = $result['cod_t_pantalon'];
		$t_pantalon      = $result['t_pantalon'];
		$cod_n_zapato    = $result['cod_n_zapatos'];
		$n_zapato        = $result['n_zapatos'];

		// CONTROL
		$sql_control = " SELECT COUNT(ficha.cod_ficha) FROM ficha, control
		WHERE ficha.cedula = '$cedula'
		AND ficha.cod_ficha_status = control.ficha_activo
		AND ficha.cod_contracto <> control.contracto_eventuales ";
		$query  = $bd->consultar($sql_control);
		$result = $bd->obtener_fila($query, 0);
		$existe =   $result[0];

		if ($existe >= 1) {
			echo '<script language="javascript" type="text/javascript">
			alert("Ya Tiene un Trabajador Activo Para Esta Cedula: (' . $cedula . ')");
			Vinculo("inicio.php?area=formularios/Cons_ficha2&Nmenu=' . $Nmenu . '&mod=' . $mod . '");
			</script>';
			exit();
		}
	} else {   // $_SESSION['ficha_preingreso']=="N"
		$ced_read = '';
		$cedula         = $codigo;

		$cod_nacionalidad = '';
		$nacionalidad     = 'Seleccione...';
		$cod_estado_civil = '';
		$estado_civil     = 'Seleccione...';
		$nombres          = '';
		$apellidos        = '';
		$fec_nacimiento   = '';
		$lugar_nac        = '';
		$observacion      = '';
		$fec_preingreso  = '';

		$sexo           = '';
		$telefono       = '';
		$celular        = '';
		$experiencia    = '';
		$correo         = '';
		$direccion      = '';
		$direccion_google = '';
		$cod_ocupacion  = '';
		$ocupacion      = 'Seleccione...';
		$cod_estado     = '';
		$estado         = 'Seleccione...';
		$cod_ciudad     = '';
		$ciudad         = 'Seleccione...';
		$cod_cargo      = '';
		$cargo          = 'Seleccione...';
		$cod_nivel_academico  = '';
		$nivel_academico = 'Seleccione...';

		$cod_t_camisa   = '';
		$t_camisas      = 'Seleccione...';
		$cod_t_pantalon = '';
		$t_pantalon     = 'Seleccione...';
		$cod_n_zapato   = '';
		$n_zapato       = 'Seleccione...';
	}
}

$sql_ciudad = " SELECT codigo, descripcion FROM ciudades WHERE cod_estado = '$cod_estado'
AND codigo <> '$cod_ciudad' ORDER BY descripcion ASC ";
?>
<form action="scripts/sc_ficha.php" method="post" name="add" id="add" enctype="multipart/form-data">
	<fieldset class="fieldset">
		<legend>Modificar Datos B&aacute;sicos <?php echo $leng["trabajador"]; ?> </legend>
		<table width="100%" align="center">
			<tr>
				<td width="20%" class="etiqueta">&nbsp;</td>
				<td width="25%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="25%">&nbsp;</td>
				<td width="10%" rowspan="16" align="left">
					<?php
                    $filename = "imagenes/fotos/$cedula.jpg";
					$filenamepng = "imagenes/fotos/$cedula.png";
                    if (file_exists($filename) && file_exists($filenamepng)){
						unlink($filename);
						echo '<img id="foto" src="' . $filenamepng. '?nocache='.time().'" width="110px" height="130px" />';
				
				    } else {
                      if (file_exists($filename)) {
						echo '<img id="foto" src="' . $filename. '?nocache='.time().'" width="110px" height="130px" />';
					   } else {
						  if (file_exists($filenamepng)) {
						echo '<img id="foto" src="' . $filenamepng. '?nocache='.time().'" width="110px" height="130px" />';
						} else {
							echo '<iemg id="foto" src="imagenes/img_no_disp.png" width="110px" height="130px"/>';
						}				
					
					}

				  }
					?>
				</td>
			</tr>
			<tr>
				<td class="etiqueta"><?php echo $leng["ci"]; ?>:</td>
				<td id="input01"><input type="text" name="cedula" maxlength="16" size="15" value="<?php echo $cedula; ?>" <?php echo $ced_read ?> /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">N. <?php echo $leng["ficha"]; ?>:</td>
				<td id="input02"><input type="text" name="cod_ficha" id="cod_ficha" maxlength="12" size="15" value="<?php echo $cod_ficha; ?>" <?php echo $fic_read; ?> /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
					<span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 2 caracteres.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta"><?php echo $leng["nacionalidad"]; ?>: </td>
				<td id="select01"><select name="nacionalidad" style="width:200px">
						<option value="<?php echo $cod_nacionalidad; ?>"><?php echo $nacionalidad; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM nacionalidad WHERE status = 'T'
						AND codigo <> '$cod_nacionalidad' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta"><?php echo $leng["estado_civil"]; ?>: </td>
				<td id="select02"><select name="estado_civil" style="width:200px">
						<option value="<?php echo $cod_estado_civil; ?>"><?php echo $estado_civil; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM estado_civil WHERE status = 'T'
						AND codigo <> '$cod_estado_civil' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>
			<tr>
				<td class="etiqueta">Apellidos: </td>
				<td id="input03"><input type="text" name="apellido" maxlength="60" size="25" value="<?php echo $apellidos; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">Nombres: </td>
				<td id="input04"><input type="text" name="nombre" maxlength="60" size="25" value="<?php echo $nombres; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
			</tr>

			<tr>
				<td class="etiqueta">Fecha de Nacimiento:</td>
				<td id="fecha01"><input type="text" name="fecha_nac" size="12" value="<?php echo $fec_nacimiento; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
				<td class="etiqueta">Lugar Nacimiento: </td>
				<td id="input05"><input type="text" name="lugar_nac" maxlength="60" size="25" value="<?php echo $lugar_nac; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
			</tr>
			<!--
     <tr>
      <td colspan="2">&nbsp;</td>
      <td class="etiqueta">Fotos (.jpg):</td>
      <td id="input03_1"><input type="file" name="file" id="file" style="width:195px" value=""/><br />
         <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
	 </tr>
	-->
			<tr>
				<td class="etiqueta">Posee Carnet:</td>
				<td id="radio01" class="texto">SI
					<input type="radio" name="carnet" value="S" style="width:auto" <?php echo CheckX($carnet, 'S') ?> />
					NO<input type="radio" name="carnet" value="N" style="width:auto" <?php echo CheckX($carnet, 'N') ?> />
					<br /><span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
				</td>
				<td class="etiqueta">Fecha Vencimiento Carnet:</td>
				<td id="fecha02">
					<input type="text" name="fec_venc_carnet" value="<?php echo $fec_venc_carnet; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Tel&eacute;fono: </td>
				<td id="input06"><input type="text" name="telefono" maxlength="40" size="25" value="<?php echo $telefono; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">Tel. Celular: </td>
				<td id="custom01"><input type="text" name="celular" maxlength="40" size="25" value="<?php echo $celular; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Años Experiencia Laboral: </td>
				<td id="input08"><input type="text" name="experiencia" maxlength="60" size="25" value="<?php echo $experiencia; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">Sexo:</td>
				<td id="radio02" class="texto"><img src="imagenes/femenino.gif" width="25" height="15" />
					<input type="radio" name="sexo" value="F" style="width:auto" <?php echo CheckX($sexo, 'F'); ?> />
					<img src="imagenes/masculino.gif" width="25" height="15" />
					<input type="radio" name="sexo" value="M" style="width:auto" <?php echo CheckX($sexo, 'M'); ?> /><br />
					<span class="radioRequiredMsg">Debe Seleccionar el Sexo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Ocupación: </td>
				<td id="select03"><select name="ocupacion" style="width:200px">
						<option value="<?php echo $cod_ocupacion; ?>"><?php echo $ocupacion; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM ocupacion WHERE status = 'T'
			AND codigo <> '$cod_ocupacion' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>

				<td class="etiqueta"><?php echo $leng["correo"]; ?>: </td>
				<td id="email01"><input type="text" name="correo" maxlength="60" size="26" value="<?php echo $correo; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Dirección:</td>
				<td id="textarea01" colspan="3">
					<input name="direccion_google" id="direccion_google" type="hidden" value="<?php echo $direccion_google; ?>" />
					<textarea name="direccion" cols="50" rows="3"><?php echo $direccion; ?></textarea>
					<span id="Counterror_mess1" class="texto">&nbsp;</span><br />
					<span class="textareaRequiredMsg">El Campo es Requerido.</span>
					<span class="textareaMinCharsMsg">Debe Escribir mas de 4 caracteres.</span>
					<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta"><?php echo $leng["estado"]; ?>:</td>
				<td id="select04"><select name="estado" style="width:200px" onchange="Add_ajax01(this.value, 'ajax/Add_ciudad.php', 'ciudad')">
						<option value="<?php echo $cod_estado; ?>"><?php echo $estado; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM estados, control WHERE status = 'T'
				AND control.cod_pais = estados.cod_pais
				AND codigo <> '$cod_estado'
				ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta"><?php echo $leng["ciudad"]; ?>:</td>
				<td id="ciudad"><select name="ciudad" style="width:200px">
						<option value="<?php echo $cod_ciudad; ?>"><?php echo $ciudad; ?></option>
						<?php $query = $bd->consultar($sql_ciudad);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>
			<tr>
				<td class="etiqueta">Cargo: </td>
				<td id="select05"><select name="cargo" style="width:200px">
						<option value="<?php echo $cod_cargo; ?>"><?php echo $cargo; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM cargos WHERE status = 'T'
				AND codigo <> '$cod_cargo' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta">Nivel Academico: </td>
				<td id="select06"><select name="nivel_academico" style="width:200px">
						<option value="<?php echo $cod_nivel_academico; ?>"><?php echo $nivel_academico; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM nivel_academico WHERE status = 'T'
				AND codigo <> '$cod_nivel_academico' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>
			<tr>
				<td class="etiqueta">Servicio Militar: <input type="checkbox" name="servicio_militar" onchange="validar_militar()" <?php echo CheckX($servicio_militar, 'T'); ?> id="servicio_militar"></td>

				<td colspan="4" id="seccion_militar" style="display: none;">
					<div>
						<span width="30%">
							Status Militar: <select id="militar" name="cod_militar" style="width:200px">
								<?php
								$sql = "SELECT ficha_status_militar.codigo,ficha_status_militar.descripcion from ficha_status_militar";
								$query = $bd->consultar($sql);

								while ($datos = $bd->obtener_fila($query, 0)) {

									echo '<option  value="' . $datos[0] . '"';
									if ($datos[0] == $cod_ficha_status_militar) {
										echo 'selected="selected"';
									}
									echo '>' . $datos[1] . '</option>';
								}
								?>
							</select>
						</span>
						<span width="50%" style="padding-left:10px; ">Observacion: <textarea cols="50" name="militar_obs"><?php echo $status_militar_obs; ?></textarea>
							<span id="Counterror_mess5" class="texto">&nbsp;</span><br />
							<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 1024.</span></span>

					</div>
				</td>

			</tr>
			<tr>
				<td class="etiqueta"><?php echo $leng["contrato"]; ?>:</td>
				<td id="select07"><select name="contracto" style="width:200px">
						<option value="<?php echo $cod_contracto; ?>"><?php echo $contracto; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM contractos
				WHERE status = 'T'  AND codigo <> '$cod_contracto' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
				<td class="etiqueta">Numeros <?php echo $leng["contrato"]; ?>:</td>
				<td id="select08"><select name="n_contracto" style="width:200px">
						<option value="<?php echo $cod_n_contracto; ?>"><?php echo $n_contracto; ?></option>
					</select><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Dias Venc. <?php echo $leng["contrato"]; ?>:</td>
				<td>
					<input type="text" name="dias_venc_contrato" readonly="readonly" value="<?php echo $dias_venc_contrato; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
				<td class="etiqueta"><?php echo $leng["rol"]; ?>:</td>
				<td id="select09"><select name="rol" style="width:200px">
						<option value="<?php echo $cod_rol; ?>"><?php echo $rol; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM roles
			WHERE status = 'T' AND codigo <> '$cod_rol' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>
			<tr>
				<td class="etiqueta"><?php echo $leng["region"]; ?>:</td>
				<td id="select10"><select name="region" style="width:200px">
						<option value="<?php echo $cod_region; ?>"><?php echo $region; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM regiones
				WHERE status = 'T'  AND codigo <> '$cod_region' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta">Turno:</td>
				<td id="select13"><select name="turno" style="width:200px">
						<option value="<?php echo $cod_turno; ?>"><?php echo $turno; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM turno
				WHERE status = 'T'  AND codigo <> '$cod_turno' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>
			<tr>
				<td class="etiqueta"><?php echo $leng["cliente"]; ?>:</td>
				<td id="select11"><select name="cliente" id="cliente" style="width:200px" onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion.php', 'cl_ubicacion')">
						<option value="<?php echo $cod_cliente; ?>"><?php echo $cliente; ?></option>
						<?php $sql = " SELECT clientes.codigo, clientes.nombre FROM clientes
				WHERE clientes.`status` = 'T' AND codigo <> '$cod_cliente'
				ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta"><?php echo $leng["ubicacion"]; ?>:</td>
				<td id="cl_ubicacion"><select name="ubicacion" style="width:200px">
						<option value="<?php echo $cod_ubicacion; ?>"><?php echo $ubicacion; ?></option>
						<?php $sql = " SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
				FROM clientes_ubicacion
				WHERE clientes_ubicacion.cod_cliente = clientes_ubicacion.cod_cliente
				AND clientes_ubicacion.`status` = 'T'
				AND clientes_ubicacion.codigo <> '$cod_ubicacion'  AND clientes_ubicacion.cod_cliente = '$cod_cliente' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>

			</tr>
			<tr>
				<td class="etiqueta">Banco:</td>
				<td id="select14"><select name="banco" style="width:200px">
						<option value="<?php echo $cod_banco; ?>"><?php echo $banco; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM bancos
				WHERE status = 'T'  AND codigo <> '$cod_banco' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta">Cta. Banco:</td>
				<td id="input09"><input type="text" name="cta_banco" maxlength="20" style="width:200px" value="<?php echo $cta_banco; ?>" />
					<br /><span class="textfieldRequiredMsg">El Campo es Requerido.</span>
					<span class="textfieldMinCharsMsg">Debe Escribir 20 caracteres.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">0bservación:</td>
				<td id="textarea02" colspan="3"><textarea name="observacion" cols="110" rows="3"><?php echo $observacion; ?></textarea>
					<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
					<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 1024.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Dosis Covid-19:</td>
				<td id="select08"><select name="n_dosis_covid19" style="width:200px">
						<option value="<?php echo $cod_dosis_covid19; ?>"><?php echo $n_dosis_covid19; ?></option>
					</select><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
				<td class="etiqueta">Dias Venc. Dosis Covid-19:</td>
				<td>
					<input type="text" name="dias_venc_dosis_covid19" readonly="readonly" value="<?php echo $dias_venc_dosis_covid19; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Fec. Sist. Integraci&oacute;n:</td>
				<td id="fecha05"><input type="text" name="fec_profit" value="<?php echo $fec_profit; ?>" /></td>

				<td class="etiqueta">Fecha de Ingreso:</td>
				<td id="fecha06">
					<input type="text" name="fec_ingreso" onchange="validarfechaingreso(this.value)" value="<?php echo $fec_ingreso; ?>"   /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Latitud: </td>
				<td><input type="text" name="latitud" id="latitud" maxlength="60" size="26" value="<?php echo $latitud; ?>" />
				</td>
				<td class="etiqueta">Longitud: </td>
				<td><input type="text" name="longitud" id="longitud" maxlength="60" size="26" value="<?php echo $longitud; ?>" />
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Status: </td>
				<td id="select10"><select name="status" style="width:200px">
						<option value="<?php echo $cod_status; ?>"><?php echo $status; ?></option>
						<?php /* 	$sql_ing = " SELECT codigo, descripcion FROM preing_status WHERE status = 'T' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo utf8_decode($datos[1]);?></option>
      <?php } */ ?>
					</select><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="etiqueta">Fec. Creacion Ficha:</td>
				<td><?php echo $fec_us_ing; ?></td>
				<td class="etiqueta">Usuario Creac. Ficha: </td>
				<td><?php echo $us_ing; ?></td>
			</tr>
			<tr>
				<td class="etiqueta">Fecha Ultima Mod.:</td>
				<td><?php echo $fec_us_mod; ?></td>
				<td class="etiqueta">Usuario Mod. Ficha: </td>
				<td><?php echo $us_mod; ?></td>
			</tr>
			<tr>
				<td height="8" colspan="4" align="center">
					<hr>
				</td>
			</tr>
		</table>
		<div align="center">
			<?php if ($metodo <> "agregar") {
				echo '<span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<input type="button" name="pdf" onClick="Pdf()" value="Imprimir" class="readon art-button" />
		</span>&nbsp;';
			} ?>

			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="submit" name="salvar" id="salvarXX" value="Guardar" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" name="setAddress" id="setAddress" value="Mapa" class="readon art-button" onclick="initMap();" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
			</span>
			<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
			<input name="pestana" type="hidden" value="ficha" />
			<input name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
			<input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>" />
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" />
			<input name="proced" id="proced" type="hidden" value="<?php echo $proced; ?>" />
		</div>
	</fieldset>
</form>

<div id="myModalMap" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="closeModalMap()">&times;</span>
			<span>Mapa de Dirección</span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido" style="height: 600px;">
				<!-- Search input -->
				<div class="autocomplete-input-container">
					<div class="autocomplete-input">
						<textarea cols="100" rows="4" id="my-input-autocomplete" placeholder="Busca una dirección" autocomplete="off" role="combobox"></textarea>
					</div>
					<ul class="autocomplete-results">
					</ul>
				</div>
				<!-- Google map -->
				<div id="mapG" style="height: 90%;"></div>
				<span class="art-button-wrapper" id="buttonSave" style="margin-bottom: 20px; height: 40px !important;">
					<span class="art-button-l"> </span>
					<span class="art-button-r"> </span>
					<input type="button" id="volver" value="Guardar" class="readon art-button" onclick="saveLatLng()" />
				</span>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
		minChars: 4,
		validateOn: ["blur", "change"]
	});
	var input02 = new Spry.Widget.ValidationTextField("input02", "none", {
		minChars: 2,
		validateOn: ["blur", "change"]
	});
	var input03 = new Spry.Widget.ValidationTextField("input03", "none", {
		minChars: 3,
		validateOn: ["blur", "change"]
	});
	var input04 = new Spry.Widget.ValidationTextField("input04", "none", {
		validateOn: ["blur", "change"]
	});
	var input05 = new Spry.Widget.ValidationTextField("input05", "none", {
		validateOn: ["blur", "change"],
		isRequired: false
	});
	var input06 = new Spry.Widget.ValidationTextField("input06", "none", {
		validateOn: ["blur", "change"]
	});
	//TELFONO CEL
	var custom = new Spry.Widget.ValidationTextField("custom01", "custom", {
		pattern: "\\0\\400\\-0000000",
		useCharacterMasking: true,
		validateOn: ["blur", "change"],
		isRequired: false
	});

	var input08 = new Spry.Widget.ValidationTextField("input08", "none", {
		validateOn: ["blur", "change"],
		isRequired: false
	});
	var input09 = new Spry.Widget.ValidationTextField("input09", "integer", {
		minChars: 20,
		validateOn: ["blur", "change"],
		isRequired: false
	});

	var email01 = new Spry.Widget.ValidationTextField("email01", "email", {
		validateOn: ["blur"],
		isRequired: false
	});

	var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});
	var fecha02 = new Spry.Widget.ValidationTextField("fecha02", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true,
		isRequired: false
	});

	//var fecha03 = new Spry.Widget.ValidationTextField("fecha03", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
	//   validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});
	/*
	var fecha04 = new Spry.Widget.ValidationTextField("fecha04", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
	validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});	*/
	var fecha05 = new Spry.Widget.ValidationTextField("fecha05", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});
	var fecha06 = new Spry.Widget.ValidationTextField("fecha06", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});

	var radio01 = new Spry.Widget.ValidationRadio("radio01", {
		validateOn: ["change", "blur"]
	});
	var radio02 = new Spry.Widget.ValidationRadio("radio02", {
		validateOn: ["change", "blur"]
	});

	var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {
		maxChars: 255,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess1",
		useCharacterMasking: false
	});
	var textarea02 = new Spry.Widget.ValidationTextarea("textarea02", {
		maxChars: 1024,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess2",
		useCharacterMasking: false,
		isRequired: false
	});
	var textarea03 = new Spry.Widget.ValidationTextarea("textarea03", {
		maxChars: 120,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess3",
		useCharacterMasking: false,
		isRequired: false
	});
	var textarea04 = new Spry.Widget.ValidationTextarea("textarea04", {
		maxChars: 255,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess4",
		useCharacterMasking: false,
		isRequired: false
	});
	var textarea05 = new Spry.Widget.ValidationTextarea("textarea05", {
		maxChars: 120,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess5",
		useCharacterMasking: false,
		isRequired: false
	});

	var select01 = new Spry.Widget.ValidationSelect("select01", {
		validateOn: ["blur", "change"]
	});
	var select02 = new Spry.Widget.ValidationSelect("select02", {
		validateOn: ["blur", "change"]
	});
	var select03 = new Spry.Widget.ValidationSelect("select03", {
		validateOn: ["blur", "change"]
	});
	var select04 = new Spry.Widget.ValidationSelect("select04", {
		validateOn: ["blur", "change"]
	});
	var select05 = new Spry.Widget.ValidationSelect("select05", {
		validateOn: ["blur", "change"]
	});
	var select06 = new Spry.Widget.ValidationSelect("select06", {
		validateOn: ["blur", "change"]
	});
	var select07 = new Spry.Widget.ValidationSelect("select07", {
		validateOn: ["blur", "change"]
	});
	var select08 = new Spry.Widget.ValidationSelect("select08", {
		validateOn: ["blur", "change"]
	});
	var select09 = new Spry.Widget.ValidationSelect("select09", {
		validateOn: ["blur", "change"]
	});
	var select10 = new Spry.Widget.ValidationSelect("select10", {
		validateOn: ["blur", "change"]
	});
	var select11 = new Spry.Widget.ValidationSelect("select11", {
		validateOn: ["blur", "change"]
	});

	var select13 = new Spry.Widget.ValidationSelect("select13", {
		validateOn: ["blur", "change"]
	});
	var select14 = new Spry.Widget.ValidationSelect("select14", {
		validateOn: ["blur", "change"]
	});
	validar_militar();
</script>