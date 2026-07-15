<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();
session_start();
$linea      = $_POST['linea'];
$sub_linea  = $_POST['sub_linea'];
$producto   = $_POST['producto'];
$trabajador = $_POST['trabajador'];

// Filtros dinámicos basados únicamente en los parámetros recibidos
$where = " WHERE 1 = 1 ";

if($linea != "TODOS"){
    $where .= " AND prod_lineas.codigo = '$linea' ";
}

if($sub_linea != "TODOS"){
    $where .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
}

if($producto != "TODOS"){
    $where .= " AND productos.item  = '$producto' ";
}

if($trabajador != NULL && $trabajador != ""){
    $where .= " AND v_ficha.cod_ficha = '$trabajador' ";
}

$sql = " SELECT 
            IFNULL(v_ficha.cod_ficha, 'ASIGNADO A UBICACION') AS cod_ficha,
            IFNULL(v_ficha.cedula, '-') AS cedula, 
            IFNULL(v_ficha.nombres, CONCAT('STOCK: ', clientes_ubicacion.descripcion)) AS trabajador,
            clientes.nombre AS cliente,
            clientes_ubicacion.descripcion AS ubicacion,
            prod_lineas.descripcion AS linea,
            prod_sub_lineas.descripcion AS sub_linea, 
            productos.descripcion AS producto,
            productos.item AS serial,
            SUM(IF(prod_asignacion.tipo = 'ASIGNACION', prod_asignacion_det.cantidad, -prod_asignacion_det.cantidad)) AS balance,
            -- Subquerie para obtener los EANs válidos de este producto para este custodio/ubicación
            (
                SELECT GROUP_CONCAT(sub_eans.cod_ean SEPARATOR ',')
                FROM (
                    -- Aquí calculamos el balance neto de cada EAN individualmente
                    SELECT 
                        pae_int.cod_ean, 
                        pae_int.cod_producto,
                        pa_int.cod_ubicacion,
                        pa_int.cod_ficha,
                        SUM(CASE WHEN pa_int.tipo = 'ASIGNACION' THEN 1 ELSE -1 END) AS balance_ean
                    FROM prod_asignacion_eans pae_int
                    INNER JOIN prod_asignacion pa_int ON pae_int.cod_asignacion = pa_int.codigo
                    GROUP BY 
                        pae_int.cod_ean, 
                        pae_int.cod_producto,
                        pa_int.cod_ubicacion,
                        pa_int.cod_ficha
                ) AS sub_eans
                WHERE sub_eans.cod_producto = productos.item 
                AND sub_eans.cod_ubicacion = prod_asignacion.cod_ubicacion
                AND (
                    (prod_asignacion.cod_ficha IS NULL AND (sub_eans.cod_ficha IS NULL OR sub_eans.cod_ficha = ''))
                    OR 
                    (prod_asignacion.cod_ficha = sub_eans.cod_ficha)
                )
                AND sub_eans.balance_ean > 0 
            ) AS eans_acumulados
        FROM prod_asignacion
        INNER JOIN prod_asignacion_det ON prod_asignacion.codigo = prod_asignacion_det.cod_asignacion
        INNER JOIN productos           ON prod_asignacion_det.cod_producto = productos.item
        INNER JOIN prod_lineas         ON productos.cod_linea = prod_lineas.codigo
        INNER JOIN prod_sub_lineas     ON productos.cod_sub_linea = prod_sub_lineas.codigo
        INNER JOIN clientes_ubicacion  ON prod_asignacion.cod_ubicacion = clientes_ubicacion.codigo
        INNER JOIN clientes            ON clientes_ubicacion.cod_cliente = clientes.codigo
        LEFT JOIN v_ficha              ON prod_asignacion.cod_ficha = v_ficha.cod_ficha
        $where
        GROUP BY 
            IFNULL(v_ficha.cod_ficha, 'ASIGNADO A UBICACION'),
            IFNULL(v_ficha.cedula, '-'),
            IFNULL(v_ficha.nombres, CONCAT('STOCK: ', clientes_ubicacion.descripcion)),
            clientes.nombre,
            clientes_ubicacion.descripcion,
            prod_lineas.descripcion, 
            prod_sub_lineas.descripcion, 
            productos.descripcion, 
            productos.item,
            prod_asignacion.cod_ubicacion,
            prod_asignacion.cod_ficha
        HAVING balance > 0
        ORDER BY trabajador ASC, productos.descripcion ASC ";

?>

<table width="100%" border="0" align="center">
    <tr class="fondo00">
        <th width="15%" class="etiqueta"><?php echo $leng['ficha']?></th>
        <th width="10%" class="etiqueta"><?php echo $leng['ci']?></th>
        <th width="10%" class="etiqueta"><?php echo $leng['cliente']?></th>
        <th width="25%" class="etiqueta">Custodio / Destino</th>
        <th width="15%" class="etiqueta">Linea</th>
        <th width="15%" class="etiqueta">Sub Linea</th>
        <th width="15%" class="etiqueta">Producto </th>
        <th width="10%" class="etiqueta">EANs </th>
        <th width="5%" class="etiqueta">Stock</th>
    </tr>
    <?php
    $valor = 0;
    $query = $bd->consultar($sql);

    while ($datos=$bd->obtener_fila($query,0)){
        
        // Formateamos los EANs agrupados de a dos directamente desde el string de GROUP_CONCAT
        $eans_html = "";
        if (!empty($datos['eans_acumulados'])) {
            $array_eans = explode(',', $datos['eans_acumulados']);
            $contador_eans = 0;
            foreach ($array_eans as $ean) {
                $contador_eans++;
                $eans_html .= $ean;
                if ($contador_eans % 2 == 0) {
                    $eans_html .= "<br />";
                } else {
                    $eans_html .= " &nbsp; ";
                }
            }
            $eans_html = rtrim($eans_html, " &nbsp; ");
        } else {
            $eans_html = "-";
        }

        if ($valor == 0){
            $fondo = 'fondo01';
            $valor = 1;
        }else{
            $fondo = 'fondo02';
            $valor = 0;
        }
        
        echo '<tr class="'.$fondo.'">
                <td class="texto">'.$datos["cod_ficha"].'</td>
                <td class="texto">'.$datos["cedula"].'</td>
                <td class="texto">'.$datos["cliente"].'</td>
                <td class="texto">'.$datos["trabajador"].'</td>
                <td class="texto">'.longitud($datos["linea"]).'</td>
                <td class="texto">'.longitud($datos["sub_linea"]).'</td>
                <td class="texto">'.$datos["producto"].' ('.$datos["serial"].')</td>
                <td class="texto" style="white-space: nowrap; line-height: 1.4;">'.$eans_html.'</td>
                <td class="texto"><b>'.$datos["balance"].'</b></td>
              </tr>';
    };?>
</table>