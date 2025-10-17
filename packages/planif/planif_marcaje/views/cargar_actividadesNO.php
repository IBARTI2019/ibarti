<?php
require "../modelo/marcaje_modelo.php";
require "../../../../" . Leng;

$marcaje   = new Marcaje;
$resultNO=array();
$ficha     = $_POST['auxficha'];
$cliente     = $_POST['auxcliente'];
$ubicacion     = $_POST['auxubicacion'];
$proyecto      =$_POST['auxproyecto'];
$realizado      = $_POST['realizado'];
$codigo      = $_POST['codigo'];

$resultNO = $marcaje->get_actividadesNO($ficha, $cliente, $ubicacion, $proyecto, $codigo);

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
        echo '<td> <input type="checkbox" id="'. $datos["codigo"] .'" name="marcado"  checked disabled  width="15px" height="15px"></td><td>';
        if (!empty($datos["link"])) {
            echo '<img class="imgLink" src="imagenes/pdf.gif" alt="Ver Archivo" title="Ver Archivo de Actividad" onclick="verArchivo(\'' . $datos["link"] . '\')" width="20px" height="20px" style="cursor:pointer;">';
        } else {
            echo 'Sin archivo';
        }
        echo '</td><td><img class="imgLink" id="m_observaciones" src="imagenes/detalle.bmp" alt="Modificar Observaciones" title="Modificar Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td>';
        if ($datos["participantes"] == 'T') {
            echo '<td><img class="imgLink" id="m_participantes" src="imagenes/detalle.bmp" alt="Modificar Participantes" title="Modificar Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td></tr>';
        } else {
            echo '<td>N/A</td></tr>';
        }
    } else {

       echo '<td> <input type="checkbox" id="'. $datos["codigo"] .'" name="marcado" disabled onchange="enableFileInput(this)" width="15px" height="15px"></td>
       <td><input type="file" name="archivo[' . $datos["codigo"] . ']" disabled /></td>';
        if($realizado == "true"){
            echo '<td><img src="imagenes/cerrar.bmp" ' . $disabled . ' alt="Realizado" title="Actividad Realizada" width="20px" height="20px" border="null"/></td>';
        }else{
            echo '<td><img class="imgLink" id="m_observaciones" src="imagenes/detalle.bmp" alt="Modificar Observaciones" title="Modificar Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["fichas"] . ')</td>';
        }
        if ($datos["participantes"] == 'T') {
            if($realizado == "true"){
                echo '<td>N/A</td></tr>';
            }else{
                echo '<td><img class="imgLink" id="m_participantes" src="imagenes/detalle.bmp" alt="Modificar Participantes" title="Modificar Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">(' . $datos["observaciones"] . ')</td></tr>';
            }
        } else {
            echo '<td>N/A</td></tr>';
        }
    }
    
 echo $resultNO;   
}




