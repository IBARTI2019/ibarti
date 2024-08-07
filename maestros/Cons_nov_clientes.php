<?php
$Nmenu    =  3401;
$metodo   = "actualizar";
$titulo   = " Novedades ".$leng['cliente']."";
$archivo  = "nov_clientes";
require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=$Nmenu&mod=".$_GET['mod']."";
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">
	function marcar(source) 
	{
		
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	var cliente       = document.getElementById("cliente").value;
	var ubicacion     = document.getElementById("ubicacion").value;
	var nov_clasif    = document.getElementById("nov_clasif").value;
	var nov_tipo      = document.getElementById("nov_tipo").value;

	var campo01 = 1;
    var errorMessage = 'Debe Seleccionar Todo Los Campos';

     if(cliente == '') {
     var errorMessage = 'Codigo Invalido';
	 var campo01 = campo01+1;
	}

     if((ubicacion == '') ||  (ubicacion == 'TODOS')) {
	 var campo01 = campo01+1;
	}
     if(nov_clasif == '') {
	 var campo01 = campo01+1;
	}

     if(nov_tipo == '') {
	 var campo01 = campo01+1;
	}

	if(campo01 == 1){
		var valor = "ajax/nov_clientes.php";
		ajax=nuevoAjax();
	ajax.open("POST", valor, true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
		document.getElementById("Contenedor01").innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("cliente="+cliente+"&ubicacion="+ubicacion+"&nov_clasif="+nov_clasif+"&nov_tipo="+nov_tipo+"");
	 }else{
	toastr.info(errorMessage);
	 }
}

function Validar01(){
 var valorX = "XXX";
}
function ejecutar(valor){
	llenar_nov_tipo(valor);
	Add_filtroX();
	
}
function llenar_nov_tipo(clasificacion){
	
	var parametros = { 'clasificacion':clasificacion,'inicial':'TODOS'};
		$.ajax({
		data:  parametros,
		url:   'ajax/Add_novedades_tipo.php',
		type:  'post',
		success:  function (response) {
			$('#nov_tipo').html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}

</script>
<form action="sc_maestros/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add">
  <fieldset class="fieldset">
  <legend><?php echo $titulo;?></legend>
     <table width="90%" align="center">
   <tr>
   <tr>
           <td class="etiqueta"><?php echo $leng['cliente']?>:</td>
			<td><select name="cliente" id="cliente" style="width:200px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'F', '200')">
					    <option value="TODOS">TODOS</option>
					<?php
	   			$query01 = $bd->consultar($sql_cliente);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			<td class="etiqueta"><?php echo $leng['ubicacion']?>: </td>
			<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:200px;">
					                        <option value="TODOS">TODOS</option>
                                    </select></td>
</tr>
<tr>
      <td class="etiqueta">Novedades Clasificacion:</td>
      	<td id="select01"><select id="nov_clasif" name="nov_clasif" style="width:200px" onchange="ejecutar(this.value)">
							      <option value="TODOS">TODOS</option>
          <?php
		   $query = $bd->consultar($sql_nov_clasif);
		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>

      <td class="etiqueta">Novedades Tipo :</td>
      	<td id="select01"><select id="nov_tipo" name="nov_tipo" style="width:200px" onchange="Add_filtroX()">
							      <option value="TODOS">TODOS</option>
          <?php
	   $query = $bd->consultar($sql_nov_tipo);
		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	</tr>
	 	<tr>
            <td height="8" colspan="4" align="center"><hr></td>
     </tr>
	<tr>
		<td colspan="4" id="Contenedor01">&nbsp; </td>
	</tr>
	 	<tr>
            <td height="8" colspan="4" align="center"><hr></td>
         </tr>
  </table>
  <div align="center">
<span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />
                </span>&nbsp;
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
                </span>&nbsp;
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
                </span>
		    <input name="metodo" id="metodo" type="hidden"  value="<?php echo $metodo;?>" />
            <input name="tabla" id="tabla" type="hidden"  value="<?php echo $tabla;?>" />
            <input name="usuario" id="usuario" type="hidden"  value="<?php echo $usuario;?>" />
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>
  </div>
</fieldset>
</form>
</body>
</html>
