<?php
require "../modelo/marcaje_modelo.php";
require "../../../../" . Leng;

$marcaje = new Marcaje;
$result = array();
$ficha = $_POST['ficha'];
$cliente = $_POST['cliente'];
$ubicacion = $_POST['ubicacion'];
$result = $marcaje->get_actividades($ficha, $cliente, $ubicacion);

foreach ($result as $datos) {
    $proyectoRealizado = ($datos["realizado"] == 'SI');
    
    if ($proyectoRealizado) {
        echo '<tr class="marcar">';
    } else {
        echo '<tr>';
    }

    echo '<td>' . $datos["codigo"] . '</td>
      <td>' . $datos["ubicacion"] . '</td>
      <td>' . $datos["proyecto"] . '</td>
      <td>' . $datos["hora_inicio"] . '</td>
      <td>' . $datos["hora_fin"] . '</td>';

    if ($proyectoRealizado) {
        echo '<td><span style="color: green;">✓ REALIZADO</span></td>
          <td><img src="imagenes/cerrar.bmp" alt="Realizado" title="Proyecto con actividades marcadas" onclick="openModalObservacionesdos(' . $datos["codigo"] . ',\'' . $ficha . '\',\'' . $cliente . '\',' . $ubicacion . ',\'' . $datos["cod_proyecto"] . '\', ' . $datos["cod_planif"] . ')" width="30px" height="30px" border="null"/></td>
        </tr>';
    } else {
        echo '<td><span style="color: orange;">⏳ PENDIENTE</span></td>
          <td><img class="imgLink" id="m_observaciones" src="imagenes/nuevo.bmp" alt="Marcar" title="Marcar Actividades" onclick="openModalObservacionesdos(' . $datos["codigo"] . ',\'' . $ficha . '\',\'' . $cliente . '\',' . $ubicacion . ',\'' . $datos["cod_proyecto"] . '\', ' . $datos["cod_planif"] . ')" width="30px" height="30px"></td>
        </tr>';
    }
}
?>