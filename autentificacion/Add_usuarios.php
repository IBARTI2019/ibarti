<?php
$Nmenu = '004';
$proced      = "p_usuario";
require_once('autentificacion/aut_verifica_menu.php');
$metodo = $_GET['metodo'];
$mod   = $_GET['mod'];
$titulo = "Usuario de Sistema";
$vinculo = "../inicio.php?area=autentificacion/Cons_usuarios&Nmenu=$Nmenu&mod=$mod";
if (isset($_GET['codigo'])) { //== ''

	$titulo = " Modificar " . $titulo . "";
	$codigo = $_GET['codigo'];
	$archivo = "empresa";
	$archivo2 = "autentificacion/cons_perfil&Nmenu=$Nmenu";

	$sql = " SELECT men_usuarios.cod_perfil, men_perfiles.descripcion AS perfil, 
	                men_usuarios.cedula,
                    men_usuarios.nombre, men_usuarios.apellido,
                    men_usuarios.login, men_usuarios.email,
					men_usuarios.r_cliente, men_usuarios.r_rol,
                    men_usuarios.status, men_usuarios.admin_kanban,
					men_usuarios.admin_rrhh,
					men_usuarios.cod_region, IFNULL(regiones.descripcion, 'TODAS') region,
					men_usuarios.cod_estado, IFNULL(estados.descripcion, 'TODAS') estado,
					men_usuarios.cod_ciudad, IFNULL(ciudades.descripcion, 'TODAS') ciudad
               FROM men_usuarios LEFT JOIN regiones ON men_usuarios.cod_region = regiones.codigo
			   LEFT JOIN estados ON men_usuarios.cod_estado = estados.codigo
			   LEFT JOIN ciudades ON men_usuarios.cod_ciudad = ciudades.codigo, men_perfiles
              WHERE men_usuarios.cod_perfil = men_perfiles.codigo
			    AND men_usuarios.codigo = '$codigo' ";
	$query = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);

	$cedula     = $result['cedula'];
	$nombre     = $result['nombre'];
	$apellido   = $result['apellido'];
	$email      = $result['email'];
	$login      = $result['login'];
	$cod_perfil = $result['cod_perfil'];
	$perfil     = $result['perfil'];
	$cod_region = $result['cod_region'];
	$region     = $result['region'];
	$cod_estado = $result['cod_estado'];
	$estado     = $result['estado'];
	$cod_ciudad = $result['cod_ciudad'];
	$ciudad     = $result['ciudad'];
	$r_cliente  = $result['r_cliente'];
	$r_rol      = $result['r_rol'];
	$admin_kanban = $result['admin_kanban'];
	$admin_rrhh = $result['admin_rrhh'];
	$status     = $result['status'];
	$disabled   = 'readonly="true"';
	$metodo     = 'modificar';
	$check_pass     = '<input name="check"  id"check" type="checkbox"  value="S"/>';
} else {

	$codigo     = '';
	$cedula     = '';
	$titulo     = " Agregar " . $titulo . "";
	$nombre     = '';
	$apellido   = '';
	$email      = '';
	$login      = '';
	$cod_perfil = '';
	$perfil     = 'Seleccione...';
	$cod_region = '';
	$region = 'TODAS';
	$cod_estado = '';
	$estado     = 'TODAS';
	$cod_ciudad = '';
	$ciudad     = 'TODAS';
	$r_cliente  = '';
	$r_rol      = '';
	$admin_kanban      = '';
	$admin_rrhh      = '';
	$status     = '';
	$disabled   = '';
	$metodo     = 'agregar';
	$check_pass = '<input name="check" id="check"  type="hidden" value="N" />';
}

?>
<form action="autentificacion/sc_usuarios.php" method="post" name="mod_usuarios" id="mod_usuarios">
	<fieldset class="fieldset">
		<legend><?php echo $titulo; ?> </legend>
		<table width="80%" align="center">

			<tr>
				<td class="etiqueta">Codigo:</td>
				<td id="input00"><input type="text" id="checkboxC" name="codigo" onblur="validUserType('codigo',this.value,'checkboxC')" maxlength="11" value="<?php echo $codigo; ?>" <?php echo $disabled; ?> style="width:120px" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
					<p id="checkboxCC" invalido</p>

				</td>
			</tr>
			<tr>
				<td class="etiqueta">C&eacute;dula:</td>
				<td id="input01"><input type="text" name="cedula" maxlength="15" value="<?php echo $cedula; ?>" style="width:120px" />
					Activo: <input name="status" type="checkbox" <?php echo statusCheck($status); ?> value="T" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Nombres:</td>
				<td id="input02"><input type="text" name="nombre" maxlength="120" style="width:200px" value="<?php echo $nombre; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Apellidos: </td>
				<td id="input03"><input type="text" name="apellido" maxlength="120" style="width:200px" value="<?php echo $apellido; ?>" /><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Email:</td>
				<td id="email"><input name="email" type="text" maxlength="60" style="width:250px" value="<?php echo $email; ?>"><br />
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Login:</td>
				<td id="input04"><input name="login" type="text" maxlength="15" id="checkboxL" onblur="validUserType('login',this.value,'checkboxL')" style="width:120px" value="<?php echo $login; ?>" /><br />
					<p id="checkboxLC" style="float:left;"></p>
					<span class="textfieldRequiredMsg">El Campo es Requerido.</span>
					<span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Password:</td>
				<td><?php echo $check_pass; ?>
					<span id="customPasswordFunction">
						<input name="password1" id="password1" type="password" style="width:120px" maxlength="11" value="<?php echo ""; ?>" /><br>
						<span class="textfieldRequiredMsg">El Valor es Requerido.</span>
						<span class="textfieldInvalidFormatMsg"> Minimo (2 Numeros, 2 numero alpha numerico), de 8 a 10 caracteres.</span>
					</span>
					</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Password (repitalo):</td>
				<td id="dupFunction">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="password2" id="password2" type="password" style="width:120px" maxlength="11" value="<?php echo ""; ?>"><br>
					<span class="textfieldRequiredMsg">El Valor es Requerido.</span>
					<span class="textfieldInvalidFormatMsg">El passwords es diferente.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Perfil:</td>
				<td id="select01">
					<select name="perfil" style="width:200px">
						<option value="<?php echo $cod_perfil; ?>"><?php echo $perfil; ?></option>
						<?php
						$sql = " SELECT codigo, descripcion FROM men_perfiles WHERE status = 'T' ORDER BY 2 ASC ";
						$query01 = $bd->consultar($sql);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
						?>
							<option value="<?php echo $row01[0]; ?>"><?php echo $row01[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Región:</td>
				<td>
					<select name="region" style="width:200px">
						<option value="<?php echo $cod_region; ?>"><?php echo $region; ?></option>
						<?php
						$sql = " SELECT codigo, descripcion FROM regiones WHERE status = 'T' ORDER BY 2 ASC ";
						$query01 = $bd->consultar($sql);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
						?>
							<option value="<?php echo $row01[0]; ?>"><?php echo $row01[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Estado:</td>
				<td>
					<select name="estado" style="width:200px">
						<option value="<?php echo $cod_estado; ?>"><?php echo $estado; ?></option>
						<?php
						$sql = " SELECT codigo, descripcion FROM estados WHERE status = 'T' ORDER BY 2 ASC ";
						$query01 = $bd->consultar($sql);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
						?>
							<option value="<?php echo $row01[0]; ?>"><?php echo $row01[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Ciudad:</td>
				<td>
					<select name="ciudad" style="width:200px">
						<option value="<?php echo $cod_ciudad; ?>"><?php echo $ciudad; ?></option>
						<?php
						$sql = " SELECT codigo, descripcion FROM ciudades WHERE status = 'T' ORDER BY 2 ASC ";
						$query01 = $bd->consultar($sql);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
						?>
							<option value="<?php echo $row01[0]; ?>"><?php echo $row01[1]; ?></option>
						<?php } ?>
					</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Restringir Cliente:</td>
				<td id="radio01">SI<input type="radio" name="r_cliente" value="T" style="width:auto" <?php echo CheckX($r_cliente, "T"); ?> /> NO<input type="radio" name="r_cliente" value="F" style="width:auto" <?php echo CheckX($r_cliente, "F"); ?> />
					<br /><span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Restringir Rol:</td>
				<td id="radio02">SI<input type="radio" name="r_rol" value="T" style="width:auto" <?php echo CheckX($r_rol, "T"); ?> /> NO<input type="radio" name="r_rol" value="F" style="width:auto" <?php echo CheckX($r_rol, "F"); ?> />
					<br /><span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Administrador Kanban:</td>
				<td id="radio03">SI<input type="radio" name="admin_kanban" value="T" style="width:auto" <?php echo CheckX($admin_kanban, "T"); ?> /> NO<input type="radio" name="admin_kanban" value="F" style="width:auto" <?php echo CheckX($admin_kanban, "F"); ?> />
					<br /><span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Administrador RRHH:</td>
				<td id="radio03">SI<input type="radio" name="admin_rrhh" value="T" style="width:auto" <?php echo CheckX($admin_rrhh, "T"); ?> /> NO<input type="radio" name="admin_rrhh" value="F" style="width:auto" <?php echo CheckX($admin_rrhh, "F"); ?> />
					<br /><span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
				</td>
			</tr>
			<tr>
				<td height="8" colspan="2" align="center">
					<hr>
				</td>
			</tr>
		</table>
		<div align="center"><span class="art-button-wrapper">
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
			<input name="proced" id="proced" type="hidden" value="<?php echo $proced; ?>" />
			<input name="passOLD" type="hidden" value="" />
			<input name="as_orden" type="hidden" value="" />
			<input name="usuario" id="usuario" type="hidden" value="<?php echo $usuario; ?>" />
			<input name="href" type="hidden" value="<?php echo $vinculo; ?>" />
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	var input00 = new Spry.Widget.ValidationTextField("input00", "none", {
		validateOn: ["blur", "change"]
	});
	var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
		validateOn: ["blur", "change"]
	});
	var input02 = new Spry.Widget.ValidationTextField("input02", "none", {
		validateOn: ["blur", "change"]
	});
	var input03 = new Spry.Widget.ValidationTextField("input03", "none", {
		validateOn: ["blur", "change"]
	});
	var input04 = new Spry.Widget.ValidationTextField("input04", "none", {
		minChars: 4,
		validateOn: ["blur", "change"]
	});

	var radio01 = new Spry.Widget.ValidationRadio("radio01", {
		validateOn: ["change", "blur"]
	});
	var radio02 = new Spry.Widget.ValidationRadio("radio02", {
		validateOn: ["change", "blur"]
	});
	var radio03 = new Spry.Widget.ValidationRadio("radio03", {
		validateOn: ["change", "blur"]
	});
	var select01 = new Spry.Widget.ValidationSelect("select01", {
		validateOn: ["blur", "change"]
	});
	var email = new Spry.Widget.ValidationTextField("email", "email", {
		validateOn: ["blur"]
	});

	var passwordStrength = function(value, options) {

		if (value.length < 8 || value.length > 10)
			return false;

		if (value.match(/[0-9]/g).length < 2)
			return false;

		if (value.match(/[a-z]/g).length < 2)
			return false;
		/*
			if (value.replace(/[0-9a-z]/g, '').length == 0)
				return false;  */
		return true;
	}

	var passwordTheSame = function(value, options) {
		var other_value = document.getElementById('password1').value;
		if (value != other_value) {
			return false;
		}
		return true;
	}

	function validUserType(type, value, selector) {
		if (value) {
			let newvalue = 'login' ? value.toUpperCase() : value;
			var parametros = {
				q: newvalue,
				filtro: type
			}
			$.ajax({
				data: parametros,
				url: 'autocompletar/tb/usuario.php',
				type: 'get',
				success: function(response) {
					if (Number(response)) {
						$('#' + selector).val('');
						$('#' + selector + "C").html('invalido');
					} else {
						$('#' + selector + "C").html('valido');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
	}

	var customFunction = new Spry.Widget.ValidationTextField("customPasswordFunction", "custom", {
		validation: passwordStrength,
		validateOn: ["blur", "change"],
		isRequired: false
	});
	var passwordDuplication = new Spry.Widget.ValidationTextField("dupFunction", "custom", {
		validation: passwordTheSame,
		validateOn: ["change", "blur"],
		isRequired: false
	});
</script>