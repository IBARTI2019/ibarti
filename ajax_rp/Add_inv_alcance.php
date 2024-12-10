<?php
	include_once "../funciones/funciones.php";
	require "../autentificacion/aut_config.inc.php";
	require "../".class_bd;
	require "../".Leng;
	$bd = new DataBase();
	session_start();

	$linea      = $_POST['linea'];
	$sub_linea  = $_POST['sub_linea'];
	$cliente	= $_POST['cliente'];
	$ubicacion	= $_POST['ubicacion'];
	$restri	    = $_SESSION['r_cliente'];
	$allFechas	= $_POST['allFechas'];
	
	if($allFechas == false){
		$fecha_D    = conversion($_POST['fecha_desde']);
		$fecha_H    = conversion($_POST['fecha_hasta']);
	}

	$where = "  WHERE ajuste_alcance.codigo = ajuste_alcance_reng.cod_ajuste 
					AND ajuste_alcance.anulado = 'F'
					AND ajuste_alcance.cod_ubicacion = clientes_ubicacion.codigo 
					AND clientes_ubicacion.cod_cliente = clientes.codigo 
					AND ajuste_alcance_reng.cod_producto = productos.item 
					AND productos.cod_linea = prod_lineas.codigo 
					AND productos.cod_sub_linea = prod_sub_lineas.codigo 
					AND ajuste_alcance_reng.cod_anulado = 0
			     ";

	if($allFechas == false){
		$where .= " AND DATE_FORMAT(ajuste_alcance.fecha, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\" ";
	}

	if($linea != "TODOS"){
		$where .= " AND prod_lineas.codigo = '$linea' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($sub_linea != "TODOS"){
		$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
	}
	
	if($cliente != "TODOS" && $cliente != ""){
		$where  .= " AND  clientes.codigo  = '$cliente' ";
	}

	if($ubicacion != "TODOS" && $ubicacion != ""){
		$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}

 $sql = " SELECT
 			clientes.nombre cliente,
			ajuste_alcance.cod_ubicacion,
			clientes_ubicacion.descripcion ubicacion,
			prod_lineas.descripcion AS linea,
			productos.cod_sub_linea,
			prod_sub_lineas.descripcion AS sub_linea,
			SUM(ajuste_alcance_reng.cantidad) cantidad,
			( SELECT clientes_ub_alcance.cantidad FROM clientes_ub_alcance WHERE clientes_ub_alcance.cod_cl_ubicacion = ajuste_alcance.cod_ubicacion AND clientes_ub_alcance.cod_sub_linea = productos.cod_sub_linea LIMIT 1 ) alcance 
		FROM
			ajuste_alcance,
			ajuste_alcance_reng,
			prod_lineas,
			prod_sub_lineas,
			clientes,
			clientes_ubicacion,
			productos
        $where
		GROUP BY ajuste_alcance.cod_ubicacion, productos.cod_sub_linea
		ORDER BY
			ajuste_alcance.fecha; ";

// echo $sql;

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
	echo '<tr class="'.$fondo.'" onclick="Ver_Detalle(\''.$datos['cliente'].'\',\''.$datos['ubicacion'].'\',\''.$datos['sub_linea'].'\',\''.$datos['cod_ubicacion'].'\',\''.$datos["cod_sub_linea"].'\')">
			<td class="texto">'.$datos["cliente"].'</td>
			<td class="texto">'.$datos["ubicacion"].'</td>
			<td class="texto">'.$datos["linea"].'</td>
			<td class="texto">'.$datos["sub_linea"].'</td>
			<td class="texto">'.$datos["cantidad"].'</td>
			<td class="texto">'.$datos["alcance"].'</td></tr>';
	};
	?>
