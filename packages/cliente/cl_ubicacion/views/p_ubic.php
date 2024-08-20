<script language="javascript">
	$("#ub_form").on('submit', function(evt) {
		evt.preventDefault();
		save_ubic();
	});


	function closeModalMap() {
		$("#myModalMapUbic").hide();
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
		var autocomplete_input = document.getElementById('my-input-autocomplete-ubic');
		var autocomplete_results = document.querySelector('.autocomplete-results-ubic');
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
		var autocomplete_input = document.getElementById('my-input-autocomplete-ubic');
		var direccion_input = $("#ub_direccion_google").val();
		autocomplete_input.value = direccion_input;
		var autocomplete_results = document.querySelector('.autocomplete-results-ubic');
		var lat = Number($("#ub_latitud").val());
		var lng = Number($("#ub_longitud").val());

		var positionInitial = {
			lat: lat === 0 || lat === undefined ? 10.1675248 : lat,
			lng: lng === 0 || lng === undefined ? -67.9637274 : lng
		}

		map = new google.maps.Map(document.getElementById("mapUbic"), {
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

		$("#myModalMapUbic").show();
	}

	function saveLatLng() {
		var point_current = marker.getPosition();
		var lat = point_current.lat();
		var lng = point_current.lng();
		var error = 0;
		var errorMessage = ' ';
		if (error == 0) {
			var parametros = {
				"tabla": "clientes_ubicacion",
				"lat": lat,
				"lng": lng,
				"codigo": $("#ub_codigo").val(),
				"address": $('#my-input-autocomplete-ubic').val()
			};
			$.ajax({
				data: parametros,
				url: 'scripts/sc_lat_lng.php',
				type: 'post',
				success: (response) => {
					$("#ub_latitud").val(lat);
					$("#ub_longitud").val(lng);
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
<form id="ub_form" name="ub_form" method="post">
	<fieldset class="fieldset">
		<legend><?php echo $titulo; ?></legend>
		<table width="90%" align="center">
			<tr>
				<td width="15%" class="etiqueta">C&oacute;digo:</td>
				<td width="35%"><input type="text" id="ub_codigo" maxlength="11" size="15" value="<?php echo $ubic["codigo"]; ?>" readonly />
					Activo: <input id="ub_status" type="checkbox" <?php echo statusCheck($ubic["status"]); ?> value="T" />
				</td>
				<td width="15%" class="etiqueta"><?php echo $leng['ubicacion'] ?>: </td>
				<td width="35%"><input type="text" id="ub_nombre" maxlength="30" size="30" required value="<?php echo $ubic["descripcion"]; ?>" />
				</td>
			</tr>

			<tr>
				<td class="etiqueta"><?php echo $leng['region'] ?>:</td>
				<td><select id="ub_region" style="width:250px" required>
						<option value="<?php echo $ubic["cod_region"]; ?>"><?php echo $ubic["region"]; ?></option>
						<?php
						foreach ($region as  $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						} ?>
					</select></td>
				<td class="etiqueta"><?php echo $leng['estado'] ?>: </td>
				<td><select id="ub_estado" required style="width:250px" onchange="Filtrar_select(this.value, 'ub_ciudad', 'ajax/Add_select_ciudad.php', 'ciudad', '250px', '')">
						<option value="<?php echo $ubic["cod_estado"]; ?>"><?php echo $ubic["estado"]; ?></option>
						<?php
						foreach ($estado as $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						} ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>

			<tr>
				<td class="etiqueta"><?php echo $leng['ciudad'] ?>:</td>
				<td id="ciudad"><select id="ub_ciudad" style="width:250px" required>
						<option value="<?php echo $ubic["cod_ciudad"]; ?>"><?php echo $ubic["ciudad"]; ?></option>
						<?php
						foreach ($ciudad as $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						} ?>
					</select></td>
				<td class="etiqueta">Zona:</td>
				<td id="ciudad"><select id="ub_zona" style="width:250px" required>
						<option value="<?php echo $ubic["cod_zona"]; ?>"><?php echo $ubic["zona"]; ?></option>
						<?php
						foreach ($zona as $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						} ?>
					</select></td>
			</tr>
			<tr>
				<td class="etiqueta">Calendario:</td>
				<td id="ciudad"><select id="ub_calendario" style="width:250px" required>
						<option value="<?php echo $ubic["cod_calendario"]; ?>"><?php echo $ubic["calendario"]; ?></option>
						<?php
						foreach ($calendario as $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						} ?>
					</select></td>
			</tr>
			<tr>
				<td class="etiqueta">Contacto: </td>
				<td id="input03"><input type="text" id="ub_contacto" maxlength="30" size="30" value="<?php echo $ubic["contacto"]; ?>" required /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>

				<td class="etiqueta">Cargo: </td>
				<td id="input04"><input type="text" id="ub_cargo" maxlength="30" size="30" value="<?php echo $ubic["cargo"]; ?>" required />
				</td>
			</tr>
			<tr>

				<td class="etiqueta">Tel&eacute;fono: </td>
				<td id="input05"><input type="text" id="ub_telefono" maxlength="60" size="30" value="<?php echo $ubic["telefono"]; ?>" />
				</td>

				<td class="etiqueta"><?php echo $leng['correo'] ?>: </td>
				<td><input type="email" id="ub_email" maxlength="60" size="30" value="<?php echo $ubic["email"]; ?>" /></td>
			</tr>
			<tr>
				<td class="etiqueta">Direcci&oacute;n:</td>
				<td id="textarea01"><textarea id="ub_direccion" cols="50" rows="3"><?php echo $ubic["direccion"]; ?></textarea>
					<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
					<span class="textareaRequiredMsg">El Campo es Requerido.</span>
					<span class="textareaMinCharsMsg">Debe Escribir mas de 4 caracteres.</span>
					<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 300.</span>
				</td>

				<td class="etiqueta">Observación:</td>
				<td id="textarea02"><textarea id="ub_observ" cols="50" rows="3"><?php echo $ubic["observacion"]; ?></textarea>
					<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
					<span class="textareaRequiredMsg">El Campo es Requerido.</span>
					<span class="textareaMinCharsMsg">Debe Escribir mas de 4 caracteres.</span>
					<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 300.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Latitud: </td>
				<td id="input03"><input type="number" id="ub_latitud" step="any" readonly value="<?php echo $ubic["latitud"]; ?>" required /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">Longitud: </td>
				<td id="input04"><input type="number" id="ub_longitud" step="any" readonly value="<?php echo $ubic["longitud"]; ?>" required />
				</td>

			</tr>
			<tr>
				<td class="etiqueta">Fecha Última Asistencia: </td>
				<td><input type="text" name="fec_ult_asistencia" id="fec_ult_asistencia" maxlength="60" size="26" value="<?php echo $ubic['fec_ult_asistencia']; ?>" />
				</td>
			</tr>
			<tr>
				<td height="8" colspan="4" align="center">
					<hr>
				</td>
			</tr>

		</table>
		<div align="center"><br />
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
			</span>&nbsp;
			<?php if ($metodo == 'modificar') { ?>
				<span class="art-button-wrapper">
					<span class="art-button-l"> </span>
					<span class="art-button-r"> </span>
					<input type="button" name="setAddressUbic" id="setAddressUbic" value="Mapa" class="readon art-button" onclick="initMap();" />
				</span>&nbsp;
			<?php } ?>
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" id="volver" value="Cerrar" onClick="CloseModal();" class="readon art-button" />
			</span>
		</div>
		<input name="metodo" id="ub_metodo" type="hidden" value="<?php echo $metodo; ?>" />
		<input name="direccion_google" id="ub_direccion_google" type="hidden" value="<?php echo $cl["direccion_google"]; ?>" />
	</fieldset>
</form>