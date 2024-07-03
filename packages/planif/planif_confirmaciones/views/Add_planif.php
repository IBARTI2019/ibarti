<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../" . Leng;

$confirmaciones   = new Confirmaciones;
$result = array();
$ficha     = $_POST['ficha'];
$cliente     = $_POST['cliente'];
$ubicacion     = $_POST['ubicacion'];
$result  =  $confirmaciones->get_planif($ficha, $cliente, $ubicacion);
$disabled = "";

foreach ($result as  $datos) {
    if( $datos["confirm"] == 'F' && $datos["in_transport"] == 'F'){
        echo '<tr class="fondo03" style="color: white;">';
    }else if( $datos["confirm"] == 'T' && $datos["in_transport"] == 'T'){
        echo '<tr class="fondo02">';
    }else{
        echo '<tr>';
    }

    echo '<td style="background=\'red\' !important;">' . $datos["ubicacion"] . '</td>
        <td>' . $datos["ficha"] . '</td>
        <td>' .  $datos["telefono"] . '</td>
        <td>' . $datos["ap_nombre"] . '</td>
        <td>' . $datos["turno"] . '</td>
        <td>' . $datos["horario"] . '</td>
        <td>' . $datos["concepto"] . '</td>
        <td>' . $datos["hora_entrada"] . '</td>
        <td>';
        if( $datos["confirm"] == 'T'){
            echo $datos["fec_confirm"];
        }else{
            echo 'Sin confirmar';
        }
        echo '</td><td>';
        if( $datos["in_transport"] == 'T'){
            echo $datos["fec_in_transport"];
        }else{
            echo 'Sin confirmar';
        }
        echo '</td>';
}
