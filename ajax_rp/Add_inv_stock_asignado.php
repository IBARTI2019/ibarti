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
                SUM(IF(prod_asignacion.tipo = 'ASIGNACION', prod_asignacion_det.cantidad, -prod_asignacion_det.cantidad)) AS balance
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
            v_ficha.nombres, 
            clientes.nombre,
            clientes_ubicacion.descripcion,
            prod_lineas.descripcion, 
            prod_sub_lineas.descripcion, 
            productos.descripcion, 
            productos.item
          HAVING balance > 0
          ORDER BY trabajador ASC, productos.descripcion ASC ";

?>

<table width="100%" border="0" align="center">
        <tr class="fondo00">
            <th width="15%" class="etiqueta"><?php echo $leng['ficha']?></th>
            <th width="10%" class="etiqueta"><?php echo $leng['ci']?></th>
            <th width="25%" class="etiqueta">Custodio / Destino</th>
            <th width="15%" class="etiqueta">Linea</th>
            <th width="15%" class="etiqueta">Sub Linea</th>
            <th width="15%" class="etiqueta">Producto</th>
            <th width="5%" class="etiqueta">Stock En Custodia</th>
    </tr>
    <?php
    $valor = 0;
    $query = $bd->consultar($sql);

        while ($datos=$bd->obtener_fila($query,0)){
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
                    <td class="texto">'.$datos["trabajador"].'</td>
                    <td class="texto">'.longitud($datos["linea"]).'</td>
                    <td class="texto">'.longitud($datos["sub_linea"]).'</td>
                    <td class="texto">'.$datos["producto"].' ('.$datos["serial"].')</td>
                    <td class="texto" align="center"><b>'.$datos["balance"].'</b></td>
                  </tr>';
        };?>
</table>