<script language="javascript">
  $("#add_life_line").on('submit', function(evt) {
    evt.preventDefault();
    save_life_line();
  });
</script>
<?php
require "../modelo/life_line_act_modelo.php";
require "../../../../" . Leng;
$life_line_act = new LifeLine;
$metodo = $_POST['metodo'];

if ($metodo == 'modificar') {
  $codigo   = $_POST['codigo'];
  $titulo   = "Modificar Actividad de Line de Vida";
  $actividad      =  $life_line_act->editar("$codigo");
} else {
  $titulo    = "Agregar Actividad de Line de Vida";
  $actividad      =  $life_line_act->inicio();
}

?>
<form action="" method="post" name="add_life_line" id="add_life_line">
  <fieldset class="fieldset">
    <legend><?php echo $titulo; ?> </legend>
    <table width="95%" align="center">
      <tr>
        <td class="etiqueta">C&oacute;digo:</td>
        <td><input type="text" id="r_codigo" readonly value="<?php echo $actividad["codigo"]; ?>" />
        </td>
        <td class="etiqueta">Abrev:</td>
        <td><input type="text" id="r_abrev" minlength="2" maxlength="16" required value="<?php echo $actividad["abrev"]; ?>" />
        </td>
        <td class="etiqueta">
          Activo: <input id="r_status" type="checkbox" <?php echo statusCheck($actividad["status"]); ?> value="T" />
        </td>
       </td>
      </tr>
      <tr>
        <td class="etiqueta">Nombre: </td>
        <td colspan="3"><input type="text" id="r_nombre" minlength="4" maxlength="240" required style="width:500px" value="<?php echo $actividad["descripcion"]; ?>" /></td>
        <td class="etiqueta">Color: <input name="r_color" id="r_color" type="color" value="<?php echo $actividad["color"]; ?>" ></td>
      </tr>
    </table>
    <br>
    <div align="center"><span class="art-button-wrapper">
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
        <input type="button" id="volver" value="Volver" onClick="Cons_life_line_inicio()" class="readon art-button" />
      </span>

      <input name="metodo" id="h_metodo" type="hidden" value="<?php echo $metodo; ?>" />
    </div>
  </fieldset>
</form>