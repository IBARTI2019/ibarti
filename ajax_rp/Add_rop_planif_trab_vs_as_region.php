<?php
define("SPECIALCONSTANT", true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();
$region    = $_POST['region'];
$horario    = $_POST['horario'];
$fecha_D    = conversion($_POST['fecha_desde']);
$fecha_H    = conversion($_POST['fecha_hasta']);
$result = array();

$WHERE = " WHERE a.fecha BETWEEN \"$fecha_D\" AND \"$fecha_H\" AND h2.codigo <> '9999' ";

$WHERE_21 =" WHERE v_as_planif_horario.fec_diaria BETWEEN \"$fecha_D\" AND \"$fecha_H\"";

$WHERE_22 = " AND v_as_planif_horario.cod_horario = horarios.codigo
AND v_as_planif_horario.cod_cliente = clientes.codigo
AND clientes.cod_region = regiones.codigo 
AND v_as_planif_horario.cod_cliente <> control.oesvica ";

if( $region != "TODOS"){
	$WHERE .= " AND regiones.codigo = '$region' ";
	$WHERE_21 .= " AND regiones.codigo = '$region' ";
}

if( $horario != "TODOS"){
	$WHERE .= " AND h.codigo = '$horario' ";
	$WHERE_21 .= " AND v_as_planif_horario.cod_horario = '$horario' ";
}

if(isset($_POST['r_cliente'])){
	$r_cliente	    = $_POST['r_cliente'];
	$usuario	    = $_POST['usuario'];
	if($r_cliente  == "T"){
		$WHERE  .= " AND a.cod_ubicacion IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		cod_usuario = '$usuario') ";
		$WHERE_21  .= " AND v_as_planif_horario.cod_ubicacion  IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		cod_usuario = '$usuario') ";
	}
}

$sql = "SELECT 
			v_as_planif_horario.fec_diaria fecha,
			regiones.codigo cod_region,
			regiones.descripcion,
			v_as_planif_horario.cod_horario,
			horarios.nombre horario,
			SUM( v_as_planif_horario.valor ) valor 
		FROM v_as_planif_horario,  clientes , regiones, horarios, control
	$WHERE_21
	$WHERE_22 
	GROUP BY cod_region, cod_horario, fecha
	ORDER BY 1,2,4 ASC";

$qry  = $bd->consultar($sql);
while($rows=$bd->obtener_name($qry)){
	$result['asistencia'][] = $rows;
}

$sql = "SELECT 
a.fecha,
cl.cod_region,
regiones.descripcion region,
h2.codigo cod_horario,
h2.nombre horario,
COUNT(h2.codigo) cantidad
FROM
planif_clientes_trab_det AS a
INNER JOIN clientes cl ON a.cod_cliente = cl.codigo
INNER JOIN regiones ON cl.cod_region = regiones.codigo
INNER JOIN turno t ON a.cod_turno = t.codigo
LEFT JOIN horarios h ON t.cod_horario = h.codigo
LEFT JOIN conceptos ON h.cod_concepto = conceptos.codigo
INNER JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
$WHERE
GROUP BY cod_region,h2.codigo,a.fecha";

$query = $bd->consultar($sql);
while($rows=$bd->obtener_name($query)){
	$result['servicio'][] = $rows;
}

print_r(json_encode($result));
return json_encode($result);
?>
