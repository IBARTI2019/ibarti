<?php
require "../modelo/proyecto_modelo.php";
require "../../../../".Leng;
$titulo = 'Formas de Contactos';
$proyecto = new Proyecto;
$matriz  =  $proyecto->get();
?>

<div align="center" class="etiqueta_title"> Consulta De <?php echo $titulo;?> </div> <hr />
<div class="tabla_sistema"><table width="100%" border="0" align="center">
		<tr>
			<th width="15%">Codigo</th>
  		<th width="20%">Abrev</th>
			<th width="45%">Nombre</th>
      <th width="10%" class="etiqueta">Status</th>
  		<th width="10%" align="center"><img src="imagenes/nuevo.bmp" alt="Agregar" onclick="Cons_proyecto('', 'agregar')" title="Agregar Registro" width="30px" height="30px" border="null"/></th>
		</tr>
    <?php
      foreach ($matriz as  $datos) {
        echo '<tr>
          <td>'.$datos["codigo"].'</td>
          <td>'.$datos["abrev"].'</td>
				  <td>'.longitud($datos["descripcion"]).'</td>
					<td>'.statuscal($datos["status"]).'</td>
				  <td><img src="imagenes/actualizar.bmp" onclick="Cons_proyecto(\''.$datos[0].'\', \'modificar\')" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="Borrar_proyecto(\''.$datos[0].'\')"/></td></tr>';
        }
      ?>
    </table>
</div>
