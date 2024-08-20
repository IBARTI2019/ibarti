<script language="javascript">
$("#cl_contratacion_form").on('submit', function(evt){
	 evt.preventDefault();
	 
});
	</script>
<?php

require "../modelo/contratacion_modelo.php";
require "../../../../".Leng;
$contratacion = new Contratacion;
$metodo = $_POST['metodo'];


if($metodo == 'consultar')
{
	$codigo = $_POST['codigo'];
	
	$titulo   = "Consultar ".$leng['contratacion'];
	$cont1=$contratacion->get_ubicacion($codigo);
	$ubic=$contratacion->get_puesto($codigo);
}
?>

<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div> <hr />
	<form id="cl_contratacion_form" name="cl_contratacion_form" method="post">
     <table width="98%" align="center">
    <tr>
      <td width="14%" class="etiqueta">Ubicacion:</td>
      <td><select id="cont_ubicacion"  onclick="CargarDetalleContplanif(this.value)"  required style="width:120px;">
					    <option  value="">Seleccione...</option>
  					<?php
					     
					 foreach ($cont1 as  $datos) {
  					      echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
                     }
  					?>
					</select></td>
	</td>
     
	</tr>

	 	<tr>
         <td height="8" colspan="6" align="center"><hr></td>
     </tr>
  </table>
	<div align="center"><br/>
	<span class="art-button-wrapper">
				 <span class="art-button-l"> </span>
				 <span class="art-button-r"> </span>
		 <input type="button" id="volver" value="volver" onClick="Cons_contratacion_inicio()" class="readon art-button" />
		 </span>
	</div>
	 <input name="metodo" id="cont_metodo" type="hidden"  value="<?php echo $metodo;?>" />
	
	</form><div id="Cont_detalleCont"  class="tabla_sistema listar" style="width: 90%;"></div>
