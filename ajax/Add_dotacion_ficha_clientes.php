<?php
define("SPECIALCONSTANT", true);
require  "../autentificacion/aut_config.inc.php";
require_once "../".Funcion;
require_once  "../".class_bdI;
$bd = new Database;

$ficha = $_POST['cod_ficha'];
$result =array();
try {
$sql = "SELECT  CONCAT(productos.descripcion,' (',productos.codigo,') ') sub_linea,
 clientes_ub_alcance.cantidad,
IFNULL((SELECT CONCAT(MAX(prod_dotacion_clientes.fec_us_mod),'  (',prod_dotacion_det_clientes.cantidad,')') FROM prod_dotacion_clientes, prod_dotacion_det_clientes
WHERE prod_dotacion_clientes.codigo = prod_dotacion_det_clientes.cod_dotacion
AND prod_dotacion_det_clientes.cod_producto = clientes_ub_alcance.cod_producto
AND prod_dotacion_clientes.cod_ubicacion = clientes_ub_alcance.cod_cl_ubicacion) ,'SIN DOTACION') ult_dotacion
,clientes_ubicacion.cod_cliente,clientes_ubicacion.codigo
FROM clientes_ub_alcance LEFT JOIN
productos ON 
 clientes_ub_alcance.cod_producto = productos.item,clientes_ubicacion
WHERE
clientes_ub_alcance.cod_cl_ubicacion='$ficha'
AND clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo
GROUP BY clientes_ub_alcance.cod_producto";
$query         = $bd->consultar($sql);
while ($datos= $bd->obtener_fila($query)) {
	$result[] = $datos;
}
}catch (Exception $e) {
 $error =  $e->getMessage();
 $result['error'] = true;
 $result['mensaje'] = $error;
 $bd->log_error("Aplicacion", "dotacion_ficha_clientes.php",  "$us", "$error", "$sql");
}	
echo json_encode($result);
?>