<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php 
	$Nmenu   = '535'; 
    $mod     =  $_GET['mod'];
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report.php');
	$bd = new DataBase();
	
	$archivo2 = "reportes/rp_pl_rotacion_trab_det";
    $archivo  = "$archivo2.php?Nmenu=$Nmenu&mod=$mod";
	$titulo  = " PLANTILLA DE ROTACION TRABAJADOR ";

	$titulo      = "REPORTE $titulo";
	$codigo      = '';		
?>
<script language="JavaScript" type="text/javascript">

function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //


	var rol         = document.getElementById("rol").value; 
	var region      = document.getElementById("region").value; 
	var estado      = document.getElementById("estado").value; 
    var contrato    = document.getElementById("contrato").value; 			
    var cargo       = document.getElementById("cargo").value; 			
    var cliente     = document.getElementById("cliente").value;
    var ubicacion   = document.getElementById("ubicacion").value; 			
	var rotacion    = document.getElementById("rotacion").value; 
	var trabajador  = document.getElementById("stdID").value; 

	var error = 0; 
    var errorMessage = ' ';

     if(rol == '') {
	 var error = error+1; 
	  errorMessage = errorMessage + ' \n Debe Seleccionar un Rol ';
	}  
	
     if(cliente == '') {
	 var error = error+1; 
	  errorMessage = errorMessage + ' \n Debe Seleccionar un cliente ';
	}  		
	if(error == 0){	 
		 
		var contenido = "listar";
 
		ajax=nuevoAjax();
			ajax.open("POST", "ajax_rp/Add_pl_rotacion_trab.php", true);
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
			ajax.send("rol="+rol+"&region="+region+"&estado="+estado+"&contrato="+contrato+"&cargo="+cargo+"&cliente="+cliente+"&ubicacion="+ubicacion+"&rotacion="+rotacion+"&trabajador="+trabajador+"");	
	
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
		 <td width="10%">Rol: </td>
		 <td width="14%"><select name="rol" id="rol" style="width:120px;" required>
					<?php 
					echo $select_rol;
				$query01 = $bd->consultar($sql_rol);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>

		    <td width="10%">Region: </td>
			<td width="14%"><select name="region" id="region" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 
	   			$query01 = $bd->consultar($sql_region);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
             <td width="10%">Estado: </td>
			<td width="14%"><select name="estado" id="estado" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php 
	   			$query01 = $bd->consultar($sql_estado);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>

        <td width="24%"></td> 
      <td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td> 
        </tr>
        <tr> 
		 <td>Contrato: </td>
			<td><select name="contrato" id="contrato" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 
	   			$query01 = $bd->consultar($sql_contracto);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
		 <td>Cargo: </td>
			<td><select name="cargo" id="cargo" style="width:120px;">
					<option value="TODOS">TODOS</option> 
					<?php 
	   			$query01 = $bd->consultar($sql_cargo);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
			<td>Cliente:</td>
			<td><select name="cliente" id="cliente" style="width:120px;" required
                        onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')">
					  
					<?php 
					echo $select_cl;
	   			$query01 = $bd->consultar($sql_cliente);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			   }?></select></td>
			<td>Ubicacion: </td>
			<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
					                        <option value="TODOS">TODOS</option> 
                                    </select></td> 
				<td>&nbsp;</td>
      </tr> 
        <tr> 
	 <td>Rotacion: </td>
			<td><select name="rotacion" id="rotacion" style="width:120px;">
				   	    <option value="TODOS">TODOS</option>
					<?php 
	   			$query01 = $bd->consultar($sql_rotacion);		
		 		while($row01=$bd->obtener_fila($query01,0)){							   							
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
			 	}?></select></td>

 <td>Filtro Trab.:</td>	
		<td id="select01">
			<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
				<option value="TODOS"> TODOS</option>
				<option value="codigo"> C&oacute;digo </option>
				<option value="cedula"> C&eacute;dula </option>
				<option value="trabajador"> Trabajador </option>
                <option value="nombres"> Nombre </option>
                <option value="apellidos"> Apellido </option>                
		</select></td>
          <td>Trabajador:</td> 
      <td colspan="3" ><input  id="stdName" type="text" style="width:220px" disabled="disabled" />
	      <input type="hidden" name="trabajador" id="stdID" value=""/>
           &nbsp; <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
            <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
            <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>    </td>
        
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