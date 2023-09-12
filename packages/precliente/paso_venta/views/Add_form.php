<script language="javascript">
	$("#cl_pasoventa_det_form").on('submit', function(evt) {
		evt.preventDefault();
		save_paso_venta("", "agregar");
	});
</script>
<?php
require "../modelo/pasoventa_modelo.php";
require "../../../../" . Leng;
$pasoventa = new Pasoventa;
$preclientes = $pasoventa->get_preclientes();
$rutas = $pasoventa->get_rutas();
?>

<div>
  <form id="cl_pasoventa_det_form" name="cl_pasoventa_det_form" method="post">
    <table width="75%" border="0" align="center">
        <td height="8" colspan="2" align="center"><hr></td>
        <tr>
          <td class="etiqueta" ><?php echo $leng['precliente'];: ?></td>
          <td>
            <select name="precliente" id="paso_precliente" style="width:320px">
              <option value="">Seleccione...</option>
              <?php
                foreach ($preclientes as  $datos) {
                  echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
                }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td class="etiqueta" >Ruta de venta:</td>
          <td>
            <select name="ruta" id="paso_ruta" style="width:320px" onchange="getSubRutas(this.value)">
              <option value="">Seleccione...</option>
                <?php
                  foreach ($rutas as  $datos) {
                    echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
                  }
                ?>
            </select>
          </td>
        </tr>

        <tr>
          <td class="etiqueta">Sub ruta de venta:</td>
          <td>
            <select name="sub_ruta" id="paso_subruta" style="width:320px">
              <option value="">Seleccione...</option>
            </select>
          </td>
        <tr>
          <td class="etiqueta">Comentario:</td>	
          <td>
            <textarea name="comentario" id="paso_comentario" cols="60" rows="1"></textarea>
          </td>
        </tr>
        <td height="8" colspan="2" align="center"><hr></td>
    </table>


    <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="submit" name="guardar" id="guardar" value="Guardar" class="readon art-button" >
    </span>
    <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
    </span>
  </form>
</div>