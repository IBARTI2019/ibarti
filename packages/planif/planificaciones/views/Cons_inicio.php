<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$titulo = $leng['planificacion'];
$plan   = new Planificacion;
$cliente  =  $plan->get_cliente($_POST["usuario"], $_POST["r_cliente"]);
?>
<div align="center" class="etiqueta_title"><?php echo $titulo; ?> </div>
<hr />
<form action="" method="post" name="add_planificacion" id="add_planificacion">
  <table width="100%" align="center">
    <tr>
      <td width="15%" class="etiqueta"><?php echo $leng['cliente'] ?>:</td>
      <td width="35%"><select id="planf_cliente" style="width:200px" required onchange="verificar_cl(this.value)">
          <option value="">Seleccione</option>
          <?php
          foreach ($cliente as  $datos) {
            echo '<option value="' . $datos[0] . '">' . $datos[1] . ' ' . $datos[3] . '</option>';
          } ?>
        </select></td>
      <td width="15%" class="etiqueta"><span id="contratacion_texto"><?php echo $leng['contratacion'] ?>:</span></td>
      <td width="35%"><span id="contratacion_cont"><select id="planf_contratacion" required onchange="verificar_cont(this.value)" style="width:200px">
            <option value="">Seleccione</option>
          </select><img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Contratacion" title="Agregar Contratacion" onclick="B_contratacion()" width="15px" height="15px"></span></td>
    </tr>
    <tr>
      <td width="15%" class="etiqueta"><span id="ubicacion_texto"><?php echo $leng['ubicacion'] ?>:</span> </td>
      <td width="35%"><span id="ubicacion_cont"><select id="planf_ubicacion" required onchange="cargar_planif($('#planf_apertura').val())" style="width:200px">
            <option value="">Seleccione</option>
          </select></span></td>
      <td width="15%" class="etiqueta"><span id="apertura_texto">Apertura De Planificacion:</span></td>
      <td width="35%"><span id="apertura_cont"><select id="planf_apertura" onchange="mostrar_icono_apertura(this.value)" required onclick="cargar_planif(this.value)" style="width:200px">
            <option value="">Seleccione</option>
          </select><img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Planificacion" title="Agregar Planificacion" onclick="B_planif_apertura()" width="15px" height="15px">
          <img class="imgLink" id="mod_ap_planif" style="display:none;" src="imagenes/detalle.bmp" alt="Modificar Apertura" title="Modificar Apertura" onclick="mod_apertura_planif()" width="15px" height="15px">
          <img class="imgLink" id="delete_ap_planif" style="display:none;" src="imagenes/borrar.bmp" alt="Borrar Apertura" title="Borrar Apertura" onclick="delete_apertura_planif()" width="15px" height="15px"></span>
      </td>
    </tr>
    <tr>
      <td height="8" colspan="4" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <div id="cont_contratacion_det"></div>
  <!-- borrar
  <div id="cont_planif_pl" class="tabla_sistema"></div>

-->
  <div id="cont_planif_det" class="tabla_sistema"></div>
  <div align="center">
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Volver" onClick="Cons_planificacion_inicio()" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Servicios Trabajadores" onClick="B_reporte('F')" class="readon art-button" />
    </span>
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Servicios Trabajadores Detalle" onClick="B_reporte('T')" class="readon art-button" />
    </span>
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Trabajadores Sin Planificar" onClick="trab_sin_planificar()" class="readon art-button" />
    </span>
    <input name="metodo" id="h_metodo" type="hidden" value="<?php echo $metodo; ?>" />
  </div>
</form>
<form action="packages/planif/planificaciones/views/rp_planif_trab.php" method="post" name="add_planif_det" id="add_planif_det" method="post" target="_blank">
  <input type="hidden" name="apertura" id="cod_apertura" value="">
  <input type="hidden" name="ficha" id="cod_ficha" value="">
  <input type="hidden" name="reporte" id="reporte" value="">
</form>
<form action="packages/planif/planificaciones/views/rp_planif_serv.php" method="post" name="add_planif_serv" id="add_planif_serv" method="post" target="_blank">
  <input type="hidden" name="contratacion" id="cod_contratacion_serv" value="">
  <input type="hidden" name="apertura" id="cod_apertura_serv" value="">
  <input type="hidden" name="ubicacion" id="cod_ubic_serv" value="">
  <input type="hidden" name="usuario" id="cod_usuario_serv" value="">
  <input type="hidden" name="reporte" id="reporte_serv" value="">
</form>