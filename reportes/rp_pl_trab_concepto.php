<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php 
	$Nmenu   = '536'; 
    $mod     =  $_GET['mod'];
	require_once('sql/sql_report.php');
	require_once('autentificacion/aut_verifica_menu.php');

	$bd = new DataBase();

$cod_ficha = "TODOS";
$titulo  = " REPORTE PLANIFICACION TRABAJADOR CONCEPTOS";
$archivo = "reportes/rp_pl_trab_concepto_det.php?Nmenu=$Nmenu&mod=$mod";
?>
<script language="JavaScript" type="text/javascript">

function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var fecha_desde = document.getElementById("fecha_desde").value; 
	var fecha_hasta = document.getElementById("fecha_hasta").value; 
	var rol         = document.getElementById("rol").value; 
	var region      = document.getElementById("region").value; 
	var estado      = document.getElementById("estado").value; 
    var cargo       = document.getElementById("cargo").value; 			
    var contrato    = document.getElementById("contrato").value; 			
    var horario       = document.getElementById("horario").value; 	
	var	trabajador  = document.getElementById("stdID").value; 		
		

	var error = 0; 
    var errorMessage = ' ';
	 if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
    var errorMessage = ' Campos De Fecha Incorrectas ';
	 var error = error+1; 		 
	}
	
	
     if(rol == '') {
	 var error = error+1; 
	  errorMessage = errorMessage + ' \n Debe Seleccionar un Rol ';
	}  
	if(error == 0){	 
		var contenido = "listar";
 
		ajax=nuevoAjax();
			ajax.open("POST", "ajax_rp/Add_pl_trab_concepto.php", true);
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
			ajax.send("rol="+rol+"&region="+region+"&estado="+estado+"&cargo="+cargo+"&contrato="+contrato+"&horario="+horario+"&trabajador="+trabajador+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"");	
	
	}else{
		 	alert(errorMessage);
	}	
}
</script>	
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div> 
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
<hr />
	<table width="100%" class="etiqueta">
		<tr><td width="10%">Fecha Desde:</td>
		 <td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
        <td width="10%">Fecha Hasta:</td>   
		 <td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>            
		<td width="10%">Roles:</td>
        <td width="14%"><select name="rol" id="rol" style="width:120px;" required>
               <?php 
				echo $select_rol;
            $query01 = $bd->consultar($sql_rol);		
            while($row01=$bd->obtener_fila($query01,0)){							   							
                 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
           }?></select></td>

           <td>Horario: </td>
			<td><select name="horario" id="horario" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 
	   			$query01 = $bd->consultar($sql_horario);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
       
      <td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>  
        </tr>
        <tr> 

		 <td>Region: </td>
			<td><select name="region" id="region" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 

	   			$query01 = $bd->consultar($sql_region);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
             <td>Estado: </td>
			<td><select name="estado" id="estado" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 
	   			$query01 = $bd->consultar($sql_estado);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
		 <td>cargo: </td>
			<td><select name="cargo" id="cargo" style="width:120px;">
				   	<option value="TODOS">TODOS</option> 
					<?php 
		   			$query01 = $bd->consultar($sql_cargo);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			 	}
			   ?></select></td>
             <td>Contrato: </td>
			<td><select name="contrato" id="contrato" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 
	   			$query01 = $bd->consultar($sql_contracto);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
           <td>&nbsp;</td>
      </tr>    
      
      <tr>
			<td>Filtro Trab.:</td>	
            <td><select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
                   <option value="TODOS"> TODOS</option>
                    <option value="codigo"> C&oacute;digo</option>
                    <option value="cedula"> C&eacute;dula</option>
                    <option value="trabajador">Trabajador</option>
                    <option value="apellidos">Apellido</option>                
                    <option value="nombres">Nombre</option>
            </select><br />
            <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	  <td>Trabajador:</td> 
      <td colspan="3"><input id="stdName" type="text" style="width:250px" disabled="disabled" value=""/>
	        <span id="input01"><input type="hidden" name="trabajador" id="stdID" value="<?php echo $cod_ficha;?>"/>	<br /> 
            <span class="textfieldRequiredMsg">Debe De Seleccionar Un Campo De la Lista.</span>
            <span class="textfieldInvalidFormatMsg">El Formato es Invalido</span> </span></td>
			<td>&nbsp; 
             <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
            <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" /> 
             <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>  </td>
      </tr>      
</table>
<hr />
<div id="listar">&nbsp;</div>
<div align="center"><br/>
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
        <input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')" 
               class="readon art-button">
        </span>&nbsp; 
		

        <input type="submit" name="procesar" id="procesar" hidden="hidden">
		<input type="text" name="reporte" id="reporte" hidden="hidden">
									
		<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0"
		onclick="{$('#reporte').val('pdf');$('#procesar').click();}" width="25px" title="imprimir a pdf">

		<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
		onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel"> 
  
</div>
</form>
<script type="text/javascript">
	r_cliente = $("#r_cliente").val(); 
	r_rol     = $("#r_rol").val(); 
	usuario   = $("#usuario").val();
	filtroValue = $("#paciFiltro").val();

    new Autocomplete("stdName", function() { 
        this.setValue = function(id) {
            document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
          return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
</script>