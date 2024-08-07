<?php
session_start();
require_once('../../../../sql/sql_report_t.php');
require "../modelo/stock_ubic_alcance_modelo.php";
require "../../../../" . Leng;
$titulo     = "MOVIMIENTO";
$ajuste  = new stock_ubic_alcance;
$listar  =  $ajuste->get();
?>
<div align="center" class="etiqueta_title">CONSULTA <?php echo $titulo; ?></div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes">
  <fieldset>
    <legend>Filtros:</legend>
    <table width="100%">
      <tr>
        <td width="10%">Fecha Desde:</td>
        <td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="10" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
        <td width="10%">Fecha Hasta:</td>
        <td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="10" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>
        <td width="10%">Código:</td>
        <td>
          <input type="text" id="codigo" maxlength="60" style="width:50px;" placeholder="" value="">
        </td>

        <td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0" onclick=" buscarMovimiento()" /></td>
        <td><input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
          <input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
          <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo; ?>" />
          <input type="hidden" name="tabla" id="tabla" value="<?php echo $tabla; ?>" /></td>
      </tr>
      <tr>
        <td width="12%">Cliente:</td>
        <td width="14%">
          <select name="cliente" id="cliente" style="width:150px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'F', '150')">
            <option value="TODOS"> TODOS</option>
            <?php $query02 = $bd->consultar($sql_cliente);
            while ($row02 = $bd->obtener_fila($query02, 0)) {
              echo '<option value="' . $row02[0] . '">' . $row02[1] . '</option>';
            } ?>
          </select>
        </td>
        <td width="12%" >Ubicación:</td>
          <td id="contenido_ubic">
          <select name="ubicacion" id="ubicacion" style="width:150px;" >
            <option value="<?php echo $ped['cod_ubicacion']; ?>"><?php echo $ped['ubicacion']; ?></option>
          </select>
         </td>
                
        <td>Producto:</td>
        <td colspan="4"><input id="stdName" type="text" style="width:200px" />
          <input type="hidden" name="producto" id="stdID" value="" /></td>
      </tr>
    </table>
  </fieldset>
</form>
<table width="100%">
  <tr>
    <td><input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
      <input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
      <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo; ?>" />
      <input type="hidden" name="tabla" id="tabla" value="<?php echo $tabla; ?>" /></td>
  </tr>
</table>
<div class="tabla_sistema listar">
  <table width="100%" border="0" align="center">
    <thead>
      <tr>
        <th>Nro. Movimiento</th>
        <th>Ubicación</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Anulado</th>
        <th align="center"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null" onclick="Form_stock_ubic_alcance('','agregar')" title="Agregar Registro" /></th>
      </tr>
    </thead>
    <tbody id="listar_stock_ubic_alcance" align="center">
      <?php
      foreach ($listar as  $datos) {
        echo ' <tr onclick="Form_stock_ubic_alcance(\'' . $datos["codigo"] . '\', \'modificar\',\'' . $datos["anulado"] . '\')">
            <td>' . $datos["codigo"] . '</td>
            <td>' . $datos["ubicacion"] . '</td>
            <td>' . $datos["fecha"] . '</td>
            <td>' . $datos["motivo"] . '</td>
            <td>' . $datos["anulado_des"] . '</td>
            <td></td>
            </tr>';
      } ?>

    </tbody>
  </table>
</div>
<script language="JavaScript" type="text/javascript">
  new Autocomplete("stdName", function() {
    this.setValue = function(id) {
      $("#stdID").val(id);
    }
    if (this.value.length < 1) return;
    return "autocompletar/tb/producto_base_serial.php?q=" + this.text.value + "&filtro=codigo"
  });
</script>