<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = '602';
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$archivo = "reportes/rp_inv_stock_asignado_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " REPORTE DE STOCK ASIGNADO (EN CUSTODIA) ";
?>
<script language="JavaScript" type="text/javascript">
    function Add_filtroX() {
        var producto = document.getElementById("producto").value;
        var linea = document.getElementById("linea").value;
        var sub_linea = document.getElementById("sub_linea").value;
        var trabajador = document.getElementById("stdID").value;

        var parametros = {
            "linea": linea,
            "sub_linea": sub_linea,
            "producto": producto,
            "trabajador": trabajador
        }
        
        $.ajax({
            data: parametros,
            url: 'ajax_rp/Add_inv_stock_asignado.php',
            type: 'post',
            beforeSend: function() {
                $(".listar").html('<img src="imagenes/loading.gif" />');
                document.getElementById("cont_img").innerHTML = '<img src="imagenes/loading.gif" class="imgLink" />';
            },
            success: function(response) {
                $(".listar").html(response);
                document.getElementById("cont_img").innerHTML = '<img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()">';
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }

    // CORRECCIÓN: Función vital para habilitar/deshabilitar el buscador predictivo
    function EstadoFiltro(val) {
        var stdName = document.getElementById("stdName");
        var stdID = document.getElementById("stdID");
        
        if (val == "TODOS") {
            stdName.value = "";
            stdID.value = "";
            stdName.disabled = true;
        } else {
            stdName.disabled = false;
            stdName.focus();
        }
    }
</script>

<div align="center" class="etiqueta_title"><?php echo $titulo; ?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo; ?>" method="post" target="_blank">
    <hr />
    <table width="100%" class="etiqueta">
        <tr>
            <td>Linea: </td>
            <td>
                <select name="linea" id="linea" style="width:120px;" onchange="Add_filtroX()">
                    <option value="TODOS">TODOS</option>
                    <?php
                    $query01 = $bd->consultar($sql_linea);
                    while ($row01 = $bd->obtener_fila($query01, 0)) {
                        echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
                    } ?>
                </select>
            </td>
            <td>Sub Linea:</td>
            <td>
                <select name="sub_linea" id="sub_linea" style="width:120px;" onchange="Add_filtroX()">
                    <option value="TODOS">TODOS</option>
                    <?php
                    $query01 = $bd->consultar($sql_sub_lineas);
                    while ($row01 = $bd->obtener_fila($query01, 0)) {
                        echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
                    } ?>
                </select>
            </td>
            <td>Producto: </td>
            <td>
                <select name="producto" id="producto" style="width:120px;" onchange="Add_filtroX()">
                    <option value="TODOS">TODOS</option>
                    <?php
                    $query01 = $bd->consultar($sql_producto);
                    while ($row01 = $bd->obtener_fila($query01, 0)) {
                        echo '<option value="' . $row01[2] . '">' . $row01[1] . ' (' . $row01[2] . ')</option>';
                    } ?>
                </select>
            </td>
            <td width="4%" id="cont_img">
                <img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()">
            </td>
        </tr>
        <tr>
            <td>Filtro <?php echo $leng['trab'] ?>.:</td>
            <td>
                <select id="paciFiltro" onchange="EstadoFiltro(this.value); Add_filtroX();" style="width:120px">
                    <option value="TODOS"> TODOS</option>
                    <option value="codigo"> Codigo </option>
                    <option value="cedula"> C&eacute;dula </option>
                    <option value="nombre"> Nombre </option>
                </select>
            </td>
            <td><?php echo $leng['trabajador'] ?>:</td>
            <td colspan="2">
                <input id="stdName" type="text" size="35" disabled="disabled" placeholder="Seleccione Tipo de Filtro..." />
                <input type="hidden" name="trabajador" id="stdID" value="" />
            </td>
            <td>&nbsp;
                <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
                <input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
                <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
                <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
                <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /> 
            </td>
        </tr>
    </table>
    <hr />
    <div class="listar">&nbsp;</div>
    <div align="center"><br />
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')" class="readon art-button">
        </span>&nbsp;

        <input type="submit" name="procesar" id="procesar" style="display:none;">
        <input type="hidden" name="reporte" id="reporte" value="">

        <img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0" onclick="{$('#reporte').val('pdf'); $('#form_reportes').submit();}" width="25px" title="imprimir a pdf">
        <img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0" onclick="{$('#reporte').val('excel'); $('#form_reportes').submit();}" width="25px" title="imprimir a excel">
    </div>
</form>

<script type="text/javascript">
    // Carga inicial del reporte al entrar a la pantalla
    $(document).ready(function() {
        Add_filtroX();
    });

    new Autocomplete("stdName", function() {
        this.setValue = function(id) {
            document.getElementById("stdID").value = id;
            Add_filtroX(); // Lanza la búsqueda apenas el usuario selecciona un trabajador de la lista
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return;
        
        var r_cliente = $("#r_cliente").val();
        var usuario = $("#usuario").val();
        var filtroValue = $("#paciFiltro").val();
        
        return "autocompletar/tb/trabajador.php?q=" + this.text.value + "&filtro=" + filtroValue + "&r_cliente=" + r_cliente + "&usuario=" + usuario;
    });
</script>