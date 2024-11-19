<?php
$Nmenu    =  303;
$metodo   = "actualizar";
$titulo   = " Huella De ".$leng['ficha']." ";
$archivo  = "ficha_huella";

require_once('autentificacion/aut_verifica_menu.php');
require_once('bd/class_mysql2.php');
require_once('sql/sql_report_t.php');

$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=$Nmenu&mod=".$_GET['mod']."";
$bd   = new DataBase();
$bd2  = new DataBase2();

	   $sql01 =	" SELECT ficha_huella.cedula, ficha_huella.huella,
                         ficha_huella.cod_us_ing, ficha_huella.fec_us_ing,
                         ficha_huella.cod_us_mod, ficha_huella.fec_us_mod
                    FROM ficha_huella ORDER BY 1 DESC ";
?>
<script language="JavaScript" type="text/javascript">
 	function validarCedula(metodo, incremento){
		var cedula     = document.getElementById("cedula"+incremento+"").value;
		var campo01 = 0;
	 if(cedula.length < 4) {
	var errorMessage    = 'Debe Ingresar minimo 4 Caracateres ';
	 campo01++;
     }
	if(campo01 == 0){
		validarHuella(metodo, incremento);
	 }else{
	 	alert(errorMessage);
	 }
}

 	function validarHuella(metodo, incremento){
	var huella     = document.getElementById("huella"+incremento+"").value;
	 var campo01 = 0;
	 if(huella.length < 4) {
	var errorMessage    = 'Debe Ingresar minimo 4 Caracateres ';
	 campo01++;
     }
	if(campo01 == 0){
	var archivo  = "sc_maestros/sc_ficha_validar_huella.php";
 		ajax=nuevoAjax();
			ajax.open("POST", archivo, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				// document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
				//window.location.href=""+href+"";
					if(ajax.responseText == 0){
						ValidarSubmit(metodo, incremento);
						}else{
					alert("Ya Existe esta Huella ("+huella+")");
					}
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+huella+"");
	 }else{
	 	alert(errorMessage);
	 }
}

	function ValidarSubmit(metodo, auto){
		var cedula      = document.getElementById("cedula"+auto+"").value;
		var cedula_old  = document.getElementById("cedula_old"+auto+"").value;
		var huella      = encodeURIComponent(document.getElementById("huella"+auto+"").value);
		var huella_old  = encodeURIComponent(document.getElementById("huella_old"+auto+"").value);
		var usuario     = document.getElementById("usuario").value;
		var archivo     = "sc_maestros/sc_ficha_huella.php";
   		var proced      = "p_ficha_huella";

	//	alert(huella);
		 var campo01 = 0;
		if(cedula.length < 4) {
		var errorMessage    = 'Debe Ingresar minimo 4 Caracateres ';
	 	campo01++;
     	}

	 	 if(huella.length < 4) {
		var errorMessage    = 'Debe Ingresar minimo 4 Caracateres ';
	 	campo01++;
     	}

		 if(campo01 == 0){
			ajax=nuevoAjax();
				ajax.open("POST", archivo, true);
				ajax.onreadystatechange=function()
				{
					if (ajax.readyState==4){
					document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
					if((metodo == "agregar") || (metodo == "eliminar")) {
					 ActualizarDet(cedula);
					}
					//window.location.href=""+href+"";
					}
				}
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("cedula="+cedula+"&cedula_old="+cedula_old+"&huella="+huella+"&huella_old="+huella_old+"&usuario="+usuario+"&href=''&metodo="+metodo+"&proced="+proced+"");
		}else{
			alert(errorMessage);
		}
	}

	function ActualizarDet(codigo){
		var archivo  = "ajax/Add_ficha_huella.php";
		ajax=nuevoAjax();
			ajax.open("POST", archivo, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				document.getElementById("listar").innerHTML = ajax.responseText;

				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+codigo+"");
	}

function Borrar(metodo, valor ){
	if (confirm("¿ Esta Seguro Eliminar Este Registro")) {
	var cedula     = document.getElementById("cedula"+valor+"").value;
	var huella     = encodeURIComponent(document.getElementById("huella"+valor+"").value);

	var archivo  = "sc_maestros/sc_ficha_huella.php";
    var proced = "p_ficha_huella";

		ajax=nuevoAjax();
			ajax.open("POST", archivo, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
				ActualizarDet(cedula);
				//window.location.href=""+href+"";
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("cedula="+cedula+"&cedula_old=''&huella="+huella+"&huella_old=''&usuario="+usuario+"&href=''&metodo="+metodo+"&proced="+proced+"");
	 }else{
	 	alert(errorMessage);
	 }
}

	function BuscarDatos(metodo){
		var cedula     = document.getElementById("cedula").value;
		var huella     = encodeURIComponent(document.getElementById("huella").value);
		var archivo  = "ajax/Bus_ficha_huella.php";
		ajax=nuevoAjax();
			ajax.open("POST", archivo, true);
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState==4){
				document.getElementById("listar").innerHTML = ajax.responseText;

				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("cedula="+cedula+"&huella="+huella+"&metodo="+metodo+"");
	}

	function huellaX(valor){
		document.getElementById("huella").value = valor;
	}
</script>
<form action="sc_maestros/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add">
<div align="center" class="etiqueta_title"> Huella <?php echo $leng['trabajador']?> </div>
<hr /><div id="Cont_mensaje"  class="mensaje"></div>
<div id="listar"><table width="99%" border="0" align="center"><tr class="fondo02">
			<td width="20%" id="input01_3"><span class="etiqueta"><?php echo $leng['ci']?>:</span><br /><input type="text" id="cedula" name="cedula"
              style="width:150px" /><input type="hidden" id="cedula_old" name="cedula_old"/></td>
            <td width="5%" class="etiqueta"><img src="imagenes/buscar.bmp" onclick="BuscarDatos('cedula')" width="22px" height="22px"  class="imgLink"/></td>

            <td width="35%" class="etiqueta" id="input02_3"> Huella:<br /><input type="text" id="huella" name="huella"
                          style="width:350px" maxlength="64"/><input type="hidden" id="huella_old" name="huella_old"/></td>
			      <td width="5%" class="etiqueta"><img src="imagenes/buscar.bmp" onclick="BuscarDatos('huella')" width="22px" height="22px" class="imgLink" /></td>
				  <td width="10%"><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="button"  name="submit" id="submit" value="Ingresar"  class="readon art-button"
                           onclick=" validarCedula('agregar','')"/>
             </span></td> 
			  <td width="25%" class="etiqueta">Huellas Nuevas: <br /><select name="huella_new" id="huella_new" style="width:180px;" onchange="huellaX(this.value)"><option value="TODOS">TODOS</option><?php

		$sql_ch = "	SELECT v_ch_huella.huella, v_ch_huella.fecha FROM v_ch_huella ORDER BY fecha DESC ";

		$query_ch  = $bd2->consultar($sql_ch) or die ("error ch");
		while ($datos_ch=$bd2->obtener_fila($query_ch,0)){

					$huella = $datos_ch[0];
					$sql02 = " SELECT COUNT(ficha_huella.huella)
								 FROM ficha_huella
								WHERE ficha_huella.huella = '$huella' ";
					$query02 = $bd->consultar($sql02);
					$row02=$bd->obtener_fila($query02,0);
					if($row02[0] == 0){
						echo '<option value="'.$datos_ch[0].'">'.$datos_ch[1].'('.$datos_ch[0].')</option>';
						}
			   }?></select></td>
 		</tr><?php
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

			$modificar = 	 "'modificar', '".$i."'";
			$borrar    = 	 "'eliminar', '".$i."'";
        echo '<tr class="'.$fondo.'">
                  <td colspan="2"><input type="text" id="cedula'.$i.'" style="width:150px" maxlength="20"
				             value="'.$datos['cedula'].'"/><input type="hidden" id="cedula_old'.$i.'"
				             value="'.$datos['cedula'].'"/>
				  </td>
                  <td td colspan="3"><input type="text" id="huella'.$i.'" style="width:450px" maxlength="64"
				             value="'.$datos['huella'].'"/><input type="hidden" id="huella_old'.$i.'" maxlength="64" value="'.$datos['huella'].'"/></td>
		  </td><td align="center" colspan="2"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null" onclick="ValidarSubmit('.$modificar.')" class="imgLink"/><img src="imagenes/borrar.bmp" alt="Detalle" title="Borrar Registro" width="20px" height="20px" border="null"
			   onclick="Borrar('.$borrar.')" class="imgLink" /></td>
	</tr>';
        } ?></table></div>
    <div align="center">
    <input name="metodo" type="hidden"  value="<?php echo $metodo;?>" />
 	<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>"/>
	<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo;?>"/>
	<input type="hidden"  id="i" value="<?php echo $i;?>"/>
    </div>
<br />
<br />
</form>
</body>
</html>
