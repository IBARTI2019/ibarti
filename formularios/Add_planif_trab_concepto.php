<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu = 445;
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');

$metodo = $_GET['metodo'];
$titulo = ' '.$leng["trabajador"].' Exepciones ';
$archivo = $_GET['archivo'];
$proced  = 'p_pl_trab_concepto';
$archivo2 = "../inicio.php?area=formularios/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod']."";

if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$titulo = "Modificar  $titulo";
	$bd = new DataBase();
	$sql = "SELECT pl_trab_concepto.fec_desde, pl_trab_concepto.fec_hasta,
                    pl_trab_concepto.cod_horario, horarios.nombre AS  horario,
                    pl_trab_concepto.cod_cliente, clientes.nombre AS cliente,
                    pl_trab_concepto.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion,
                    pl_trab_concepto.cod_ficha, v_ficha.cedula, v_ficha.nombres AS trabajador,
                    pl_trab_concepto.observacion,
				    pl_trab_concepto.cod_us_ing, pl_trab_concepto.fec_us_ing,
                    pl_trab_concepto.cod_us_mod, pl_trab_concepto.fec_us_mod
			   FROM pl_trab_concepto , horarios , v_ficha, clientes, clientes_ubicacion
              WHERE pl_trab_concepto.codigo        = '$codigo'
			    AND pl_trab_concepto.cod_horario  = horarios.codigo
                AND pl_trab_concepto.cod_ficha     = v_ficha.cod_ficha
                AND pl_trab_concepto.cod_cliente   = clientes.codigo
                AND pl_trab_concepto.cod_ubicacion = clientes_ubicacion.codigo
           ORDER BY 2 DESC ";

	$query = $bd->consultar($sql);
	$result = $bd->obtener_fila($query,0);

	$fec_inicio       = conversion($result['fec_desde']);
	$fec_fin          = conversion($result['fec_hasta']);
	$f_desde          = "";
	$f_hasta          = "";

	$cod_filtro       = 'codigo';
    $filtro           = 'codigo';
    $filtro_disabled  = 'disabled="disabled"';
	$disabled         = '';
	$cod_trabajador   = $result['cod_ficha'];
	$trabajador       = $result['trabajador'];
	$disabled         = 'disabled="disabled"';
	$cod_cliente      = $result['cod_cliente'];
    $cliente          = $result['cliente'];
	$cod_ubicacion    = $result['cod_ubicacion'];
    $ubicacion        = $result['ubicacion'];

	$cod_horario        = $result['cod_horario'];
	$horario            = $result['horario'];
	$observacion      = $result['observacion'];
	}else{

	$titulo           = " Agregar $titulo";
	$codigo           = '';
	$f_desde          = "javascript:muestraCalendario('form_reportes', 'fecha_desde');";
	$f_hasta          = "javascript:muestraCalendario('form_reportes', 'fecha_hasta');";
	$fec_inicio       = '';
	$fec_fin          = '';
	$cod_cliente      = '';
    $cliente          = 'Seleccione...';
	$cod_ubicacion    = '';
    $ubicacion        = 'Seleccione...';
	$cod_filtro       = '';
    $filtro           = 'Seleccione...';
	$filtro_disabled  = '';
	$cod_trabajador   = '';
	$disabled         = 'disabled="disabled"';
	$trabajador       = '';
	$cod_horario      = '';
	$horario          = 'Seleccione...';
	$observacion      = '';

	}
?>
<form name="form_reportes" id="form_reportes" action="scripts/sc_<?php echo $archivo;?>.php" method="post">
  <fieldset class="fieldset">
  <legend><?php echo $titulo;?> </legend>
     <table width="95%" align="center">
    <tr>
<td width="20%">Fecha Desde:</td>
		 <td width="30%" id="fecha01"><input type="text" name="fec_inicio" id="fecha_desde" size="10" value="<?php echo $fec_inicio;?>"
                                             onclick="<?php echo $f_desde;?>">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="<?php echo $f_desde;?>" border="0" width="17px"></td>
        <td width="20%">Fecha Hasta:</td>
		 <td width="30%" id="fecha02"><input type="text" name="fec_fin" id="fecha_hasta" size="10" value="<?php echo $fec_fin;?>"
                                            onclick="<?php echo $f_hasta;?>">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="<?php echo $f_hasta;?>" border="0" width="17px"></td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['cliente']?>:</td>
      	<td id="select01"><select name="cliente" id="cliente" style="width:200px;"
                                  onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion.php', 'select02')">
					<option value="<?php echo $cod_cliente;?>"><?php echo $cliente;?></option>
					<?php
					if($metodo == "agregar"){
	   			$query01 = $bd->consultar($sql_cliente);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }}?></select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td class="etiqueta"><?php echo $leng['ubicacion']?>:</td>
      	<td id="select02"><select name="ubicacion" style="width:200px" >
                                  <option value="<?php echo $cod_ubicacion;?>"><?php echo $ubicacion;?></option>
                  </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
 	<tr>
		<td class="etiqueta">Filtro <?php echo $leng['trabajador']?>:</td>
		<td id="select03">
			<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:200px" <?php echo $filtro_disabled;?>>
				<option value="<?php echo $cod_filtro;?>"><?php echo $filtro;?></option>
				<option value="codigo"> C&oacute;digo </option>
				<option value="cedula"><?php echo $leng['ci']?></option>
				<option value="nombre"> Nombre </option>
		</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td class="etiqueta"><?php echo $leng['trabajador']?>:</td>
      <td>
		  <input  id="stdName" type="text" style="width:300px" <?php echo $disabled;?> value="<?php echo $trabajador;?>"/>
	        <span id="input01"><input type="hidden" name="trabajador" id="stdID" value="<?php echo $cod_trabajador;?>"/>
            <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/> <br />
            <span class="textfieldRequiredMsg">Debe De Seleccionar Un Campo De la Lista.</span>
            <span class="textfieldInvalidFormatMsg">El Formato es Invalido</span> </span></td>
    </tr>
    <tr>
      <td class="etiqueta">Horario:</td>
       <td id="select04"><select name="horario" style="width:200px">
							<option value="<?php echo $cod_horario;?>"><?php echo $horario;?></option>
          <?php  	$sql = " SELECT horarios.codigo, horarios.nombre
                               FROM horarios
                              WHERE horarios.`status` = 'T'
                           ORDER BY 2 ASC ";

		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
          <td class="etiqueta">Observacion:</td>
          <td id="textarea01"><textarea  name="observacion" cols="40" rows="2"><?php echo $observacion;?></textarea>
            <span id="Counterror_mess1" class="texto">&nbsp;</span><br />
            <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 120.</span></td>
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

  		    <input name="metodo" id="metodo" type="hidden"  value="<?php echo $metodo;?>" />
             <input name="proced" id="proced" type="hidden"  value="<?php echo $proced;?>" />
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>
            <input name="codigo" id="codigo" type="hidden"  value="<?php echo $codigo;?>" />
            <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>
                 </div>
  </fieldset>
  </form>
  <script type="text/javascript">
/*
var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
    validateOn:["blur", "change"], useCharacterMasking:true});

var fecha02 = new Spry.Widget.ValidationTextField("fecha02", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
    validateOn:["blur", "change"], useCharacterMasking:true});
*/

var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
var select03 = new Spry.Widget.ValidationSelect("select03", {validateOn:["blur", "change"]});
var select04 = new Spry.Widget.ValidationSelect("select04", {validateOn:["blur", "change"]});

var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {maxChars:255, validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess1", useCharacterMasking:false, isRequired:false });

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
