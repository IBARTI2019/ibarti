<?php
define("SPECIALCONSTANT", true);
require  "../autentificacion/aut_config.inc.php";
require_once "../".Funcion;
require_once  "../".class_bdI;
$bd = new Database;

$ficha = $_POST['cod_ficha'];
$result =array();
try {
$sql = "SELECT  CONCAT(prod_sub_lineas.descripcion,' (',prod_sub_lineas.codigo,') ') sub_linea,tallas.descripcion talla,
 ficha_dotacion.cantidad,
IFNULL((SELECT CONCAT(MAX(prod_dotacion.fec_dotacion),'  (',prod_dotacion_det.cantidad,')') FROM prod_dotacion, prod_dotacion_det
WHERE prod_dotacion.codigo = prod_dotacion_det.cod_dotacion
AND prod_dotacion_det.cod_sub_linea = ficha_dotacion.cod_sub_linea
AND prod_dotacion.cod_ficha = ficha_dotacion.cod_ficha) ,'SIN DOTACION') ult_dotacion
,ficha.cod_cliente,ficha.cod_ubicacion,
	(
		SELECT
			clientes_ub_uniforme.cod_cl_ubicacion
		FROM				
			clientes_ub_uniforme
		WHERE prod_sub_lineas.codigo = clientes_ub_uniforme.cod_sub_linea
		AND ficha.cod_ubicacion = clientes_ub_uniforme.cod_cl_ubicacion
		AND ficha.cod_cargo = clientes_ub_uniforme.cod_cargo
	) aplica
FROM ficha_dotacion LEFT JOIN
productos ON 
 ficha_dotacion.cod_sub_linea = productos.cod_sub_linea,prod_sub_lineas,tallas,ficha
WHERE
ficha_dotacion.cod_ficha = '$ficha'
AND ficha_dotacion.cod_sub_linea = prod_sub_lineas.codigo
AND ficha_dotacion.cod_talla = tallas.codigo
AND ficha_dotacion.cod_ficha = ficha.cod_ficha
GROUP BY ficha_dotacion.cod_sub_linea";
$query         = $bd->consultar($sql);
while ($datos= $bd->obtener_fila($query)) {
	$result[] = $datos;
}
}catch (Exception $e) {
 $error =  $e->getMessage();
 $result['error'] = true;
 $result['mensaje'] = $error;
 $bd->log_error("Aplicacion", "dotacion_ficha.php",  "$us", "$error", "$sql");
}	
echo json_encode($result);
?>