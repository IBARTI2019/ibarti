<?php
require "../modelo/planificacion_modelo.php";

$plan   = new Planificacion;

$cliente  =  $plan->verificar_cl($_POST['codigo']);

$result[] = $cliente;

print_r(json_encode($result));
return json_encode($result);

?>
