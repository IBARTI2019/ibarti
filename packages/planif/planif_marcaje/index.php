<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<script type="text/javascript" src="packages/planif/planif_marcaje/controllers/marcajeCtrl.js"></script>
<style>
  .marcar {
    text-decoration: line-through;
  }
</style>
<?php
$Nmenu = '4406';

$img_src = ""; 
if (isset($_SESSION['usuario_cod'])) {
    require_once('autentificacion/aut_verifica_menu.php');
    require_once('sql/sql_report.php');
    $us = $_SESSION['usuario_cod'];
        
} else {
  $us = $_POST['usuario'];
 
}
?>
<div id="Cont_marcaje">

  <span class="etiqueta_title" id="title_horario">Marcaje de Supervisor</span>
  <table width="90%" align="center">
    <tr>
      <td height="8" colspan="4" align="center">
        <hr>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Filtro Trab.:</td>
      <td>
        <select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:250px">
          <option value="TODOS"> TODOS</option>
          <option value="codigo"> Ficha </option>
          <option value="cedula"> C&eacute;dula </option>
          <option value="trabajador"> Trabajador </option>
          <option value="nombres"> Nombre </option>
          <option value="apellidos"> Apellido </option>
        </select>
      </td>
      <td class="etiqueta">Trabajador:</td>
      <td><input id="stdName" type="text" style="width:380px" disabled="disabled" />
        <input type="hidden" name="trabajador" id="stdID" value="" onchange="Add_filtroX()" />
      </td>
    </tr>
    <tr>
    <tr>

      <td class="etiqueta">Cliente:</td>
      <td><select name="cliente" id="cliente" style="width:250px;" onchange="changeCliente(this.value)" required>
          <?php
          echo $select_cl;
          $query01 = $bd->consultar($sql_cliente);
          while ($row01 = $bd->obtener_fila($query01, 0)) {
            echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
          } ?></select></td>
      <td class="etiqueta">Ubicacion: </td>
      <td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:250px;">
          <option value="TODOS">TODOS</option>
        </select></td>
    </tr>
    <tr>
      <td height="8" colspan="4" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <table width="90%" class="tabla_sistema">
    <thead>
      <tr>
        <th>Código</th>
        <th>Ubicación</th>
        <th>Proyecto</th>
        <th>Actividad</th>
        <th>Hora Inicio <br> Hora Fin</th>
        <th>Realizado</th>
        <th>Marcar</th>
        <th>Observaciones</th>
        <th>Participantes</th>
      </tr>
    </thead>
    <tbody id="actividades">

    </tbody>
  </table>
</div>

<div id="myModalP" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalParticipantes()">&times;</span>
      <span>Participantes</span>
    </div>
    <div class="modal-body">
      <div id="modal_contenido">
        <table width="90%" align="center">
          <tr>
            <td class="etiqueta">Filtro:</td>
            <td>
              <select id="paciFiltroP" onchange="EstadoFiltro(this.value)" style="width:250px">
                <option value="codigo"> Ficha </option>
                <option value="cedula"> C&eacute;dula </option>
                <option value="trabajador"> Trabajador </option>
                <option value="nombres"> Nombre </option>
                <option value="apellidos"> Apellido </option>
              </select>
            </td>
            <td class="etiqueta">Trabajador:</td>
            <td><input id="stdNameP" type="text" style="width:480px" />
              <input type="hidden" name="trabajador" id="stdIDP" value="" />
              <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Participante" title="Agregar Participante" onclick="addParticipante('agregar')" width="15px" height="15px">
            </td>
          </tr>
        </table>
        <table width="90%" class="tabla_sistema">
          <thead>
            <tr>
              <th>Ficha</th>
              <th>Nombre</th>
              <th>Cargo</th>
              <th>Fecha</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody id="participantes">

          </tbody>
        </table>
        <script type="text/javascript">
          filtroValueP = $("#paciFiltroP").val();
          new Autocomplete("stdNameP", function() {
            this.setValue = function(id) {
              document.getElementById("stdIDP").value = id;
            }
            if (this.isModified) this.setValue("");
            if (this.value.length < 1) return;
            return "autocompletar/tb/trabajador.php?q=" + this.text.value + "&filtro=" + filtroValueP + ""
          });
        </script>
      </div>
    </div>
  </div>
</div>

<div id="myModalO" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalObservaciones()">&times;</span>
      <span>Participantes</span>
    </div>
    <div class="modal-body">
      <div id="modal_contenido">
        <table width="90%" align="center">
          <tr>
            <td class="etiqueta">Observación:</td>
            <td>
              <textarea name="observacion" id="observacion" cols="60" rows="5"></textarea>
              <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Observación" title="Agregar Observación" onclick="addObservacion()" width="15px" height="15px">
            </td>
          </tr>
        </table>
        <table width="90%" class="tabla_sistema">
          <thead>
            <tr>
              <th width="15%">Código</th>
              <th width="85%">Observación</th>
            </tr>
          </thead>
          <tbody id="observaciones">

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div id="myModalO2" class="modal" >
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalfile()">&times;</span>
      <span>Cargar Archivos y Actividades No Obligatorias</span>
    </div>
   
    <script type="text/javascript" src="upload/functions.js"></script>
<link rel="stylesheet" href="jQueryUpLoadyPHP/css/estilos.css" type="text/css" />
<link rel="stylesheet" href="upload/upload.css" type="text/css" />
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div><hr />
<div id="Contenedor01" class="mensaje"></div>
<form name="some_form" enctype="multipart/form-data" class="formulario" >
<table width="100%">
  <tr><td 
      
      width="100%"><div id="contenedorImagen"><img id="fotografia" class="fotografia" src="<?php echo $img_src?>"></div>
                      <input name="images" type="file" id="imagen"  value="Subir Imagen"  onfocus="activarcheckbox()"/><br/>
                
                 <br/>
   
      </div>
    </td></tr>     
</table>
<table width="100%" class="tabla_sistema">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Ubicacion</th>
              <th>Proyecto</th>
              <th>Actividad</th>
              <th>Hora Inicio Hora Fin</th>
              <th>Realziado</th>
              <th>Marcar</th>
              <th>Participantes</th>
              <th>Observaciones</th>
            </tr>
          </thead>
          <tbody id="actividadesNO">

          </tbody>
  </table>
  <table width="100%">
  <tr><td 
       width="100%"><div align="center"  ><br/>
                <span class="art-button-wrapper" id="imgMostrar"  style="display:none">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span> 
                      <input type="button" id="subir_img" value="Subir Archivo"  class="readon art-button" 
                             onClick="subirImagenS3marcaje()"   /></span> 
                <br/>
                 <br/>
   
                   
       
    </div>
    </td></tr>     
</table>
  
        <input name="ficha" id="ficha" type="hidden"  value="<?php echo $ficha;?>" />
        <input name="ci" id="ci" type="hidden"  value="<?php echo $ci;?>" />
        <input name="cliente" id="cliente" type="hidden"  value="<?php echo $cliente;?>" />
        <input name="ubicacion" id="ubicacion" type="hidden"  value="<?php echo $ubicacion;?>" />
		    <input name="doc" id="doc" type="hidden"  value="<?php echo $doc;?>" />   
        <input name="url_new" id="url_new" type="hidden"  value="<?php echo $url_new;?>" />   
 </form>
  </div>
</div>
<div id="myModalP2" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalParticipantesNO()">&times;</span>
      <span>Participantes</span>
    </div>
    <div class="modal-body">
      <div id="modal_contenido">
        <table width="90%" align="center">
          <tr>
            <td class="etiqueta">Filtro:</td>
            <td>
              <select id="paciFiltroP1" onchange="EstadoFiltro(this.value)" style="width:250px">
                <option value="codigo"> Ficha </option>
                <option value="cedula"> C&eacute;dula </option>
                <option value="trabajador"> Trabajador </option>
                <option value="nombres"> Nombre </option>
                <option value="apellidos"> Apellido </option>
              </select>
            </td>
            <td class="etiqueta">Trabajador:</td>
            <td><input id="stdNameP1" type="text" style="width:480px" />
              <input type="hidden" name="trabajador" id="stdIDP1" value="" />
              <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Participante" title="Agregar Participante" onclick="addParticipanteNO('agregar')" width="15px" height="15px">
            </td>
          </tr>
        </table>
        <table width="90%" class="tabla_sistema">
          <thead>
            <tr>
              <th>Ficha</th>
              <th>Nombre</th>
              <th>Cargo</th>
              <th>Fecha</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody id="participantesNO">

          </tbody>
        </table>
        <script type="text/javascript">
          filtroValueP = $("#paciFiltroP1").val();
          new Autocomplete("stdNameP1", function() {
            this.setValue = function(id) {
              document.getElementById("stdIDP1").value = id;
            }
            if (this.isModified) this.setValue("");
            if (this.value.length < 1) return;
            return "autocompletar/tb/trabajador.php?q=" + this.text.value + "&filtro=" + filtroValueP + ""
          });
        </script>
      </div>
    </div>
  </div>
</div>
<div id="myModalO1" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalObservacionesNO()">&times;</span>
      <span>Participantes</span>
    </div>
    <div class="modal-body">
      <div id="modal_contenido">
        <table width="90%" align="center">
          <tr>
            <td class="etiqueta">Observación:</td>
            <td>
              <textarea name="observacionNO" id="observacionNO" cols="60" rows="5"></textarea>
              <img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Observación" title="Agregar Observación" onclick="addObservacionNO()" width="15px" height="15px">
            </td>
          </tr>
        </table>
        <table width="90%" class="tabla_sistema">
          <thead>
            <tr>
              <th width="15%">Código</th>
              <th width="85%">Observación</th>
            </tr>
          </thead>
          <tbody id="observacionesNO">

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<input type="hidden" name="cod_det2" id="cod_det2" value="">
<input type="hidden" name="cod_det" id="cod_det" value="">
<input type="hidden" name="vector" id="vector" value="">
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us; ?>" />

<script type="text/javascript">
  filtroValue = $("#paciFiltro").val();
  new Autocomplete("stdName", function() {
    this.setValue = function(id) {
      document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
      Add_filtroX();
    }
    if (this.isModified) this.setValue("");
    if (this.value.length < 1) return;
    return "autocompletar/tb/trabajador.php?q=" + this.text.value + "&filtro=" + filtroValue + ""
  });
</script>