<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<?php
$Nmenu   = 544;
$mod     =  $_GET['mod'];
$titulo  = " REPORTE CUADRICULADO ASISTENCIA ";
$archivo = "reportes/rp_nom_cuadriculado_det.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<div align="center" class="etiqueta_title"> <?php echo $titulo;?></div>
<br/>

<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>" method="post" target="_blank">
  <table width="75%" border="0" align="center">
	<tr>
       <td height="8" colspan="2" align="center"><hr></td>
    </tr>
    <tr>
      <td class="etiqueta" width="30%">Fecha :</td>
      <td id="date01" width="70%">
      <input type="text" name="fecha_desde" id="fecha_desde" style="width:100px"  required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"><br />
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">El Campo Es Requerido.</span>
	  <span class="textfieldInvalidFormatMsg">Fromato Invalido.</span></td>
    </tr>
    <tr>
        <td class="etiqueta">Quincena:</td>
		<td id="select01"><select name="quincena" id="quincena" style="width:250px;" required>
                    <option value="">Seleccione...</option>
                    <option value="01">Primera Quincena</option>
                    <option value="02">Segunda Quincena</option>
            </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td></tr>

	 <tr>
  	<tr>
        <td class="etiqueta"><?php echo $leng['nomina']?>:</td>
		<td><select name="nomina" id="nomina" style="width:250px;">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_contracto);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
  	<tr>
        <td class="etiqueta"><?php echo $leng['rol']?>:</td>
		<td><select name="rol" id="rol" style="width:250px;" required>

		<?php
		echo $select_rol;
		 $query02 = $bd->consultar($sql_rol);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
    <tr>
        <td class="etiqueta"><?php echo $leng['region']?>:</td>
		<td><select name="region" id="region" style="width:250px;">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_region);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
  	<tr>
        <td class="etiqueta"><?php echo $leng['estado']?></td>
		<td><select name="estado" id="estado" style="width:250px;">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_estado);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
  	<tr>
        <td class="etiqueta"><?php echo $leng['ciudad']?>:</td>
		<td><select name="ciudad" id="ciudad" style="width:250px;">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_ciudad);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>

        
	 <tr>
       <td height="8" colspan="2" align="center"><hr></td>
    </tr>
  </table>
	 <br />
     <div align="center">
      <input type="submit" name="procesar" id="procesar" hidden="hidden">
    <input type="text" name="reporte" id="reporte" hidden="hidden">
                  
    <img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0"
    onclick="{$('#reporte').val('pdf');$('#procesar').click();}" width="25px" title="imprimir a pdf">

    <img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
    onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel">
    <span class="art-button-wrapper" onclick="rotacionModalOpen()">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" id="comparar" value="Verificar Rotaciones" class="readon art-button" />
                </span>
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
                </span>
 			    <input name="usuario" type="hidden"  value="<?php echo $usuario;?>"/>
		</div></form>



<div id="rotarionModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="rotacionCloseModal()" >&times;</span>
			<span id="modal_titulo">Verificación de rotaciones</span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido_v_r">
			</div>
		</div>
	</div>
</div>

<script language="JavaScript" type="text/javascript">

function rotacionModalOpen(){
	var quincena         = $("#quincena").val();
	var nomina     = $("#nomina").val();
	var rol      = $("#rol").val();
	var region       = $("#region").val();
      var estado       = $("#estado").val();
      var ciudad       = $("#ciudad").val();
      var fecha_desde = $( "#fecha_desde").val();
	var error = 0;
      var errorMessage = ' Debe Seleccionar Un Campo ';

      if( fechaValida(fecha_desde) !=  true && fecha_desde != ""){ 
		var errorMessage = ' Campos De Fecha Incorrecto ';
		var error = error+1;
	}
	if( quincena == ""){
            var errorMessage = ' \n Debe seleccionar una quincena ';
	      var error      = error+1;
	}
	      if(error == 0){
                  $("#rotarionModal").show();
                  var contenido = "modal_contenido_v_r";
                  ajax=nuevoAjax();
                        ajax.open("POST", "ajax/Add_verificacion_rotacion.php", true);
                        ajax.onreadystatechange=function(){

                              if (ajax.readyState==1 || ajax.readyState==2 || ajax.readyState==3){
                                    document.getElementById(contenido).innerHTML = '<img src="imagenes/loading.gif" /><h2>Este proceso puede tardar en finalizar, por favor tenga paciencia.. </h2>';
                              }
                              if (ajax.readyState==4){
                                    document.getElementById(contenido).innerHTML = ajax.responseText;
                              }
                        }
                  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                  ajax.send("fecha_desde="+fecha_desde+"&quincena="+quincena+"&rol="+rol+"&nomina="+nomina+"&region="+region+"&estado="+estado+"&ciudad="+ciudad+"");
                 
            }else{ 
                  alert(errorMessage);
            }
      }

function rotacionCloseModal(){
    $("#rotarionModal").hide();
}
            </script>