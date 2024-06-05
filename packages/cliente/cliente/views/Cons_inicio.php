<?php
require "../modelo/cliente_modelo.php";
require "../../../../".Leng;
$titulo  = $leng['cliente'];
$cliente = new Cliente;
$matriz  =  $cliente->get();
?>
<div align="center" class="etiqueta_title"> Consulta De <?php echo $titulo;?> </div> <hr />
<div class="tabla_sistema listar"><table width="100%" border="0" align="center">
  <tr>
    <th width="12%">Codigo</th>
    <th width="12%"><?php echo $leng["rif"];?></th>
    <th width="32%">Nombre</th>
    <th width="22%"><?php echo $leng['region'];?></th>
    <th width="14%" ></th>
   <th width="6%" align="center"><img src="imagenes/nuevo.bmp" alt="Agregar" onclick="Cons_cliente('', 'agregar')" title="Agregar Registro" width="30px" height="30px" border="null"/></th>
  </tr>
  <?php
    foreach ($matriz as  $datos) {
      echo '<tr>
              <td>'.$datos["codigo"].'</td>
              <td>'.$datos["rif"].'</td>
              <td>'.$datos["nombre"].'</td>
              <td>'.$datos["region"].'</td>
              <td>'.statuscal($datos["status"]).'</td>
    				  <td><img src="imagenes/actualizar.bmp" onclick="Cons_clienteDETALLESX('', "consultar")" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="Borrar_cliente(\''.$datos[0].'\')"/></td></tr>';
        }
  	?>
    </table>
</div>
