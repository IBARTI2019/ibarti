<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$planif      = new Planificacion;
$data = $planif->get_confirmaciones_esp();

echo '<tr>
<th width="32%">'.$leng["cliente"].'</th>
<th width="23%">'.$leng["ubicacion"].'</th>
<th width="23%">Cargo</th>
<th width="11%">'.$leng["horario"].'</th>
<th width="11%">Hora entrada</th>
<th width="16%">Ventana de marcaje</th>
<th width="8%">Editar</th>
<th width="8%">Eliminar</th>
</tr>';
foreach ($data as  $datos)
  {
    $rango = (!empty($datos["inicio_marc_entrada"]) && !empty($datos["fin_marc_entrada"]))
      ? $datos["inicio_marc_entrada"].' - '.$datos["fin_marc_entrada"]
      : 'Ventana abierta';
    echo '<tr>
          <td>'.$datos["cliente"].'</td>
          <td>'.$datos["ubicacion"].'</td>
           <td>'.$datos["cargo"].'</td>
          <td>'.$datos["horario"].'</td>
          <td>'.$datos["hora_entrada"].'</td>
          <td>'.$rango.'</td>
          <td>
            <img src="imagenes/actualizar.bmp" width="16px" height="16px" onClick="Editar_confirmacion_det(\''.$datos["codigo"].'\', \''.$datos["cod_cliente"].'\', \''.$datos["cod_ubicacion"].'\', \''.addslashes($datos["ubicacion"]).'\', \''.$datos["cod_cargo"].'\', \''.$datos["cod_horario"].'\', \''.$datos["hora_entrada"].'\', \''.$datos["inicio_marc_entrada"].'\', \''.$datos["fin_marc_entrada"].'\')" alt="Editar" title="Editar Registro" class="imgLink">
          </td>
          <td>
            <img src="imagenes/borrar.bmp" width="16px" height="16px" onClick="Borrar_confirmacion_det('.$datos["codigo"].')"  alt="Borrar" title="Borrar Registro" class="imgLink">
          </td>
        <tr>';
  }
?>
