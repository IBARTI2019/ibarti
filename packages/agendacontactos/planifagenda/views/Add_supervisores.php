<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$plan   = new Planificacion;
$result = "";
$ubicacion     = $_POST['ubicacion'];
$filtro     = $_POST['filtro'];
$usuario     = $_POST['usuario'];
$cargo     = $_POST['cargo'];
$supervisores = $plan->get_supervisores($ubicacion, $filtro, $usuario, $cargo);
foreach ($supervisores as  $datos) {
    $result .= '<div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" cod_ficha="' . $datos[0] . '" cedula="' . $datos[5] . '">
    <div class="fc-event-main">' . $datos[0] . ' - ' . $datos[5] . '<br>' . $datos[1] . '<br>' . $datos[6] . '</div>
    </div>';
}
echo $result;
