<?php 	   
	$foto     = "imagenes/fotos/$cedula.jpg";
	$cedu     = "imagenes/cedula/$cedula.jpg";	
 
	  if (file_exists($foto)) {
		$foto     = "imagenes/fotos/$cedula.jpg?nochache=".time();
 		   $foto01 = '<img src="'.$foto.'" />';
		   $foto_Mens = 'Cambiar Foto';
		   
		} else {
		   $foto_Mens = 'Cargar Foto';
		   $foto01 = '<img src="imagenes/foto.jpg"/>';
		}
		
		if (file_exists($cedu)) {
			
			$cedu     = "imagenes/cedula/$cedula.jpg?nochache=".time();
			$cedula_Mens = 'Cambiar Cedula';
 		    $cedula01 = '<img src="'.$cedu.'"/>';
		} else {
			$cedula_Mens = 'Cargar Cedula';
		   $cedula01 =  '<img src="imagenes/cedula.jpg"/>';
		}		
		 ?>  



<table width="80%" align="center">
<tr>
<td width="35%"> <button type="button" class="boton" id="addImage" onClick="Vinculo('inicio.php?area=formularios/add_imagenes&ci=<?php echo $cedula;?>&tipo=01')"><?php echo $foto_Mens;?></button></td>
<td width="75%"><button type="button" class="boton" id="addImage" onClick="Vinculo('inicio.php?area=formularios/add_imagenes&ci=<?php echo $cedula;?>&tipo=02')"><?php echo $cedula_Mens;?></button></td>
</tr>
<tr height="350px">
<td><?php echo $foto01;?></td>
<td><?php echo $cedula01;?></td>
</tr>

</table>