<?php
require "../modelo/life_line_act_modelo.php";
require "../../../../".Leng;
$titulo = 'Actividades Linea de Vida';
$life_line_act = new LifeLine;
$matriz  =  $life_line_act->get();
?>

<div align="center" class="etiqueta_title"> Consulta De <?php echo $titulo;?> </div> <hr />
<div class="tabla_sistema"><table width="100%" border="0" align="center">
		<tr>
			<th width="15%">Codigo</th>
  		<th width="10%">Abrev</th>
			<th width="45%">Nombre</th>
      <th width="10%" class="etiqueta">Color</th>
      <th width="10%" class="etiqueta">Status</th>
  		<th width="10%" align="center"><img src="imagenes/nuevo.bmp" alt="Agregar" onclick="Cons_life_line('', 'agregar')" title="Agregar Registro" width="30px" height="30px" border="null"/></th>
		</tr>
    <?php
      foreach ($matriz as  $datos) {
        echo '<tr>
          <td>'.$datos["codigo"].'</td>
          <td>'.$datos["abrev"].'</td>
				  <td>'.longitud($datos["descripcion"]).'</td>
          <td><input disabled="true" type="color" value="'.$datos["color"].'"></td>
					<td>'.statuscal($datos["status"]).'</td>
				  <td><img src="imagenes/actualizar.bmp" onclick="Cons_life_line(\''.$datos[0].'\', \'modificar\')" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="Borrar_life_line(\''.$datos[0].'\')"/></td></tr>';
        }
      ?>
    </table>
</div>
