<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../" . Leng;

$confirmaciones   = new Confirmaciones;
$result = array();
$ficha     = $_POST['ficha'];
$cliente     = $_POST['cliente'];
$ubicacion     = $_POST['ubicacion'];
$horario     = $_POST['horario'];
$data  =  $confirmaciones->get_estadistica($ficha, $cliente, $ubicacion, $horario);
$porcentaje_asistenia = round((($data["confirm"] * 100) / $data["total"]), 2);
$porcentaje_transporte = round((($data["in_transport"] * 100) / $data["total"]), 2);
echo '<table>
<tr align="left">
    <td align="left">
        <b> Asistencia: </b>
    </td>
    <td align="left">
        <b> '.$data["confirm"].'/'.$data["total"].'</b>
    </td>
    <td align="left">
        <b>  -  '. $porcentaje_asistenia .'% </b>
    </td>
</tr>
<tr align="left">
    <td align="left">
        <b> Transporte: </b>
    </td>
    <td align="left">
        <b> '.$data["in_transport"].'/'.$data["total"].'</b>
    </td>
    <td align="left">
        <b>  -  '. $porcentaje_transporte .'% </b>
    </td>
</tr>
</table>';
