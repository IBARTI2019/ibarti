<?php
	$Nmenu   = '745';
    $mod     =  $_GET['mod'];
	require_once('sql/sql_report.php');
	require_once('autentificacion/aut_verifica_menu.php');
	$bd = new DataBase();
$archivo = "reportes/rp_rutas_ventas_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " Reporte Rutas de Venta ";
?>
<script language="JavaScript" type="text/javascript">

function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var precliente   = document.getElementById("precliente").value;
    var ruta     = document.getElementById("ruta").value;
    var sub_ruta   = document.getElementById("sub_ruta").value;
	var error     = 0;
    var errorMessage = ' ';

	if(error == 0){
		var contenido = "listarData";

		ajax=nuevoAjax();
			ajax.open("POST", "ajax_rp/Add_rutas_ventas.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==1){
		        document.getElementById(contenido).innerHTML =  '<img src="imagenes/loading.gif" />';
				ajax.responseText;
				}
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("precliente="+precliente+"&ruta="+ruta+"&sub_ruta="+sub_ruta+"");

	}else{
		 	alert(errorMessage);
	}
}

function Add_Sub_Ruta(valor, contenido, activar, tamano) {  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
	var error = 0;
	var errorMessage = ' ';
    
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Un Cliente ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_sub_ruta_ventas.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
				if (activar == "T") {
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano + "&activar=" + activar + "");
	} else {
		alert(errorMessage);
	}
}
</script>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
<hr />
	<table width="100%" class="etiqueta">
        <tr>
		<td width="15%"><?php echo $leng['precliente']?>:</td>
		<td width="15%"><select name="precliente" id="precliente" style="width:120px;" required>
				<?php
                echo $select_precliente;
	   			$query01 = $bd->consultar($sql_precliente);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
		 <td  width="15%">Ruta de Venta: </td>
		<td  width="15%"><select name="ruta" id="ruta" style="width:120px;" onchange="Add_Sub_Ruta(this.value, 'contenido_sub_ruta', 'T', '120')">
					<option value="TODOS">TODOS</option>
					<?php

	   			$query01 = $bd->consultar($sql_ruta_venta);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
        <td width="15%">Sub Ruta de Venta: </td>
		<td width="15%" id="contenido_sub_ruta">
			<select name="sub_ruta" id="sub_ruta" style="width:120px;">
					<option value="TODOS">TODOS</option>
			</select>
		</td>
		<td width="6%"></td>
      	<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>
      </tr>
        
</table>
<hr />
<div id="listarData" class="listar">&nbsp;</div>
<div align="center"><br/>
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
        <input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')"
               class="readon art-button">
        </span>&nbsp;
		

        <input type="submit" name="procesar" id="procesar" hidden="hidden">
    <input type="text" name="reporte" id="reporte" hidden="hidden">
                  
    <img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
    onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel">
</div>
</form>
