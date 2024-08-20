<?php
require "../modelo/marcaje_modelo.php";
require "../../../../" . Leng;

$marcaje   = new Marcaje;
$result = array();
$ficha     = $_POST['ficha'];
$cliente     = $_POST['cliente'];
$ubicacion     = $_POST['ubicacion'];
$result  =  $marcaje->get_actividades($ficha, $cliente, $ubicacion);
$disabled = "";

foreach ($result as  $datos) {
    if ($datos["realizado"] == 'SI') {
        echo '<tr class="marcar">';
        $disabled = 'disabled = "disabled"';
    } else {
        echo '<tr>';
        $disabled = "";
    }

    echo '<td>' . $datos["codigo"] . '</td>
      <td>' . $datos["ubicacion"] . '</td>
             <td>' . $datos["proyecto"] . '</td>
       <td>' . $datos["actividad"] . '</td>
       <td>' . $datos["hora_inicio"] . ' </br> ' . $datos["hora_fin"] . '</td>
             <td>' . $datos["realizado"] . '</td>';

    if ($datos["realizado"] == 'SI') {
        echo '<td><img src="imagenes/cerrar.bmp" ' . $disabled . ' alt="Realizado" title="Actividad Realizada" width="20px" height="20px" border="null"/></a></td>
        <td><img class="imgLink" id="m_observaciones" src="imagenes/detalle.bmp" alt="Modificar Observaciones" title="Modificar Observaciones" onclick="openModalObservaciones(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["observaciones"] . ')</td>';
        if ($datos["participantes"] == 'T') {
            echo '<td><img class="imgLink" id="m_participantes" src="imagenes/detalle.bmp" alt="Modificar Participantes" title="Modificar Participantes" onclick="openModalParticipantes(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td></tr>';
        } else {
            echo '<td>N/A</td></tr>';
        }
    } else {
        echo '<td><img src="imagenes/ok3.gif" ' . $disabled . ' onclick="setRealizado(\'' . $datos['codigo'] . '\')"
        alt="Realizado" title="Marcar como realizado" width="20px" height="20px" border="null"/></a></td>
        <td><img class="imgLink" id="m_observaciones" src="imagenes/detalle.bmp" alt="Modificar Observaciones" title="Modificar Observaciones" onclick="openModalObservaciones(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["observaciones"] . ')</td>';
        if ($datos["participantes"] == 'T') {
            echo '<td><img class="imgLink" id="m_participantes" src="imagenes/detalle.bmp" alt="Modificar Participantes" title="Modificar Participantes" onclick="openModalParticipantes(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td></tr>';
        } else {
            echo '<td>N/A</td></tr>';
        }
    }
}
