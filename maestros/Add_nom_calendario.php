<?php
$metodo = $_GET['metodo'];
$archivo = $_GET['archivo'];
$Nmenu = 339;
$mod   = $_GET['mod'];
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=" . $Nmenu . "&mod=" . $mod . "";
$titulo = " CALENDARIO ";
$metodo  = $_GET['metodo'];
require_once('autentificacion/aut_verifica_menu.php');

$proced      = "p_nom_calendario";
if ($metodo == 'modificar') {
    $titulo      = "MODIFICAR $titulo";
    $codigo      = $_GET['codigo'];
    $bd = new DataBase();

    $sql =   "SELECT nom_calendario.codigo, nom_calendario.descripcion,
                     nom_calendario.tipo, nom_calendario.`status`
                FROM nom_calendario
			   WHERE nom_calendario.codigo = '$codigo'
            ORDER BY 2 DESC ";
    $query = $bd->consultar($sql);
    $result = $bd->obtener_fila($query, 0);

    $descripcion  = $result['descripcion'];
    $tipo         = $result['tipo'];
    $activo       = $result['status'];
} else {
    $titulo      = "AGREGAR $titulo";
    $codigo      = '';
    $descripcion = '';
    $tipo        = '';
    $activo      = 'T';
} ?>
<form action="sc_maestros/sc_<?php echo $archivo; ?>.php" method="post" name="add" id="add" enctype="multipart/form-data">
    <table width="80%" align="center">
        <tr valign="top">
            <td height="23" colspan="2" class="etiqueta_title" align="center"><?php echo $titulo; ?></td>
        </tr>
        <tr>
            <td height="8" colspan="2" align="center">
                <hr>
            </td>
        </tr>
        <tr>
            <td class="etiqueta">Descripcion:</td>
            <td id="input01"><input type="text" name="descripcion" style="width:250px" value="<?php echo $descripcion; ?>" />Activo: <input name="activo" type="checkbox" <?php echo statusCheck("$activo"); ?> value="T" /><br />
                <span class="textareaRequiredMsg">El Campo es Requerido.</span>
                <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Feriados:</td>
            <td id="radio01">Fijo <input type="radio" name="tipo" value="FIJO" style="width:auto" <?php echo CheckX($tipo, "FIJO"); ?> /> Variable <input type="radio" name="tipo" value="VAR" style="width:auto" <?php echo CheckX($tipo, "VAR"); ?> />
                <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
            </td>
        </tr>
        <tr>
            <td height="8" colspan="2" align="center">
                <hr>
            </td>
        </tr>
    </table>
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
            <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
        </span>
        <input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
        <input name="proced" type="hidden" value="<?php echo $proced; ?>" />
        <input name="usuario" type="hidden" value="<?php echo $usuario; ?>" />
        <input name="href" type="hidden" value="<?php echo $archivo2; ?>" />
        <input name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
    </div>
</form>
</body>

</html>
<script type="text/javascript">
    var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
        validateOn: ["blur", "change"]
    });
    var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {
        format: "dd-mm-yyyy",
        hint: "DD-MM-AAAA",
        validateOn: ["blur", "change"],
        useCharacterMasking: true
    });

    var radio01 = new Spry.Widget.ValidationRadio("radio01", {
        validateOn: ["change", "blur"]
    });
</script>