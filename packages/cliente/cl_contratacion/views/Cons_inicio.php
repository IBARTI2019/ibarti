<?php
  require "../modelo/contratacion_modelo.php";
  require "../../../../".Leng;
  $titulo = $leng['contratacion'];
  $cliente = $_POST['cliente'];
  $contratacion = new Contratacion;
  $matriz  =  $contratacion->get($cliente);
  
?>

<div align="center" class="etiqueta_title"> Consulta De <?php echo $titulo;?> </div> <hr />
<div class="tabla_sistema"><table width="100%" border="0" align="center">
  <tr>
    <th width="12%">Codigo</th>
    <th width="50%">Descripcion</th>
		<th width="14%">Fecha Inicio</th>
	<th width="14%" >Status </th>
    <th width="10%"><img src="imagenes/nuevo.bmp" alt="Agregar" onclick="Cons_contratacion('', 'agregar')" title="Agregar Registro" width="30px" height="30px" border="null"/></th>
  </tr>
  <?php
    foreach ($matriz as  $datos) {
      echo '<tr>
      <td>'.$datos["codigo"].'</td>
      <td>'.longitudMax($datos["descripcion"]).'</td>
			<td>'.$datos["fecha_inicio"].'</td>
			<td>'.statuscal($datos["status"]).'</td>
      <td><img src="imagenes/actualizar.bmp" onclick="Cons_contratacion(\''.$datos[0].'\', \'modificar\')" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="Borrar_contratacion(\''.$datos[0].'\')"/></td></tr>';
    }
  	?>
    </table>
    <span class="art-button-wrapper">
				 <span class="art-button-l"> </span>
				 <span class="art-button-r"> </span>
     <input name="codigo" id="codigo" type="hidden"  value="<?php echo $cliente;?>" />  
		 <input type="button" id="" value="Detalles Contratacion" onclick="Cons_contratacionDETALLESX()" class="readon art-button" />
		 </span>
</div>
