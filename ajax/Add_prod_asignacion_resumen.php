<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$tipo       = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$ubicacion  = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : '';
$trabajador = isset($_POST['trabajador']) ? $_POST['trabajador'] : '';

echo "<fieldset class='fieldset'><legend>Resumen de Inventario (Custodia y Alcance)</legend>";

if ($trabajador == "" && $ubicacion == "") {
    echo "<div style='padding: 10px;'>Seleccione un Trabajador o Ubicación para ver el resumen pertinente.</div>";
} else {
    // 1. Mostrar Alcance (solo para Asignaciones a Ubicacion sin ficha)
    if ($tipo == "ASIGNACION" && $ubicacion != "" && $trabajador == "") {
        $sql = "SELECT psl.descripcion, cua.cantidad, cua.dias, cua.vencimiento 
                FROM clientes_ub_alcance cua
                JOIN prod_sub_lineas psl ON cua.cod_sub_linea = psl.codigo
                WHERE cua.cod_cl_ubicacion = '$ubicacion'";
        $query = $bd->consultar($sql);
        
        if ($bd->num_fila($query) > 0) {
            echo "<table width='100%' class='fondo00'>";
            echo "<tr class='fondo01'><th style='text-align: center;'>Sub Línea (Alcance Configurado)</th><th style='text-align: center;'>Cantidad Permitida</th><th style='text-align: center;'>Días Reposición</th><th style='text-align: center;'>Vencimiento</th></tr>";
            $i = 0;
            while ($row = $bd->obtener_fila($query, 0)) {
                $fondo = ($i % 2 == 0) ? "fondo02" : "fondo01";
                echo "<tr class='$fondo'>";
                echo "<td class='texto' style='text-align: center;'>".$row['descripcion']."</td>";
                echo "<td class='texto' style='text-align: center;'>".$row['cantidad']."</td>";
                echo "<td class='texto' style='text-align: center;'>".$row['dias']."</td>";
                echo "<td class='texto' style='text-align: center;'>".$row['vencimiento']."</td>";
                echo "</tr>";
                $i++;
            }
            echo "</table><br>";
        } else {
            echo "<div style='padding: 10px;'>No hay alcance de productos configurado para esta ubicación.</div>";
        }
    }

    // 2. Mostrar Stock Asignado (siempre, tanto para Asignacion como Devolucion)
    $ficha_cond = ($trabajador == "") ? "AND (pa.cod_ficha = '' OR pa.cod_ficha IS NULL) AND pa.cod_ubicacion = '$ubicacion'" : "AND pa.cod_ficha = '$trabajador'";
    
    $sql = "SELECT p.descripcion, p.item, 
            SUM(IF(pa.tipo='ASIGNACION', pad.cantidad, -pad.cantidad)) as asignado
            FROM prod_asignacion pa
            JOIN prod_asignacion_det pad ON pa.codigo = pad.cod_asignacion
            JOIN productos p ON pad.cod_producto = p.item
            WHERE 1=1 $ficha_cond
            GROUP BY p.descripcion, p.item
            HAVING asignado > 0";
    $query = $bd->consultar($sql);
    
    if ($bd->num_fila($query) > 0) {
        echo "<table width='100%' class='fondo00'>";
        echo "<tr class='fondo01'><th style='text-align: center;'>Producto</th><th style='text-align: center;'>Código</th><th style='text-align: center;'>Cantidad actual en Custodia</th></tr>";
        $i = 0;
        while ($row = $bd->obtener_fila($query, 0)) {
            $fondo = ($i % 2 == 0) ? "fondo02" : "fondo01";
            echo "<tr class='$fondo'>";
            echo "<td class='texto' style='text-align: center;'>".$row['descripcion']."</td>";
            echo "<td class='texto' style='text-align: center;'>".$row['item']."</td>";
            echo "<td class='texto' style='text-align: center;'><b>".$row['asignado']."</b></td>";
            echo "</tr>";
            $i++;
        }
        echo "</table>";
    } else {
        echo "<div style='padding: 10px; color: gray;'>No hay equipo asignado en custodia actualmente para este destino.</div>";
    }
}

echo "</fieldset><br>";
@mysql_close();
?>
