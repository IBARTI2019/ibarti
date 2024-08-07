<?php
require "../modelo/stock_ubic_alcance_modelo.php";

$fecha_desde = conversion($_POST['fecha_desde']);
$fecha_hasta = conversion($_POST['fecha_hasta']);
$codigo = $_POST['codigo'];
$ubicacion = $_POST['ubicacion'];
$producto = $_POST['producto'];
$cliente =  $_POST['cliente'];
$stock_ubic_alcance    = new stock_ubic_alcance;
$lista     = $stock_ubic_alcance->buscar($fecha_desde, $fecha_hasta, $codigo, $ubicacion, $producto, $cliente);

foreach ($lista as  $datos) {
  echo '<tr onclick="Form_stock_ubic_alcance(\'' . $datos["codigo"] . '\', \'modificar\',\'' . $datos["anulado"] . '\')">
        <td>' . $datos["codigo"] . '</td>
        <td>' . $datos["ubicacion"] . '</td>
        <td>' . $datos["fecha"] . '</td>
        <td>' . $datos["motivo"] . '</td>
        <td>' . $datos["anulado_des"] . '</td>
        <td></td>
       </tr>';
}
