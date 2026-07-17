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
$tipo       = $_POST['tipo'];
$trabajador = $_POST['trabajador'];
$cliente	= $_POST['cliente'];
$ubicacion	= $_POST['ubicacion'];
$restri	    = $_SESSION['r_cliente'];
$usuario    = $_SESSION['usuario_cod'];
$fecha_D    = conversion($_POST['fecha_desde']);
$fecha_H    = conversion($_POST['fecha_hasta']);
$almacen    = $_POST['almacen'];


	$where = "  WHERE DATE_FORMAT(prod_asignacion.fecha, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\" ";

	if($restri  == "T"){
		$where  .= " AND prod_asignacion.cod_ubicacion IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		usuario_clientes.cod_usuario = '$usuario') ";
	}

	if($linea != "TODOS"){
		$where .= " AND prod_lineas.codigo = '$linea' ";
	}

	if($sub_linea != "TODOS"){
		$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
	}
	if($producto != "TODOS"){
		$where  .= " AND productos.item  = '$producto' ";
	}

	if($tipo != "TODOS" && $tipo != ""){
		$where  .= " AND  prod_asignacion.tipo  = '$tipo' ";
	}

	if($trabajador != "TODOS" && $trabajador != NULL && $trabajador != ""){
		$where  .= " AND prod_asignacion.cod_ficha = '$trabajador' ";
	}

	if($cliente != "TODOS" && $cliente != ""){
		$where  .= " AND  clientes.codigo  = '$cliente' ";
	}

	if($ubicacion != "TODOS" && $ubicacion != ""){
		$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}
	if($almacen != "TODOS" && $almacen != ""){
		$where  .= " AND prod_asignacion_det.cod_almacen = '$almacen' ";
	}

 $sql = " SELECT prod_asignacion.codigo, prod_asignacion.fecha as fec_asignacion, 
                 IFNULL(v_ficha.cod_ficha, 'N/A') as cod_ficha,
                 IFNULL(v_ficha.cedula, 'N/A') as cedula, 
                 IFNULL(v_ficha.nombres, 'SIN FICHA (UBICACION)') AS trabajador,
                 prod_asignacion.descripcion, prod_lineas.descripcion AS linea,
                 prod_sub_lineas.descripcion AS sub_linea, productos.descripcion AS producto,
                 prod_asignacion_det.cantidad,clientes.nombre cliente, clientes_ubicacion.descripcion ubicacion,
				 prod_asignacion_det.cod_almacen,
				 almacenes.descripcion almacen,
				 prod_asignacion.tipo,
				 (SELECT GROUP_CONCAT(cod_ean SEPARATOR ', ') FROM prod_asignacion_eans WHERE cod_asignacion = prod_asignacion.codigo AND cod_producto = prod_asignacion_det.cod_producto) AS eans
            FROM prod_asignacion 
            INNER JOIN prod_asignacion_det ON prod_asignacion.codigo = prod_asignacion_det.cod_asignacion
            INNER JOIN productos ON prod_asignacion_det.cod_producto = productos.item
            INNER JOIN prod_lineas ON productos.cod_linea = prod_lineas.codigo
            INNER JOIN prod_sub_lineas ON productos.cod_sub_linea = prod_sub_lineas.codigo
            INNER JOIN clientes_ubicacion ON prod_asignacion.cod_ubicacion = clientes_ubicacion.codigo
            INNER JOIN clientes ON clientes_ubicacion.cod_cliente = clientes.codigo
            INNER JOIN almacenes ON prod_asignacion_det.cod_almacen = almacenes.codigo
            LEFT JOIN v_ficha ON v_ficha.cod_ficha = prod_asignacion.cod_ficha
          $where
ORDER BY 2 DESC, prod_asignacion.codigo DESC ";
?>

<table width="100%" border="0" align="center">
		<tr class="fondo00">
  			<th width="8%" class="etiqueta">Codigo</th>
            <th width="8%" class="etiqueta">Fecha</th>
			<th width="8%" class="etiqueta">Tipo</th>
            <th width="10%" class="etiqueta"><?php echo $leng['cliente']?></th>
            <th width="10%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
			<th width="8%" class="etiqueta">Almacén</th>
            <th width="8%" class="etiqueta"><?php echo $leng['ficha']?></th>
            <th width="15%" class="etiqueta">Sub Linea</th>
            <th width="10%" class="etiqueta">Producto </th>
            <th width="10%" class="etiqueta">EAN / Serial</th>
            <th width="5%" class="etiqueta">Cantidad</th>
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
				<td class="texto">'.$datos["codigo"].'</td>
				<td class="texto">'.$datos["fec_asignacion"].'</td>
				<td class="texto">'.$datos["tipo"].'</td>
				<td class="texto">'.$datos["cliente"].'</td>
				<td class="texto">'.$datos["ubicacion"].'</td>
				<td class="texto">'.$datos["almacen"].'</td>
				<td class="texto">'.$datos["cod_ficha"].'</td>
				<td class="texto">'.longitud($datos["sub_linea"]).'</td>
				<td class="texto">'.$datos["producto"].'</td>
				<td class="texto">'.$datos["eans"].'</td>
				<td class="texto">'.$datos["cantidad"].'</td></tr>';
        };?>
    </table>
