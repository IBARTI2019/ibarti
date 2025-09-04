<?php
define("SPECIALCONSTANT", true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;

$bd = new DataBase();
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$horario    = $_POST['horario'];
$estado    = $_POST['estado'];
$usuario    = $_POST['usuario'];
$fecha_D    = conversion($_POST['fecha_desde']);
$fecha_H    = conversion($_POST['fecha_hasta']);
$result = array();

$WHERE = " WHERE a.fecha BETWEEN \"$fecha_D\" AND \"$fecha_H\" AND a.`status`='T' ";
$WHERE_21 =" WHERE asistencia_apertura.fec_diaria BETWEEN  \"$fecha_D\" AND \"$fecha_H\"";

$WHERE_22 = " AND asistencia.cod_as_apertura = asistencia_apertura.codigo
AND asistencia.cod_concepto = conceptos.codigo
AND conceptos.cod_horario <> '9999'
AND conceptos.cod_horario = horarios.codigo
AND asistencia.cod_cliente = clientes.codigo
AND asistencia.cod_ubicacion = clientes_ubicacion.codigo
AND clientes_ubicacion.cod_estado = estados.codigo
AND asistencia.cod_cliente <> control.oesvica
AND conceptos.asist_perfecta = 'T'
GROUP BY
asistencia_apertura.fec_diaria,
asistencia.cod_cliente,
asistencia.cod_ubicacion,
conceptos.cod_horario
ORDER BY
  asistencia_apertura.fec_diaria DESC
";

if(isset($_POST['r_cliente'])){
	$r_cliente = $_POST['r_cliente'];
	if($r_cliente  == "T"){
		$WHERE  .= " AND a.cod_ubicacion IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		usuario_clientes.cod_usuario = '$usuario') ";
		$WHERE_21  .= " AND asistencia.cod_ubicacion  IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		usuario_clientes.cod_usuario = '$usuario') ";
	}
}

if( $cliente != "TODOS"){
	$WHERE .= " AND a.cod_cliente = '$cliente' ";
	$WHERE_21 .= " AND asistencia.cod_cliente = '$cliente' ";
}

if( $ubicacion != "TODOS"){
	$WHERE .= " AND a.cod_ubicacion = '$ubicacion' ";
	$WHERE_21 .= " AND asistencia.cod_ubicacion = '$ubicacion' ";
}

if( $horario != "TODOS"){
	$WHERE .= " AND h.codigo = '$horario' ";
	$WHERE_21 .= " AND horarios.codigo = '$horario' ";
}

if( $estado != "TODOS"){
	$WHERE .= " AND estados.codigo = '$estado' ";
	$WHERE_21 .= " AND clientes_ubicacion.cod_estado = '$estado' ";
}

$sql = "SELECT
  asistencia_apertura.fec_diaria AS fec_diaria,
  asistencia.cod_cliente AS cod_cliente,
  clientes.nombre cliente,
  asistencia.cod_ubicacion AS cod_ubicacion,
  clientes_ubicacion.descripcion AS ubicacion,
  conceptos.cod_horario AS cod_horario,
  horarios.nombre horario,
  estados.descripcion AS estado,
  count(conceptos.valor) AS valor
FROM
  asistencia,
  asistencia_apertura,
  estados,
  clientes,
  clientes_ubicacion,
  conceptos,
  horarios,
  control
$WHERE_21
$WHERE_22";

$qry  = $bd->consultar($sql);
while($rows=$bd->obtener_name($qry)){
	$result['asistencia'][] = $rows;
}

$sql = "SELECT
a.fecha,
estados.descripcion estado,
a.cod_ubicacion,
cu.descripcion ubicacion,
a.cod_cliente,
cl.abrev cliente,
h.codigo cod_horario,
h.nombre horario,
Sum(a.cantidad) AS cantidad
FROM
clientes_contratacion_ap AS a
INNER JOIN turno AS t ON a.cod_turno = t.codigo
INNER JOIN horarios AS h ON t.cod_horario = h.codigo
INNER JOIN dias_habiles ON t.cod_dia_habil = dias_habiles.codigo
INNER JOIN dias_habiles_det ON dias_habiles_det.cod_dias_habiles = dias_habiles.codigo
INNER JOIN dias_tipo ON (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND Dia_semana(a.fecha)= dias_tipo.descripcion) 
OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND dias_tipo.tipo = 'D')
OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND DATE_FORMAT(a.fecha,'%d') = dias_tipo.descripcion) 
INNER JOIN clientes AS cl ON a.cod_cliente = cl.codigo
INNER JOIN clientes_ubicacion AS cu ON a.cod_ubicacion = cu.codigo
INNER JOIN clientes_ub_puesto AS cp ON a.cod_ub_puesto = cp.codigo
INNER JOIN estados ON cu.cod_estado = estados.codigo
$WHERE
GROUP BY a.cod_cliente, a.cod_ubicacion, h.codigo, a.fecha
ORDER BY 1,4";

$query = $bd->consultar($sql);
while($rows=$bd->obtener_name($query)){
	$result['contrato'][] = $rows;
}

print_r(json_encode($result));
return json_encode($result);

?>
