<?php
require_once('sql/sql_report_t.php');
$bd = new DataBase();
?>
<style>
  .horarios-esp-form { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 10px 16px; padding: 6px 4px; }
  .horarios-esp-form .campo { display: flex; flex-direction: column; gap: 3px; }
  .horarios-esp-form .campo .etiqueta { white-space: nowrap; }
  .confirmaciones-tabla-wrap { overflow-x: auto; }
  .confirmaciones-tabla-wrap table { min-width: 760px; }
</style>
<table width="80%" align="center">
   <tr valign="top">                    
     <td height="23" colspan="2" class="etiqueta_title" align="center">CONTROL DE CONFIRMACIONES</td>
   </tr>
   <tr><td height="8" colspan="2" align="center"><hr></td></tr>			 
   <tr>  
     <td class="etiqueta" width="40%">Minimo de minutos para confirmacion de asistencia: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="min_confirm" value="<?php echo $min_confirm;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Maximo de minutos para confirmacion de asistencia: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="max_confirm" value="<?php echo $max_confirm;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Minimo de minutos para confirmacion en transporte: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="min_in_transport" value="<?php echo $min_in_transport;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Maximo de minutos para confirmacion en transporte: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="max_in_transport" value="<?php echo $max_in_transport;?>">
    </td>
   </tr>
   <tr><td height="4" colspan="2" align="center"><hr></td></tr>			  
   <tr>      
</table>
   <div align="center"><span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />	
  </span>&nbsp;
  <span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />	
  </span>&nbsp;
  <span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />	
  </span>           
</div> 
<br>
<br>
<table width="80%" align="center">
  <tr valign="top">                    
     <td height="23" colspan="9" class="etiqueta_title" align="center">CONTROL DE HORARIOS PARA UBICACIONES ESPECIFICAS</td>
   </tr>
  <tr><td height="8" colspan="9" align="center"><hr></td></tr>
  <tr>
    <td colspan="9">
      <div class="horarios-esp-form">
        <div class="campo">
          <span class="etiqueta"><?php echo $leng['cliente']?>:</span>
          <select name="cliente" id="cliente" style="width:120px;" onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion_simple.php', 'selectUbic')">
            <option value="TODOS">TODOS</option>
            <?php
            $query01 = $bd->consultar($sql_cliente);
            while($row01=$bd->obtener_fila($query01,0)){
              echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
            }
            ?>
          </select>
        </div>
        <div class="campo" id="selectUbic">
          <span class="etiqueta"><?php echo $leng['ubicacion']?>:</span>
          <select name="ubicacion" id="ubicacion" style="width:120px;">
            <option value="TODOS">TODOS</option>
          </select>
        </div>
        <div class="campo" id="selectCargo">
          <span class="etiqueta">Cargo:</span>
          <select name="cargo" id="cargo" style="width:120px;">
            <option value="TODOS">TODOS</option>
            <?php
            $sql_cargo = "SELECT codigo, descripcion FROM cargos WHERE cargos.status = 'T' AND cargos.codigo NOT IN (SELECT cod_cargo FROM cargos_excl_confirm) ORDER BY 2;";
            $query01 = $bd->consultar($sql_cargo);
            while($row01=$bd->obtener_fila($query01,0)){
              echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
            }
            ?>
          </select>
        </div>
        <div class="campo">
          <span class="etiqueta"><?php echo $leng['horario']?>:</span>
          <select name="horario" id="horario_conf" style="width:120px;">
            <option value="TODOS">TODOS</option>
            <?php
            $query01 = $bd->consultar($sql_horario);
            while($row01=$bd->obtener_fila($query01,0)){
              echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
            }
            ?>
          </select>
        </div>
        <div class="campo">
          <span class="etiqueta">Hora de entrada: </span>
          <input type="time" name="hora" id="hora_entrada_conf">
        </div>
        <div class="campo">
          <span class="etiqueta">Ventana desde: </span>
          <input type="time" name="ventana_ini" id="ventana_ini_conf">
        </div>
        <div class="campo">
          <span class="etiqueta">Ventana hasta: </span>
          <input type="time" name="ventana_fin" id="ventana_fin_conf">
        </div>
        <div class="campo">
          <span class="etiqueta">&nbsp;</span>
          <input type="hidden" id="codigo_conf_esp" value="">
          <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar" title="Agregar"  width="15px" height="15px" onclick="addConfEsp()">
        </div>
      </div>
    </td>
  </tr>
  <tr id="editando_conf_esp" style="display:none;">
    <td colspan="9" align="center" class="mensaje">Editando registro — <a href="javascript:void(0);" onclick="cancelarEdicionConfEsp()">Cancelar edición</a></td>
  </tr>
</table>
<div class="confirmaciones-tabla-wrap">
<table class="tabla_sistema" width="80%" border="0" align="center" id="confirmaciones_especificas">
</table>
</div>
<br>
<br>
<br>
 <table width="80%" align="center">
  <tr valign="top">                    
     <td height="23" colspan="9" class="etiqueta_title" align="center">CARGOS EXCLUIDOS</td>
   </tr>
  <tr><td height="8" colspan="9" align="center"><hr></td></tr>			 
  </tr>
    <td class="etiqueta">Cargo: </td>
    <td>
      <select name="cargo" id="cargo" style="width:500px;">
        <option value="">Seleccione</option>
        <?php
        $query01 = $bd->consultar($sql_cargo);
        while($row01=$bd->obtener_fila($query01,0)){
          echo '<option value="'.$row01[0].'">'.$row01[1].' ('.$row01[0].')</option>';
        }
        ?>
      </select>
    </td>
    <td>
      <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar" title="Agregar"  width="15px" height="15px" onclick="addCargo()">
    </td>
  </tr>
</table>
<table class="tabla_sistema" width="80%" border="0" align="center" id="cargos_excl">
</table> 
<input name="usuario" type="hidden" value="<?php echo $usuario; ?>" />

<script>
  $(function () {
    getConfEsp();
    getCargosExcl();
  });

  function getConfEsp() {
    $.ajax({
      url: 'packages/planif/planificaciones/views/Add_confirmaciones_esp.php',
      type: 'get',
      beforeSend: function () {
        $("#confirmaciones_especificas").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
      },
      success: function (response) {
        $("#confirmaciones_especificas").html(response);
        resetData();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
  }
  
  function resetData(){
    $("#cliente").val("TODOS");
    $("#ubicacion").val("TODOS");
    $("#horario_conf").val("TODOS");
    $("#hora_entrada_conf").val("");
    $("#ventana_ini_conf").val("");
    $("#ventana_fin_conf").val("");
    $("#cargo").val("");
    $("#codigo_conf_esp").val("");
    $("#editando_conf_esp").hide();
  }

  function Editar_confirmacion_det(codigo, cliente, ubicacion, ubicacion_desc, cargo, horario, hora_entrada, ventana_ini, ventana_fin) {
    $("#codigo_conf_esp").val(codigo);
    $("#cliente").val(cliente);
    $("#cargo").val(cargo);
    $("#horario_conf").val(horario);
    $("#hora_entrada_conf").val(hora_entrada);
    $("#ventana_ini_conf").val(ventana_ini);
    $("#ventana_fin_conf").val(ventana_fin);
    $("#editando_conf_esp").show();

    $.ajax({
      url: 'ajax/Add_cl_ubicacion_simple.php',
      type: 'post',
      data: { codigo: cliente },
      success: function (response) {
        $("#selectUbic").html(response);
        $("#ubicacion").val(ubicacion);
        // Si la ubicación quedó inactiva (no aparece en la lista recargada),
        // se agrega igual para no perder la selección al editar.
        if ($("#ubicacion").val() != ubicacion) {
          $("#ubicacion").append('<option value="' + ubicacion + '">' + ubicacion_desc + '</option>');
          $("#ubicacion").val(ubicacion);
        }
      }
    });

    $('html, body').animate({ scrollTop: $("#cliente").offset().top - 100 }, 300);
  }

  function cancelarEdicionConfEsp() {
    resetData();
  }

  function addConfEsp() {
    var ubicacion = $("#ubicacion").val();
    var cargo = $("#cargo").val();
    var horario = $("#horario_conf").val();
    var hora_entrada = $("#hora_entrada_conf").val();
    var ventana_ini = $("#ventana_ini_conf").val();
    var ventana_fin = $("#ventana_fin_conf").val();
    var codigo = $("#codigo_conf_esp").val();
    var usuario = $("#usuario").val();
    if(ubicacion != "TODOS" && ubicacion != "" && horario != "TODOS" && horario != "" && hora_entrada != "" && cargo != "TODOS" && cargo != ""){
      var parametros = { "codigo": codigo, "ubicacion": ubicacion, "cargo": cargo, "horario": horario, "hora_entrada": hora_entrada, "ventana_ini": ventana_ini, "ventana_fin": ventana_fin, "usuario": usuario };
      $.ajax({
        data: parametros,
        url: 'packages/planif/planificaciones/modelo/confirmaciones_esp.php',
        type: 'post',
        // beforeSend: function () {
        //   $("#confirmaciones_especificas").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        // },
        success: function (response) {
          getConfEsp();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });
    }else{
      alert("Debe definir todos los datos");
    }
  }

  function Borrar_confirmacion_det(codigo){
    if (confirm("Estas seguro(a) de que deseas eliminar este registro?..")) {
      var parametros = {"codigo": codigo };
      $.ajax({
        data: parametros,
        url: 'packages/planif/planificaciones/modelo/delete_confirmaciones_esp.php',
        type: 'post',
        beforeSend: function () {
          $("#confirmaciones_especificas").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
          getConfEsp();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });
    }
  }

  function getCargosExcl() {
    $.ajax({
      url: 'packages/planif/planificaciones/views/Add_cargos_excl.php',
      type: 'get',
      beforeSend: function () {
        $("#cargos_excl").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
      },
      success: function (response) {
        $("#cargos_excl").html(response);
        resetData();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
  }

  function addCargo() {
    var cargo = $("#cargo").val();
    var usuario = $("#usuario").val();
    if(cargo != ""){
      var parametros = { "cargo": cargo, "usuario": usuario };
      $.ajax({
        data: parametros,
        url: 'packages/planif/planificaciones/modelo/cargos_excl.php',
        type: 'post',
        // beforeSend: function () {
        //   $("#confirmaciones_especificas").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        // },
        success: function (response) {
          getCargosExcl();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });
    }else{
      alert("Debe seleccionar el cargo");
    }
  }

  function Borrar_cargo_det(codigo){
    if (confirm("Estas seguro(a) de que deseas eliminar este cargo de la lista de exclusion?..")) {
      var parametros = {"codigo": codigo };
      $.ajax({
        data: parametros,
        url: 'packages/planif/planificaciones/modelo/delete_cargo_excl.php',
        type: 'post',
        beforeSend: function () {
          $("#cargos_excl").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
          getCargosExcl();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });
    }
  }
</script>
