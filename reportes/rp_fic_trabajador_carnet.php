<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = 522;
$mod     =  $_GET['mod'];
$titulo  = " REPORTE TRABAJADORES CARNET ";
$archivo = "reportes/rp_fic_trabajador_carnet_det.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">

function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var rol         = $( "#rol").val();
	var region      = $( "#region").val();
	var estado      = $( "#estado").val();
	var cliente      = $( "#cliente").val();
	var ubicacion     = $( "#ubicacion").val();
	var contrato    = $( "#contrato").val();
	var carnet_vencido = $( "#carnet_vencido").val();
	var foto        = $( "#foto").val();
	var trabajador  = $( "#stdID").val();


	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();

	var error = 0;
    var errorMessage = ' ';

     if(rol == '') {
	 var error = error+1;
	  errorMessage = errorMessage + ' \n Debe Seleccionar un Rol ';
	}

	if(error == 0){
	  var parametros = {
						"rol" : rol,
						"region" : region,
						"estado" : estado,
						"cliente" : cliente,
						"ubicacion" : ubicacion,
						"contrato" : contrato,
						"carnet_vencido" : carnet_vencido,
						"foto" : foto,
						"trabajador":trabajador,
						"Nmenu" : Nmenu,
						"mod" : mod,
						"archivo": archivo
				};
				$.ajax({
						data:  parametros,
						url:   'ajax_rp/Add_fic_trabajador_carnet.php',
						type:  'post',
						beforeSend: function () {
							 $("#img_actualizar").remove();
							 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
							},
						success:  function (response) {
								$("#listar").html(response);
								$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
						},

					error: function (xhr, ajaxOptions, thrownError) {
       				alert(xhr.status);
        			alert(thrownError);}

				});

		}else{
			alert(errorMessage);
		}
}

</script>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank" enctype="multipart/form-data">
<hr /><table width="100%" class="etiqueta">
		<tr><td width="10%"><?php echo $leng['rol']?>:</td>
		 <td width="14%"><select name="rol" id="rol" style="width:120px;" required>
					<?php
					echo $select_rol;
		   			$query01 = $bd->consultar($sql_rol);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
        <td width="10%"><?php echo $leng['region']?>:</td>
		 <td width="14%"><select name="region" id="region" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
	   			$query01 = $bd->consultar($sql_region);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			<td width="10%"><?php echo $leng['estado']?>: </td>
			<td width="14%"><select name="estado" id="estado" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
	   			$query01 = $bd->consultar($sql_estado);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			         <td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
                                        onclick=" Add_filtroX()" ></td>
</tr>
<tr>
			   <td width="10%"><?php echo $leng['contrato']?>: </td>
			<td width="14%"><select name="contrato" id="contrato" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
	 			$query01 = $bd->consultar($sql_contrato);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
		 <td>Carnet Vencido:</td>
		<td><select name="carnet_vencido" id="carnet_vencido" style="width:120px;">
     		         <option value="S">SI</option>
                    <option value="TODOS"> TODOS</option>
            </select></td>
			<td>Fotos:</td>
		<td><select name="foto" id="foto" style="width:120px;">
     		        <option value="TODOS"> TODOS</option>
                    <option value="S">SI</option>
                    <option value="N">NO</option>

            </select></td>
			<td><?php echo $leng['cliente']?>:</td>
			<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')" required>

					<?php
                echo $select_cl;
	   			$query01 = $bd->consultar($sql_cliente);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			</tr>
			<tr>
			<td>Ubicacion: </td>
			<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
					                        <option value="TODOS">TODOS</option>
                                    </select></td>
           <td>Filtro <?php echo $leng['trab']?>.:</td>
		<td id="select01">
			<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
				<option value="TODOS"> TODOS</option>
				<option value="codigo"> <?php echo $leng['ficha']?> </option>
				<option value="cedula"><?php echo $leng['ci']?></option>
				<option value="trabajador"><?php echo $leng['trabajador']?></option>
                <option value="nombres"> Nombre </option>
                <option value="apellidos"> Apellido </option>
		</select></td>
<td><?php echo $leng['trabajador']?>:</td>
      <td colspan="4"><input  id="stdName" type="text" style="width:300px" disabled="disabled" />
	      <input type="hidden" name="trabajador" id="stdID" value=""/></td>
    <td>&nbsp;</td>
  <td>&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
            <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
            <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
            <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></td>
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

        <img class="imgLink" id=img_pantalla src="imagenes/pantalla.png" border="0" 
        onclick="{$('#reporte').val('pantalla');$('#procesar').click();}" width="25px" title="visualizar en pantalla">

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
