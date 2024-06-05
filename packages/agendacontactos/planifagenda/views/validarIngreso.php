<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$plan   = new Planificacion;

$cliente     = $_POST['cliente'];
$fecha     = $_POST['fecha'];
$apertura     = $_POST['apertura'];
$ubic     = $_POST['ubic'];
$fecha     = $_POST['fecha'];
$hora_inicio     = $_POST['hora_inicio'];
$hora_fin     = $_POST['hora_fin'];
$actividades     = $_POST['actividades'];
$cod_ficha = $_POST["cod_ficha"];
$result  =  $plan->validar_ingreso($apertura, $cliente, $ubic, $actividades, $fecha, $hora_inicio, $hora_fin, $cod_ficha);

print_r(json_encode($result));
return json_encode($result);
?>
