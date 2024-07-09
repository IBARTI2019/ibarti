<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu = '4410';
if (isset($_SESSION['usuario_cod'])) {
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report.php');
 	$us = $_SESSION['usuario_cod'];
} else {
  $us = $_POST['usuario'];
}
$titulo = "CHECK LIST PARTICIPANTE";
$archivo = "novedades_check_list";

define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
require "../../../../" . Leng;
$bd = new DataBase();

$sql    = " SELECT control.cl_campo_04_desc  FROM control ";
$query  = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);
$campo_04  = $result[0];

$proced    = "p_nov_check_list";
$proced2   = "p_nov_check_list_det";
$proced3   = "p_nov_check_list_max";

$sql   = "SELECT codigo, descripcion FROM nov_status, control
			WHERE status =  'T'
				AND nov_status.codigo = control.novedad ";
$query     = $bd->consultar($sql);
$row02     = $bd->obtener_fila($query, 0);

$titulo       = "AGREGAR $titulo";
$codigo       = $_POST['codigo'];

$sql = "SELECT
p.cod_ficha,
v_ficha.ap_nombre,
clientes.codigo cod_cliente,
clientes.nombre cliente,
cu.codigo cod_ubicacion,
cu.descripcion ubicacion,
pd.cod_proyecto,
pp.descripcion proyecto,
pd.cod_actividad,
pa.descripcion actividad 
FROM
planif_clientes_superv_trab_det_participantes p,
planif_clientes_superv_trab_det pd,
planif_clientes_superv_trab pt,
v_ficha,
clientes,
clientes_ubicacion cu,
planif_actividad pa,
planif_proyecto pp 
WHERE
p.codigo = $codigo
AND p.cod_det = pd.codigo 
AND p.cod_ficha = v_ficha.cod_ficha 
AND pd.cod_planif_cl_trab = pt.codigo 
AND pt.cod_cliente = clientes.codigo 
AND pt.cod_ubicacion = cu.codigo 
AND pd.cod_actividad = pa.codigo 
AND pd.cod_proyecto = pp.codigo";


$query = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);

$cod_ficha    = $result['cod_ficha'];;
$trabajador   = $result['ap_nombre'];;
$cod_cliente  = $result['cod_cliente'];;
$cliente      = $result['cliente'];
$cod_ubicacion = $result['cod_ubicacion'];
$ubicacion    =  $result['ubicacion'];
$proyecto    =  $result['proyecto'];
$cod_actividad    =  $result['cod_actividad'];
$actividad    =  $result['actividad'];
$respuesta    = '';
$observacion  = '';
$contato      = '';
$campo_04_d   = '';

$cod_status   = $row02[0];
$status       = $row02[1];

$sql2 = "SELECT
novedades.cod_nov_clasif,
novedades.cod_nov_tipo
FROM
nov_planif_actividad,
novedades 
WHERE
nov_planif_actividad.cod_actividad = $cod_actividad 
AND nov_planif_actividad.cod_novedad = novedades.codigo
LIMIT 1;";

$query2 = $bd->consultar($sql2);
$result2 = $bd->obtener_fila($query2, 0);

$clasif  =  $result2['cod_nov_clasif'];
$tipo = $result2['cod_nov_tipo'];

$actividad    =  $result['actividad'];
$fecha_sistema = date("Y-m-d");
$hora          = date("H:i:s");
$us_mod        = '';
$fec_us_mod    = '';
?>
<form name="add_check_list" id="add_check_list">
	<div id="Contenedor01"></div>
	<table width="100%" align="center">
		<tr valign="top">
			<td height="23" colspan="2" class="etiqueta_title" align="center"><?php echo $titulo; ?></td>
		</tr>
		<tr>
			<td height="8" colspan="4" align="center">
				<hr>
			</td>
		</tr>
		<tr>
			<td class="etiqueta" width="15%">Codigo:</td>
			<td width="25%"><input name="codigo" type="text" readonly="readonly" value="<?php echo $codigo; ?>" /></td>
			<td class="etiqueta" width="15%">Fecha De Sistema:</td>
			<td id="fecha01" width="25%"><input type="text" size="20" value="<?php echo $fecha_sistema . ' &nbsp; ' . $hora; ?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td class="etiqueta">Participante:</td>
			<td colspan="3"><?php echo $trabajador; ?></td>
		</tr>
		<tr>
			<td class="etiqueta"><?php echo $leng["cliente"]; ?>:</td>
			<td><?php echo $cliente; ?></td>
			<td class="etiqueta"><?php echo $leng["ubicacion"]; ?>:</td>
			<td><?php echo $ubicacion; ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Observacion:</td>
			<td id="textarea01"><textarea name="observacion" id="observacion" cols="42" rows="2"><?php echo $observacion; ?></textarea>
				<span id="Counterror_mess1" class="texto">&nbsp;</span><br />
				<span class="textareaRequiredMsg">El Campo es Requerido.</span>
				<span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
				<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 500.</span>
			</td>
			<td class="etiqueta">Repuesta:</td>
			<td id="textarea02"><textarea name="repuesta" id="repuesta"  cols="42" rows="2" readonly="readonly"><?php echo $respuesta; ?></textarea>
				<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
				<span class="textareaRequiredMsg">El Campo es Requerido.</span>
				<span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
				<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 500.</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Status:</td>
			<td id="select10"><select name="status" id="status" style="width:200px">
					<option value="<?php echo $cod_status; ?>"><?php echo $status; ?></option>
				</select><br />
				<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
			</td>
		</tr>
		<tr>
				<td class="etiqueta" width="15%">Proyecto:</td>
				<td width="25%"><?php echo $proyecto; ?> </td>
				<td class="etiqueta">Actividad:</td>
				<td><?php echo $actividad; ?></td>
			</tr>
	</table>
	<br>
	<br>
	<div class="listar" width="100%">
		<table width="100%" align="center">
			<tr>
				<td class="etiqueta" width="45%">Check List:</td>
				<td class="etiqueta" width="15%">Valor:</td>
				<td class="etiqueta" width="40%">Observacion:</td>
			</tr>
			<?php
				
				$sql   = " SELECT
							novedades.codigo,
							novedades.descripcion,
							novedades.cod_nov_agrupacion
						FROM
							nov_planif_actividad,
							novedades 
						WHERE
							nov_planif_actividad.cod_actividad = '$cod_actividad' 
							AND nov_planif_actividad.cod_novedad = novedades.codigo 
							AND novedades.`status` = 'T' 
						ORDER BY
							novedades.orden,
						2 ASC";
				$query = $bd->consultar($sql);
				while ($datos = $bd->obtener_fila($query, 0)) {
					$cod_c = $datos[0];

					$sql02 = " SELECT nov_valores.codigo, nov_valores.abrev ,nov_valores.descripcion
								FROM nov_valores_det , nov_valores
								WHERE nov_valores_det.cod_novedades = '$cod_c'
								AND nov_valores_det.cod_valores = nov_valores.codigo
							ORDER BY 2 ASC ";
					$query02 = $bd->consultar($sql02);

					echo '<tr>
				<td><textarea disabled="disabled" cols="60">' . $datos[1] . '</textarea></td>
				<td>';
					while ($datos02 = $bd->obtener_fila($query02, 0)) {
						echo ' ' . $datos02[1] . ' <input type = "radio"  name="check_list_valor_' . $cod_c . '" value ="' . $datos02[0] . '" style="width:auto" title="' . $datos02[2] . '" />';
					}
					echo '<input type="hidden" name="cod_agrupacion_' . $cod_nov  . '" value="' . $datos[2] . '" /><input type="hidden" name="cod_valor_' . $cod_c . '" value="' . $datos[0] . '" /><input type="hidden" name="check_list[]" value="' . $datos[0] . '" /> </td>
				<td><textarea  name="observacion_' . $datos[0] . '" cols="50" rows="1"></textarea>
				</tr>';
				}
				mysql_free_result($query); 
			?>
		</table>
	</div>
	<div align="center">
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="salvar" id="salvar" value="Guardar" class="readon art-button" onclick="guardarCheckList()" />
		</span>&nbsp;
		&nbsp;
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" id="volver" value="Volver" onClick="cerrarModalCheckList()" class="readon art-button" />
		</span>
		<input name="proced" type="hidden" value="<?php echo $proced; ?>" />
		<input name="proced2" type="hidden" value="<?php echo $proced2; ?>" />
		<input name="proced3" type="hidden" value="<?php echo $proced3; ?>" />
		<input name="href" type="hidden" value="<?php echo $href; ?>" />
		<input name="perfil" id="perfil" type="hidden" value="<?php echo $_SESSION['cod_perfil']; ?>" />
		<input type="hidden" value="<?php echo $cod_status; ?>" />
		<input type="hidden" name="descripcion" value="" />
		<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
		<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
		<input type="hidden" name="usuario" id="usuario" value="<?php echo $us; ?>" />
		<input type="hidden" name="stdID" id="stdID" value="<?php echo $us; ?>" />
		<input type="hidden" name="trabajador" id="trabajador" value="<?php echo $cod_ficha; ?>" />
		<input type="hidden" name="cliente" id="cliente" value="<?php echo $cod_cliente; ?>" />
		<input type="hidden" name="ubicacion" id="ubicacion" value="<?php echo $cod_ubicacion; ?>" />
		<input type="hidden" name="clasif" id="clasif" value="<?php echo $clasif; ?>" />
		<input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
		<input type="hidden" name="cod_participante_det" id="cod_participante_det" value="<?php echo $codigo; ?>" />
	</div>
	<br>
</form>
</body>

</html>