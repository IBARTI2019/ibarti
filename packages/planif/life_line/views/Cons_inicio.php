<?php
require "../modelo/life_line_modelo.php";
require "../../../../" . Leng;

$titulo = "Linea de Vida";
$plan   = new LifeLine;
$cliente  =  $plan->get_cliente($_POST["usuario"], $_POST["r_cliente"]);
?>
<div align="center" class="etiqueta_title"><?php echo $titulo; ?> </div>
<hr />
<form action="" method="post" name="add_planificacion" id="add_planificacion">
  <table width="100%" align="center">
    <tr>
      <td width="10%"  class="etiqueta"><?php echo $leng['cliente'] ?>:</td>
      <td ><select id="planf_cliente" style="width:300px" required onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '300')">
          <option value="">Seleccione</option>
          <?php
          foreach ($cliente as  $datos) {
            echo '<option value="' . $datos[0] . '">' . $datos[1] . ' ' . $datos[3] . '</option>';
          } ?>
        </select>
      </td>

      <td width="15%" class="etiqueta"><span id="ubicacion_texto"><?php echo $leng['ubicacion'] ?>:</span> </td>
      <td width="35%">
        <span id="contenido_ubic"><select id="ubicacion" required onchange="Add_filtroX()" style="width:300px">
            <option value="">Seleccione</option>
          </select>
        </span>
      </td>
    
    </tr>
    <tr>
      <td height="8" colspan="4" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <div id="cont_planif_det" class="tabla_sistema"></div>
  <br><br>
  <div align="center">
    <!-- <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Volver" onClick="Cons_planificacion_inicio()" class="readon art-button" />
    </span>&nbsp; -->
    <!-- <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Ver Gracifa" onClick="B_reporte('F')" class="readon art-button" />
    </span> -->
    <input name="metodo" id="h_metodo" type="hidden" value="<?php echo $metodo; ?>" />
  </div>
</form>
<form action="packages/planif/life_line/views/rp_planif_trab.php" method="post" name="add_planif_det" id="add_planif_det" method="post" target="_blank">
  <input type="hidden" name="apertura" id="cod_apertura" value="">
  <input type="hidden" name="ficha" id="cod_ficha" value="">
  <input type="hidden" name="reporte" id="reporte" value="">
</form>
<form action="packages/planif/life_line/views/rp_planif_serv.php" method="post" name="add_planif_serv" id="add_planif_serv" method="post" target="_blank">
  <input type="hidden" name="contratacion" id="cod_contratacion_serv" value="">
  <input type="hidden" name="apertura" id="cod_apertura_serv" value="">
  <input type="hidden" name="ubicacion" id="cod_ubic_serv" value="">
  <input type="hidden" name="usuario" id="cod_usuario_serv" value="">
  <input type="hidden" name="reporte" id="reporte_serv" value="">
  <input type="hidden" name="idcliente" id="idcliente" value="<?php echo $cliente; ?>">

</form>