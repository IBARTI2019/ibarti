<?php
// header('Content-Type: application/json; charset=utf-8');  
define("SPECIALCONSTANT", true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();

$region    = $_POST['region'];
$estado    = $_POST['estado'];
$cliente    = $_POST['cliente'];
$ubicacion    = $_POST['ubicacion'];
//$cargos  = $_POST['cargos'];

$fecha_D   = conversion($_POST['fecha_desde']);
//$car = MatrizListar($cargos);
//$car2 = MatrizListar2($cargos);
$result = array();

$WHERE = " ";
/*$WHEREFA = " WHERE v_ficha_activo_det.cod_cargo IN ($car)
AND v_ficha_activo_det.cod_region = regiones.codigo
AND  v_ficha_activo_det.cod_estado = estados.codigo
AND  v_ficha_activo_det.cod_cliente = clientes_ubicacion.cod_cliente
AND  v_ficha_activo_det.cod_ubicacion = clientes_ubicacion.codigo ";*/

$WHEREFA = " WHERE v_ficha_activo_det.cod_region = regiones.codigo
AND  v_ficha_activo_det.cod_estado = estados.codigo
AND  v_ficha_activo_det.cod_cliente = clientes_ubicacion.cod_cliente
AND  v_ficha_activo_det.cod_ubicacion = clientes_ubicacion.codigo ";

if($region != 'TODOS'){
	$WHERE .= " AND clientes_ubicacion.cod_region = '$region' "; 
	//$WHEREFA .= " AND  v_ficha_activo_det.cod_region = '$region' ";
}

if($estado != 'TODOS'){
	$WHERE .= " AND clientes_ubicacion.cod_estado = '$estado' "; 
	//$WHEREFA .= " AND  v_ficha_activo_det.cod_estado = '$estado' ";
}

if($cliente != 'TODOS'){
	$WHERE .= " AND clientes_ubicacion.cod_cliente = '$cliente' "; 
	$WHEREFA .= " AND  v_ficha_activo_det.cod_cliente = '$cliente' ";
}

if($ubicacion != 'TODOS'){
	$WHERE .= " AND clientes_ubicacion.codigo = '$ubicacion' "; 
	$WHEREFA .= " AND  v_ficha_activo_det.cod_ubicacion = '$ubicacion' ";
}

$sql = "SELECT v_ficha_activo_det.cantidad ,regiones.codigo cod_region,regiones.descripcion region, 
estados.codigo cod_estado,estados.descripcion estado,v_ficha_activo_det.cod_cliente,v_ficha_activo_det.cod_ubicacion
FROM v_ficha_activo_det,estados,regiones,clientes_ubicacion
$WHEREFA";

$query = $bd->consultar($sql);
while($rows=$bd->obtener_name($query)){
	$result['trab_activos'][] = $rows;
}

/*$sql2 = "SELECT regiones.codigo cod_region,regiones.descripcion region, estados.codigo cod_estado,
estados.descripcion estado,clientes_ubicacion.cod_cliente,clientes.abrev cliente,clientes_ubicacion.codigo cod_ubicacion,
clientes_ubicacion.descripcion ubicacion, SUM(turno.trab_cubrir) cantidad
FROM planif_clientes_trab_det,turno,clientes_ubicacion,ficha,regiones,estados,clientes
WHERE planif_clientes_trab_det.cod_turno = turno.codigo
AND planif_clientes_trab_det.cod_ficha = ficha.cod_ficha
AND ficha.cod_cargo IN ($car)
AND planif_clientes_trab_det.fecha = '$fecha_D' 
AND planif_clientes_trab_det.cod_ubicacion = clientes_ubicacion.codigo
AND clientes_ubicacion.cod_region = regiones.codigo
AND clientes_ubicacion.cod_estado = estados.codigo
AND clientes_ubicacion.cod_cliente = clientes.codigo
AND turno.factor = 'dis'
$WHERE
GROUP BY 1,3,5,7";*/

$sql2 = "SELECT regiones.codigo cod_region,regiones.descripcion region, estados.codigo cod_estado,
estados.descripcion estado,clientes_ubicacion.cod_cliente,clientes.abrev cliente,clientes_ubicacion.codigo cod_ubicacion,
clientes_ubicacion.descripcion ubicacion, SUM(turno.trab_cubrir) cantidad
FROM planif_clientes_trab_det,turno,clientes_ubicacion,ficha,regiones,estados,clientes
WHERE planif_clientes_trab_det.cod_turno = turno.codigo
AND planif_clientes_trab_det.cod_ficha = ficha.cod_ficha
AND planif_clientes_trab_det.fecha = '$fecha_D' 
AND planif_clientes_trab_det.cod_ubicacion = clientes_ubicacion.codigo
AND clientes_ubicacion.cod_region = regiones.codigo
AND clientes_ubicacion.cod_estado = estados.codigo
AND clientes_ubicacion.cod_cliente = clientes.codigo
AND turno.factor = 'dis'
$WHERE
GROUP BY 5,7";
$query2 = $bd->consultar($sql2);
while($rows=$bd->obtener_name($query2)){
	$result['excepcion'][] = $rows;
}

$sql = "SELECT regiones.codigo cod_region,regiones.descripcion region,
estados.codigo cod_estado,estados.descripcion estado,
a.cod_cliente,clientes.abrev cliente,a.cod_ubicacion,clientes_ubicacion.descripcion ubicacion,
SUM(a.cantidad) cantidad,SUM(a.cantidad*t.trab_cubrir) trab_neces
FROM
clientes_contratacion_ap AS a
INNER JOIN clientes ON a.cod_cliente = clientes.codigo
INNER JOIN clientes_ubicacion ON a.cod_ubicacion = clientes_ubicacion.codigo
INNER JOIN regiones ON clientes_ubicacion.cod_region = regiones.codigo
INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
INNER JOIN turno t ON a.cod_turno = t.codigo
INNER JOIN horarios AS h ON t.cod_horario = h.codigo
INNER JOIN dias_habiles ON t.cod_dia_habil = dias_habiles.codigo
INNER JOIN dias_habiles_det ON dias_habiles_det.cod_dias_habiles = dias_habiles.codigo
INNER JOIN dias_tipo ON (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND Dia_semana(a.fecha)= dias_tipo.descripcion) 
OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND dias_tipo.tipo = 'D')
OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND DATE_FORMAT(a.fecha,'%d') = dias_tipo.descripcion)
WHERE a.fecha = '$fecha_D'
$WHERE
GROUP BY 5,7";

$query = $bd->consultar($sql);
while($rows=$bd->obtener_name($query)){
	$result['contrato'][] = $rows;
}

print_r(json_encode($result));
return json_encode($result);
?>