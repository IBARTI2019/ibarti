<?php
// require_once('autentificacion/aut_verifica_menu.php');
$archivo = "pestanas/add_ficha3&Nmenu=" . $Nmenu . "&codigo=" . $codigo . "&mod=$mod&pagina=0&metodo=modificar";

$proced      = "p_fichas";
if ($metodo == 'modificar') {

	$bd = new DataBase();

	$sql = "SELECT v_ficha.cod_ficha, v_ficha.cod_turno,
                   v_ficha.turno, v_ficha.turno_abrev,
                   v_ficha.cod_rol, v_ficha.rol,
                   v_ficha.cod_cliente, v_ficha.cliente,
                   v_ficha.abrev_cliente, 
				   v_ficha.cod_ubicacion,
                   v_ficha.ubicacion, v_ficha.cod_contracto,
                   v_ficha.contracto, v_ficha.cod_n_contracto,
                   v_ficha.n_contracto, 
				   v_ficha.cod_region,
				   v_ficha.region, v_ficha.cod_banco,
				   v_ficha.banco, 
				   v_ficha.cta_banco,
				   v_ficha.carnet,  v_ficha.fec_carnet,
				   v_ficha.fec_ingreso, v_ficha.fec_profit,
				   v_ficha.fec_contracto, v_ficha.cod_us_ing,
				   v_ficha.fec_us_ing, v_ficha.cod_us_mod,
				   v_ficha.fec_us_mod, v_ficha.cod_ficha_status,
				   v_ficha.`status`, 	v_ficha.cedula,
					v_ficha.cod_cargo,
					v_ficha.cargo,
					v_ficha.cod_estado,
					v_ficha.estado,
					v_ficha.cod_ciudad,
					v_ficha.ciudad,
			 	   v_preingreso.cod_nacionalidad, v_preingreso.nacionalidad,
				   v_preingreso.cod_estado_civil, v_preingreso.estado_civil,
				   v_preingreso.cod_ocupacion, v_preingreso.ocupacion,
				   v_preingreso.cod_nivel_academico, v_preingreso.nivel_academico,
				   v_preingreso.cod_t_pantalon, v_preingreso.pantalon,
				   v_preingreso.cod_t_camisas, v_preingreso.camisa,
				   v_preingreso.cod_n_zapatos, v_preingreso.zapato,
				   	v_ficha.nombres,
					v_ficha.apellidos,
					v_ficha.ap_nombre,
					v_ficha.fec_nacimiento,
					v_ficha.lugar_nac,
					v_ficha.celular,
					v_ficha.correo,
					v_ficha.experiencia,
					v_ficha.sexo,
					v_ficha.telefono,
					v_ficha.direccion, v_preingreso.fec_preingreso,
				   v_preingreso.fec_psic, v_preingreso.psic_apto,
				   v_preingreso.psic_observacion, v_preingreso.fec_pol,
				   v_preingreso.pol_apto, v_preingreso.pol_observacion,
				   v_preingreso.observacion, v_preingreso.refp01_nombre,
				   v_preingreso.refp01_telf, v_preingreso.refp01_parentezco,
				   v_preingreso.refp01_observacion, v_preingreso.refp01_apto,
				   v_preingreso.refp02_nombre, v_preingreso.refp02_telf,
				   v_preingreso.refp02_parentezco, v_preingreso.refp02_observacion,
				   v_preingreso.refp02_apto, v_preingreso.refp03_nombre,
				   v_preingreso.refp03_telef, v_preingreso.refp03_parentezco,
				   v_preingreso.refp03_observacion, v_preingreso.refp03_apto,
				   v_preingreso.refl01_empresa, v_preingreso.refl01_telf,
				   v_preingreso.refl01_contacto, v_preingreso.refl01_observacion,
				   v_preingreso.refl01_apto, v_preingreso.refl02_empresa,
				   v_preingreso.refl02_telf, v_preingreso.refl02_contacto,
				   v_preingreso.refl02_observacion, v_preingreso.refl02_apto
              FROM v_ficha LEFT JOIN v_preingreso ON v_ficha.cedula = v_preingreso.cedula
				WHERE v_ficha.cod_ficha = '$codigo'";

	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);

	$cedula         = $result['cedula'];
	$cod_ficha      = $codigo;
	$cod_nacionalidad = $result['cod_nacionalidad'];
	$nacionalidad   = $result['nacionalidad'];
	$cod_estado_civil = $result['cod_estado_civil'];
	$estado_civil   = $result['estado_civil'];
	$nombres        = $result['nombres'];
	$apellidos        = $result['apellidos'];
	$fec_nacimiento = conversion($result['fec_nacimiento']);
	$lugar_nac      = $result['lugar_nac'];
	$carnet         = $result['carnet'];
	$fec_venc_carnet = conversion($result['fec_carnet']);
	$fec_preingreso  = conversion($result['fec_preingreso']);
	$fec_ingreso    = conversion($result['fec_ingreso']);
	$sexo           = $result['sexo'];
	$telefono       = $result['telefono'];
	$celular        = $result['celular'];
	$experiencia    = $result['experiencia'];
	$correo         = $result['correo'];
	$direccion      = $result['direccion'];
	$cod_ocupacion  = $result['cod_ocupacion'];
	$ocupacion      = $result['ocupacion'];
	$cod_estado     = $result['cod_estado'];
	$estado         = $result['estado'];
	$cod_ciudad     = $result['cod_ciudad'];
	$ciudad         = $result['ciudad'];
	$cod_cargo      = $result['cod_cargo'];
	$cargo          = $result['cargo'];
	$cod_nivel_academico      = $result['cod_nivel_academico'];
	$nivel_academico = $result['nivel_academico'];
	$cod_contracto  = $result['cod_contracto'];
	$contracto      = $result['contracto'];
	$fec_venc_contracto = conversion($result['fec_contracto']);
	$cod_n_contracto  = $result['cod_n_contracto'];
	$n_contracto    = $result['n_contracto'];
	$cod_rol        = $result['cod_rol'];
	$rol            = $result['rol'];
	$cod_region     = $result['cod_region'];
	$region         = $result['region'];
	$cod_cliente    = $result['cod_cliente'];
	$cliente        = $result['cliente'];
	$cod_ubicacion  = $result['cod_ubicacion'];
	$ubicacion      = $result['ubicacion'];
	$cod_turno      = $result['cod_turno'];
	$turno          = $result['turno'];
	$cod_banco      = $result['cod_banco'];
	$banco          = $result['banco'];
	$cta_banco      = $result['cta_banco'];

	$fec_psic       = conversion($result['fec_psic']);
	$psic_apto      = $result['psic_apto'];
	$psic_observacion = $result["psic_observacion"];
	$fec_pol        = conversion($result['fec_pol']);
	$pol_apto       = $result['pol_apto'];
	$pol_observacion = $result["pol_observacion"];
	$observacion    = $result["observacion"];
	$cod_status     = $result['cod_ficha_status'];
	$status         = $result['status'];
	$fec_profit     = conversion($result['fec_profit']);
	$fec_us_ing     = conversion($result['fec_us_ing']);
	$fec_us_mod     = conversion($result['fec_us_mod']);

	// Dotacion  
	$cod_t_pantalon = $result['cod_t_pantalon'];
	$t_pantalon     = $result['pantalon'];
	$cod_t_camisa   = $result['cod_t_camisas'];
	$t_camisa       = $result['camisa'];
	$cod_n_zapato   = $result['cod_n_zapatos'];
	$n_zapato       = $result['zapato'];

	// PARTE adiccional	
	$campo01    = $result["campo01"];
	$campo02    = $result["campo02"];
	$campo03    = $result["campo03"];
	$campo04    = $result["campo04"];

	$fic_read = ' readonly="readonly" ';
} else {
	$archivo = "formularios/Cons_ficha_eventuales&Nmenu=" . $Nmenu . "&codigo=" . $codigo . "&mod=$mod&pagina=0&metodo=modificar";
	$sql = " SELECT ficha_status.codigo AS cod_status, ficha_status.descripcion AS status
               FROM ficha_status , control 

              WHERE ficha_status.codigo = control.ficha_activo ";

	$query  = $bd->consultar($sql);
	$result02 = $bd->obtener_fila($query, 0);

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
					preingreso.cod_us_ing, preingreso.fec_us_ing,
					preingreso.cod_us_mod, preingreso.fec_us_mod,
					preingreso.observacion,
					preingreso.`status` AS cod_status, 	preing_status.descripcion AS status
               FROM preingreso, cargos, preing_status, estados, nacionalidad, estado_civil, 
			        ciudades, preing_camisas, preing_pantalon, preing_zapatos,
					nivel_academico, ocupacion
              WHERE preingreso.cod_cargo = cargos.codigo 
                AND preingreso.`status` = preing_status.codigo
				AND preingreso.cod_estado = estados.codigo
				AND preingreso.cod_nacionalidad = nacionalidad.codigo 
				AND	preingreso.cod_estado_civil = estado_civil.codigo
				AND preingreso.cod_ciudad = ciudades.codigo
                AND preingreso.cod_nivel_academico = nivel_academico.codigo 
				AND preingreso.cod_t_camisas = preing_camisas.codigo
                AND preingreso.cod_t_pantalon  = preing_pantalon.codigo
                AND preingreso.cod_n_zapatos = preing_zapatos.codigo
				AND preingreso.cod_ocupacion = ocupacion.codigo
                AND preingreso.cedula = '$codigo' ";

	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);


	$cedula         = $codigo;
	$cod_ficha       = 'E' . $codigo;
	$cod_nacionalidad = $result['cod_nacionalidad'];
	$nacionalidad   = $result['nacionalidad'];
	$cod_estado_civil = $result['cod_estado_civil'];
	$estado_civil   = $result['estado_civil'];
	$nombres        = $result['nombres'];
	$apellidos        = $result['apellidos'];
	$fec_nacimiento = conversion($result['fec_nacimiento']);
	$lugar_nac      = $result['lugar_nac'];
	$carnet         = '';
	$fec_venc_carnet = '';
	$fec_preingreso  = conversion($result['fec_preingreso']);
	$fec_ingreso    = '';
	$sexo           = $result['sexo'];
	$telefono       = $result['telefono'];
	$celular        = $result['celular'];
	$experiencia    = $result['experiencia'];
	$correo         = $result['correo'];
	$direccion      = $result['direccion'];
	$cod_ocupacion  = $result['cod_ocupacion'];
	$ocupacion      = $result['ocupacion'];
	$cod_estado     = $result['cod_estado'];
	$estado         = $result['estado'];
	$cod_ciudad     = $result['cod_ciudad'];
	$ciudad         = $result['ciudad'];
	$cod_cargo      = $result['cod_cargo'];
	$cargo          = $result['cargo'];
	$cod_nivel_academico      = $result['cod_nivel_academico'];
	$nivel_academico = $result['nivel_academico'];
	$cod_contracto  = '';
	$contracto      = 'Seleccione...';
	$fec_venc_contracto = '';
	$cod_n_contracto  = '';
	$n_contracto    = 'Seleccione...';
	$cod_rol        = '';
	$rol            = 'Seleccione...';
	$cod_region     = '';
	$region         = 'Seleccione...';
	$cod_cliente    = '';
	$cliente        = 'Seleccione...';
	$cod_ubicacion  = '';
	$ubicacion      = 'Seleccione...';
	$cod_turno      = '';
	$turno          = 'Seleccione...';
	$cod_banco      = '';
	$banco          = 'Seleccione...';
	$cta_banco      = '';
	$fec_psic       = conversion($result['fec_psic']);
	$psic_apto      = $result['psic_apto'];
	$psic_observacion = $result["psic_observacion"];
	$fec_pol        = conversion($result['fec_pol']);
	$pol_apto       = $result['pol_apto'];
	$pol_observacion = $result["pol_observacion"];
	$observacion    = $result["observacion"];
	$fec_profit     = '';
	$fec_us_ing     = '';
	$fec_us_mod     = '';
	$cod_status     = $result02["cod_status"];
	$status         = $result02["status"];


	// Dotacion  
	$cod_t_pantalon = $result['cod_t_pantalon'];
	$t_pantalon     = $result['t_pantalon'];
	$cod_t_camisa   = $result['cod_t_camisas'];
	$t_camisa       = $result['t_camisas'];
	$cod_n_zapato   = $result['cod_n_zapatos'];
	$n_zapato       = $result['n_zapatos'];

	// PARTE adiccional	
	$campo01    = '';
	$campo02    = '';
	$campo03    = '';
	$campo04    = '';

	$fic_read = ' readonly="readonly" ';

	// CONTROL DE EVENTUALES
	$sql_control = " SELECT control.contracto_eventuales, ficha_n_contracto.descripcion,
                        control.n_contracto_ecentuales, contractos.descripcion
                   FROM control , contractos , ficha_n_contracto
                  WHERE control.contracto_eventuales = contractos.codigo 
                    AND control.n_contracto_ecentuales = ficha_n_contracto.codigo ";

	$query  = $bd->consultar($sql_control);
	$result = $bd->obtener_fila($query, 0);

	$cod_contracto   =  $result[0];
	$contracto       = $result[1];
	$cod_n_contracto = $result[2];
	$n_contracto     = $result[3];

	$sql_control = " SELECT COUNT(ficha.cod_ficha) FROM ficha WHERE ficha.cod_ficha = '$cod_ficha' ";
	$query  = $bd->consultar($sql_control);
	$result = $bd->obtener_fila($query, 0);
	$existe =   $result[0];

	if ($existe >= 1) {
		echo '<script language="javascript" type="text/javascript">
							   alert("Ya Se Ingreso Un Trabajador Eventual Para Este Codigo (' . $cod_ficha . ')");	
							   Vinculo("inicio.php?area=formularios/Cons_ficha_eventuales&Nmenu=' . $Nmenu . '&mod=' . $mod . '");				
						</script>';
		exit();
	}
}

$sql_ciudad = " SELECT codigo, descripcion FROM ciudades WHERE cod_estado = '$cod_estado'
			       AND codigo <> '$cod_ciudad' ORDER BY descripcion ASC ";

?>

<form action="scripts/sc_ficha.php" method="post" name="add" id="add" enctype="multipart/form-data">
	<fieldset class="fieldset">
		<legend>Modificar Datos B&aacute;sicos Trabajadores </legend>
		<table width="98%" align="center">
			<tr>
				<td width="20%" class="etiqueta">&nbsp;</td>
				<td width="25%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="25%">&nbsp;</td>
				<td width="10%" rowspan="16" align="left">
					<?php

					$filename = "imagenes/fotos/$cedula.jpg";

					if (file_exists($filename)) {
						echo '<img src="' . $filename . '?nocache='.time().'" width="110px" height="130px" />';
					} else {
						echo '<img src="imagenes/img_no_disp.png" width="110px" height="130px"/>';
					} ?>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Cedula:</td>
				<td id="input01"><input type="text" name="cedula" maxlength="16" size="15" value="<?php echo $cedula; ?>" readonly="readonly" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>

				</td>
				<td class="etiqueta">N. Ficha:</td>
				<td id="input02"><input type="text" name="cod_ficha" id="cod_ficha" maxlength="12" style="width:120px" value="<?php echo $cod_ficha; ?>" <?php echo $fic_read; ?> /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
					<span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 2 caracteres.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Nacionalidad: </td>
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
				<td class="etiqueta">Estado Civil: </td>
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
				<td id="input04"><input type="text" name="apellido" maxlength="60" size="25" value="<?php echo $apellidos; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">Nombres: </td>
				<td id="input05"><input type="text" name="nombre" maxlength="60" size="25" value="<?php echo $nombres; ?>" /><br />
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
				<td colspan="2">&nbsp;</td>
				<td class="etiqueta">Fotos (.jpg):</td>
				<td id="input03_1"><input type="file" name="file" id="file" style="width:195px" value="" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Posee Carnet:</td>
				<td id="radio01" class="texto">SI
					<input type="radio" name="carnet" value="S" style="width:auto" <?php echo CheckX($carnet, 'S') ?> />
					NO<input type="radio" name="carnet" value="N" style="width:auto" <?php echo CheckX($carnet, 'N') ?> />
					<span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
				</td>
				<td class="etiqueta">Fecha Vencimiento Carnet:</td>
				<td id="fecha02">
					<input type="text" name="fec_venc_carnet" value="<?php echo $fec_venc_carnet; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Tel&eacute;fono: </td>
				<td id="input06"><input type="text" name="telefono" maxlength="40" size="25" value="<?php echo $telefono; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
				</td>
				<td class="etiqueta">Tel. Celular: </td>
				<td id="input07"><input type="text" name="celular" maxlength="40" size="25" value="<?php echo $celular; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
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
				<td class="etiqueta">Ocupacion: </td>
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

				<td class="etiqueta">correo: </td>
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
				<td class="etiqueta">Estado:</td>
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
				<td class="etiqueta">Ciudad:</td>
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
				<td class="etiqueta">cargo: </td>
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
				<td class="etiqueta">Contrato:</td>
				<td id="select07"><select name="contracto" style="width:200px">
						<option value="<?php echo $cod_contracto; ?>"><?php echo $contracto; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM contractos 
		                     WHERE status = 'T'  AND codigo <> '$cod_contracto' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
				<td class="etiqueta">Numeros Contracto:</td>
				<td id="select08"><select name="n_contracto" style="width:200px">
						<option value="<?php echo $cod_n_contracto; ?>"><?php echo $n_contracto; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM ficha_n_contracto 
		                      WHERE status = 'T'  AND codigo <> '$cod_n_contracto' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Fecha Venc. Contracto:</td>
				<td id="fecha03">
					<input type="text" name="fec_venc_contracto" value="<?php echo $fec_venc_contracto; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
				<td class="etiqueta">Roles:</td>
				<td id="select09"><select name="rol" style="width:200px">
						<option value="<?php echo $cod_rol; ?>"><?php echo $rol; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM roles 
		                      WHERE status = 'T' AND codigo <> '$cod_rol' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>

			<tr>
				<td class="etiqueta">Region:</td>
				<td id="select10"><select name="region" style="width:200px">
						<option value="<?php echo $cod_region; ?>"><?php echo $region; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM regiones 
		                      WHERE status = 'T'  AND codigo <> '$cod_region' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta">turno:</td>
				<td id="select13"><select name="turno" style="width:200px">
						<option value="<?php echo $cod_turno; ?>"><?php echo $turno; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM turno 
		                      WHERE status = 'T'  AND codigo <> '$cod_turno' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			</tr>
			<tr>
				<td class="etiqueta">Cliente:</td>
				<td id="select11"><select name="cliente" id="cliente" style="width:200px" onchange="Add_ajax01(this.value, 'ajax/Add_cl_ubicacion.php', 'cl_ubicacion')">
						<option value="<?php echo $cod_cliente; ?>"><?php echo $cliente; ?></option>
						<?php $sql = " SELECT clientes.codigo, clientes.nombre FROM clientes
                              WHERE clientes.`status` = 'T' AND codigo <> '$cod_cliente' 
						      ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta">Ubicacion:</td>
				<td id="cl_ubicacion"><select name="ubicacion" style="width:200px">
						<option value="<?php echo $cod_ubicacion; ?>"><?php echo $ubicacion; ?></option>
						<?php $sql = " SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
                               FROM clientes_ubicacion
                              WHERE clientes_ubicacion.cod_cliente = clientes_ubicacion.cod_cliente  
                                AND clientes_ubicacion.`status` = 'T'  
								AND clientes_ubicacion.codigo <> '$cod_ubicacion' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>

			</tr>
			<tr>
				<td class="etiqueta">Banco:</td>
				<td id="select14"><select name="banco" style="width:200px">
						<option value="<?php echo $cod_banco; ?>"><?php echo $banco; ?></option>
						<?php $sql = " SELECT codigo, descripcion FROM bancos 
		                      WHERE status = 'T'  AND codigo <> '$cod_banco' ORDER BY 2 ASC ";
						$query = $bd->consultar($sql);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
				<td class="etiqueta">Cta. Banco:</td>
				<td id="input05"><input type="text" name="cta_banco" maxlength="20" style="width:200px" value="<?php echo $cta_banco; ?>" />
					<br /><span class="textfieldRequiredMsg">El Campo es Requerido.</span>
					<span class="textfieldMinCharsMsg">Debe Escribir 20 caracteres.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">0bservacion:</td>
				<td id="textarea02" colspan="3"><textarea name="observacion" cols="50" rows="3"><?php echo $observacion; ?></textarea>
					<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
					<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Fecha De Sistema:</td>
				<td id="fecha04">
					<input type="text" name="fecha_sistema" value="<?php echo $fec_us_ing; ?>" readonly="true" />
				</td>
				<td class="etiqueta">Fecha Sistema De Integraci&oacute;n:</td>
				<td id="fecha05"><input type="text" name="fec_profit" value="<?php echo $fec_profit; ?>" /></td>
			</tr>
			<tr>
				<td class="etiqueta">Fecha de Preingreso:</td>
				<td id="fecha06">
					<input type="text" name="fec_ingreso" value="<?php echo $fec_preingreso; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
				<td class="etiqueta">Fecha de Ingreso:</td>
				<td id="fecha07">
					<input type="text" name="fec_ingreso" value="<?php echo $fec_ingreso; ?>" /><br />
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Fecha De Ultima Actualizacion:</td>
				<td id="fecha08">
					<input type="text" name="fec_act" value="<?php echo $fec_us_mod; ?>" readonly="true" />
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
				<td height="8" colspan="4" align="center">
					<hr>
				</td>
			</tr>
		</table>
		<div align="center">
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
			<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
			<input name="pestana" type="hidden" value="ficha" />
			<input name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
			<input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>" />
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" />
			<input name="proced" id="proced" type="hidden" value="<?php echo $proced; ?>" />
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
		minChars: 4,
		validateOn: ["blur", "change"]
	});
	var input02 = new Spry.Widget.ValidationTextField("input02", "none", {
		minChars: 2,
		validateOn: ["blur", "change"]
	});
	var input03 = new Spry.Widget.ValidationTextField("input03", "none", {
		minChars: 3,
		validateOn: ["blur", "change"],
		isRequired: false
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
	var input07 = new Spry.Widget.ValidationTextField("input07", "none", {
		validateOn: ["blur", "change"]
	});
	var input08 = new Spry.Widget.ValidationTextField("input08", "none", {
		validateOn: ["blur", "change"]
	});
	/*
	var input05 = new Spry.Widget.ValidationTextField("input10", "integer", {minChars:20, validateOn:["blur", "change"],isRequired:false});
	*/

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
		useCharacterMasking: true,
		isRequired: false
	});
	var fecha03 = new Spry.Widget.ValidationTextField("fecha03", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true,
		isRequired: false
	});
	var fecha04 = new Spry.Widget.ValidationTextField("fecha04", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true,
		isRequired: false
	});
	var fecha05 = new Spry.Widget.ValidationTextField("fecha05", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});
	var fecha06 = new Spry.Widget.ValidationTextField("fecha06", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});
	var fecha07 = new Spry.Widget.ValidationTextField("fecha07", "date", {
		format: "dd-mm-yyyy",
		hint: "DD-MM-AAAA",
		validateOn: ["blur", "change"],
		useCharacterMasking: true
	});

	var radio01 = new Spry.Widget.ValidationRadio("radio01", {
		validateOn: ["change", "blur"]
	});
	var radio02 = new Spry.Widget.ValidationRadio("radio02", {
		validateOn: ["change", "blur"]
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
	var textarea03 = new Spry.Widget.ValidationTextarea("textarea03", {
		maxChars: 120,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess3",
		useCharacterMasking: false,
		isRequired: false
	});
	var textarea04 = new Spry.Widget.ValidationTextarea("textarea04", {
		maxChars: 255,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess4",
		useCharacterMasking: false,
		isRequired: false
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
	var select07 = new Spry.Widget.ValidationSelect("select07", {
		validateOn: ["blur", "change"]
	});
	var select08 = new Spry.Widget.ValidationSelect("select08", {
		validateOn: ["blur", "change"]
	});
	var select09 = new Spry.Widget.ValidationSelect("select09", {
		validateOn: ["blur", "change"]
	});
	var select10 = new Spry.Widget.ValidationSelect("select10", {
		validateOn: ["blur", "change"]
	});
	var select11 = new Spry.Widget.ValidationSelect("select11", {
		validateOn: ["blur", "change"]
	});
	// var select12 = new Spry.Widget.ValidationSelect("select12", {validateOn:["blur", "change"]});
	var select13 = new Spry.Widget.ValidationSelect("select13", {
		validateOn: ["blur", "change"]
	});
	var select14 = new Spry.Widget.ValidationSelect("select14", {
		validateOn: ["blur", "change"]
	});
</script>