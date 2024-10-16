<script language="javascript">
  $("#add").on('submit', function(evt) {
    evt.preventDefault();
    save();
  });
</script>
<?php
require "../modelo/sub_linea_modelo.php";
require "../../../../" . Leng;
$codigo   = $_POST['codigo'];
$metodo   = $_POST['metodo'];
$modelo = new ProductoSubLinea;
$productos = array();
$disabled = '';
if ($metodo == 'modificar') {
  $titulo    = " MODIFICAR SUB LINEA (" . $codigo . ")";
  $productos        = $modelo->get_productos($codigo);
  if(count($productos) > 0){
    $disabled = 'disabled';
  }
  $prod        = $modelo->editar($codigo);
  $lineas   = $modelo->get_lineas();
} else {
  $prod   = $modelo->inicio();
  $lineas   = $modelo->get_lineas();
  $titulo    = "AGREGAR SUB LINEA";
}

$activo = $prod['status'];
?>

<form name="add" id="add">

  <fieldset class="fieldset">
    <span style="float: right;" align="center">
      <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/borrar.bmp" title="Borrar Registro" onclick="borrarSubLinea()" id="borrar_modelo" />
      <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/nuevo.bmp" alt="Agregar" onclick="irAAgregarSubLinea()" title="Agregar Registro" id="agregar_modelo" />
      <img border="null" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscar_producto_title" onclick="B_sub_lineas()" />
    </span>
    <legend> <?php echo $titulo; ?> </legend>
    <table width="80%" align="center">
      <tr>
        <td class="etiqueta">C&oacute;digo:</td>
        <td><input type="text" name="codigo" id="codigo" maxlength="11" style="width:120px" value="<?php echo $codigo; ?>" <?php echo $disabled; ?> required />
          Activo: <input name="activo" id="activo" type="checkbox" <?php echo statusCheck("$activo"); ?> value="T" /><br />
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Linea:</td>
        <td>
          <select name="linea" id="linea" style="width:250px" <?php echo $disabled; ?> required>
            <?php
            if ($metodo == "agregar") {
              echo '<option value="">Seleccione...</option>';
            }
            foreach ($lineas as  $datos) {
              if ($prod['cod_linea'] == $datos[0]) {
                echo '<option value="' . $datos[0] . '" selected>' . $datos[1] . '</option>';
              } else {
                echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
              }
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Descripcion: </td>
        <td><input type="text" name="descripcion" id="descripcion" maxlength="60" style="width:350px" value="<?php echo $prod['descripcion']; ?>" required />
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Propiedades: </td>
        <td id="prop">
          <?php
          echo 'Color: <input name="color" id="color" type="checkbox" ' . statusCheck($prod['color']) . ' value="T" '. $disabled .'/> 

          Talla: <input name="talla" id="talla" type="checkbox" ' . statusCheck($prod['talla']) . ' value="T" '. $disabled .'/> 

          Peso: <input name="peso" id="peso" type="checkbox" ' . statusCheck($prod['peso']) . ' value="T" '. $disabled .' /> 
          
          Piecubico: <input name="piecubico" id="piecubico" type="checkbox" ' . statusCheck($prod['piecubico']) . ' value="T" '. $disabled .'/>      ';
          ?>
        </td>
      </tr>
      <tr>
      <tr>
        <td height="8" colspan="2" align="center">
          <hr>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="hidden" name="metodo" id="metodo" value="<?php echo $metodo; ?>">
        </td>
      </tr>
    </table>
  </fieldset>
  <div align="center">
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
    </span>
  </div>
</form>