<?php
$mod     =  $_GET['mod'];
$titulo  = " FORMATO NOVEDADES CHECK LIST ";

$archivo = "reportes/rp_nov_check_formato.php?Nmenu=$Nmenu&mod=$mod";

$Nmenu = '565';
if(isset($_SESSION['usuario_cod'])){
    require_once('autentificacion/aut_verifica_menu.php');
    $us = $_SESSION['usuario_cod'];
}else{
    $us = $_POST['usuario'];
}

?>
<br/>
<div align="center" class="etiqueta_title"> <?php echo $titulo;?></div>
<br/>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
  <table width="75%" border="0" align="center">
	<tr>
       <td height="8" colspan="2" align="center"><hr></td>
    </tr>
    <tr>
        <td class="etiqueta">Clasificacion:</td>
		<td>
        <select id="clasificacion" name="clasificacion" style="width:250px;" onchange="llenar_tipo_check(this.value)">
     	</select>
         </td>
    </tr>
  	<tr>
        <td class="etiqueta">Tipo:</td>
		<td>
        <select id="tipo" name="tipo" style="width:250px;">
     	</select>
        </td>
    </tr>
  	
       <td height="8" colspan="2" align="center"><hr></td>
    </tr>
    
  </table>
	 <br />
     <div align="center">
    <input type="submit" name="procesar" id="procesar" hidden="hidden">
    <input type="text" name="reporte" id="reporte" hidden="hidden">
    <input type="text" name="tipos" id="tipos" hidden="hidden">
                  
    <img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0" onclick="enviar_pdf()" width="25px" title="imprimir a pdf">
    <img class="imgLink" id="img_pdf" src="imagenes/excel.gif" border="0" onclick="enviar_excel()" width="25px" title="imprimir a excel">
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
                </span>
 			    <input name="usuario" type="hidden"  value="<?php echo $usuario;?>"/>
		</div>
<br />
<br />
</form>
<script src="packages/nov_check_rp/controllers/check_list_Ctrl.js"></script>