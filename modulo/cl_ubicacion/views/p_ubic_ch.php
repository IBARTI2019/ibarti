<?php
	   $sql01 =	"SELECT clientes_ub_ch.cod_capta_huella
                   FROM clientes_ub_ch
                  WHERE clientes_ub_ch.cod_cl_ubicacion = '$codigo'";
                ?>
<script language="javascript">

 	function validarCampo(auto, metodo){
		var cod_capta     = document.getElementById("codigo_capta"+auto+"").value;
	 	var errorMessage    = 'Debe Ingresar minimo 4 Caracateres ';
	 var campo01 = 0;

	 if(cod_capta.length < 4) {
	 campo01++;
     }

	if(campo01 == 0){
		  //var href = "inicio.php?area=maestros/Cons_Servicio_Tipo";
	var valor  = "scripts/sc_cl_ubic_validar_ch.php";
 		ajax=nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				// document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
				//window.location.href=""+href+"";
					if(ajax.responseText == 0){
					ValidarSubmit(auto, metodo);
					}else{
					alert("Ya Existe este Registro ("+cod_capta+")");
					}
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+cod_capta+"");
	 }else{
	 	alert(errorMessage);
	 }
}

	function ValidarSubmit(auto, metodo){
		var cod_ubic      = document.getElementById("codigo_ubic").value;
		var usuario       = document.getElementById("usuario").value;
		var cod_capta     = document.getElementById("codigo_capta"+auto+"").value;
		var cod_capta_old = document.getElementById("codigo_capta_old"+auto+"").value;

		var valor  = "scripts/sc_cl_ubic_ch.php";
   		var proced = "p_cl_ubic_captahuella";
		ajax=nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
				if((metodo == "agregar") || (metodo == "eliminar")) {
				ActualizarDet(cod_ubic);
				}
				//window.location.href=""+href+"";
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+cod_ubic+"&cod_capta="+cod_capta+"&cod_capta_old="+cod_capta_old+"&usuario="+usuario+"&href=''&metodo="+metodo+"&proced="+proced+"");
}

function Borrar(auto, metodo){
	if (confirm("� Esta Seguro Eliminar Este Registro")) {
	var cod_ubic      = document.getElementById("codigo_ubic").value;
	var cod_capta     = document.getElementById("codigo_capta"+auto+"").value;
	var cod_capta_old = "";
	var ususario = "";

	var valor  = "scripts/sc_cl_ubic_ch.php";
    var proced = "p_cl_ubic_captahuella";

		ajax=nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
				ActualizarDet(cod_ubic);
				//window.location.href=""+href+"";
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+cod_ubic+"&cod_capta="+cod_capta+"&cod_capta_old="+cod_capta_old+"&usuario="+usuario+"&href=''&metodo="+metodo+"&proced="+proced+"");
	 }else{
	 	alert(errorMessage);
	 }
}

	function ActualizarDet(codigo){
		var valor  = "ajax/Add_cl_ubic_ch.php";
		ajax=nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				document.getElementById("Contenedor02").innerHTML = ajax.responseText;

				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+codigo+"");
}
</script>
<div align="center" class="etiqueta_title"> CAPTA HUELLA </div>
<hr />
<div id="Cont_mensaje"  class="mensaje"></div>
<div id="Contenedor02">
	<table width="70%" border="0" align="center">
		<tr class="fondo01">
			<th width="20%" class="etiqueta">Codigo Ubicacion</th>
			<th width="60%" class="etiqueta">Codigo Capta Huella</th>
		    <th width="10%"><img src="imagenes/loading2.gif" alt="Agregar Registro" width="40" height="40"
			                     title="Agregar Registro" border="null" class="imgLink"/></th>
                  <th width="10%">&nbsp;</th>
 		</tr>
		<tr class="fondo02">
			<td id="input01_3"><input type="text" id="codigo_ubic" name="codigo_ubic" style="width:90px"
                                     value="<?php echo $codigo;?>"   readonly="readonly" /></td>
			<td id="input02_3"><input type="text" id="codigo_capta" name="codigo_capta" style="width:250px" maxlength="20"/>
            <input type="hidden" id="codigo_capta_old" name="codigo_capta_old"/></td>
			<td><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="button"  name="submit" id="submit" value="Actualizar"  class="readon art-button"
                           onclick=" validarCampo('', 'agregar')"/>
             </span></td>
 		</tr>
    <?php
        $query = $bd->consultar($sql01);
        $i =0;
        $valor = 0;
  		while($datos=$bd->obtener_fila($query,0)){
		$i++;
			if ($valor == 0){
				$fondo = 'fondo01';
				$valor = 1;
			}else{
				 $fondo = 'fonddo02';
				 $valor = 0;
			}
			$modificar = 	 "'".$i."', 'modificar'";
			$borrar    = 	 "'".$i."', 'eliminar' ";
        echo '<tr class="'.$fondo.'">
                  <td><input type="text" id="codigo_ubic'.$i.'" style="width:90px"  value="'.$codigo.'"/>
				  </td>
                  <td><input type="text" id="codigo_capta'.$i.'" style="width:250px" maxlength="20"
				             value="'.$datos['cod_capta_huella'].'"/><input type="hidden" id="codigo_capta_old'.$i.'"
				             value="'.$datos['cod_capta_huella'].'"/></td>
		  </td><td align="center">
		  <img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="25" height="25" border="null"
			   onclick="validarCampo('.$modificar.')" class="imgLink"/>
		  <img src="imagenes/borrar.bmp" alt="Detalle" title="Borrar Registro" width="25" height="25" border="null"
			   onclick="Borrar('.$borrar.')" class="imgLink" />
		  </td>
	</tr>';
        } ?>
	</table>
    </div>
    <div align="center">
    <input name="metodo" type="hidden"  value="<?php echo $metodo;?>" />
 		<input type="hidden" name="usuario"  value="<?php echo $usuario;?>"/>

	<input type="hidden" name="codigo" value="<?php echo $codigo;?>"/>
	<input type="hidden"  id="i" value="<?php echo $i;?>"/>
    </div>
<br />
<br />
<script language="javascript" type="text/javascript">
var input02_3 = new Spry.Widget.ValidationTextField("codigo_capta", "none", {minChars:4, validateOn:["blur", "change"], isRequired:false});
	// VALIDAR CAMPOS
	var incX = document.getElementById("i").value;
	var 	inc =	++incX;

	for (i = 1; i < inc; i++){
	var input02_3N = new Spry.Widget.ValidationTextField("codigo_capta"+i+"", "none", {minChars:4, validateOn:["blur", "change"]});
	}
</script>
