<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../" . Leng;

$confirmaciones   = new Confirmaciones;
$result = array();
$ficha     = $_POST['ficha'];
$cliente     = $_POST['cliente'];
$ubicacion     = $_POST['ubicacion'];
$horario     = $_POST['horario'];
$result  =  $confirmaciones->get_planif($ficha, $cliente, $ubicacion, $horario);
$estadistica  =  $confirmaciones->get_estadistica($ficha, $cliente, $ubicacion, $horario);
$disabled = "";

foreach ($result as  $datos) {
    echo '<tr>
        <td>' . $datos["cliente"] . '</td>
        <td>' . $datos["ubicacion"] . '</td>
        <td>' . $datos["ficha"] . '</td>
        <td>' .  $datos["telefono"] . '</td>
        <td>' . $datos["ap_nombre"] . '</td>
        <td>' . $datos["turno"] . '</td>
        <td>' . $datos["horario"] . '</td>
        <td>' . $datos["concepto"] . '</td>
        <td>' . $datos["hora_entrada"] . '</td>';
        if( $datos["confirm"] == 'T'){
            echo '<td class="fondo02">'.$datos["fec_confirm"];
        }else{
            echo '<td class="fondo03">Sin confirmar';
        }
        echo '</td>';
        if( $datos["in_transport"] == 'T'){
            echo '<td class="fondo02">'.$datos["fec_in_transport"];
        }else{
            echo '<td class="fondo03">Sin confirmar';
        }
        echo '</td>';
}
