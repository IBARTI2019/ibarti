<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	var fecha_desde = document.getElementById("fecha_desde").value;

	var error = 0;
    var errorMessage = ' ';

	 if(cliente=='') {
	 var error = error+1;
	 var errorMessage = ' Debe Selecionar Un Cliente';
     }

	 if( fechaValida(fecha_desde) !=  true ){
    var errorMessage = ' Campo De Fecha Incorrecta ';
	 var error = error+1;
	}

	if(error == 0){
	var contenido = "listar";
	}else{
		  alert(errorMessage);
	}
}
function Ingresar(){
	var Nmenu         = document.getElementById("Nmenu").value;
    var mod           = document.getElementById("mod").value;
	var codigo        = document.getElementById("codigo").value;
	var usuario       = document.getElementById("usuario").value;
	var proced        = document.getElementById("proced2").value;
	var observ        = document.getElementById("observ").value;
	var nov_status    = document.getElementById("nov_status").value;
	var nov_fecha     = document.getElementById("nov_fecha").value;
	var nov_hora      = document.getElementById("nov_hora").value;


	var campo01 = 1;
    var errorMessage = 'Debe Seleccionar Todo Los Campos';

     if(codigo=='') {
     var errorMessage = 'Codigo Invalido';
	 var campo01 = campo01+1;
	}

     if(nov_status=='') {
	 var campo01 = campo01+1;
	}
	if(campo01 == 1){
		var valor = "scripts/sc_novedades_det.php";
		ajax=nuevoAjax();
	ajax.open("POST", valor, true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
		document.getElementById("Contendor01").innerHTML = ajax.responseText;
		 Novedades_Det();
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
ajax.send("codigo="+codigo+"&nov_status="+nov_status+"&observacion="+observ+"&fecha="+nov_fecha+"&hora="+nov_hora+"&href=&usuario="+usuario+"&proced="+proced+"&metodo=agregar");
	 }else{
	alert(errorMessage);
	 }
}
	function Novedades_Det(){

		var Nmenu         = document.getElementById("Nmenu").value;
    	var mod           = document.getElementById("mod").value;
    	var cod           = document.getElementById("codigo").value;
		var usuario       = document.getElementById("usuario").value;
		var proced2        = document.getElementById("proced2").value;

		var valor = "ajax/Add_novedades_det.php";
		ajax=nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4){
				  document.getElementById("contenedor_listar").innerHTML = ajax.responseText;
				}
			}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("Nmenu="+Nmenu+"&mod="+mod+"&cod="+cod+"&usuario="+usuario+"&proced2="+proced2+"");
}
</script>
<?php
$Nmenu = 444;
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');
$mod = $_GET['mod'];
$titulo = "NOVEDADES";
$archivo = "novedades";
$archivo2 = $archivo."2";

$metodo  =$_GET['metodo'];
$href = "../inicio.php?area=formularios/Cons_$archivo2&Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');

$proced      = "p_nov_proc";
$proced2     = "p_nov_proc_det";

if($metodo == 'modificar'){
   $titulo = "MODIFICAR $titulo";
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
	$sql = " SELECT nov_procesos.cod_novedad,
					novedades.descripcion AS novedad, nov_procesos.cod_cliente,
					clientes.nombre AS cliente, nov_procesos.cod_ubicacion,
					clientes_ubicacion.descripcion AS ubicacion,
					nov_procesos.cod_ficha, ficha.cedula, ficha.nombres AS trabajador,
					nov_procesos.observacion, nov_procesos.repuesta,
					nov_procesos.fec_us_ing, nov_procesos.fec_us_mod,
					CONCAT(men_usuarios.apellido,' ', men_usuarios.nombre) AS us_mod,
					nov_procesos.cod_nov_status, nov_status.descripcion AS status
			   FROM nov_procesos LEFT JOIN men_usuarios ON nov_procesos.cod_us_mod = men_usuarios.codigo ,
			        clientes , novedades , clientes_ubicacion , nov_status, ficha
			  WHERE nov_procesos.codigo = '$codigo'
			    AND nov_procesos.cod_cliente = clientes.codigo
				AND nov_procesos.cod_novedad = novedades.codigo
				AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
				AND nov_procesos.cod_nov_status = nov_status.codigo
				AND nov_procesos.cod_ficha = ficha.cod_ficha ";
	$query = $bd->consultar($sql);
	$result = $bd->obtener_fila($query,0);

    $fecha_sist   = conversion($result['fec_us_ing']);
   	$us_mod        = $result['us_mod'];
	$fec_us_mod    = $result['fec_us_mod'];
	$cod_ficha    = $result['cod_ficha'];
	$trabajador   = $result['cod_ficha'].' - '.' ('.$result['cedula'].') '.$result['trabajador'];
    $cod_novedad  = $result['cod_novedad'];
    $novedad      = $result['novedad'];
    $cod_cliente  = $result['cod_cliente'];
    $cliente      = $result['cliente'];
    $cod_ubicacion = $result['cod_ubicacion'];
    $ubicacion    = $result['ubicacion'];
    $observacion  = $result['observacion'];
    $respuesta    = $result['repuesta'];
    $cod_status   = $result['cod_nov_status'];
    $status       = $result['status'];

	}else{
	$fecha_sist  = conversion($date);

	$titulo = "AGREGAR $titulo";
	$codigo      = '';
    $fecha       = '';
	$cod_ficha    = '';
	$trabajador   = '';
    $cod_novedad  = '';
    $novedad = 'Seleccione...';
    $cod_cliente = '';
    $cliente = 'Seleccione...';
    $cod_ubicacion = '';
    $ubicacion = 'Seleccione...';
    $observacion = '';
    $respuesta = '';
    $cod_status = '';
    $status = 'Seleccione...';
   	$us_mod        = '';
	$fec_us_mod    = '';
	}

	$SQL_PAG = "SELECT nov_procesos_det.observacion AS observacion_det, nov_procesos_det.cod_us_ing,
	                   CONCAT(men_usuarios.apellido, men_usuarios.nombre) AS usuarios_det, nov_procesos_det.fec_us_ing AS fecha_det,
                       nov_procesos_det.hora AS hora_det, nov_procesos_det.cod_nov_status AS cod_nov_status_det,
					   nov_status.descripcion AS nov_status_det
                  FROM nov_procesos_det LEFT JOIN men_usuarios ON nov_procesos_det.cod_us_ing = men_usuarios.codigo, nov_status
                 WHERE nov_procesos_det.cod_nov_proc = '$codigo'
                   AND nov_procesos_det.cod_nov_status = nov_status.codigo
			  ORDER BY nov_procesos_det.fec_us_ing DESC";
?>
<form action="scripts/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add">
     <table width="100%" align="center">
         <tr valign="top">
		     <td height="23" colspan="4" class="etiqueta_title" align="center"><?php echo $titulo;?></td>
         </tr>
         <tr>
    	       <td height="8" colspan="4" align="center"><hr></td>
     	</tr>

   <tr>
    <td class="etiqueta" width="15%">Codigo:</td>
    <td width="35%"><input type="text" size="10" name="codigo" id="codigo" value="<?php echo $codigo;?>" readonly="readonly" /></td>
      <td class="etiqueta" width="15%">Status:</td>
      <td width="35%"><input type="text" id="status" value="<?php echo $status;?>"/>
        <input type="hidden" size="26" name="status"  value="<?php echo $cod_status;?>"/></td>
     </tr>
    <tr>
      <td class="etiqueta">Fecha De Sistema:</td>
      <td><input type="text" size="10" readonly="readonly" value="<?php echo $fecha_sist;?>"></td>
      <td class="etiqueta">Usuario Mod.:</td>
      <td><?php echo $us_mod;?></td>
    </tr>
    <tr>
    <td colspan="2"></td>
        <td class="etiqueta">Fecha Ultima Mod.:</td>
      <td><input type="text" size="10" readonly="readonly" value="<?php echo $fec_us_mod;?>"></td>
	 </tr>

    <tr>
      <td class="etiqueta">Novedad: </td>
      	<td id="select01"><select name="novedad" style="width:200px" required>
							<option value="<?php echo $cod_novedad;?>"><?php echo $novedad;?></option>
          <?php     $query = $bd->consultar($sql_nov_novedad_resp);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[1];?></option>
          <?php } ?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    	<td colspan="2">&nbsp;</td>
    </tr>

	<tr>
		<td class="etiqueta">Filtro:</td>
		<td>
         	<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:200px">
				<option value="">Seleccione...</option>
				<option value="codigo"> C&oacute;digo </option>
				<option value="cedula"><?php echo $leng["ci"];?></option>
				<option value="trabajador"><?php echo $leng["trabajador"];?></option>
                <option value="apellidos"> Apellido </option>
                <option value="nombres"> Nombre </option></select><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>

        <td class="etiqueta"><?php echo $leng["trabajador"];?>:</td>
      <td>
		  <input  id="stdName" type="text" style="width:250px" disabled="disabled"  value="<?php echo $trabajador;?>" required/>
	        <span id="input01"><input type="hidden" name="trabajador" id="stdID" value="<?php echo $cod_ficha;?>" required/><br />
            <span class="textfieldRequiredMsg">Debe De Seleccionar Un Campo De la Lista.</span>
            <span class="textfieldInvalidFormatMsg">El Formato es Invalido</span> </span></td>

    </tr>

    <tr>
      <td class="etiqueta"><?php echo $leng["cliente"];?>:</td>
      	<td id="select02"><select name="cliente" id="cliente" style="width:200px"
    	                          onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion.php', 'select03')" required>
                                  <option value="<?php echo $cod_cliente;?>"><?php echo $cliente;?></option>
          <?php

		            $query = $bd->consultar($sql_cliente);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td class="etiqueta">Ubicaci&oacute;n:</td>
      	<td id="select03"><select name="ubicacion" style="width:200px" required>
                                  <option value="<?php echo $cod_ubicacion;?>"><?php echo $ubicacion;?></option>
          <?php
			    	$sql   = "SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
                                           FROM clientes_ubicacion
						                  WHERE clientes_ubicacion.cod_cliente = '$cliente'
						                    AND clientes_ubicacion.`status` = 'T'
						                  ORDER BY 2 ASC";
		            $query = $bd->consultar($sql);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
     </tr>
    <tr>
      <td class="etiqueta">Observacion:</td>
      <td id="textarea01"><textarea  name="observacion" cols="45" rows="3" readonly="readonly"><?php echo $observacion;?></textarea>
        <span id="Counterror_mess1" class="texto">&nbsp;</span><br />
        <span class="textareaRequiredMsg">El Campo es Requerido.</span>
        <span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 500.</span></td>

      <td class="etiqueta">Repuesta General:</td>
      <td id="textarea02"><textarea  name="repuesta" cols="45" rows="3"><?php echo $respuesta;?></textarea>
        <span id="Counterror_mess2" class="texto">&nbsp;</span><br />
        <span class="textareaRequiredMsg">El Campo es Requerido.</span>
        <span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 500.</span></td>
    </tr>
     <tr>
        <td height="8" colspan="4" align="center"><hr></td>
     </tr>
  </table>
  <div align="center"><span class="art-button-wrapper">
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
		    <input name="metodo" type="hidden" value="<?php echo $metodo;?>" />
			<input name="proced" type="hidden" value="<?php echo $proced;?>" />
            <input name="href" type="hidden" value="<?php echo $href;?>"/>
            <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></div>
</form><div id="Contendor01" class="mensaje"></div>
<div id="listar"><form id="asistencia_01" name="asistencia_01"  action="scripts/sc_asistencia.php"
                       method="post"><div id="contenedor_listar"><table width="100%" border="0" align="center">
		<tr class="fondo00">
            <th width="8%" class="etiqueta">Fecha</th>
			<th width="8%" class="etiqueta">Hora</th>
            <th width="25%" class="etiqueta">Usuario</th>
			<th width="38%" class="etiqueta">Observacion</th>
   			<th width="15%" class="etiqueta">Status</th>
			<th width="6%">&nbsp;</th>
		</tr><?php	echo '<tr class="fondo01">
                        <td><input type="text" name="nov_fecha" id="nov_fecha" size="8" value="'.$date.'"/></td>
                        <td><input type="text" name="nov_hora" id="nov_hora" size="8" value="'.date("H:i:s").'"/></td>
                        <td><input type="text" name="nov_usuario" id="nov_usuario" size="28"
                                   value="'.$_SESSION['usuarioA'].'&nbsp;'.$_SESSION['usuarioN'].'"/></td>
                        <td><textarea  name="observ" id="observ" cols="48" rows="2"></textarea></td>
                        <td><select name="nov_status" id="nov_status" style="width:120px">
                                    <option value="">Selec...</option>';
                $sql   = "SELECT codigo, descripcion FROM nov_status, control
                           WHERE status =  'T'
                             AND nov_status.codigo <> control.novedad";
                $query04 = $bd->consultar($sql);
                   while($row04=$bd->obtener_fila($query04,0)){	echo '<option value="'.$row04[0].'">'.$row04[1].'</option>';
                   }echo'</select></td>
        '; ?>
			  <td align="center"><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                 <input type="button" name="submit" id="submit" value="Ingresar" class="readon art-button" onclick="Ingresar()" />
                </span></td>
		</tr><?php
	   $query = $bd->consultar($SQL_PAG);
		$valor = 1;
		$i     = 0;
		while ($datos=$bd->obtener_fila($query,0)){
		$i++;
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}

		 $fechaX  = conversion($fecha);
		 $campo_id = $datos[0];
	echo '<tr class="fondo01">
		                    <td><input type="text" name="nov_fecha" id="nov_fecha" size="8" value="'.$datos["fecha_det"].'"/></td>
							<td><input type="text" name="nov_hora" id="nov_hora" size="8" value="'.$datos["hora_det"].'"/></td>
							<td><input type="text" name="nov_usuario" id="nov_usuario" size="28"
							           value="'.$datos["usuarios_det"].'"/></td>
							<td><textarea  name="observ" id="observ" cols="48" rows="2" readonly="readonly">'.$datos["observacion_det"].'</textarea></td>
			  			    <td><select name="nov_status" id="nov_status" style="width:120px">
							            <option value="'.$datos["cod_nov_status_det"].'">'.$datos["nov_status_det"].'</option>
                                 </select></td>
			  <td align="center">&nbsp;</td></tr>';
        } ?><tr><td colspan="5"><input type="hidden" id="Nmenu" name="Nmenu" value="<?php echo $Nmenu;?>" /><input type="hidden" id="mod" name="mod" value="<?php echo $mod;?>" /><input type="hidden" name="href"  value="../inicio.php?area=<?php echo $href;?>"/><input type="hidden" name="metodo" value="<?php echo $metodo;?>"/><input type="hidden"  id="i" value="<?php echo $i;?>"/><input type="hidden" name="proced2" id="proced2" value="<?php echo $proced2;?>"/><input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/><input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/><input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></td></tr>
	</table></div></form></div>
</body>
</html>
<script type="text/javascript">
var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
var select02 = new Spry.Widget.ValidationSelect("select02", {validateOn:["blur", "change"]});

var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {maxChars:500, validateOn:["blur", "change"],
 counterType:"chars_count", counterId:"Counterror_mess1", useCharacterMasking:false});
var textarea02 = new Spry.Widget.ValidationTextarea("textarea02", {maxChars:500, validateOn:["blur", "change"],
 counterType:"chars_count", counterId:"Counterror_mess2", useCharacterMasking:false, isRequired:false});

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
