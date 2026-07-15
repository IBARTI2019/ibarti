<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 483;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

$linea      = $_POST['linea'];
$sub_linea  = $_POST['sub_linea'];
$producto   = $_POST['producto'];
$trabajador = $_POST['trabajador'];
$reporte    = $_POST['reporte'];

$archivo    = "rp_inv_stock_asignado_".date('Ymd')."";
$titulo     = "  STOCK ASIGNADO (EN CUSTODIA) \n";

if(isset($reporte)){

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
                IFNULL(v_ficha.cod_ficha, 'UBICACION') AS cod_ficha, -- [0]
                IFNULL(v_ficha.cedula, '-') AS cedula,                 -- [1]
                IFNULL(v_ficha.ap_nombre, clientes_ubicacion.descripcion) AS trabajador, -- [2]
                prod_lineas.descripcion AS linea,                      -- [3]
                prod_sub_lineas.descripcion AS sub_linea,              -- [4]
                productos.descripcion AS producto,                     -- [5]
                productos.item AS producto_item,                       -- [6]
                SUM(IF(prod_asignacion.tipo = 'ASIGNACION', prod_asignacion_det.cantidad, -prod_asignacion_det.cantidad)) AS balance, -- [7]
                -- Subquerie optimizada que calcula el balance de cada EAN antes de concatenar
                (
                    SELECT GROUP_CONCAT(sub_eans.cod_ean SEPARATOR ', ')
                    FROM (
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
                ) AS eans_acumulados, -- [8] (Se corrigió la coma faltante aquí)
                clientes.nombre AS cliente -- [9]
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
                IFNULL(v_ficha.nombres, clientes_ubicacion.descripcion),
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

    if($reporte == 'excel'){
        echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

        $query01  = $bd->consultar($sql);
        echo "<table border=1>";
        echo "<tr><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['cliente']." </th><th> Custodio / Destino </th>
               <th> Linea </th><th> Sub Linea </th><th> Producto </th><th> Serial </th><th> EANs </th><th> Stock en Custodia </th></tr>";
        
        while ($row01 = $bd->obtener_num($query01)){
            // Usamos directamente los EANs precalculados en la posición [8]
            $eans_str = !empty($row01[8]) ? $row01[8] : "-";

            echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[9]."</td><td>".$row01[2]."</td>
                    <td>".$row01[3]."</td><td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td>
                    <td style='mso-number-format:\"@\";'>".$eans_str."</td><td>".$row01[7]."</td></tr>";
        }
         echo "</table>";
    }

    if($reporte == 'pdf'){
        require_once('../'.ConfigDomPdf);
        $dompdf = new DOMPDF();

        $query  = $bd->consultar($sql);

        ob_start();

        require('../'.PlantillaDOM.'/header_ibarti_2.php');
        include('../'.pagDomPdf.'/paginacion_ibarti.php');

        echo "<br><div>
        <table>
        <tbody>
            <tr style='background-color: #4CAF50;'>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='20%'>Custodio / Destino</th>
            <th width='15%'>Linea</th>
            <th width='25%'>Producto</th>
            <th width='20%'>EANs</th>
            <th width='10%'  style='text-align:center;'>Stock Custodia</th>
            </tr>";

        $f=0;
        while ($row = $bd->obtener_num($query)){
            // Usamos directamente los EANs precalculados en la posición [8]
            $eans_str = !empty($row[8]) ? $row[8] : "-";

            $clase_fila = ($f % 2 == 0) ? "" : "class='odd_row'";
            
            echo "<tr $clase_fila>
                 <td width='10%'>".$row[0]."</td>
            <td width='20%'>".$row[2]."</td>
            <td width='15%'>".$row[3]."</td>
            <td width='25%'>".$row[5]." (".$row[6].")</td>
            <td width='20%'>".$eans_str."</td>
            <td width='10%' style='text-align:center;'>".$row[7]."</td>
            </tr>";
            $f++;
        }

        echo "</tbody>
                </table>
        </div>
        </body>
        </html>";

        $dompdf->load_html(ob_get_clean(),'UTF-8');
        $dompdf->render();
        $dompdf->stream($archivo, array('Attachment' => 0));
    }
}
?>