<?php
require "../modelo/existencia_modelo.php";
require "../../../../".Leng;
$producto         = new Existencia;
$titulo           = "Existencia";
//$archivo      = "packages/inventario/stock/views/reporte_det.php";
$archivo      = "reportes/rp_existencia_det.php";

$prod_linea     = $producto->get_prod_linea();
$prod_sub_linea     = $producto->get_prod_sub_linea("TODOS");
$prod_almacen     = $producto->get_prod_almacenes("TODOS");
$prod_producto     = $producto->get_prod_productos("TODOS");
?>

<div align="center" class="etiqueta_title"><?php echo $titulo;?></div>
<form name="add_delivery" id="add_delivery_form" action="<?php echo $archivo;?>"  method="post" target="_blank">
  <table width="95%" align="center">
    <tr>
      <td height="8" colspan="5" align="center"><hr></td>
    </tr>
    <tr>
      <td width="20%" class="etiqueta">Linea</td>
      <td width="25%" class="etiqueta">Sub Linea</td>
      <td width="25%" class="etiqueta">Producto</td>
      <td width="25%" class="etiqueta">Almacen</td>
      <td width="5%" class="etiqueta"></td>
    </tr>
    <tr>
      <td>
        <select name="linea" id="prod_linea" style="width:200px" onchange="Actualizar_sub_linea(this.value)">
          <option value="TODOS">TODOS</option>
          <?php
          foreach ($prod_linea as  $datos) {
            echo '<option value="'.$datos["codigo"].'">'.$datos["descripcion"].'</option>';
          }?>
        </select>
      </td>
      <td id="sub_lineas">
        <select name="sub_linea" id="prod_sub_linea" style="width:200px" onchange="Actualizar_productos(this.value)">
          <option value="TODOS">TODOS</option>
          <?php
          foreach ($prod_sub_linea as  $datos) {
            echo '<option value="'.$datos["codigo"].'">'.$datos["descripcion"].'</option>';
          }?>
        </select>
      </td>
      <td id="productos">
        <select name="producto" id="prod_producto" style="width:200px" onchange="Actualizar_almacenes(this.value)">
          <option value="TODOS">TODOS</option>
          <?php
          foreach ($prod_producto as  $datos) {
            echo '<option value="'.$datos["codigo"].'">'.$datos["descripcion"].'</option>';
          }?>
        </select>
      </td>
      <td id="almacenes">
        <select name="almacen" id="prod_almacen" style="width:200px">
          <option value="TODOS">TODOS</option>
          <?php
          foreach ($prod_almacen as  $datos) {
            echo '<option value="'.$datos["codigo"].'">'.$datos["descripcion"].'</option>';
          }?>
        </select>
      </td>
      <td>
        <img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="buscar_existencia()">
      </td>
    </tr>
  </table>
  <br>

  <table width="95%" class="tabla_sistema">
    <thead>
      <tr>
        <th width="25%" align="center">Almacen</th>
        <th width="25%" align="center">Producto</th>
        <th width="20%" align="center">Serial</th>
        <th width="10%" align="center">Stock Actual</th>
        <th width="10%" align="center">Asignado / Reservado</th>
        <th width="10%" align="center">Importe</th>
        <th width="10%" align="center">Costo Promedio</th>
      </tr>
    </thead>
    <tbody id="listar_stock">
    </tbody>
  </table> 
  <br>
  <div align="center">
    <img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
    onclick="Generar_reporte('excel')" width="25px" title="imprimir a excel">
    <img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0"
    onclick="Generar_reporte('pdf')" width="25px" title="imprimir a pdf">
  </div>

  <button id="reporte_submit" type="submit"  hidden="hidden" ></button>
  <input type="hidden" name="generar_tipo" id="generar_tipo" value="">
</form>
