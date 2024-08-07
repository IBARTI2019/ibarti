<?php

require "../modelo/ficha_militar_modelo.php";

$notif      = new ficha_militar;
$cant = $notif-> llenar_tabla_status_militar();

echo '<table width="100%">';
echo '<tr  class="fondo00"><th width="20%" class="etiqueta">Codigo</th>
    <th width="50%" class="etiqueta">Descripcion</th>
    <th width="15%" class="etiqueta">Status</th>
    <th width="15%" align="center"><img src="imagenes/nuevo.bmp" alt="Agregar" onclick="operar(`agregar`,``)" title="Agregar Registro" width="30px" height="30px" border="null"/></a></th>  </tr>';
foreach ($cant as $valor){
    
    echo '<tr id = "'.$valor[0].'">
    <td class="texto" style="text-align:center;">'.$valor[0].'</td> 
    <td class="texto" style="text-align:center;">'.$valor[1].'</td>
	<td class="texto" style="text-align:center;">'.statuscal($valor[2]).'</td>
  <td class="texto" style="text-align:center;"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null" onclick="operar(`modificar`,`'.$valor[0].'`)"/><img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="eliminar_militar(`'.$valor[0].'`)" class="imgLink"/></td>
    </tr>';
  }
  echo '</table>';

?>
