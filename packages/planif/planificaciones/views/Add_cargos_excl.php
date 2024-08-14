<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$planif      = new Planificacion;
$data = $planif->get_cargos_excl();

echo '<tr>
<th width="25%">Codigo</th>
<th width="65%">Cargo</th>
<th width="10%">Eliminar</th>
</tr>';
foreach ($data as  $datos)
  {
    echo '<tr>
          <td>'.$datos["cod_cargo"].'</td>
          <td>'.$datos["cargo"].'</td>
          <td>
            <img src="imagenes/borrar.bmp" width="16px" height="16px" onClick="Borrar_cargo_det('.$datos["codigo"].')"  alt="Borrar" title="Borrar Registro" class="imgLink">
          </td>
        <tr>';
  }
?>
