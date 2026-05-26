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

    // Consulta SQL optimizada incluyendo la subconsulta para traer los EANs/Seriales específicos
    $sql = " SELECT 
                IFNULL(v_ficha.cod_ficha, 'UBICACION') AS cod_ficha, -- [0]
                IFNULL(v_ficha.cedula, '-') AS cedula,                 -- [1]
                IFNULL(v_ficha.ap_nombre, CONCAT('STOCK: ', clientes_ubicacion.descripcion)) AS trabajador, -- [2]
                prod_lineas.descripcion AS linea,                      -- [3]
                prod_sub_lineas.descripcion AS sub_linea,              -- [4]
                productos.descripcion AS producto,                     -- [5]
                productos.item AS producto_item,                       -- [6]
                SUM(IF(prod_asignacion.tipo = 'ASIGNACION', prod_asignacion_det.cantidad, -prod_asignacion_det.cantidad)) AS balance, -- [7]
                (SELECT GROUP_CONCAT(pae.cod_ean SEPARATOR ', ') 
                 FROM prod_asignacion_eans AS pae 
                 WHERE pae.cod_asignacion = prod_asignacion.codigo 
                   AND pae.cod_producto = prod_asignacion_det.cod_producto) AS eans -- [8]
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
            v_ficha.cod_ficha, 
            v_ficha.cedula, 
            v_ficha.ap_nombre, 
            clientes_ubicacion.descripcion,
            prod_lineas.descripcion, 
            prod_sub_lineas.descripcion, 
            productos.descripcion, 
            productos.item
          HAVING balance > 0
          ORDER BY trabajador ASC, productos.descripcion ASC ";

    if($reporte == 'excel'){
        echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

        $query01  = $bd->consultar($sql);
        echo "<table border=1>";
        echo "<tr><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th>
			   <th> Linea </th><th> Sub Linea </th><th> Producto </th><th> Serial </th><th> EANs </th><th> Stock en Custodia </th></tr>";
		
		while ($row01 = $bd->obtener_num($query01)){
            $cod_ficha = $row01[0];
            $ficha_cond = ($cod_ficha == 'ASIGNADO A UBICACION' || $cod_ficha == 'UBICACION' || $cod_ficha == '') ? "AND (pa.cod_ficha = '' OR pa.cod_ficha IS NULL)" : "AND pa.cod_ficha = '$cod_ficha'";

            $sql_eans = "SELECT sub.cod_ean FROM (
                  SELECT pae.cod_ean, SUM(CASE WHEN pa.tipo = 'ASIGNACION' THEN 1 ELSE -1 END) as balance
                  FROM prod_asignacion_eans pae
                  JOIN prod_asignacion pa ON pae.cod_asignacion = pa.codigo
                  WHERE pae.cod_producto = '$serial' $ficha_cond
                  GROUP BY pae.cod_ean ) as sub WHERE sub.balance > 0";
            $q_eans = $bd->consultar($sql_eans);
            $eans_arr = [];
            while($re = $bd->obtener_fila($q_eans, 0)){
                $eans_arr[] = $re['cod_ean'];
            }
            $eans_str = implode(', ', $eans_arr);

		 echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[2]."</td>
					<td>".$row01[3]."</td><td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td>
					<td>".$eans_str."</td><td>".$row01[7]."</td></tr>";
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
            <th width='20%'>".$leng['trabajador']."</th>
            <th width='15%'>Linea</th>
            <th width='25%'>Producto</th>
            <th width='20%'>EANs</th>
            <th width='10%'  style='text-align:center;'>Stock Custodia</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
            $cod_ficha = $row[0];
            $ficha_cond = ($cod_ficha == 'ASIGNADO A UBICACION' || $cod_ficha == 'UBICACION' || $cod_ficha == '') ? "AND (pa.cod_ficha = '' OR pa.cod_ficha IS NULL)" : "AND pa.cod_ficha = '$cod_ficha'";

            $sql_eans = "SELECT sub.cod_ean FROM (
                  SELECT pae.cod_ean, SUM(CASE WHEN pa.tipo = 'ASIGNACION' THEN 1 ELSE -1 END) as balance
                  FROM prod_asignacion_eans pae
                  JOIN prod_asignacion pa ON pae.cod_asignacion = pa.codigo
                  WHERE pae.cod_producto = '$serial' $ficha_cond
                  GROUP BY pae.cod_ean ) as sub WHERE sub.balance > 0";
            $q_eans = $bd->consultar($sql_eans);
            $eans_arr = [];
            while($re = $bd->obtener_fila($q_eans, 0)){
                $eans_arr[] = $re['cod_ean'];
            }
            $eans_str = implode(', ', $eans_arr);

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