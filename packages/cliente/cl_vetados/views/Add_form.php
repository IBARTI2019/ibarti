<script language="javascript">
  $("#add_vetado_form").on('submit', function(evt){
    evt.preventDefault();
    var ubicac = $("#ubic_vetado_update").val(); 
    var cod_ficha_vet = $("#cod_ficha_update").val(); 
    update_vetado(cod_ficha_vet,ubicac);
  });

</script>
<?php
require "../modelo/vetados_modelo.php";
require "../../../../".Leng;
$codigo   = $_POST['codigo'];
$metodo   = $_POST['metodo'];
$cliente   = $_POST['cliente'];
$ubicacion   = $_POST['ubicacion'];
$titulo = "";
$vetado = new Vetado;
$proced   = "p_vetado";
$vet =  $vetado->get_trab_det($codigo,$cliente,$ubicacion);
if($metodo == 'modificar'){
  $titulo    = "".$leng['trabajador']." : ".$vet['ap_nombre']."(".$codigo.")";
  $readonly = "";
}else{
  $readonly = "readonly";
  $titulo    = "Consultar Vetado ( ".$vet['ap_nombre']." )";
}
?>

<div id="add_vetado_mod">
  <form action="" method="post" name="add_vetado_form" id="add_vetado_form">
    <span class="etiqueta_title" id="title_vetado"><?php echo $titulo;?></span>
    <table width="80%" align="center">
     <tr>
      <td height="8" colspan="5" align="center"><hr>
        <input type="text" name="ubic_vetado_update" id="ubic_vetado_update"value="<?php echo $ubicacion; ?>" hidden="hidden">
        <input type="text" name="cod_ficha_update" id="cod_ficha_update" value="<?php echo $codigo;?>" hidden="hidden">
      </td>
    </tr>
    <tr>
      <td class="etiqueta">C&oacute;digo:</td>
      <td id="cod_ficha_vet"><?php echo $vet['cod_ficha'];?></td>
      <td class="etiqueta">C&eacute;dula: </td>
      <td><?php echo $vet['cedula'];?></td>
      <td width="10%" rowspan="4" align="left">
        <?php

        $filename = "imagenes/fotos/".$vet['cedula'].".jpg";
        //if (file_exists($filename)) {
         echo '<img src="'.$filename.'?nocache='.time().'" width="110px" height="130px" />';
      /* } else {
         echo '<img src="imagenes/img_no_disp.png" width="110px" height="130px"/>';
       } */?>
     </td>
   </tr>
   <tr>
    <td class="etiqueta">Nombres: </td>
    <td><?php echo $vet['nombres'];?></td>
    <td class="etiqueta">Apellidos: </td>
    <td><?php echo $vet['apellidos'];?></td>
  </tr>
  <tr>
    <td class="etiqueta">Contracto: </td>
    <td><?php echo $vet['contracto'];?></td>
    <td class="etiqueta">Cargo: </td>
    <td><?php echo $vet['cargo'];?></td>
  </tr>
 <tr>
    <td class="etiqueta">Usuario Ingreso: </td>
    <td><?php echo $vet['us_ing'];?></td>
    <td class="etiqueta">Fecha ingreso: </td>
    <td><?php echo $vet['fec_us_ing'];?></td>
  </tr>
   <tr>
    <td class="etiqueta">Usuario Ult. Mod.: </td>
    <td><?php echo $vet['us_mod'];?></td>
    <td class="etiqueta">Fecha Ult. Mod.: </td>
    <td><?php echo $vet['fec_us_mod'];?></td>
  </tr>
    <tr>
    <td class="etiqueta">Comentario:</td>
    <td colspan="5"><?php echo '<textarea id="comentario_vet" required  cols="90" rows="3" '.$readonly.' >'.$vet["comentario"].'</textarea>';?></td>
</tr>
<tr>
 <td height="8" colspan="5" align="center"><hr></td>
</tr>
</table>
<?php 
if($metodo == 'modificar'){
echo '<div align="center"><span class="art-button-wrapper">
  <span class="art-button-l"> </span>
  <span class="art-button-r"> </span>
  <input type="submit" name="salvar"  id="salvar_vetado" value="Guardar" class="readon art-button" />
</span>&nbsp;
<span class="art-button-wrapper">
  <span class="art-button-l"> </span>
  <span class="art-button-r"> </span>
  <input type="reset" id="limpiar_vetado" value="Restablecer" class="readon art-button" />
</span>
</div>';
}
?>
</form>
</div>