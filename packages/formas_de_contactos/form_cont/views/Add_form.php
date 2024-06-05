<script language="javascript">
  $("#add_proyecto").on('submit', function(evt) {
    evt.preventDefault();
    save_proyecto();
  });
</script>
<?php
require "../modelo/proyecto_modelo.php";
require "../../../../".Leng;
$horario = new Proyecto;
$metodo = $_POST['metodo'];

if ($metodo == 'modificar') {
  $codigo   = $_POST['codigo'];
  $titulo   = "Modificar Formas de Contactos";
  $rot      =  $horario->editar("$codigo");
} else {
  $titulo    = "Agregar Formas de Contactos";
  $rot      =  $horario->inicio();
}

$sql_areas = "SELECT codigo, descripcion FROM formas_de_contactos WHERE status = 'T';";
?>
<form action="" method="post" name="add_proyecto" id="add_proyecto">
  <fieldset class="fieldset">
    <legend><?php echo $titulo; ?> </legend>
    <table width="95%" align="center">
      <tr>
        <td width="15%" class="etiqueta">C&oacute;digo:</td>
        <td width="35%"><input type="text" id="r_codigo" readonly value="<?php echo $rot["codigo"]; ?>" />
          Activo: <input id="r_status" type="checkbox" <?php echo statusCheck($rot["status"]); ?> value="T" />
        </td>
        
      </tr>
      <tr>
      <td width="15%" class="etiqueta">Abrev:</td>
        <td width="35%"><input type="text" id="r_abrev" minlength="2" maxlength="16" required value="<?php echo $rot["abrev"]; ?>" /></td>
        <td class="etiqueta">Nombre: </td>
        <td colspan="2"><input type="text" id="r_nombre" minlength="4" maxlength="60" required style="width:300px" value="<?php echo $rot["descripcion"]; ?>" /></td>
      </tr>
    </table>
    <div id="Cont_detalleR" class="tabla_sistema"></div><br><br>
    <div align="center" class="etiqueta_title"></div>
    <div id="Cont_cargosR" class="tabla_sistema"></div><br><br>
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
        <input type="button" id="volver" value="Volver" onClick="Cons_proyecto_inicio()" class="readon art-button" />
      </span>

      <input name="metodo" id="h_metodo" type="hidden" value="<?php echo $metodo; ?>" />
    </div>
  </fieldset>
</form>