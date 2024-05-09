<script language="javascript">
  $("#add_planif_ap_ing").on('submit', function(evt) {
    evt.preventDefault();
    save_planif_apertura();
  });
</script>
<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;
$ubic = $_POST["ubic"];
$cargo = $_POST["cargo"];
$planif      = new Planificacion;
$ap_incio   = $planif->get_planif_ap_inicio($ubic, $cargo);
?>
<form action="" method="post" name="add_planif_ap_ing" id="add_planif_ap_ing">
  <table width="100%" align="center">
    <tr>
      <td height="8" colspan="6" align="center">
        <hr>
      </td>
    </tr>
    <tr>
      <td width="14%" class="etiqueta">Fecha Inicio:</td>
      <td width="18%"><input type="date" id="ap_fecha_inicio" min="<?php echo $ap_incio['fecha_inicio']; ?>" value="<?php echo $ap_incio['fecha_inicio']; ?>" required></td> <!-- Omitir onchange="ActFechaMin(this.value, 'ap_fecha_fin')"-->
      <td width="14%" class="etiqueta">Fecha Fin:</td>
      <td width="18%"><input type="date" id="ap_fecha_fin" value="" min="<?php echo $ap_incio['fecha_inicio']; ?>" required></td>
      <td><span style="float: right;" align="center">
          <img class="imgLink" style="display: none;" width="25px" height="25px" src="imagenes/borrar.bmp" title="Borrar Registro" onclick="Borrar_horario()" id="borrar_horario" />
          <img class="imgLink" style="display: none;" width="25px" height="25px" src="imagenes/nuevo.bmp" alt="Agregar" onclick="irAAgregarHorario()" title="Agregar Registro" id="agregar_horario" />
          <img class="imgLink" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscar_horario_title" onclick="Cons_Apertura()" />
        </span></td>
    </tr>
    <tr>
      <td height="8" colspan="6" align="center">
        <hr>
      </td>
    </tr>
  </table>

  <div align="center"><span class="art-button-wrapper" id="planif_apertura_ing">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Volver" onClick="	CloseModal()" class="readon art-button" />
    </span>

    <input name="metodo" id="h_metodo" type="hidden" value="<?php echo $metodo; ?>" />
  </div>

</form>