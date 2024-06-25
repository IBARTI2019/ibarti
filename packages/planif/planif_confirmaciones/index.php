<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<script type="text/javascript" src="packages/planif/planif_confirmaciones/controllers/confirmacionesCtrl.js"></script>
<style>
  .marcar {
    text-decoration: line-through;
  }
</style>
<?php
$Nmenu = '4409';
require "autentificacion/aut_config.inc.php";
require Leng;
if (isset($_SESSION['usuario_cod'])) {
  require_once('autentificacion/aut_verifica_menu.php');
  require_once('sql/sql_report.php');
  $us = $_SESSION['usuario_cod'];
} else {
  $us = $_POST['usuario'];
}
?>
<div id="Cont_confirmaciones">

  <span class="etiqueta_title" id="title_confirmaciones">Confirmaciones</span>
  <table width="90%" align="center">
    <tr>
      <td height="8" colspan="4" align="center">
        <hr>
      </td>
    </tr>
        <tr>

      <td class="etiqueta"><?php echo $leng["cliente"];?>:</td>
      <td><select name="cliente" id="cliente" style="width:250px;" onchange="changeCliente(this.value)" required>
      <?php
          echo $select_cl;
          $query01 = $bd->consultar($sql_cliente);
          while ($row01 = $bd->obtener_fila($query01, 0)) {
            echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
          } ?></select></td>
      <td class="etiqueta"><?php echo $leng["ubicacion"];?>: </td>
      <td id="contenido_ubic">
        <select name="ubicacion" id="ubicacion" style="width:250px;">
          <option value="TODOS">TODOS</option>
        </select>
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
          <option value="telefono"> Tel&eacute;fono </option>
        </select>
      </td>
      <td class="etiqueta">Trabajador:</td>
      <td><input id="stdName" type="text" style="width:380px" disabled="disabled" />
        <input type="hidden" name="trabajador" id="stdID" value="" onchange="Add_filtroX()" />
      </td>
    </tr>
    <tr>
    <tr>
      <td height="8" colspan="4" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <table width="90%" class="tabla_sistema">
    <thead>
      <tr>
        <th><?php echo $leng["cliente"]; ?></th>
        <th><?php echo $leng["ubicacion"]; ?></th>
        <th><?php echo $leng["ficha"]; ?></th>
        <th>Telefonos</th>
        <th><?php echo $leng["trabajador"]; ?></th>
        <th><?php echo $leng["turno"]; ?></th>
        <th><?php echo $leng["horario"]; ?></th>
        <th><?php echo $leng["concepto"]; ?></th>
        <th>Hora entrada</th>
        <th>Hora de confirmacion</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="planificacion">

    </tbody>
  </table>
</div>

<input name=" usuario" id="usuario" type="hidden" value="<?php echo $us; ?>" />
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
  var time01 = new Spry.Widget.ValidationTextField("time01", "time", {format:"HH:mm:ss", hint:'HH:mm:ss', useCharacterMasking:true, validateOn:["change"],isRequired:true});
</script>