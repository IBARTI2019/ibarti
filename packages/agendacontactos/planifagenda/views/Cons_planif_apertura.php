<?php
  require "../modelo/planificacion_modelo.php";
  require "../../../../".Leng;
  $titulo = "Apertura de Planificacion";
  $planif = new Planificacion;
  $cliente     = $_POST['cliente'];
  $matriz  =  $planif->get_planif_apertura($cliente);
?>
<div align="center" class="etiqueta_title"> Consulta De <?php echo $titulo;?> </div> <hr />
<div class="tabla_sistema"><table width="100%" border="0" align="center">
		<tr>
			<th width="10%">Codigo</th>
			<th width="15%">Fecha Inicio</th>
      <th width="15%">Fecha Fin</th>
      <th width="15%">Fec. Ult. Mod.</th>
     <th width="30%">Usuario Ult. Mod.</th>
      <th width="10%">Status</th>
  	  <th width="5%" align="center"><img src="imagenes/nuevo.bmp" alt="Agregar" onclick="B_planif_apertura()" title="Agregar Registro" width="30px" height="30px" border="null"/></th>
		</tr>
    <?php
    foreach ($matriz as  $datos){
      echo '<tr>
         <td>'.$datos["codigo"].'</td>
			   <td>'.$datos["fecha_inicio"].'</td>
			   <td>'.$datos["fecha_fin"].'</td>
         <td>'.$datos["fec_us_mod"].'</td>
         <td>'.$datos["us_mod"].'</td>
			   <td>'.statuscal($datos["status"]).'</td>
			  <td><img src="imagenes/cerrar.bmp" onclick="Cerrar_ap_planif(\''.$datos[0].'\', \''.$datos["status"].'\')" alt="Cerrar" title="Cerrar Apertura Planificacion" width="20px" height="20px" border="null"/></a></td></tr>';
    }?>
    </table>
</div>
