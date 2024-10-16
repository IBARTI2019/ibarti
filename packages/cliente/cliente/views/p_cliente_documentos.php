<?php
//	require_once('autentificacion/aut_verifica_menu.php');
$proced      = "p_clientes_documentos";
$metodo       = "agregar";
if (isset($_POST['codigo'])) {
	$codigo = $_POST['codigo'];
	define("SPECIALCONSTANT", true);
	include_once('../../../../funciones/funciones.php');
	require("../../../../autentificacion/aut_config.inc.php");
	require_once("../../../../" . class_bdI);
	$bd = new DataBase();
} else {
	$codigo = $cl['codigo'];
}
$archivo = "area=packages/cliente/cliente/index&Nmenu=&codigo=$codigo&metodo=modificar";
?>
<form id="addDoc">
	<hr>
	<legend>Documentos </legend>
	<hr>
	<table width="100%" align="center">
		<tr>
			<td width="26%" class="etiqueta">Documentos:</td>
			<td width="11%" class="etiqueta">Check:</td>
			<td width="18%" class="etiqueta">observación:</td>
			<td width="12%" class="etiqueta">Descargar - Subir</td>
			<td width="25%" class="etiqueta">Vencimiento - Fecha</td>
			<td width="8%" class="etiqueta">Fec. Ult. Mod.</td>

		</tr>
		<?php
		$sql = "SELECT count(clientes_documentos.cod_documento) as cantidad
		FROM documentos_cl, clientes_documentos
	   WHERE clientes_documentos.cod_cliente = '$codigo'
			 AND clientes_documentos.cod_documento = documentos_cl.codigo
		 AND documentos_cl.`status` = 'T'
		 ORDER BY documentos_cl.orden ASC ";
		$query = $bd->consultar($sql);
		$existe = $bd->obtener_fila($query, 0);
		if (intval($existe['cantidad']) > 0) {
			$metodo       = "modificar";
			$sql = " SELECT clientes_documentos.cod_documento, clientes_documentos.`checks`,
			clientes_documentos.`link`, clientes_documentos.observacion,
							clientes_documentos.vencimiento, clientes_documentos.venc_fecha,
					clientes_documentos.fec_us_mod,
				documentos_cl.descripcion, control.url_doc
			FROM documentos_cl, clientes_documentos, control
			WHERE clientes_documentos.cod_cliente = '$codigo'
				AND clientes_documentos.cod_documento = documentos_cl.codigo
			AND documentos_cl.`status` = 'T'
			ORDER BY documentos_cl.orden ASC ";
		} else {
			$metodo       = "agregar";

			$sql = "SELECT
			documentos_cl.codigo as cod_documento,
			'N' as `checks`,
			'' as `link`,
			'' as observacion,
			'N' as vencimiento,
		''  as venc_fecha,
		''	as fec_us_mod,
			documentos_cl.descripcion,
			control.url_doc
		FROM
			documentos_cl,
			control
		WHERE
			documentos_cl.`status` = 'T'
		ORDER BY
			documentos_cl.orden ASC";
		}
		$query = $bd->consultar($sql);

		while ($datos = $bd->obtener_fila($query, 0)) {
			extract($datos);
			$img_src = $url_doc . "" . $link;
			if (file_exists("../../../../" . $link) && $link != null && $link != "") {

				$img_ext =  imgExtension($img_src);
				$img_src = 	'<a target="_blank" href="' . $img_src . '"><img src="' . $img_ext . '" width="22px" height="22px" /></a>';
			} else {
				$img_src = 	'<img src="imagenes/img-no-disponible_p.png" width="22px" height="22px" />';
			}
			//$subir = "Vinculo('inicio.php?area=formularios/add_imagenes_doc_cl&cliente=$codigo&doc=$cod_documento')";
			$subir = "modalUpload('$codigo', '$cod_documento')";
			echo '<tr>
							<td class="texto">' . longitudMax($descripcion) . '</td>
							<td class="texto">SI <input type = "radio" name="documento' . $cod_documento . '"  value = "S" style="width:auto"
														' . CheckX($checks, 'S') . '/>NO <input type = "radio" name="documento' . $cod_documento . '"
														value = "N" style="width:auto" ' . CheckX($checks, 'N') . '/><input type="hidden"                                                     name="documento_old' . $cod_documento . '" value = "' . $checks . '"/></td>
							<td><textarea name="observ_doc' . $cod_documento . '" cols="20" rows="1">' . $observacion . '</textarea></td>
							<td>' . $img_src . ' - <a target="_blank" onClick="' . $subir . '"><img class="ImgLink" src="imagenes/subir.gif" width="22px" height="22px" /></a></td>
							<td class="texto">SI <input type = "radio" name="vencimiento' . $cod_documento . '"  value = "S" style="width:auto"
																					' . CheckX($vencimiento, 'S') . '/>NO <input type = "radio"
																					name="vencimiento' . $cod_documento . '" value = "N" style="width:auto"
														' . CheckX($vencimiento, 'N') . '/><input type="date" name="fecha_venc' . $cod_documento . '"
													id="fecha_venc' . $cod_documento . '"  value="' . $venc_fecha . '"/><input type="hidden" "
														name="fecha_venc_old' . $cod_documento . '" value = "' . $venc_fecha . '"/>
							</td>
	
						<td class="texto">' . $fec_us_mod . '</td>
						</tr>';
		} ?>
	</table>
	<div align="center"><span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="salvar" id="salvar" value="Guardar" onclick="saveDocuments()" class="readon art-button" />
		</span>&nbsp;
		<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
		<input name="proced" type="hidden" value="<?php echo $proced; ?>" />
		<input name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
		<input name="usuario" type="hidden" value="<?php echo $usuario; ?>" />
		<input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>" />
	</div>
	</fieldset>
</form>

<script language="javascript" type="text/javascript">
	function spryFecVenc(ValorN) {
		var ValorN = new Spry.Widget.ValidationTextField(ValorN, "date", {
			format: "dd-mm-yyyy",
			hint: "DD-MM-AAAA",
			validateOn: ["blur", "change"],
			useCharacterMasking: true
		});
	}
</script>