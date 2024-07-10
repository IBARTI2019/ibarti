<?php
$Nmenu   = 760;
$mod     =  $_GET['mod'];
$titulo  = " REPORTE CUADRICULADO CLIENTES ASISTENCIA ";
$archivo = "reportes/rp_nom_cuadriculado_cliente_det.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">
      function generatePDF() {
            var cliente = $("#cliente").val();
            if(cliente != "TODOS"){
                  $('#reporte').val('pdf');
                  $('#procesar').click();
            }else{
                  toastr.error("Para generar el reporte en formato pdf, se debe seleccionar un cliente obligatoriamente.");
            }
      }
</script>
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
		<td id="select01"><select name="quincena" style="width:250px;" required>
                    <option value="">Seleccione...</option>
                    <option value="01">Primera Quincena</option>
                    <option value="02">Segunda Quincena</option>
            </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td></tr>

	 <tr>
  	<tr>
        <td class="etiqueta"><?php echo $leng['nomina']?>:</td>
		<td><select name="nomina" style="width:250px;">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_contracto);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
  	<tr>
        <td class="etiqueta"><?php echo $leng['rol']?>:</td>
		<td><select name="rol" style="width:250px;" required>

		<?php
		echo $select_rol;
		 $query02 = $bd->consultar($sql_rol);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
        <td class="etiqueta"><?php echo $leng['cliente']?>:</td>
		<td><select name="cliente" id="cliente" style="width:250px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '250')">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_cliente);
             while($row02=$bd->obtener_fila($query02,0)){
                   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
             }?></select></td></tr>
	 <tr>
       <tr>
        <td class="etiqueta"><?php echo $leng['ubicacion']?>:</td>
		<td id="contenido_ubic" ><select name="ubicacion" style="width:250px;">
     		        <option value="TODOS"> TODOS</option>
		<?php $query02 = $bd->consultar($sql_ubicacion);
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
    onclick="generatePDF()" width="25px" title="imprimir a pdf">

    <img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
    onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel">
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
                </span>
 			    <input name="usuario" type="hidden"  value="<?php echo $usuario;?>"/>
		</div></form>
