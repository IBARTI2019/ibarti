<script type="text/javascript">
function Pdf(){
 	$('#pdf').attr('action', "reportes/rp_prod_movimiento.php");
	$('#pdf').submit();
	}
</script>
<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu = $_GET['Nmenu'];
$titulo = "CONTROL MOVIMIENTO DE INVENTARIO";
$metodo  =$_GET['metodo'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');
$proced      = "p_prod_movimiento";
if($metodo == 'modificar'){
   $titulo = "MODIFICAR $titulo";
	$codigo = $_GET['codigo'];
	$bd = new DataBase();

	$sql = " SELECT prod_movimiento.cod_producto, productos.descripcion AS producto,
                    prod_movimiento.cod_ficha, v_ficha.ap_nombre AS trabajador,
                    productos.cod_linea, prod_lineas.descripcion AS linea,
					productos.item AS serial,
                    clientes_ubicacion.cod_estado, estados.descripcion AS estado,
                    productos.campo01 AS n_porte, productos.campo02 AS fec_venc_permiso,
                    prod_movimiento.cod_cliente, clientes.nombre AS cliente,
                    prod_movimiento.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion,
                    prod_movimiento.fecha, prod_movimiento.hora,
					prod_movimiento.cod_mov_tipo, prod_mov_tipo.descripcion AS mov_tipo,
					prod_movimiento.observacion,
					prod_movimiento.campo01, prod_movimiento.campo02,
                    prod_movimiento.campo03, prod_movimiento.campo04,
                    prod_movimiento.`status`
               FROM prod_movimiento, productos, clientes_ubicacion, clientes, estados, prod_mov_tipo, prod_lineas, v_ficha
			  WHERE prod_movimiento.cod_producto = productos.codigo
			    AND prod_movimiento.cod_cliente = clientes.codigo
			    AND prod_movimiento.cod_ubicacion = clientes_ubicacion.codigo
			    AND clientes_ubicacion.cod_estado = estados.codigo
                AND prod_movimiento.cod_mov_tipo = prod_mov_tipo.codigo
                AND productos.cod_linea = prod_lineas.codigo
                AND prod_movimiento.cod_ficha = v_ficha.cod_ficha
				AND prod_movimiento.codigo =  '$codigo' ";
	$query = $bd->consultar($sql);
	$result = $bd->obtener_fila($query,0);

    $filtro_d       = 'disabled="disabled"';
    $linea_d       = 'disabled="disabled"';
    $cod_linea     = $result['cod_linea'];
    $linea         = $result['linea'];
    $cod_ficha     = $result['cod_ficha'];
    $trabajador    = $result['trabajador'];
    $cod_producto  = $result['cod_producto'];
    $producto      = $result['producto'].' - '.$result['serial'];
    $cod_cliente   = $result['cod_cliente'];
    $cliente_d     = 'disabled="disabled"';
    $cliente       = $result['cliente'];
    $cod_ubicacion = $result['cod_ubicacion'];
    $ubicacion     = $result['ubicacion'];
    $cod_tipo_mov  = $result['cod_mov_tipo'];
    $tipo_mov      = $result['mov_tipo'];
    $fecha         = conversion($result['fecha']);
    $hora          = $result['hora'];
    $observacion   = $result['observacion'];
    $campo01       = $result['campo01'];
    $campo02       = $result['campo02'];
    $campo03       = $result['campo03'];
    $campo04       = $result['campo04'];
	$activo        = $result['status'];

	}else{
    $titulo        = "AGREGAR $titulo";

	$codigo        = '';
    $filtro_d      = '';
    $linea_d       = '';
    $cod_linea     = '';
    $linea         = 'Seleccione...';
    $cod_ficha     = '';
    $trabajador    = '';
    $cod_producto  = '';
    $producto      = 'Seleccione...';
    $cod_cliente   = '';
    $cliente       = 'Seleccione...';
    $cod_ubicacion = '';
    $ubicacion     = 'Seleccione...';
    $cod_tipo_mov  = '';
    $tipo_mov      = 'Seleccione...';
    $fec_entrega   = '';
    $observacion   = '';

    $campo01      = '';
    $campo02      = '';
    $campo03      = '';
    $campo04      = '';
	$activo       = 'T';

	$hora          = date("H:i:s");

	}?>
<script language="javascript">

function Add_filtroX(){  // CARGAR  UBICACION DE CLIENTE  Y tamaño  //
	var error = 0;
    var errorMessage = ' ';
	var valor = document.getElementById("sub_linea").value;
	var contenido = "cont_producto";
	var activar = "F";
	var tamano = "250";

    if(valor == '') {
	 var error = error+1;
	  errorMessage = errorMessage + ' \n Debe Seleccionar Una Linea ';
	}
	 if(error == 0){
		ajax=nuevoAjax();
			ajax.open("POST", "ajax/Add_prod_filtro.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				Validar02("");
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo="+valor+"&tamano="+tamano+"&activar="+activar+"");
	 }else{
	 alert(errorMessage);
	}
}

function Validar01(valor){
    if(valor != ""){
		document.getElementById("ubicacion2").value = valor;
	}else{
		document.getElementById("ubicacion2").value = '';
	}
}
function Validar02(valor){
    if(valor != ""){
		document.getElementById("producto2").value = valor;
	}else{
		document.getElementById("producto2").value = '';
	}
}
</script>
     <table width="90%" align="center">
         <tr valign="top">
		     <td height="23" colspan="4" class="etiqueta_title" align="center"><?php echo $titulo;?></td>
         </tr>
         <tr>
    	       <td height="8" colspan="4" align="center"><hr></td>
     	</tr>
    <tr>
		<td class="etiqueta">Codigo:</td>
      	<td ><input name="codigo" id="codigo" type="text" value="<?php echo $codigo;?>" readonly="readonly"/></td>
        <td class="etiqueta">Fecha:</td>
      	<td id="fecha01"><input type="text" name="fecha" size="19" value="<?php echo $fecha.' &nbsp; '.$hora;?>" /><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span></td>
    </tr>
    <tr>
	<td class="etiqueta">Filtro <?php echo $leng['trabajador'];?>:</td>
		<td id="select01"><select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:200px" <?php echo $filtro_d;?> />
				<option value="">Seleccione...</option>
				<option value="codigo"><?php echo $leng['ficha'];?> </option>
				<option value="cedula"><?php echo $leng['ci'];?> </option>
				<option value="trabajador"><?php echo $leng['trabajador'];?> </option>
                <option value="apellidos"> Apellido </option>
                <option value="nombres"> Nombre </option>
		</select><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	  <td class="etiqueta"><?php echo $leng['trabajador'];?>:</td>
      <td colspan="2"><input id="stdName" type="text" size="30" value="<?php echo $trabajador;?>" disabled="disabled" />
	      <input type="hidden" name="trabajador" id="stdID" value="<?php echo $cod_ficha;?>" required />Activo:
          <input name="activo" type="checkbox"  <?php echo statusCheck("$activo");?> value="T" />
           &nbsp; <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
            <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" /></td>
	    </tr>
		<tr><td class="etiqueta">Linea: </td>
			<td id="select02"><select name="linea" id="linea" style="width:250px;" <?php echo $linea_d;?>
                                      onchange="Add_Sub_Linea(this.value, 'cont_sub_linea', 'T', '250')" required>
					<option value="<?php echo $cod_linea?>"><?php echo $linea; ?></option>
					<?php
	   			$query01 = $bd->consultar($sql_linea);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
            <td class="etiqueta">Sub Linea:</td>
			<td id="cont_sub_linea"><select name="sub_linea" id="sub_linea" style="width:250px;" <?php echo $linea_d;?> required />
					<option value="<?php echo $cod_linea?>"><?php echo $linea; ?></option>
			<?php
	   			$query01 = $bd->consultar($sql_sub_linea);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
  	 </tr>
		<tr><td class="etiqueta">Producto: </td>
			<td id="input01"><span id="cont_producto"><select name="producto" id="producto" style="width:250px;"
                                                                   onchange="Add_filtroX()" required>
                                                <option value="<?php echo $cod_producto?>"><?php echo $producto; ?></option>
          <?php
			    	$sql   = "SELECT codigo, item, descripcion FROM productos
                               WHERE productos.cod_linea = '$cod_linea'
                                 AND status = 'T' ORDER BY 2 ASC";
		            $query = $bd->consultar($sql);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[2].' - '.$row02[1];?></option>
          <?php }?>
          </select></span><input  type="hidden" name="producto2" id="producto2" value="<?php echo $cod_producto?>" /><br />
                                              <span class="textfieldRequiredMsg">El Campo es Requerido...</span></td>
        <td class="etiqueta">Tipo Movimiento:</td>
      	<td id="select04"><select name="tipo_mov" style="width:250px" required>
                                  <option value="<?php echo $cod_tipo_mov;?>"><?php echo $tipo_mov;?></option>
          <?php     $query = $bd->consultar($sql_tipo_mov);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
  	 </tr>
     <tr>
      <td class="etiqueta"><?php echo $leng['cliente'];?>:</td>
      	<td id="select03"><select name="cliente" id="cliente" style="width:250px"
    	                          onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion.php', 'cont_ubicacion')" required>
                                  <option value="<?php echo $cod_cliente;?>"><?php echo $cliente;?></option>
          <?php
		            $query = $bd->consultar($sql_cliente);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td class="etiqueta"><?php echo $leng['ubicacion'];?>:</td>
      	<td id="input02"><span id="cont_ubicacion"><select name="ubicacion" style="width:250px" required>
                                  <option value="<?php echo $cod_ubicacion;?>"><?php echo $ubicacion;?></option>
          <?php
			    	$sql   = "SELECT clientes_ubicacion.id, clientes_ubicacion.descripcion
							    FROM clientes_ubicacion
							   WHERE clientes_ubicacion.co_cli = '$cliente'
							 	 AND clientes_ubicacion.`status` = 'T'
								 AND clientes_ubicacion.id <> '$cod_ubicacion'
							   ORDER BY 2 ASC";
		            $query = $bd->consultar($sql);
            		while($row02 = $bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $row02[0];?>"><?php echo $row02[1];?></option>
          <?php }?>
        </select></span><input type="hidden" name="ubicacion2" id="ubicacion2" value="<?php echo $cod_ubicacion?>" /><br />
                                              <span class="textfieldRequiredMsg">El Campo es Requerido...</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Observacion:</td>
      <td id="textarea01" colspan="3"><textarea  name="observacion" cols="90" rows="2"><?php echo $observacion;?></textarea>
        <span id="Counterror_mess1" class="texto">&nbsp;</span> <br />
        <span class="textareaRequiredMsg">El Campo es Requerido.</span>
        <span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span></td>
    </tr>
         <tr>
            <td height="8" colspan="4" align="center"><hr></td>
         </tr>
  </table>
  <div align="center">
	<?php if ($metodo == "agregar"){ ?>

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

<?php }else{ ?>
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
        <input type="button" name="pdf" onClick="Pdf()" value="Imprimir" class="readon art-button" />
        </span>&nbsp;
					<span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" name="anular" id="anular" onclick="Anular()" value="Anular" class="readon art-button" />
                </span>&nbsp;
 <?php } ?><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
                </span>
			<input name="metodo" type="hidden" value="<?php echo $metodo;?>" />
			<input name="proced" type="hidden" value="<?php echo $proced;?>" />
            <input name="usuario" type="hidden" value="<?php echo $usuario;?>" />
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>
            <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
             <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
             <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>
            </div>
</body>
</html>
<script type="text/javascript">
var input01   = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
var input02   = new Spry.Widget.ValidationTextField("input02", "none", {validateOn:["blur", "change"]});

var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {maxChars:255, validateOn:["blur", "change"],
 counterType:"chars_count", counterId:"Counterror_mess1", useCharacterMasking:false , isRequired:false});

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
