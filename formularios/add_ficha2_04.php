<?php
//	require_once('autentificacion/aut_verifica_menu.php');
$metodo = 'agregar';
$proced      = "p_fichas_04";
$archivo = "$area&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=3&metodo=modificar";
?>
<form action="scripts/sc_ficha_04.php" method="post" name="add" id="add">
	<fieldset class="fieldset">
		<legend>Documento Trabajador </legend>
		<table width="98%" align="center">
			<tr>
				<td width="26%" class="etiqueta">Documentos:</td>
				<td width="11%" class="etiqueta">Check:</td>
				<td width="18%" class="etiqueta">observación:</td>
				<td width="12%" class="etiqueta">Descargar - Subir</td>
				<td width="25%" class="etiqueta">Vencimiento - Fecha</td>
				<td width="8%" class="etiqueta">Fec. Ult. Mod.</td>
			</tr>
			<?php

				$sql = " SELECT
					ficha_documentos.cod_documento,
					ficha_documentos.`checks`,
					ficha_documentos.`link`,
					ficha_documentos.observacion,
					ficha_documentos.vencimiento,
					ficha_documentos.venc_fecha,
					ficha_documentos.fec_us_mod,
					documentos.descripcion,
					control.url_doc,
					documentos.orden 
				FROM
					documentos,
					ficha_documentos,
					control 
				WHERE
					ficha_documentos.cod_ficha = '$codigo' 
					AND ficha_documentos.cod_documento = documentos.codigo 
					AND documentos.`status` = 'T' UNION
				SELECT
					documentos.codigo AS cod_documento,
					'N' AS `checks`,
					'' AS `link`,
					'' AS observacion,
					'N' AS vencimiento,
					'' AS venc_fecha,
					'' AS fec_us_mod,
					documentos.descripcion,
					control.url_doc,
					documentos.orden 
				FROM
					documentos,
					control 
				WHERE
					documentos.`status` = 'T' 
					AND documentos.codigo NOT IN (
					SELECT
						ficha_documentos.cod_documento 
					FROM
						documentos,
						ficha_documentos 
					WHERE
						ficha_documentos.cod_ficha = '$codigo' 
						AND ficha_documentos.cod_documento = documentos.codigo 
						AND documentos.`status` = 'T' 
					) 
				ORDER BY
					orden ASC";

			$query = $bd->consultar($sql);

			while ($datos = $bd->obtener_fila($query, 0)) {
				extract($datos);
				$img_src = $link;
				if ($img_src) {
					$img_ext =  imgExtension($img_src);
					$img_src = 	'<a target="_blank" href="' . $img_src . '"><img class="imgLink" src="' . $img_ext . '" width="22px" height="22px" /></a>';
				} else {
					$img_src = 	'<img src="imagenes/img-no-disponible_p.png" width="22px" height="22px" />';
				}
				$subir = "Vinculo('inicio.php?area=formularios/add_imagenes_doc&ficha=$cod_ficha&ci=$cedula&doc=$cod_documento')";
				// 	<td>oookoko  xxx </td>
				//
				echo '
					<tr>
						<td class="texto">' . longitudMax($descripcion) . '</td>
						<td class="texto">SI <input type = "radio" name="documento' . $cod_documento . '"  value = "S" style="width:auto" disabled="disabled"
						                            ' . CheckX($checks, 'S') . '/>NO <input type = "radio" name="documento' . $cod_documento . '"
													value = "N" style="width:auto" disabled="disabled" ' . CheckX($checks, 'N') . '/><input type="hidden"                                                     name="documento_old' . $cod_documento . '" value = "' . $checks . '"/></td>
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
				<input type="button" id="volver04" value="Volver" onClick="history.back(-1);" class="readon art-button" />
			</span>
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