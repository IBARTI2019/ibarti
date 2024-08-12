<?php
require "../modelo/marcaje_modelo.php";
require "../../../../" . Leng;

$marcaje   = new Marcaje;
$resultNO=array();
$ficha     = $_POST['auxficha'];
$cliente     = $_POST['auxcliente'];
$ubicacion     = $_POST['auxubicacion'];
$proyecto      =$_POST['auxproyecto'];

$resultNO = $marcaje->get_actividadesNO($ficha, $cliente, $ubicacion, $proyecto);


$disabled = "";

foreach ($resultNO as  $datos) {
    if ($datos["realizado"] == 'SI') {
        echo '<tr>';
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
        echo '<td> <input type="checkbox" id="marcado" name="marcado"  checked disabled  width="15px" height="15px"></td> <td><img src="imagenes/cerrar.bmp" ' . $disabled . ' alt="Realizado" title="Actividad Realizada" width="20px" height="20px" border="null"/></a></td>
        <td><img class="imgLink" id="m_observaciones" src="imagenes/detalle.bmp" alt="Modificar Observaciones" title="Modificar Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td>';
        if ($datos["participantes"] == 'T') {
            echo '<td><img class="imgLink" id="m_participantes" src="imagenes/detalle.bmp" alt="Modificar Participantes" title="Modificar Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td></tr>';
        } else {
            echo '<td>N/A</td></tr>';
        }
    } else {
        
        echo '<td> <input type="checkbox" id="marcado" name="marcado" disabled  width="15px" height="15px"></td>
         <td><img class="imgLink" id="m_observaciones" src="imagenes/detalle.bmp" alt="Modificar Observaciones" title="Modificar Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td>';
        if ($datos["participantes"] == 'T') {
            echo '<td><img class="imgLink" id="m_participantes" src="imagenes/detalle.bmp" alt="Modificar Participantes" title="Modificar Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["observaciones"] . ')</td></tr>';
        } else {
            echo '<td>N/A</td></tr>';
        }
    }
    
 echo $resultNO;   
}




