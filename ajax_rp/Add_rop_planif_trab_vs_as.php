<?php
define("SPECIALCONSTANT", true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();
$region    = $_POST['region'];
$estado  = $_POST['estado'];
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$horario    = $_POST['horario'];
$fecha_D    = conversion($_POST['fecha_desde']);
$fecha_H    = conversion($_POST['fecha_hasta']);
$result = array();

$WHERE = " WHERE a.fecha BETWEEN \"$fecha_D\" AND \"$fecha_H\" AND h2.codigo <> '9999' ";

$WHERE_21 =" WHERE v_as_planif_horario.fec_diaria BETWEEN \"$fecha_D\" AND \"$fecha_H\"";

$WHERE_22 = " AND v_as_planif_horario.cod_horario = horarios.codigo
AND v_as_planif_horario.cod_ubicacion   = clientes_ubicacion.codigo
AND v_as_planif_horario.cod_cliente = clientes.codigo
AND clientes_ubicacion.cod_estado = estados.codigo 
AND v_as_planif_horario.cod_cliente <> control.oesvica ";

if( $region != "TODOS"){
	$WHERE .= " AND cu.cod_region = '$region' ";
	$WHERE_21 .= " AND clientes_ubicacion.cod_region = '$region' ";
}

if( $estado != "TODOS"){
	$WHERE .= " AND cu.cod_estado = '$estado' ";
	$WHERE_21 .= " AND clientes_ubicacion.cod_estado = '$estado' ";
}

if( $cliente != "TODOS"){
	$WHERE .= " AND a.cod_cliente = '$cliente' ";
	$WHERE_21 .= " AND v_as_planif_horario.cod_cliente = '$cliente' ";
}

if( $ubicacion != "TODOS"){
	$WHERE .= " AND a.cod_ubicacion = '$ubicacion' ";
	$WHERE_21 .= " AND v_as_planif_horario.cod_ubicacion = '$ubicacion' ";
}

if( $horario != "TODOS"){
	$WHERE .= " AND h.codigo = '$horario' ";
	$WHERE_21 .= " AND v_as_planif_horario.cod_horario = '$horario' ";
}

$sql = "SELECT v_as_planif_horario.fec_diaria fecha,v_as_planif_horario.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion,v_as_planif_horario.cod_horario,horarios.nombre AS horario, estados.descripcion AS estado, v_as_planif_horario.cod_cliente, clientes.nombre cliente, v_as_planif_horario.valor
FROM v_as_planif_horario,  clientes_ubicacion , clientes , estados, horarios, control
$WHERE_21
$WHERE_22 
ORDER BY 1,2,4 ASC";

$qry  = $bd->consultar($sql);
while($rows=$bd->obtener_name($qry)){
	$result['asistencia'][] = $rows;
}

$sql = "SELECT 
a.fecha,
a.cod_ubicacion,
cu.descripcion ubicacion,
a.cod_cliente,
cl.abrev cliente,
h2.codigo cod_horario,
h2.nombre horario,
estados.descripcion estado,
COUNT(h2.codigo) cantidad
FROM
planif_clientes_trab_det AS a
INNER JOIN clientes cl ON a.cod_cliente = cl.codigo
INNER JOIN clientes_ubicacion cu ON a.cod_ubicacion = cu.codigo
INNER JOIN estados ON cu.cod_estado = estados.codigo
INNER JOIN turno t ON a.cod_turno = t.codigo
LEFT JOIN horarios h ON t.cod_horario = h.codigo
LEFT JOIN conceptos ON h.cod_concepto = conceptos.codigo
INNER JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
$WHERE
GROUP BY a.cod_ubicacion,h2.codigo,a.fecha";

$query = $bd->consultar($sql);
while($rows=$bd->obtener_name($query)){
	$result['servicio'][] = $rows;
}

print_r(json_encode($result));
return json_encode($result);
?>
