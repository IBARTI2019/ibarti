<script language="javascript">
  $("#add_stock_ubic_alcance_form").on('submit', function(evt) {
    evt.preventDefault();
    save_stock_ubic_alcance();
  });
</script>

<?php
session_start();
require_once('../../../../sql/sql_report_t.php');
require "../modelo/stock_ubic_alcance_modelo.php";
require "../../../../" . Leng;
$stock_ubic_alcance = new stock_ubic_alcance;
$metodo = $_POST['metodo'];
$disabled = '';
if ($metodo == 'modificar') {
  $anulado   = $_POST['anulado'];
  $codigo   = $_POST['codigo'];
  $titulo   = "Modificar Movimiento";
  $ped      =  $stock_ubic_alcance->editar($codigo);
  $disabled = 'disabled="true"';
} else {
  $titulo    = "Agregar Movimiento";
  $ped       = $stock_ubic_alcance->inicio();
  $anulado   = "F";
  $codigo    = 0;
}
?>

<div id="add_stock_ubic_alcances">
  <span class="etiqueta_title"><?php echo $titulo; ?></span>

  <form name="add_stock_ubic_alcance" id="add_stock_ubic_alcance_form">

    <div style="float: right;" align="center">
      <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/borrar.bmp" title="Borrar Registro" id="borrar_stock_ubic_alcance" onclick="Borrar_stock_ubic_alcance()" />
      <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/nuevo.bmp" id="agregar_stock_ubic_alcance" title="Agregar Registro" onclick="Agregarstock_ubic_alcance()" />
      <img border="null" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscar_producto_title" onclick="Cons_stock_ubic_alcance()" />
    </div>
    <table width="95%" align="center">
      <tr>
        <td height="8" colspan="5" align="center">
          <hr>
        </td>
      </tr>
      <tr>
        <td width="14%" class="etiqueta">N. Movimiento:</td>
        <td width="27%" class="etiqueta">Cliente:</td>
        <td width="27%" class="etiqueta">Ubicación:</td>
        <td width="20%" class="etiqueta">Fecha</td>
      </tr>
      <tr>
        <td>
          <input type="number" id="ped_codigo" title="Este codigo es generado por el sistema, al guardar el movimiento" placeholder="Código" value="<?php echo $ped['codigo']; ?>" required readonly>
        </td>
        <td>
          <select name="cliente" id="cliente" style="width:250px;" value="<?php echo $ped['cod_cliente']; ?>" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '250')">

            <?php
            if ($metodo == 'agregar') {
              $query02 = $bd->consultar($sql_cliente);
              echo '<option value="TODOS"> TODOS</option>';
              while ($row02 = $bd->obtener_fila($query02, 0)) {
                echo '<option value="' . $row02[0] . '">' . $row02[1] . '</option>';
              }
            } else {
              echo '<option value="' . $ped['cod_cliente'] . '">' . $ped['cliente'] . '</option>';
            } ?>
          </select>
        </td>
        <td id="contenido_ubic" onchange="Add_Cl_Alcance(ubicacion.value, 'datos_dotacion', 'F', '150')">
          <select name="ubicacion" id="ubicacion" style="width:150px;" >
            <option value="<?php echo $ped['cod_ubicacion']; ?>"><?php echo $ped['ubicacion']; ?></option>
          </select>
        </td>
        <td>
          <input type="date" id="ped_fecha" value="<?php echo $ped['fecha']; ?>" <?php echo $disabled; ?> placeholder="Fecha de Emisión" required>
        </td>
      </tr>
      <tr>
        <td colspan="4" class="etiqueta">Descripcion</td>
      </tr>
      <tr>
        <td colspan="4">
          <textarea <?php echo $disabled; ?> id="ped_descripcion" cols="100" rows="3"><?php echo $ped['motivo']; ?></textarea>
        </td>
      </tr>
      <?php
      if ($anulado == "T") {
        echo '<tr><td colspan="4"><p><b>Nota</b>: Ajuste ANULADO, ' . $ped['descripcion_anulacion'] . '</p></td></tr>';
      }
      ?>
      <tr>
        <td height="8" colspan="5" align="center">
          <hr>
        </td>
      </tr>
    </table>
    <div id="stock_ubic_alcance_det"></div>
    <br>
    <div align="center">
      <?php if ($metodo == "agregar") {
        echo '<span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input  type="submit" title="Guardar Registro" class="readon art-button" value="Guardar" />
        </span>';
      } else {
        echo '<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0" width="25px" title="imprimir a pdf"  onclick="imprimir()">';

        if ($anulado == "F") {
          echo '<span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="button" title="Anular Ajuste" class="readon art-button" id="anulador" value="Anular" onclick="anular_stock_ubic_alcance()" />
        </span>';
        }
      } ?>
      <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="button" title="Volver a la página anterior" onclick="Cons_stock_ubic_alcance()" class="readon art-button" value="Volver" />
        <input id="ped_metodo" type="text" value="<?php echo $metodo; ?>" hidden>
      </span>
    </div>

  </form>
  <form name="form_reportes" id="form_reportes" action="reportes/pdf_dot_cliente.php" method="post" target="_blank">
    <input type="int" id="codigo" name="codigo" hidden="hidden">
    <input type="submit" name="procesar" id="procesar" hidden="hidden">
  </form>
</div>