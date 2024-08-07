<?php
	$proced      = "p_ingresos_excepcion";
	$metodo    = $_GET['metodo'];
	$titulo = 'INGRESO EXCEPCION';
	$archivo = $_GET['archivo'];
	$Nmenu = $_GET['Nmenu'];
	$mod   = $_GET['mod'];
	$archivo2 = "../inicio.php?area=formularios/Cons_$archivo&Nmenu=".$Nmenu."&mod=".$mod."";

if($metodo == 'modificar'){

	$codigo = $_GET['codigo'];
	$codigo_readonly = 'readonly="readonly"';
	$codigo_onblur = "";

	$bd = new DataBase();
	$sql = " SELECT preingreso.cod_cargo, cargos.descripcion AS cargo,
	                preingreso.cod_estado, estados.descripcion AS estado,
                    preingreso.cod_ciudad, ciudades.descripcion AS ciudad,
	                preingreso.cod_nivel_academico, nivel_academico.descripcion AS nivel_academico,
					preingreso.apellidos, preingreso.nombres,
					preingreso.fec_nacimiento, preingreso.lugar_nac,
                    preingreso.sexo, preingreso.telefono,
					preingreso.celular,
					preingreso.cod_nacionalidad, nacionalidad.descripcion AS nacionalidad,
					preingreso.cod_estado_civil, estado_civil.descripcion AS estado_civil,
					preingreso.experiencia, preingreso.correo,
					preingreso.direccion, preingreso.fec_preingreso,
					preingreso.fec_psic,
                    preingreso.psic_apto, preingreso.psic_observacion,
					preingreso.fec_pol, preingreso.pol_apto,
					preingreso.pol_observacion, preingreso.refp01_nombre,
					preingreso.cod_us_ing, preingreso.fec_us_ing,
					preingreso.cod_us_mod, preingreso.fec_us_mod,
					preingreso.refl02_direccion,  preingreso.observacion,
					preingreso.`status` AS cod_status, 	preing_status.descripcion AS status
               FROM preingreso, cargos, preing_status, estados,
			        ciudades, nivel_academico,  nacionalidad, estado_civil
              WHERE preingreso.cod_cargo = cargos.codigo
                AND preingreso.`status` = preing_status.codigo
				AND preingreso.cod_estado = estados.codigo
				AND preingreso.cod_ciudad = ciudades.codigo
                AND preingreso.cod_nivel_academico = nivel_academico.codigo
				AND preingreso.cod_nacionalidad = nacionalidad.codigo
				AND	preingreso.cod_estado_civil = estado_civil.codigo
                AND preingreso.cedula = '$codigo' ";

	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query,0);

	$cod_nacionalidad = $result['cod_nacionalidad'];
	$nacionalidad  = $result['nacionalidad'];
    $cod_estado_civil = $result['cod_estado_civil'];
    $estado_civil   = $result['estado_civil'];
	$nombre         = $result['nombres'];
	$apellido       = $result['apellidos'];
	$fec_nacimiento = conversion($result['fec_nacimiento']);
	$lugar_nac      = $result['lugar_nac'];
	$sexo           = $result['sexo'];
	$telefono       = $result['telefono'];
	$celular        = $result['celular'];
	$correo         = $result['correo'];
	$experiencia    = $result['experiencia'];
	$direccion      = $result['direccion'];
	$cod_estado     = $result['cod_estado'];
	$estado         = $result['estado'];
	$cod_ciudad     = $result['cod_ciudad'];
	$ciudad         = $result['ciudad'];
	$cod_cargo      = $result['cod_cargo'];
	$cargo          = $result['cargo'];
	$cod_nivel_academico = $result['cod_nivel_academico'];
	$nivel_academico = $result['nivel_academico'];
	$fec_preingreso = conversion($result['fec_preingreso']);
	$fec_psic       = conversion($result['fec_psic']);
	$psic_apto      = $result['psic_apto'];
	$psic_observacion = $result["psic_observacion"];
	$fec_pol        = conversion($result['fec_pol']);
	$pol_apto       = $result['pol_apto'];
	$pol_observacion = $result["pol_observacion"];
	$observacion = $result["observacion"];
	$cod_status     = $result['cod_status'];
	$status         = $result['status'];
	$fec_us_ing     = conversion($result['fec_us_ing']);
	}

	$sql_ciudad = " SELECT codigo, descripcion FROM ciudades WHERE cod_estado = '$cod_estado'
				       AND codigo <> '$cod_ciudad' ORDER BY descripcion ASC ";

	$sql    = "SELECT control.preingreso_apto FROM control";
	$query     = $bd->consultar($sql);
	$result    = $bd->obtener_fila($query,0);
	$apto      = $result['preingreso_apto'];
	if($cod_status == $apto){
	$fec_preingreso_d =	'';
	}else{
	$fec_preingreso_read = ' readonly="readonly" ';
	}
 	 $sql    = "SELECT preing_status.codigo, preing_status.descripcion
	              FROM control, preing_status
                 WHERE control.preingreso_apto = preing_status.codigo";
	$query     = $bd->consultar($sql);
	$result    = $bd->obtener_fila($query,0);
	$cod_status = $result[0];
	$status     = $result[1];

?>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
  <form action="scripts/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add" enctype="multipart/form-data">
   <fieldset class="fieldset">
  <legend>Datos Basicos</legend>
     <table width="98%" align="center">
   <tr>
      <td width="20%" class="etiqueta">&nbsp;</td>
      <td width="25%">&nbsp;</td>
      <td  width="20%">&nbsp;</td>
      <td width="25%">&nbsp;</td>
      <td width="10%" rowspan="16" align="left">
	  <?php

	   $filename = "imagenes/fotos/".$codigo.".jpg";
	 //   $filename = "imagenes/fotos/".$codigo.".jpg";

	  if (file_exists($filename)) {
 		   echo '<img src="'.$filename.'?nocache='.time().'" width="110px" height="130px" />';
		} else {
		   echo '<img src="imagenes/img_no_disp.png" width="110px" height="130px"/>';
		} ?>
     </td>
</tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["ci"];?>:</td>
      <td id="input01"><input type="text" name="codigo" id="codigo" maxlength="16" size="15" value="<?php echo $codigo;?>"
	                          <?php echo $codigo_readonly;?> onblur="<?php echo $codigo_onblur;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>

	<?php if($metodo == "agregar"){  ?>
      <td class="etiqueta">Verificar <?php echo $leng["ci"];?>:</td>
      <td id="input0101"><input type="text" id="codigo2" name="codigo2" maxlength="16" size="15"
                                value="<?php echo $codigo;?>" onblur="Verficar_cedula(this.value)"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>

   	<?php }elseif($metodo == "modificar"){  ?>
     <tr>
	    <td id="input0101" colspan="2"><input type="hidden" id="codigo2" name="codigo2"  value="<?php echo $codigo;?>"/></td>

	<?php }  ?>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["nacionalidad"];?>: </td>
      	<td id="select01"><select name="nacionalidad" style="width:200px">
							<option value="<?php echo $cod_nacionalidad;?>"><?php echo $nacionalidad;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM nacionalidad WHERE status = 'T'
		                        AND codigo <> '$cod_nacionalidad' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
        <td class="etiqueta"><?php echo $leng["estado_civil"];?>: </td>
      	<td id="select02"><select name="estado_civil" style="width:200px">
							<option value="<?php echo $cod_estado_civil;?>"><?php echo $estado_civil;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM estado_civil WHERE status = 'T'
		                        AND codigo <> '$cod_estado_civil' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Apellidos: </td>
      <td id="input04"><input type="text" name="apellido" maxlength="60" size="25" value="<?php echo $apellido;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
      <td class="etiqueta">Nombres: </td>
      <td id="input05"><input type="text" name="nombre" maxlength="60" size="25" value="<?php echo $nombre;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span></td>
    </tr>

	 <tr>
      <td class="etiqueta">Fecha de Nacimiento:</td>
    <td id="fecha01"><input type="text" name="fecha_nac" size="12" value="<?php echo $fec_nacimiento;?>" /><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
       <td class="etiqueta">Lugar Nacimiento: </td>
      <td id="input05"><input type="text" name="lugar_nac" maxlength="60" size="25" value="<?php echo $lugar_nac;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span></td>
     </tr>
     <tr>
      <td colspan="2">&nbsp;</td>
      <td class="etiqueta">Fotos (.jpg):</td>
      <td id="input03_1"><input type="file" name="file" id="file" style="width:195px" value=""/><br />
         <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
	 </tr>
    <tr>
      <td class="etiqueta">Tel&eacute;fono: </td>
      <td id="input06"><input type="text" name="telefono" maxlength="40" size="25" value="<?php echo $telefono;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
      <td class="etiqueta">Tel. Celular: </td>
      <td id="input07"><input type="text" name="celular" maxlength="40" size="25" value="<?php echo $celular;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Años Experiencia Laboral: </td>
      <td id="input08"><input type="text" name="experiencia" maxlength="60" size="25" value="<?php echo $experiencia;?>"/><br />
		<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
      <td class="etiqueta">Sexo:</td>
      <td id="radio01" class="texto"><img src="imagenes/femenino.gif" width="25" height="15" />
            <input type = "radio" name="sexo"  value = "F" style="width:auto"  <?php echo CheckX($sexo, 'F');?> />
            <img src="imagenes/masculino.gif" width="25" height="15" />
            <input type = "radio" name="sexo"  value = "M" style="width:auto"  <?php echo CheckX($sexo, 'M');?> />
            <span class="radioRequiredMsg">Debe Seleccionar el Sexo.</span>
        </td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["correo"];?>: </td>
      <td id="input09" colspan="3"><input type="text" name="correo" maxlength="60" size="40" value="<?php echo $correo;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Direcci&oacute;n:</td>
      <td id="textarea01" colspan="3"><textarea  name="direccion" cols="50" rows="3"><?php echo $direccion;?></textarea>
        <span id="Counterror_mess1" class="texto">&nbsp;</span><br />
        <span class="textareaRequiredMsg">El Campo es Requerido.</span>
        <span class="textareaMinCharsMsg">Debe Escribir mas de 4 caracteres.</span>
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span></td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["estado"];?>:</td>
      	<td id="select03"><select name="estado" style="width:200px" onchange="Add_ajax01(this.value, 'ajax/Add_ciudad.php', 'ciudad')">
							<option value="<?php echo $cod_estado;?>"><?php echo $estado;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM estados, control WHERE status = 'T'
                                AND control.cod_pais = estados.cod_pais
                                AND codigo <> '$cod_estado'
                           ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td class="etiqueta"><?php echo $leng["ciudad"];?>:</td>
      	<td id="ciudad"><select name="ciudad" style="width:200px">
						<option value="<?php echo $cod_ciudad;?>"><?php echo $ciudad;?></option>
          <?php     $query = $bd->consultar($sql_ciudad);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
     <tr>
      <td class="etiqueta">Cargo: </td>
      	<td id="select04"><select name="cargo" style="width:200px">
							<option value="<?php echo $cod_cargo;?>"><?php echo $cargo;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM cargos WHERE status = 'T'
		                        AND codigo <> '$cod_cargo' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td class="etiqueta">Nivel Academico: </td>
      	<td id="select05"><select name="nivel_academico" style="width:200px">
							<option value="<?php echo $cod_nivel_academico;?>"><?php echo $nivel_academico;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM nivel_academico WHERE status = 'T'
		                        AND codigo <> '$cod_nivel_academico' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">0bservación:</td>
      <td id="textarea02" colspan="3"><textarea  name="observacion" cols="50" rows="3"><?php echo $observacion;?></textarea>
        <span id="Counterror_mess2" class="texto">&nbsp;</span><br />
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span></td>
    </tr>
	 <tr>
      <td class="etiqueta">Fecha de Ingreso:</td>
    <td id="fecha02">
          	<input type="text" name="fec_preingreso" value="<?php echo $fec_preingreso;?>" <?php echo $fec_preingreso_read;?>  size="12 "/><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
      <td class="etiqueta">Fecha De Sistema:</td>
      <td id="fecha05">
          	<input type="text" name="fecha_sistema" value="<?php echo $fec_us_ing;?>" readonly="true"  size="12"/>
       </td>
    </tr>
     <tr>
      <td class="etiqueta">Status: </td>
      	<td id="select10"><select name="status" style="width:200px">
							<option value="<?php echo $cod_status;?>"><?php echo $status;?></option>
          <?php /* 	$sql_ing = " SELECT codigo, descripcion FROM preing_status WHERE status = 'T' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo utf8_decode($datos[1]);?></option>
          <?php } */ ?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
  </table>
    </fieldset>
    <fieldset class="fieldset">
  <legend>Chequeos</legend>

  <table width="90%" align="center">
    <tr>
      <td width="25%" class="etiqueta"><?php echo $leng["psic_fec"];?>:</td>
      <td width="75%"><span id="fecha01_3"><input type="text" name="fec_psi" value="<?php echo $fec_psic;?>" size="12"/></span></td>
	</tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["psic_desc"];?>:</td>
      <td class="texto">
			<span id="radio01_3">&nbsp;&nbsp; <?php echo $leng["aprobado"];?>
			<input name="psi_apto" type="radio" value="S" style="width:auto" <?php echo CheckX($psic_apto, 'S');?>
                   />&nbsp;&nbsp; <?php echo $leng["reprobado"];?>
			<input name="psi_apto" type="radio" value="N" style="width:auto" <?php echo CheckX($psic_apto, 'N');?>
                   />&nbsp;&nbsp; <?php echo $leng["condiccional"];?>
			<input name="psi_apto" type="radio" value="C" style="width:auto" <?php echo CheckX($psic_apto, 'C');?>
                   />&nbsp;&nbsp; <?php echo $leng["indefinido"];?>
           <input name="psi_apto" type="radio" value="I" style="width:auto" <?php echo CheckX($psic_apto, 'I');?>
                  /></span>
	  </td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["psic_observ"];?>:</td>
      <td id="textarea01_3"><textarea  name="psic_observacion" cols="40" rows="2"><?php echo $psic_observacion;?></textarea>
        <span id="Counterror_mess01_3" class="texto">&nbsp;</span><br />
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 120.</span></td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["pol_fec"];?>:</td>
      <td><span id="fecha02_3"><input type="text" name="fec_pol" value="<?php echo $fec_pol;?>" size="12"/></span></td>
	</tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["pol_desc"];?>:</td>
      <td class="texto">
			<span id="radio02_3">&nbsp;&nbsp; <?php echo $leng["aprobado"];?>
			<input name="pol_apto" type="radio"  value="S" style="width:auto" <?php echo CheckX($pol_apto, 'S');?>
                  />&nbsp;&nbsp; <?php echo $leng["reprobado"];?>
			<input name="pol_apto"  type="radio" value="N" style="width:auto" <?php echo CheckX($pol_apto, 'N');?>
                   />&nbsp;&nbsp; <?php echo $leng["indefinido"];?>
			<input name="pol_apto"  type="radio" value="I" style="width:auto" <?php echo CheckX($pol_apto, 'I');?> />
			<span class="radioRequiredMsg">Seleccione...</span>
			</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng["pol_observ"];?>:</td>
      <td id="textarea02_3"><textarea  name="pol_observacion" cols="40" rows="2"><?php echo $pol_observacion;?></textarea>
        <span id="Counterror_mess02_3" class="texto">&nbsp;</span><br />
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 120.</span></td>
    </tr></table>
   </fieldset>
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
            <input name="usuario" id="usuario" type="hidden"  value="<?php echo $usuario;?>" />
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>
</div>
  </form>
<script type="text/javascript">
var input01   = new Spry.Widget.ValidationTextField("input01", "integer", {validateOn:["blur", "change"]});
var input0101 = new Spry.Widget.ValidationTextField("input0101", "integer", {validateOn:["blur", "change"]});
var input04   = new Spry.Widget.ValidationTextField("input04", "none", {validateOn:["blur", "change"]});
var input05   = new Spry.Widget.ValidationTextField("input05", "none", {validateOn:["blur", "change"]});
var input06   = new Spry.Widget.ValidationTextField("input06", "none", {validateOn:["blur", "change"]});
var input07   = new Spry.Widget.ValidationTextField("input07", "none", {validateOn:["blur", "change"]});
var input08   = new Spry.Widget.ValidationTextField("input08", "none", {validateOn:["blur", "change"]});
var input09   = new Spry.Widget.ValidationTextField("input09", "none", {validateOn:["blur", "change"]});


var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
    validateOn:["blur", "change"], useCharacterMasking:true});
var fecha02 = new Spry.Widget.ValidationTextField("fecha02", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
    validateOn:["blur", "change"], useCharacterMasking:true});


var radio01 = new Spry.Widget.ValidationRadio("radio01", { validateOn:["change", "blur"]});

var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
var select02 = new Spry.Widget.ValidationSelect("select02", {validateOn:["blur", "change"]});
var select03 = new Spry.Widget.ValidationSelect("select03", {validateOn:["blur", "change"]});
var select04 = new Spry.Widget.ValidationSelect("select04", {validateOn:["blur", "change"]});
var select05 = new Spry.Widget.ValidationSelect("select05", {validateOn:["blur", "change"]});
var select10 = new Spry.Widget.ValidationSelect("select10", {validateOn:["blur", "change"]});

var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {maxChars:255, validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess1", useCharacterMasking:false});
var textarea02 = new Spry.Widget.ValidationTextarea("textarea02", {maxChars:120, validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess2", useCharacterMasking:false});

var fecha01_3 = new Spry.Widget.ValidationTextField("fecha01_3", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
    validateOn:["blur", "change"], useCharacterMasking:true});
var fecha02_3 = new Spry.Widget.ValidationTextField("fecha02_3", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
    validateOn:["blur", "change"], useCharacterMasking:true , isRequired:false});

var radio01_3 = new Spry.Widget.ValidationRadio("radio01_3", { validateOn:["change", "blur"]});
var radio02_3 = new Spry.Widget.ValidationRadio("radio02_3", { validateOn:["change", "blur"]});

var textarea01_3 = new Spry.Widget.ValidationTextarea("textarea01_3", {maxChars:120, validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess01_3", useCharacterMasking:false , isRequired:false});
var textarea01_3 = new Spry.Widget.ValidationTextarea("textarea02_3", {maxChars:120, validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess02_3", useCharacterMasking:false , isRequired:false});
</script>
