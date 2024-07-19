<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$planif      = new Planificacion;
$data = $planif->get_confirmaciones_esp();

echo '<tr>
<th width="40%">'.$leng["cliente"].'</th>
<th width="30%">'.$leng["ubicacion"].'</th>
<th width="15%">'.$leng["turno"].'</th>
<th width="15%">Hora entrada</th>
<th width="10%">Eliminar</th>
</tr>';
foreach ($data as  $datos)
  {
    echo '<tr>
          <td>'.$datos["cliente"].'</td>
          <td>'.$datos["ubicacion"].'</td>
          <td>'.$datos["turno"].'</td>
          <td>'.$datos["hora_entrada"].'</td>
          <td>
            <img src="imagenes/borrar.bmp" width="16px" height="16px" onClick="Borrar_confirmacion_det('.$datos["codigo"].')"  alt="Borrar" title="Borrar Registro" class="imgLink">
          </td>
        <tr>';
  }
?>
