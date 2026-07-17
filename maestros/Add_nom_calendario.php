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
            <td class="etiqueta">Roles que Aplican:</td>
            <td>
                <div style="max-height: 180px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; width: 280px; background-color: #fff; border-radius: 4px;">
                <?php
                    // 1. Consultar todos los roles activos del sistema
                    $sql_roles = "SELECT codigo, descripcion FROM roles WHERE status = 'T' ORDER BY descripcion ASC;";
                    $query_roles = $bd->consultar($sql_roles);
                    
                    // 2. Si estamos modificando, traer los roles que ya están asociados a este calendario
                    $roles_guardados = array();
                    if ($metodo == 'modificar' && !empty($codigo)) {
                        $sql_assoc = "SELECT cod_rol FROM roles_calendario WHERE cod_calendario = '$codigo';";
                        $query_assoc = $bd->consultar($sql_assoc);
                        while ($row_assoc = $bd->obtener_fila($query_assoc, 0)) {
                            $roles_guardados[] = $row_assoc['cod_rol'];
                        }
                    }

                    // 3. Renderizar la lista de checkboxes
                    while ($rol = $bd->obtener_fila($query_roles, 0)) {
                        $checked = in_array($rol['codigo'], $roles_guardados) ? 'checked="checked"' : '';
                        echo '<div style="padding: 3px 0;">';
                        echo '  <label style="cursor: pointer; font-size: 13px;">';
                        echo '    <input type="checkbox" name="roles_destino[]" value="'.$rol['codigo'].'" '.$checked.' style="width: auto; margin-right: 6px;" /> ';
                        echo $rol['descripcion'];
                        echo '  </label>';
                        echo '</div>';
                    }
                ?>
                </div>
                <span class="texto" style="font-size: 11px; color: #5a6472;">Selecciona uno o varios roles para este calendario.</span>
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