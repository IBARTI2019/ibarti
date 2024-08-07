<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();
session_start();
$rol        = $_POST['rol'];
$linea      = $_POST['linea'];
$sub_linea  = $_POST['sub_linea'];
$producto   = $_POST['producto'];
$anulado    = $_POST['anulado'];
$trabajador = $_POST['trabajador'];
$cliente	= $_POST['cliente'];
$ubicacion	= $_POST['ubicacion'];
$restri	    = $_SESSION['r_cliente'];
$fecha_D    = conversion($_POST['fecha_desde']);
$fecha_H    = conversion($_POST['fecha_hasta']);
$almacen    = $_POST['almacen'];

	$where = "  WHERE DATE_FORMAT(prod_dotacion.fec_dotacion, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\"
   	              AND prod_dotacion.codigo = prod_dotacion_det.cod_dotacion
   	              AND prod_dotacion.cod_cliente = clientes.codigo
   	              AND prod_dotacion.cod_ubicacion = clientes_ubicacion.codigo
			      AND prod_dotacion_det.cod_producto = productos.item
			      AND productos.cod_linea = prod_lineas.codigo
			      AND productos.cod_talla = tallas.codigo
			      AND productos.cod_sub_linea = prod_sub_lineas.codigo
				  AND v_ficha.cod_ficha = prod_dotacion.cod_ficha 
			      AND ajuste.referencia = prod_dotacion.codigo
				AND ajuste_reng.cod_ajuste = ajuste.codigo
				AND ajuste_reng.cod_almacen = prod_dotacion_det.cod_almacen
				AND ajuste_reng.cod_producto = prod_dotacion_det.cod_producto
				AND (ajuste.cod_tipo = 'DOT' OR ajuste.cod_tipo = 'ANU_DOT') 
				AND ajuste_reng.cod_almacen = almacenes.codigo";

	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($linea != "TODOS"){
		$where .= " AND prod_lineas.codigo = '$linea' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($sub_linea != "TODOS"){
		$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
	}
	if($producto != "TODOS"){
		$where  .= " AND productos.item  = '$producto' ";
	}

	if($anulado != "TODOS"){
		$where  .= " AND  prod_dotacion.anulado  = '$anulado' ";
	}

	if($trabajador != NULL){
		$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
	}

	
	if($cliente != "TODOS" && $cliente != ""){
		$where  .= " AND  clientes.codigo  = '$cliente' ";
	}

	if($ubicacion != "TODOS" && $ubicacion != ""){
		$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}
	if($almacen != "TODOS" && $almacen != ""){
		$where  .= " AND ajuste_reng.cod_almacen = '$almacen' ";
	}

 $sql = " SELECT DISTINCT prod_dotacion.codigo, prod_dotacion.fec_dotacion,
                 v_ficha.rol, v_ficha.cod_ficha,
                 v_ficha.cedula, v_ficha.nombres AS trabajador,
                 prod_dotacion.descripcion, prod_lineas.descripcion AS linea,
                 prod_sub_lineas.descripcion AS sub_linea, CONCAT(productos.descripcion,' (',tallas.descripcion,') ') AS producto,
                 prod_dotacion_det.cantidad,clientes.nombre cliente, clientes_ubicacion.descripcion ubicacion, ajuste_reng.neto,
                 Valores(prod_dotacion.anulado) anulado,
				 ajuste_reng.cod_almacen,
				almacenes.descripcion almacen
            FROM prod_dotacion , prod_dotacion_det , productos , prod_lineas ,
                 prod_sub_lineas, v_ficha,clientes,clientes_ubicacion, ajuste,ajuste_reng,tallas,almacenes
          $where
        GROUP BY prod_dotacion.codigo,ajuste.codigo,prod_dotacion_det.cod_producto
HAVING MAX(ajuste.codigo)
ORDER BY 2 ASC ";

?>

<table width="100%" border="0" align="center">
		<tr class="fondo00">
  			<th width="9%" class="etiqueta">Codigo</th>
            <th width="8%" class="etiqueta">Fecha</th>
            <th width="10%" class="etiqueta"><?php echo $leng['cliente']?></th>
            <th width="10%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
			<!-- <th width="5%" class="etiqueta">Cod. Almacén</th> -->
			<th width="5%" class="etiqueta">Almacén</th>
            <th width="10%" class="etiqueta"><?php echo $leng['ficha']?></th>
            <th width="20%" class="etiqueta"><?php echo $leng['rol']?></th>
            <th width="24%" class="etiqueta">Sub Linea</th>
            <th width="24%" class="etiqueta">Producto </th>
            <th width="5%" class="etiqueta">Cantidad</th>
            <?php echo ($restri=="F")?'<th width="5%" class="etiqueta">Importe</th>':'';?>
             <th width="5%" class="etiqueta">Anulado</th>

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
				<td class="texto">'.$datos["fec_dotacion"].'</td>
				<td class="texto">'.$datos["cliente"].'</td>
				<td class="texto">'.$datos["ubicacion"].'</td>
				<td class="texto">'.$datos["almacen"].'</td>
				<td class="texto">'.$datos["cod_ficha"].'</td>
				<td class="texto">'.longitud($datos["rol"]).'</td>
				<td class="texto">'.longitud($datos["sub_linea"]).'</td>
				<td class="texto">'.$datos["producto"].'</td>
				<td class="texto">'.$datos["cantidad"].'</td>';
			echo ($restri=="F")?'<td class="texto">'.$datos["neto"].'</td>':'';
           echo '<td class="texto">'.$datos["anulado"].'</td></tr>';
        };?>
    </table>
