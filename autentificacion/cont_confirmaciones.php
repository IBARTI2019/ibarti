<?php
require_once('sql/sql_report_t.php');
$bd = new DataBase();
?>
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
  </tr>
    <td class="etiqueta"><?php echo $leng['cliente']?>:</td>
    <td>
      <select name="cliente" id="cliente" style="width:120px;" onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion_simple.php', 'selectUbic')">
        <option value="TODOS">TODOS</option>
        <?php
        $query01 = $bd->consultar($sql_cliente);
        while($row01=$bd->obtener_fila($query01,0)){
          echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
        }
        ?>
      </select>
    </td>
    <td class="etiqueta"><?php echo $leng['ubicacion']?>:</td>
    <td id="selectUbic">
      <select name="ubicacion" id="ubicacion" style="width:120px;">
        <option value="TODOS">TODOS</option>
      </select>
    </td>
    <td class="etiqueta"><?php echo $leng['horario']?>:</td>
    <td>
      <select name="horario" id="horario_conf" style="width:120px;">
        <option value="TODOS">TODOS</option>
        <?php
        $query01 = $bd->consultar($sql_horario);
        while($row01=$bd->obtener_fila($query01,0)){
          echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
        }
        ?>
      </select>
    </td>
    <td class="etiqueta">Hora de entrada: </td>
    <td>
      <input type="time" name="hora" id="hora_entrada_conf">
    </td>
    <td>
      <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar" title="Agregar"  width="15px" height="15px" onclick="addConfEsp()">
    </td>
  </tr>
</table>
<table class="tabla_sistema" width="80%" border="0" align="center" id="confirmaciones_especificas">
</table>
<input name="usuario" type="hidden" value="<?php echo $usuario; ?>" />

<script>
  $(function () {
    getConfEsp();
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
    $("#ubicacion").val("TODOS");
    $("#horario_conf").val("TODOS");
    $("#hora_entrada_conf").val("");
  }

  function addConfEsp() {
    var ubicacion = $("#ubicacion").val();
    var horario = $("#horario_conf").val();
    var hora_entrada = $("#hora_entrada_conf").val();
    var usuario = $("#usuario").val();
    if(ubicacion != "TODOS" && ubicacion != "" && horario != "TODOS" && horario != "" && hora_entrada != "" ){
      var parametros = { "ubicacion": ubicacion, "horario": horario, "hora_entrada": hora_entrada, "usuario": usuario };
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
</script>
