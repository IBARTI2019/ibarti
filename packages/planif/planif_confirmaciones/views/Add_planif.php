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
    echo '<tr>';
    if ($datos["confirm"] == 'T') {
        $disabled = 'disabled = "disabled"';
    } else {
        $disabled = "";
    }

    echo '<td>' . $datos["cliente"] . '</td>
      <td>' . $datos["ubicacion"] . '</td>
       <td>' . $datos["ficha"] . '</td>
       <td>' . $datos["celular"] .' <br>' .  $datos["telefono"] .' </td>
        <td>' . $datos["ap_nombre"] . '</td>
        <td>' . $datos["turno"] . '</td>
        <td>' . $datos["horario"] . '</td>
        <td>' . $datos["concepto"] . '</td>
        <td>' . $datos["hora_entrada"] . '</td>
        <td>';
        if($datos["confirm"] == 'T' && $datos["in_transport"] == 'T'){
            echo $datos['fec_confirm']. "<br>". $datos['fec_in_transport'];
        }else{
            echo '<input type="time" id="h_confirm'.$datos['codigo'].'" maxlength="12" style="width:100px"/>';
        }
        echo '</td>';

    if($datos["confirm"] == 'T' && $datos["in_transport"] == 'T'){
        echo '<td>Confimaciones completadas</td>';
    }else{
        if ($datos["confirm"] == 'T') {
            echo '<td><img class="imgLink" src="imagenes/bus.png" onclick="setConfirm(\'' . $datos['codigo'] . '\', \'' . $datos['ap_nombre'] . '\', \'T\')"
            alt="Confirmar En Transporte"  title="Confirmar En Transporte" width="30px" height="20px"></td>';
        } else {
            echo '<td><img src="imagenes/ok3.gif" onclick="setConfirm(\'' . $datos['codigo'] . '\', \'' . $datos['ap_nombre'] . '\', \'F\')"
            alt="Confirmar Asistencia" title="Confirmar Asistencia" width="20px" height="20px" border="null"/></a></td>';       
        }
    }
}
