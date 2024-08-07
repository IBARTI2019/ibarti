<script type="text/javascript">
  $("#add_cliente_form").on('submit', function(evt) {
    evt.preventDefault();
    save_cliente();

  });

  $("#add_cliente_form input, select").change(function(evt) {
    evt.preventDefault();
    $("#c_cambios").val('true');
    $('#salvar_cliente').attr('disabled', false);
  });

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
    var autocomplete_input = document.getElementById('my-input-autocomplete');
    var direccion_input = $("#c_direccion_google").val();
    autocomplete_input.value = direccion_input;
    var autocomplete_results = document.querySelector('.autocomplete-results');
    var lat = Number($("#c_latitud").val());
    var lng = Number($("#c_longitud").val());

    var positionInitial = {
      lat: lat === 0 || lat === undefined ? 10.1675248 : lat,
      lng: lng === 0 || lng === undefined ? -67.9637274 : lng
    }

    map = new google.maps.Map(document.getElementById("mapCliente"), {
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
        "tabla": "clientes",
        "lat": lat,
        "lng": lng,
        "codigo": $("#c_codigo").val(),
        "address": $('#my-input-autocomplete').val()
      };
      $.ajax({
        data: parametros,
        url: 'scripts/sc_lat_lng.php',
        type: 'post',
        success: (response) => {
          $("#c_latitud").val(lat);
          $("#c_longitud").val(lng);
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
<form action="" method="post" id="add_cliente_form">
  <fieldset class="fieldset">
    <legend>Datos <?php echo $leng['cliente']; ?></legend>
    <table width="100%" align="center">
      <tr>
        <td width="15%" class="etiqueta">C&oacute;digo:</td>
        <td width="35%"><input type="text" id="c_codigo" maxlength="11" style="width:120px" required value="<?php echo $cl['codigo']; ?>" <?php echo $readonly; ?> /></td>
        <td width="15%" class="etiqueta">Abreviatura:</td>
        <td width="35%"><input type="text" id="c_abrev" maxlength="14" required style="width:120px" value="<?php echo $cl['abrev']; ?>" />
          Activo: <input id="c_activo" type="checkbox" <?php echo statusCheck($cl['status']); ?> value="T" />
        </td>
      </tr>

      <td class="etiqueta"><?php echo $leng['rif']; ?>: </td>
      <td><input type="text" id="c_rif" maxlength="20" style="width:150px" value="<?php echo $cl['rif']; ?>" /></td>
      <td class="etiqueta"><?php echo $leng['nit']; ?>:</td>
      <td><input type="text" id="c_nit" maxlength="20" style="width:150px" value="<?php echo $cl['nit']; ?>" /></td>
      </tr>
      <tr>
        <td class="etiqueta">Nombre: </td>
        <td><input type="text" id="c_nombre" maxlength="60" style="width:280px" value="<?php echo $cl['nombre']; ?>" /></td>
        <td class="etiqueta">Aplicar</td>
        <td><?php echo $leng['juridico']; ?>: <input type="checkbox" id="c_juridico" <?php echo statusCheck($cl['juridico']); ?> value="T" /> Contribuyente: <input type="checkbox" id="c_contrib" <?php echo statusCheck($cl['contribuyente']); ?> value="T" /></td>
      </tr>

      <tr>
        <td class="etiqueta">Tel&eacute;fono: </td>
        <td><input type="text" id="c_telefono" maxlength="60" style="width:250px" value="<?php echo $cl['telefono']; ?>" /></td>
        <td class="etiqueta">Fax: </td>
        <td><input type="text" id="c_fax" maxlength="60" style="width:250px" value="<?php echo $cl['fax']; ?>" /></td>
      <tr>

      <tr>

        <td class="etiqueta">Tipo <?php echo $leng['cliente']; ?>: </td>
        <td><select id="c_cl_tipo" style="width:250px" required="required">
            <option value="<?php echo $cl['cod_cl_tipo']; ?>"><?php echo $cl['cl_tipo']; ?></option>
            <?php $sql = " SELECT codigo, descripcion FROM clientes_tipos
         WHERE status = 'T' AND codigo <> '$cod_cl_tipo' ORDER BY 2 ASC ";
            $query = $bd->consultar($sql);
            while ($datos = $bd->obtener_fila($query, 0)) {
            ?>
              <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
            <?php } ?>
          </select></td>
        <td class="etiqueta"><?php echo $leng['region']; ?>:</td>
        <td><select id="c_region" style="width:250px" required>
            <option value="<?php echo $cl['cod_region']; ?>"><?php echo $cl['region']; ?></option>
            <?php $sql = " SELECT codigo, descripcion FROM regiones WHERE status = 'T' ORDER BY 2 ASC ";
            $query = $bd->consultar($sql);
            while ($datos = $bd->obtener_fila($query, 0)) {
            ?>
              <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
            <?php } ?>
          </select></td>

      </tr>
      <tr>
        <td class="etiqueta">Vendedor:</td>
        <td><select id="c_vendedor" style="width:250px" required>
            <option value="<?php echo $cl['cod_vendedor']; ?>"><?php echo $cl['vendedor']; ?></option>
            <?php $sql = " SELECT codigo, nombre FROM vendedores  WHERE status = 'T' ORDER BY 2 ASC ";
            $query = $bd->consultar($sql);
            while ($datos = $bd->obtener_fila($query, 0)) {
            ?>
              <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
            <?php } ?>
          </select></td>
        <td class="etiqueta">Contacto:</td>
        <td><input type="text" id="c_contacto" maxlength="60" style="width:250px" value="<?php echo $cl['contacto']; ?>" /></td>

      </tr>
      <tr>
        <td class="etiqueta">Email: </td>
        <td><input type="text" id="c_email" maxlength="60" style="width:250px" value="<?php echo $cl['email']; ?>" /></td>
        <td class="etiqueta">Website: </td>
        <td><input type="text" id="c_website" maxlength="60" style="width:250px" value="<?php echo $cl['website']; ?>" /></td>
      </tr>
      <tr>
        <td class="etiqueta">Direcci&oacute;n:</td>
        <td><textarea id="c_direccion" cols="38" rows="4" maxlength="300" required><?php echo $cl['direccion']; ?></textarea>
        <td class="etiqueta">Observación:</td>
        <td><textarea id="c_observ" cols="38" rows="4"><?php echo $cl['observacion']; ?></textarea></td>
      </tr>
      <tr>
        <td class="etiqueta">Latitud: </td>
        <td><input type="text" name="latitud" id="c_latitud" maxlength="60" size="26" value="<?php echo $cl['latitud']; ?>" />
        </td>
        <td class="etiqueta">Longitud: </td>
        <td><input type="text" name="longitud" id="c_longitud" maxlength="60" size="26" value="<?php echo $cl['longitud']; ?>" />
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Fecha Última Asistencia: </td>
        <td><input type="text" name="fec_ult_asistencia" id="fec_ult_asistencia" maxlength="60" size="26" value="<?php echo $cl['fec_ult_asistencia']; ?>" />
        </td>
      </tr>
      <tr>
        <td height="8" colspan="4" align="center">
          <hr>
        </td>
      </tr>
    </table>
    <div align="center">
      <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="submit" name="salvar" id="salvar_cliente" value="Guardar" class="readon art-button" />
      </span>&nbsp;
      <?php if ($metodo == 'modificar') { ?>
        <span class="art-button-wrapper">
          <span class="art-button-l"> </span>
          <span class="art-button-r"> </span>
          <input type="button" name="setAddress" id="setAddress" value="Mapa" class="readon art-button" onclick="initMap();" />
        </span>&nbsp;
      <?php } ?>
      <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="reset" id="limpiar_cliente" value="Restablecer" class="readon art-button" />
      </span>
    </div>
    <input id="c_metodo" type="hidden" value="<?php echo $metodo; ?>" />
    <input name="direccion_google" id="c_direccion_google" type="hidden" value="<?php echo $cl["direccion_google"]; ?>" />
    <input name="cambios" id="c_cambios" type="hidden" value="false" />
  </fieldset>
</form>