<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$titulo ='';
$plan   = new Planificacion;
$cliente  =  $plan->get_cliente();
$valor= "calendario";
?>
<div align="center" class="etiqueta_title" ><?php echo $titulo; ?> </div>
<hr />
<form action="" method="post" name="add_planificacion" id="add_planificacion">
  <table width="100%" align="center" >
    <tr>
      <td width="9%" class="etiqueta" id="cliente_cont" ><?php echo $leng['cliente'] ?>:</td>
      <td width="21%"><select id="planf_cliente" style="width:200px" required onchange="verificar_cl(this.value)">
          <option value="">Seleccione</option>
          <?php
          foreach ($cliente as  $datos) {
            echo '<option value="' . $datos[0] . '">' . $datos[1] . ' ' . $datos[3] . '</option>';
          } ?>
        </select></td>
      <!--       <td width="9%" class="etiqueta"><span id="region_texto"><?php echo $leng['region'] ?>:</span></td>
      <td width="21%"><span id="region_cont"><select id="planf_region" style="width:200px" required onchange="cargar_planif_superv(undefined, undefined)">
            <option value="">TODAS</option>
          </select></span></td> -->
      <td width="9%" class="etiqueta">
        <span id="ubicacion_texto"><?php echo $leng['ubicacion'] ?>:</span>
      </td>
      <td width="21%"><span id="ubicacion_cont"><select id="planf_ubicacion" required onchange="cargar_planif_superv(this.value, undefined)" style="width:200px">
            <option value="">Seleccione..</option>
          </select></span></td>
    </tr>
    <tr>
      <td width="9%" class="etiqueta">
        <span id="cargo_texto">Formas de Contactos:</span>
      </td>
      <td width="21%"><span id="cargo_cont"><select id="planf_cargo" required onchange="cargar_planif_superv($('#planf_ubicacion').val(), this.value)" style="width:200px">
            <option value="">Seleccione..</option>
          </select></span></td>
      <td width="9%" class="etiqueta"><span id="apertura_texto">Actividades:</span></td>
      <td width="21%"><span id="apertura_cont"><select id="planf_apertura" onchange="onChangeAp(this.value)"  style="width:200px">
            <option value="">Seleccione</option>
          </select><img class="imgLink" src="imagenes\ico_agregar.ico" alt="Agregar Planificacion" title="Agregar Planificacion" onclick="B_planif_apertura()" width="15px" height="15px">
          <!--  <img class="imgLink" id="mod_ap_planif" style="display:none;" src="imagenes/detalle.bmp" alt="Modificar Apertura" title="Modificar Apertura" onclick="mod_apertura_planif()" width="15px" height="15px"></span></td> -->
    </tr>
    <tr>
      <td height="8" colspan="6" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <div id="cont_supervision_det"></div>
  <div id="cont_planif_det"></div>
  <!-- class="tabla_sistema" -->
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
      <input type="button" value="clientes sin Agendar" onClick="trab_sin_planificar()" class="readon art-button" />
    </span>
    <input name="metodo" id="h_metodo" type="hidden" value="<?php echo $metodo; ?>" />
  </div>
</form>