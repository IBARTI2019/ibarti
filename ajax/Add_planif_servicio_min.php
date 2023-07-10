<?php
define("SPECIALCONSTANT",true);
require "../autentificacion/aut_config.inc.php";
require_once "../".class_bdI;
$bd = new DataBase();
$cliente         = $_POST['cliente'];
$ubic         = $_POST['ubicacion'];
$contratacion   = $_POST['contratacion'];
$apertura = $_POST['apertura'];
$usuario   = $_POST['usuario'];

$sql = "SELECT ficha_activo FROm control LIMIT 1;";
$result['sql'][] = $sql;
$qrs  = $bd->consultar($sql);
$ficha_activo = $bd->obtener_name($qrs);

$sql = "SELECT 
a.codigo cod_planif,
a.cod_planif_cl_trab planif_cl_trab,
a.cod_planif_cl planif_cl,
CONCAT(Mes(a.fecha),'-',DATE_FORMAT(a.fecha,'%Y')) mes_anio,
a.cod_cliente,
a.cod_ubicacion,
a.cod_puesto_trabajo cod_puesto,
clientes_ub_puesto.nombre puesto,
a.cod_ficha ficha, 
horarios.nombre horario, 
(horarios.minutos_trabajo/60) horas,
conceptos.codigo cod_concepto,
conceptos.abrev concepto, 
a.fecha,  
CONCAT(ficha.apellidos,' ',ficha.nombres) ap_nombre, 
ficha.cedula,
h2.codigo cod_horario,
h2.nombre concepto_horario,
a.cod_turno
FROM
planif_clientes_trab_det AS a
INNER JOIN clientes_ub_puesto ON a.cod_puesto_trabajo = clientes_ub_puesto.codigo
INNER JOIN ficha ON a.cod_ficha = ficha.cod_ficha AND ficha.cod_ficha_status = '".$ficha_activo['ficha_activo']."'
INNER JOIN turno ON a.cod_turno = turno.codigo
LEFT JOIN horarios ON turno.cod_horario = horarios.codigo
LEFT JOIN conceptos ON horarios.cod_concepto = conceptos.codigo
INNER JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
WHERE a.cod_cliente = '$cliente' AND a.cod_ubicacion = '$ubic' AND a.cod_planif_cl ='$apertura'
GROUP BY a.cod_ubicacion,a.cod_puesto_trabajo,a.cod_ficha,a.cod_turno,a.fecha";
$result['sql'][] = $sql;
$qry  = $bd->consultar($sql);
while($rows=$bd->obtener_name($qry)){
	$result['servicio'][] = $rows;
}
/*
$sql = "SELECT
Dia_semana(a.fecha) dia_semana,
CONCAT(Mes(a.fecha),'-',DATE_FORMAT(a.fecha,'%Y')) mes_anio,
cl.nombre cliente,
a.cod_ubicacion,
a.cod_ub_puesto cod_puesto,
cp.nombre puesto,
cu.descripcion ubicacion,
a.fecha,
(h.minutos_trabajo /60)*Sum(a.cantidad) horas,
Sum(a.cantidad) AS cantidad,
h.codigo AS cod_horario,
h.nombre AS horario
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
WHERE 
GROUP BY a.cod_cliente, a.cod_ubicacion,a.cod_ub_puesto, a.cod_turno, a.fecha";
*/

$sql = "SELECT ca.codigo cod_cargo, ca.descripcion cargo,Dia_semana(a.fecha) dia_semana, CONCAT(Mes(a.fecha),'-',DATE_FORMAT(a.fecha,'%Y')) mes_anio, cl.nombre cliente,
 a.cod_ubicacion, a.cod_ub_puesto cod_puesto, cp.nombre puesto, cu.descripcion ubicacion, a.fecha, 
(h.minutos_trabajo /60)*Sum(a.cantidad) horas, Sum(a.cantidad) AS cantidad, h.codigo AS cod_horario, h.nombre AS horario
 FROM clientes_contratacion_ap AS a,turno AS t,cargos ca, horarios AS h, dias_habiles, dias_habiles_det, dias_tipo,clientes AS cl,
clientes_ubicacion AS cu, clientes_ub_puesto AS cp
WHERE a.cod_turno = t.codigo AND t.cod_horario = h.codigo AND t.cod_dia_habil = dias_habiles.codigo
AND a.cod_cargo = ca.codigo
AND dias_habiles_det.cod_dias_habiles = dias_habiles.codigo   
AND ((dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND Dia_semana(a.fecha)= dias_tipo.descripcion) 
OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND dias_tipo.tipo = 'D') 
OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND DATE_FORMAT(a.fecha,'%d') = dias_tipo.descripcion))
AND a.cod_cliente = cl.codigo AND a.cod_ubicacion = cu.codigo AND a.cod_ub_puesto = cp.codigo
AND a.cod_cliente = '$cliente' AND a.cod_ubicacion = '$ubic' AND a.cod_contratacion = '$contratacion' 
AND a.cod_planif_cl = '$apertura' AND a.`status`='T'  
GROUP BY a.cod_cliente, a.cod_ubicacion,a.cod_ub_puesto, a.cod_turno, a.fecha";
$result['sql'][] = $sql;
$query = $bd->consultar($sql);
while($rows=$bd->obtener_name($query)){
	$result['contrato'][] = $rows;
}

$sql = "SELECT 
t.codigo turno,
t.descripcion,
h.codigo horarios,
c.abrev conceptos
FROM 
turno t INNER JOIN horarios h ON t.cod_horario = h.codigo
LEFT JOIN conceptos c on h.cod_concepto = c.codigo
";
$result['sql'][] = $sql;
$qry  = $bd->consultar($sql);
while($rows=$bd->obtener_name($qry)){
	$result['conceptos'][] = $rows;
}

print_r(json_encode($result));
return json_encode($result);
?>