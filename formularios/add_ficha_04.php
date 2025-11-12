<script type="text/javascript" src="upload/functions.js"></script>
<?php
//	require_once('autentificacion/aut_verifica_menu.php');
$proced      = "p_fichas_04";
$metodo       = "agregar";
$archivo = "pestanas/add_ficha2&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=3&metodo=modificar";
?>
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />

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
				documentos.orden,
				documentos.requiere_video
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
				documentos.orden,
				documentos.requiere_video
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
			if ($link) {
				if ($requiere_video == 'T') {
					// Si requiere video y tiene link, muestra icono de video y llama a openModalVideo
					// openModalDocument manejará ambos casos (video y documento)
					$img_src = '<img src="imagenes/video_icon.png" onclick="openModalDocument(\'' . $descripcion . '\', \'' . $link . '\', \'T\')" width="22px" height="22px" style="cursor: pointer;" />';
				} else {
					// Si es un documento normal, usa la lógica original para PDFs/Imágenes
					$img_ext = imgExtension($link);
					$img_src = '<img src="' . $img_ext . '" onclick="openModalDocument(\'' . $descripcion . '\', \'' . $link . '\', \'N\')" width="22px" height="22px" style="cursor: pointer;" />';
				}
			} else {
				$img_src = '<img src="imagenes/img-no-disponible_p.png" width="22px" height="22px" />';
			}

			if ($requiere_video == 'T') {
				// Opción para subir Video (Input File)
				// Agregamos un input file y un icono que lo activa (usando una función JavaScript que debemos definir).
				//  />
				$upload_element = '
				<input type="file" 
				id="upload_video_' . $cod_documento . '" 
				name="upload_video_' . $cod_documento . '" 
				accept="video/*" 
				style="display:none;" 
					onchange="subirVideoS3(\'' .$codigo. '\', \'' . $cod_documento . '\')"/> 
				
				<img class="ImgLink" 
				src="imagenes/subir.gif" 
				width="22px" height="22px" 
				title="Subir Video"
				onclick="$(\'#upload_video_' . $cod_documento . '\').click();" />';
			} else {
				// Opción para subir Imágenes/Archivos (Redirección con Vinculo)
				$subir = "Vinculo('inicio.php?area=formularios/add_imagenes_doc&ficha=$codigo&ci=$cedula&doc=$cod_documento')";
				$upload_element = '<a target="_blank" onClick="' . $subir . '">
				<img class="ImgLink" src="imagenes/subir.gif" width="22px" height="22px" title="Subir Imagen/Archivo" /></a>';
			}

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
				<td>' . $img_src . ' - ' . $upload_element . '</td>

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

<div id="myModalDocument" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalDocument()">&times;</span>
      <span id='titleDocument'>Documento</span>
    </div>
    <div class="modal-body">
      <div id="modal_documento_cont">
      </div>
    </div>
  </div>
</div>

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

	function openModalDocument(documentName, link, isVideo) {		
		$("#myModalDocument").show();
		$("#titleDocument").html(documentName);
		var contenido = "";
		if (isVideo == 'T') {
			// Contenido para Video: usar etiqueta <video>
			contenido = '<video width="100%" height="auto" controls autoplay>'
				+ '<source src="' + link + '" type="video/mp4">'
				+ 'Tu navegador no soporta la etiqueta de video.'
				+ '</video>';
		} else {
			// Contenido para Documentos (PDF/Imagen)
			contenido = '<embed src="' + link + '" type="application/pdf" width="100%" height="800px"><noembed><p>Su navegador no admite archivos PDF.<a href="' + link + '">Descargue el archivo en su lugar</a></p></noembed></embed>';
		}
		$("#modal_documento_cont").html(contenido);
	}

	function cerrarModalDocument(refresh) {
		var modalContent = $("#modal_documento_cont");
		var videoElement = modalContent.find('video')[0];

		// 1. Detener la reproducción si hay un elemento <video>
		if (videoElement) {
			videoElement.pause();
			videoElement.currentTime = 0; // Opcional: rebobinar al inicio
		}

		// 2. Limpiar el contenido para liberar recursos (especialmente si es un video grande)
		modalContent.empty();
		$("#myModalDocument").hide();
	}
</script>