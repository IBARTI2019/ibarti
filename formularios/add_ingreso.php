<script type="text/javascript">
	function Pdf() {
		$('#pdf').attr('action', "reportes/rp_preing.php");
		$('#pdf').submit();
	}
</script>

<?php
require_once('autentificacion/aut_verifica_menu.php');
$proced     = "p_ingresos";
if ($metodo == 'modificar') {
	$codigo = $_GET['codigo'];
	$cedula = $codigo;
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
					preingreso.cod_ocupacion, ocupacion.descripcion AS ocupacion,
					preingreso.direccion, preingreso.fec_preingreso,
					preingreso.fec_psic,
                    preingreso.psic_apto, preingreso.psic_observacion,
					preingreso.fec_pol, preingreso.pol_apto,
					preingreso.pol_observacion,
					preingreso.fec_pre_emp, preingreso.pre_emp_apto,
					preingreso.pre_emp_observacion,
                    preingreso.cod_t_camisas, preing_camisas.descripcion AS t_camisas,
                    preingreso.cod_t_pantalon, preing_pantalon.descripcion AS t_pantalon,
                    preingreso.cod_n_zapatos, preing_zapatos.descripcion AS n_zapatos,
					preingreso.refp01_nombre, preingreso.refp01_ocupacion,
					preingreso.refp01_telf,
					preingreso.refp01_parentezco, preingreso.refp01_direccion,
					preingreso.refp01_observacion, preingreso.refp01_apto,
					preingreso.refp02_nombre,  preingreso.refp02_ocupacion,
					preingreso.refp02_telf,    preingreso.refp02_parentezco,
					preingreso.refp02_direccion, preingreso.refp02_observacion,
					preingreso.refp02_apto, preingreso.refp03_nombre,
					preingreso.refp03_ocupacion, preingreso.refp03_telef,
					preingreso.refp03_parentezco, preingreso.refp03_direccion,
					preingreso.refp03_observacion, preingreso.refp03_apto,
					preingreso.refl01_empresa, preingreso.refl01_telf,
					preingreso.refl01_contacto, preingreso.refl01_cargo,
					preingreso.refl01_direccion, preingreso.refl01_observacion,
					preingreso.refl01_fec_ingreso, preingreso.refl01_fec_egreso,
					preingreso.refl01_sueldo_inic, preingreso.refl01_sueldo_fin,
					preingreso.refl01_retiro, preingreso.refl01_apto,
					preingreso.refl02_empresa, 	preingreso.refl02_telf,
					preingreso.refl02_contacto, preingreso.refl02_cargo,
					preingreso.refl02_direccion,  preingreso.refl02_observacion,
					preingreso.refl02_fec_ingreso, preingreso.refl02_fec_egreso,
					preingreso.refl02_sueldo_inic, preingreso.refl02_sueldo_fin,
					preingreso.refl02_retiro, preingreso.refl02_apto,
					preingreso.campo01, preingreso.campo02,
					preingreso.campo03, preingreso.campo04,
					preingreso.cod_us_ing, CONCAT(men_usuarios.apellido,' ', men_usuarios.nombre) AS us_ing,
                    preingreso.fec_us_ing,
					preingreso.cod_us_mod, preingreso.fec_us_mod,
					preingreso.observacion,
					preingreso.`status` AS cod_status, 	preing_status.descripcion AS status
               FROM preingreso LEFT JOIN men_usuarios ON preingreso.cod_us_ing = men_usuarios.codigo
			   LEFT JOIN preing_camisas ON preingreso.cod_t_camisas = preing_camisas.codigo
			   LEFT JOIN preing_pantalon ON preingreso.cod_t_pantalon  = preing_pantalon.codigo
			   LEFT JOIN preing_zapatos ON preingreso.cod_n_zapatos = preing_zapatos.codigo,
			        cargos, preing_status, estados, nacionalidad, estado_civil,
			        ciudades, nivel_academico, ocupacion
              WHERE preingreso.cod_cargo = cargos.codigo
                AND preingreso.`status` = preing_status.codigo
				AND preingreso.cod_estado = estados.codigo
				AND preingreso.cod_nacionalidad = nacionalidad.codigo
				AND	preingreso.cod_estado_civil = estado_civil.codigo
				AND preingreso.cod_ciudad = ciudades.codigo
                AND preingreso.cod_nivel_academico = nivel_academico.codigo
				AND preingreso.cod_ocupacion = ocupacion.codigo
                AND preingreso.cedula = '$codigo' ";

	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);

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
	$cod_ocupacion  = $result['cod_ocupacion'];
	$ocupacion      = $result['ocupacion'];
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
	$fec_pre_emp        = conversion($result['fec_pre_emp']);
	$pre_emp_apto       = $result['pre_emp_apto'];
	$pre_emp_observacion = $result["pre_emp_observacion"];

	$observacion     = $result["observacion"];
	$cod_status     = $result['cod_status'];
	$status         = $result['status'];
	$fec_us_ing     = conversion($result['fec_us_ing']);
	$us_ing         = $result['us_ing'];



	// Dotacion
	$cod_t_pantalon = $result['cod_t_pantalon'];
	$t_pantalon     = $result['t_pantalon'];
	$cod_t_camisa   = $result['cod_t_camisas'];
	$t_camisa       = $result['t_camisas'];
	$cod_n_zapato   = $result['cod_n_zapatos'];
	$n_zapato       = $result['n_zapatos'];

	// PARTE referenias
	$refp01_nombre      = $result['refp01_nombre'];
	$refp01_ocupacion   = $result['refp01_ocupacion'];
	$refp01_telf        = $result['refp01_telf'];
	$refp01_parentezco  = $result['refp01_parentezco'];
	$refp01_direccion   = $result['refp01_direccion'];
	$refp01_observacion = $result['refp01_observacion'];
	$refp01_apto        = $result['refp01_apto'];

	$refp02_nombre      = $result['refp02_nombre'];
	$refp02_ocupacion   = $result['refp02_ocupacion'];
	$refp02_telf        = $result['refp02_telf'];
	$refp02_parentezco  = $result['refp02_parentezco'];
	$refp02_direccion   = $result['refp02_direccion'];
	$refp02_observacion = $result['refp02_observacion'];
	$refp02_apto        = $result['refp02_apto'];

	$refp03_nombre      = $result['refp03_nombre'];
	$refp03_ocupacion   = $result['refp03_ocupacion'];
	$refp03_telf        = $result['refp03_telef'];
	$refp03_parentezco  = $result['refp03_parentezco'];
	$refp03_direccion   = $result['refp03_direccion'];
	$refp03_observacion = $result['refp03_observacion'];
	$refp03_apto        = $result['refp03_apto'];

	$refl01_empresa     = $result['refl01_empresa'];
	$refl01_telf        = $result['refl01_telf'];
	$refl01_contacto    = $result['refl01_contacto'];
	$refl01_cargo       = $result['refl01_cargo'];
	$refl01_direccion   = $result['refl01_direccion'];
	$refl01_observacion = $result['refl01_observacion'];
	$refl01_sueldo_inic = $result['refl01_sueldo_inic'];
	$refl01_sueldo_fin  = $result['refl01_sueldo_fin'];
	$refl01_fec_ingreso = conversion($result['refl01_fec_ingreso']);
	$refl01_fec_egreso  = conversion($result['refl01_fec_egreso']);
	$refl01_retiro      = $result['refl01_retiro'];
	$refl01_apto        = $result['refl01_apto'];

	$refl02_empresa     = $result['refl02_empresa'];
	$refl02_telf        = $result['refl02_telf'];
	$refl02_contacto    = $result['refl02_contacto'];
	$refl02_cargo       = $result['refl02_cargo'];
	$refl02_direccion   = $result['refl02_direccion'];
	$refl02_observacion = $result['refl02_observacion'];
	$refl02_sueldo_inic = $result['refl02_sueldo_inic'];
	$refl02_sueldo_fin  = $result['refl02_sueldo_fin'];
	$refl02_fec_ingreso = conversion($result['refl02_fec_ingreso']);
	$refl02_fec_egreso  = conversion($result['refl02_fec_egreso']);
	$refl02_retiro      = $result['refl02_retiro'];
	$refl02_apto        = $result['refl02_apto'];

	// PARTE adiccional
	$campo01    = $result["campo01"];
	$campo02    = $result["campo02"];
	$campo03    = $result["campo03"];
	$campo04    = $result["campo04"];

	$sql    = "SELECT preingreso.fec_us_mod, CONCAT(men_usuarios.apellido, ' ',men_usuarios.nombre) AS us_mod
                 FROM preingreso , men_usuarios
                WHERE preingreso.cedula = '$cedula'
                  AND preingreso.cod_us_mod = men_usuarios.codigo";
	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);

	$fec_us_mod    = conversion($result['fec_us_mod']);
	$us_mod        = $result['us_mod'];



	echo '<script language="javascript" type="text/jscript">
				  var input03  = new Spry.Widget.ValidationTextField("file01", "none", {validateOn:["blur", "change"]});
	</script>';
} else {
	echo '<script language="javascript" type="text/jscript">
				  var input03  = new Spry.Widget.ValidationTextField("file01", "none", {validateOn:["blur", "change"]});
	</script>';

	$sql = " SELECT preing_status.codigo, preing_status.descripcion
               FROM preing_status , control
              WHERE preing_status.codigo = control.preingreso_nuevo ";
	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);


	$codigo_readonly = '';
	$codigo_onblur = "Add_ajax01(this.value, 'ajax/validar_ingreso.php', 'Contenedor')";

	$codigo         = '';
	$cedula         = '';
	$cod_nacionalidad = '';
	$nacionalidad   = 'Seleccione...';
	$cod_estado_civil = '';
	$estado_civil   = 'Seleccione...';
	$apellido       = '';
	$nombre         = '';
	$fec_nacimiento = '';
	$lugar_nac      = '';
	$sexo           = '';
	$telefono       = '';
	$celular        = '';
	$cod_ocupacion  = '';
	$ocupacion      = 'Seleccione...';
	$correo         = '';
	$experiencia    = '';
	$direccion      = '';
	$cod_estado     = '';
	$estado         = 'Seleccione...';
	$cod_ciudad     = '';
	$ciudad         = 'Seleccione...';
	$cod_cargo      = '';
	$cargo          = 'Seleccione...';
	$cod_nivel_academico = '';
	$nivel_academico = 'Seleccione...';
	$fec_preingreso = '';
	$fec_psic       = '';
	$psic_apto      = 'I';
	$psic_observacion = '';
	$fec_pol        = '';
	$pol_apto       = 'I';
	$pol_observacion = '';
	$observacion = '';
	$cod_status     = $result["codigo"];
	$status         = $result["descripcion"];
	$fec_us_ing     = '';
	$us_ing         = '';
	$fec_us_mod     = '';
	$us_mod         = '';
	$fec_pre_emp	= '';
	$pre_emp_apto = '';
	$pre_emp_observacion = '';

	// Dotacion

	$cod_t_pantalon = '';
	$t_pantalon    = 'Seleccione...';
	$cod_t_camisa   = '';
	$t_camisa    = 'Seleccione...';
	$cod_n_zapato = '';
	$n_zapato    = 'Seleccione...';

	// PARTE referenias
	$refp01_nombre      = '';
	$refp01_ocupacion   = '';
	$refp01_telf        = '';
	$refp01_parentezco  = '';
	$refp01_direccion   = '';
	$refp01_observacion = '';
	$refp01_apto        = '';
	$refp02_nombre      = '';
	$refp02_ocupacion   = '';
	$refp02_telf        = '';
	$refp02_parentezco  = '';
	$refp02_direccion   = '';
	$refp02_observacion = '';
	$refp02_apto        = '';
	$refp03_nombre      = '';
	$refp03_ocupacion   = '';
	$refp03_telf        = '';
	$refp03_parentezco  = '';
	$refp03_direccion   = '';
	$refp03_observacion = '';
	$refp03_apto        = '';
	$refl01_empresa     = '';
	$refl01_telf        = '';
	$refl01_contacto    = '';
	$refl01_cargo       = '';
	$refl01_sueldo_inic = '';
	$refl01_sueldo_fin  = '';
	$refl01_fec_ingreso = '';
	$refl01_fec_egreso  = '';
	$refl01_direccion   = '';
	$refl01_observacion = '';
	$refl01_retiro      = '';
	$refl01_apto        = '';
	$refl02_empresa     = '';
	$refl02_telf        = '';
	$refl02_contacto    = '';
	$refl02_cargo       = '';
	$refl02_sueldo_inic = '';
	$refl02_sueldo_fin  = '';
	$refl02_fec_ingreso = '';
	$refl02_fec_egreso  = '';
	$refl02_direccion   = '';
	$refl02_observacion = '';
	$refl02_retiro      = '';
	$refl02_apto        = '';

	// PARTE adiccional
	$campo01    = '';
	$campo02    = '';
	$campo03    = '';
	$campo04    = '';
}
$sql_ciudad = " SELECT codigo, descripcion FROM ciudades WHERE cod_estado = '$cod_estado'
			       AND codigo <> '$cod_ciudad' ORDER BY descripcion ASC ";

$sql    = "SELECT control.preingreso_apto FROM control";
$query     = $bd->consultar($sql);
$result    = $bd->obtener_fila($query, 0);
$apto      = $result['preingreso_apto'];
if ($cod_status == $apto) {
	$fec_preingreso_read =	'';
} else {
	$fec_preingreso_read = ' readonly="readonly" ';
}
?><script language="javascript">
	function Verficar_cedula(valor) {
		var codigo = document.getElementById("codigo").value;
		if (valor != codigo) {
			alert(" Las Cedula son Diferentes \n Cedula = " + codigo + ", es Diferente a la verificar Cedula = " + valor + "");
			document.getElementById("codigo2").value = '';
		}
	}
</script>
<div id="Contenedor" class="mensaje"></div>
<fieldset class="fieldset">
	<legend>DATOS <?php echo $titulo; ?></legend>
	<table width="98%" align="center">
		<tr>
			<td width="20%" class="etiqueta">&nbsp;</td>
			<td width="25%">&nbsp;</td>
			<td width="20%">&nbsp;</td>
			<td width="25%">&nbsp;</td>
			<td width="10%" rowspan="16" align="left">
				<?php

				$filename = "imagenes/fotos/" . $cedula . ".jpg";
				//   $filename = "imagenes/fotos/".$codigo.".jpg";

				if (file_exists($filename)) {
					echo '<img src="' . $filename . '?nocache='.time().'" width="110px" height="130px" />';
				} else {
					echo '<img src="imagenes/img_no_disp.png" width="110px" height="130px"/>';
				} ?>
			</td>
		</tr>
		<tr>
			<td class="etiqueta"><?php echo $leng["ci"]; ?></td>
			<td id="input01"><input type="text" name="codigo" id="codigo" maxlength="16" size="15" value="<?php echo $codigo; ?>" <?php echo $codigo_readonly; ?> onblur="<?php echo $codigo_onblur; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>

			<?php if ($metodo == "agregar") {  ?>
				<td class="etiqueta">Verificar <?php echo $leng["ci"]; ?>:</td>
				<td id="input0101"><input type="text" id="codigo2" name="codigo2" maxlength="16" size="15" value="<?php echo $codigo; ?>" onblur="Verficar_cedula(this.value)" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>

			<?php } elseif ($metodo == "modificar") {  ?>
		<tr>
			<td id="input0101" colspan="2"><input type="hidden" id="codigo2" name="codigo2" value="<?php echo $codigo; ?>" /></td>

		<?php }  ?>
		</tr>
		<tr>
			<td class="etiqueta"><?php echo $leng["nacionalidad"]; ?>: </td>
			<td id="select01"><select name="nacionalidad" style="width:200px">
					<option value="<?php echo $cod_nacionalidad; ?>"><?php echo $nacionalidad; ?></option>
					<?php $sql = " SELECT codigo, descripcion FROM nacionalidad WHERE status = 'T'
		                        AND codigo <> '$cod_nacionalidad' ORDER BY 2 ASC ";
					$query = $bd->consultar($sql);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			<td class="etiqueta"><?php echo $leng["estado_civil"]; ?>: </td>
			<td id="select02"><select name="estado_civil" style="width:200px">
					<option value="<?php echo $cod_estado_civil; ?>"><?php echo $estado_civil; ?></option>
					<?php $sql = " SELECT codigo, descripcion FROM estado_civil WHERE status = 'T'
		                        AND codigo <> '$cod_estado_civil' ORDER BY 2 ASC ";
					$query = $bd->consultar($sql);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
		</tr>
		<tr>
			<td class="etiqueta">Apellidos: </td>
			<td id="input04"><input type="text" name="apellido" maxlength="60" size="25" value="<?php echo $apellido; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
			<td class="etiqueta">Nombres: </td>
			<td id="input05"><input type="text" name="nombre" maxlength="60" size="25" value="<?php echo $nombre; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Fecha de Nacimiento:</td>
			<td id="fecha01"><input type="text" name="fecha_nac" size="12" value="<?php echo $fec_nacimiento; ?>" /><br />
				<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
				<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
			</td>
			<td class="etiqueta">Lugar Nacimiento: </td>
			<td id="input05"><input type="text" name="lugar_nac" maxlength="60" size="25" value="<?php echo $lugar_nac; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Tel&eacute;fono: </td>
			<td id="input06"><input type="text" name="telefono" maxlength="40" size="25" value="<?php echo $telefono; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
			<td class="etiqueta">Tel. Celular: </td>
			<td id="custom01"><input type="text" name="celular" maxlength="40" size="25" value="<?php echo $celular; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Años Experiencia Laboral: </td>
			<td id="input08"><input type="text" name="experiencia" maxlength="60" size="25" value="<?php echo $experiencia; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
			<td class="etiqueta">Sexo:</td>
			<td id="radio01" class="texto"><img src="imagenes/femenino.gif" width="25" height="15" />
				<input type="radio" name="sexo" value="F" style="width:auto" <?php echo CheckX($sexo, 'F'); ?> />
				<img src="imagenes/masculino.gif" width="25" height="15" />
				<input type="radio" name="sexo" value="M" style="width:auto" <?php echo CheckX($sexo, 'M'); ?> />
				<span class="radioRequiredMsg">Debe Seleccionar el Sexo.</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Ocupación: </td>
			<td id="select03"><select name="ocupacion" style="width:200px">
					<option value="<?php echo $cod_ocupacion; ?>"><?php echo $ocupacion; ?></option>
					<?php $sql = " SELECT codigo, descripcion FROM ocupacion WHERE status = 'T'
		                        AND codigo <> '$cod_ocupacion' ORDER BY 2 ASC ";
					$query = $bd->consultar($sql);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>

			<td class="etiqueta"><?php echo $leng["correo"]; ?>: </td>
			<td id="email01"><input type="text" name="correo" maxlength="60" size="26" value="<?php echo $correo; ?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Direcci&oacute;n:</td>
			<td id="textarea01" colspan="3"><textarea name="direccion" cols="50" rows="3"><?php echo $direccion; ?></textarea>
				<span id="Counterror_mess1" class="texto">&nbsp;</span><br />
				<span class="textareaRequiredMsg">El Campo es Requerido.</span>
				<span class="textareaMinCharsMsg">Debe Escribir mas de 4 caracteres.</span>
				<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta"><?php echo $leng["estado"]; ?>:</td>
			<td id="select04"><select name="estado" style="width:200px" onchange="Add_ajax01(this.value, 'ajax/Add_ciudad.php', 'ciudad')">
					<option value="<?php echo $cod_estado; ?>"><?php echo $estado; ?></option>
					<?php $sql = " SELECT codigo, descripcion FROM estados, control WHERE status = 'T'
                                AND control.cod_pais = estados.cod_pais
                                AND codigo <> '$cod_estado'
                           ORDER BY 2 ASC ";
					$query = $bd->consultar($sql);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			<td class="etiqueta"><?php echo $leng["ciudad"]; ?>:</td>
			<td id="ciudad"><select name="ciudad" style="width:200px">
					<option value="<?php echo $cod_ciudad; ?>"><?php echo $ciudad; ?></option>
					<?php $query = $bd->consultar($sql_ciudad);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
		</tr>
		<tr>
			<td class="etiqueta">Cargo: </td>
			<td id="select05"><select name="cargo" style="width:200px">
					<option value="<?php echo $cod_cargo; ?>"><?php echo $cargo; ?></option>
					<?php $sql = " SELECT codigo, descripcion FROM cargos WHERE status = 'T'
		                        AND codigo <> '$cod_cargo' ORDER BY 2 ASC ";
					$query = $bd->consultar($sql);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			<td class="etiqueta">Nivel Academico: </td>
			<td id="select06"><select name="nivel_academico" style="width:200px">
					<option value="<?php echo $cod_nivel_academico; ?>"><?php echo $nivel_academico; ?></option>
					<?php $sql = " SELECT codigo, descripcion FROM nivel_academico WHERE status = 'T'
		                        AND codigo <> '$cod_nivel_academico' ORDER BY 2 ASC ";
					$query = $bd->consultar($sql);
					while ($datos = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
		</tr>

		<tr>
			<td class="etiqueta">0bservación:</td>
			<td id="textarea02" colspan="3"><textarea name="observacion" cols="50" rows="3"><?php echo $observacion; ?></textarea>
				<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
				<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Fecha de Ingreso:</td>
			<td id="fecha02">
				<input type="text" name="fec_preingreso" value="<?php echo $fec_preingreso; ?>" <?php echo $fec_preingreso_read; ?> size="12 " /><br />
				<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
				<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
			</td>
			<td class="etiqueta">Status: </td>
			<td id="select10"><select name="status" style="width:200px">
					<option value="<?php echo $cod_status; ?>"><?php echo $status; ?></option>
					<?php /* 	$sql_ing = " SELECT codigo, descripcion FROM preing_status WHERE status = 'T' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo utf8_decode($datos[1]);?></option>
          <?php } */ ?>
				</select><br />
				<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
			</td>

		</tr>
		<tr>
			<td class="etiqueta">Fecha Ingreso Sist:</td>
			<td><?php echo $fec_us_ing; ?></td>
			<td class="etiqueta">Usuario Ingreso. Sist.: </td>
			<td><?php echo $us_ing; ?></td>
		</tr>
		<tr>
		<tr>
			<td class="etiqueta">Fecha Mod. Sistema:</td>
			<td><?php echo $fec_us_mod; ?></td>
			<td class="etiqueta">Usuario Mod. Sistema: </td>
			<td><?php echo $us_mod; ?></td>
		</tr>
		<tr>
			<td height="8" colspan="4" align="center">
				<hr>
			</td>
		</tr>
	</table>

	<div align="center">
		<?php if ($metodo <> "agregar") {
			echo '<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
		<input type="button" name="pdf" onClick="Pdf()" value="Imprimir" class="readon art-button" />
		</span>&nbsp;';
		} ?>
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
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
		<input name="metodo" id="metodo" type="hidden" value="<?php echo $metodo; ?>" />
		<input name="proced" id="proced" type="hidden" value="<?php echo $proced; ?>" />
		<input name="tabla" id="tabla" type="hidden" value="<?php echo $tabla; ?>" />
		<input name="usuario" id="usuario" type="hidden" value="<?php echo $usuario; ?>" />
		<input name="href" type="hidden" value="<?php echo $archivo2; ?>" />
	</div>
</fieldset>
<script type="text/javascript">
	var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
		validateOn: ["blur", "change"]
	});
	var input0101 = new Spry.Widget.ValidationTextField("input0101", "integer", {
		validateOn: ["blur", "change"]
	});

	var input04 = new Spry.Widget.ValidationTextField("input04", "none", {
		validateOn: ["blur", "change"]
	});
	var input05 = new Spry.Widget.ValidationTextField("input05", "none", {
		validateOn: ["blur", "change"]
	});
	var input06 = new Spry.Widget.ValidationTextField("input06", "none", {
		validateOn: ["blur", "change"]
	});
	//TELFONO CEL
	var custom = new Spry.Widget.ValidationTextField("custom01", "custom", {
		pattern: "\\0\\400\\-0000000",
		useCharacterMasking: true,
		validateOn: ["blur", "change"],
		isRequired: false
	});
	var input08 = new Spry.Widget.ValidationTextField("input08", "none", {
		validateOn: ["blur", "change"]
	});

	var email01 = new Spry.Widget.ValidationTextField("email01", "email", {
		validateOn: ["blur"],
		isRequired: false
	});

	var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});
	var fecha02 = new Spry.Widget.ValidationTextField("fecha02", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});


	var radio01 = new Spry.Widget.ValidationRadio("radio01", {
		validateOn: ["change", "blur"]
	});

	var select01 = new Spry.Widget.ValidationSelect("select01", {
		validateOn: ["blur", "change"]
	});
	var select02 = new Spry.Widget.ValidationSelect("select02", {
		validateOn: ["blur", "change"]
	});
	var select03 = new Spry.Widget.ValidationSelect("select03", {
		validateOn: ["blur", "change"]
	});
	var select04 = new Spry.Widget.ValidationSelect("select04", {
		validateOn: ["blur", "change"]
	});
	var select05 = new Spry.Widget.ValidationSelect("select05", {
		validateOn: ["blur", "change"]
	});
	var select06 = new Spry.Widget.ValidationSelect("select06", {
		validateOn: ["blur", "change"]
	});
	var select10 = new Spry.Widget.ValidationSelect("select10", {
		validateOn: ["blur", "change"]
	});

	var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {
		maxChars: 255,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess1",
		useCharacterMasking: false
	});
	var textarea02 = new Spry.Widget.ValidationTextarea("textarea02", {
		maxChars: 120,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess2",
		useCharacterMasking: false,
		isRequired: false
	});
</script>