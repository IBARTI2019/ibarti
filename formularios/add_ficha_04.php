<?php
//	require_once('autentificacion/aut_verifica_menu.php');
$proced      = "p_fichas_04";
$metodo       = "agregar";
$archivo = "pestanas/add_ficha2&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=3&metodo=modificar";
?>
<form action="scripts/sc_ficha_04.php" method="post" name="add" id="add">
	<hr>
	<legend>Documento Trabajador </legend>
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
		$sql = "SELECT count(ficha_documentos.cod_documento) as cantidad
		  FROM documentos, ficha_documentos, control
		 WHERE ficha_documentos.cod_ficha = '$codigo'
			   AND ficha_documentos.cod_documento = documentos.codigo
		   AND documentos.`status` = 'T'
		   ORDER BY documentos.orden ASC ";
		$query = $bd->consultar($sql);
		$existe = $bd->obtener_fila($query, 0);
		if (intval($existe['cantidad']) > 0) {
			$metodo       = "modificar";
			$sql = " SELECT ficha_documentos.cod_documento, ficha_documentos.`checks`,
						   ficha_documentos.`link`, ficha_documentos.observacion,
										   ficha_documentos.vencimiento, ficha_documentos.venc_fecha,
								   ficha_documentos.fec_us_mod,
					   documentos.descripcion, control.url_doc
				   FROM documentos, ficha_documentos, control
				   WHERE ficha_documentos.cod_ficha = '$codigo'
					   AND ficha_documentos.cod_documento = documentos.codigo
				   AND documentos.`status` = 'T'
				   ORDER BY documentos.orden ASC ";
		} else {
			$metodo       = "agregar";

			$sql = "SELECT
			   documentos.codigo as cod_documento,
			   'N' as `checks`,
			   '' as `link`,
			   '' as observacion,
			   'N' as vencimiento,
		   ''  as venc_fecha,
		   ''	as fec_us_mod,
			   documentos.descripcion,
			   control.url_doc
		   FROM
			   documentos,
			   control
		   WHERE
			   documentos.`status` = 'T'
		   ORDER BY
			   documentos.orden ASC";
		}
		$query = $bd->consultar($sql);
		while ($datos = $bd->obtener_fila($query, 0)) {
			extract($datos);
			$img_src = $url_doc . "" . $link;
			if (file_exists($link)) {
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
						<td class="texto">SI <input type = "radio" name="documento' . $cod_documento . '"  value = "S" style="width:auto"
						                             disabled="disabled" ' . CheckX($checks, 'S') . '/>NO <input type = "radio"
						                             name="documento' . $cod_documento . '" value = "N" style="width:auto"
													 ' . CheckX($checks, 'N') . '/><input type="hidden" disabled="disabled"
													  name="documento_old' . $cod_documento . '" value = "' . $checks . '"/></td>
						<td><textarea name="observ_doc' . $cod_documento . '" cols="20" rows="1"
						                disabled="disabled">' . $observacion . '</textarea></td>
						<td>' . $img_src . ' / <a target="_blank" onClick="' . $subir . '"><img class="ImgLink" src="imagenes/subir.gif"
						                                                                 width="22px" height="22px" /></a></td>

					 <td class="texto">SI <input type = "radio" name="vencimiento' . $cod_documento . '"  value = "S" style="width:auto"
					 														 disabled="disabled" ' . CheckX($vencimiento, 'S') . '/>NO <input type = "radio"
					 														 name="vencimiento' . $cod_documento . '" value = "N" style="width:auto"
					 							 ' . CheckX($vencimiento, 'N') . '/><input type="hidden" disabled="disabled"
					 								name="fecha_venc_old' . $cod_documento . '" value = "' . conversion($venc_fecha) . '"/>
					        <input type="date" name="fecha_venc' . $cod_documento . '" id="fecha_venc' . $cod_documento . '"
									       value="' . $venc_fecha . '"  disabled="disabled"  onfocus="spryFecVenc(this.id)"  />
					</td>
					<td class="texto">' . $fec_us_mod . '</td>
					</tr>';
		} ?>
	</table>
	<div align="center">
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

</form>

<script language="javascript" type="text/javascript">
	function spryFecVenc(ValorN) {
		//	alert(ValorN);
		// var ValorN = new Spry.Widget.ValidationSelect(ValorN, {validateOn:["blur", "change"]});
		var ValorN = new Spry.Widget.ValidationTextField(ValorN, "date", {
			format: "dd-mm-yyyy",
			hint: "DD-MM-AAAA",
			validateOn: ["blur", "change"],
			useCharacterMasking: true
		});
	}
</script>