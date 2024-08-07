<?php
//	require_once('autentificacion/aut_verifica_menu.php');
$metodo = 'agregar';
$proced      = "p_fichas_04";
$archivo = "$area&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=3&metodo=modificar";

?>

<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />

<form action="scripts/sc_ficha_04.php" method="post" name="add" id="add">
	<fieldset class="fieldset">
		<legend>Documento Trabajador </legend>
		<table width="98%" align="center">
			<tr>
				<td width="20%" class="etiqueta">Documentos:</td>
				
				<td width="16%" class="etiqueta">Carpeta Doc</td>
				
			</tr>
			<?php
                $sql="SELECT 
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
				AND documentos.`status` = 'T' 
				GROUP by descripcion
				UNION
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
				orden ASC;";
				

			$query = $bd->consultar($sql);

			while ($datos = $bd->obtener_fila($query, 0)) {
				extract($datos);
				$sql2="SELECT count(*) as cantidad FROM `ficha_documentos` WHERE cod_documento='$cod_documento' and cod_ficha='$codigo'";
				$query2 = $bd->consultar($sql2);
				$datos2=$bd->obtener_fila($query2, 0);
				$img_src = $link;
				$borrarDoc = "";
				if ($img_src) {
					$img_ext =  imgExtension($img_src);
					$img_src = 	'<img src="' . $img_ext . '" onclick="openModalDocument(\'' . $descripcion . '\', \'' . $link . '\')" width="22px" height="22px"  />';
					if($admin_rrhh == 'T'){
						$borrarDoc = '<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Documento" width="22" height="22" border="null" onclick="BorrarDocumento(\''.$cod_documento.'\',\''.$link.'\')"/>';
					}
				} else {
					$img_src = 	'<img src="imagenes/img-no-disponible_p.png" width="22px" height="22px" />';
				}
				$subir = "Vinculo('inicio.php?area=formularios/add_imagenes_doc&ficha=$cod_ficha&ci=$cedula&doc=$cod_documento')";
				$carpeta = "Vinculo('inicio.php?area=formularios/add_ficha2_0401&ficha=$cod_ficha&ci=$cedula&doc=$cod_documento&nombre=$descripcion')";
				
				// 	<td>oookoko  xxx </td>
				//
				echo '
					<tr>
						<td class="texto">' . longitudMax($descripcion) . '</td>
							<td>  <a target="_blank" - <a target="_blank" title="Documentos:'. $datos2[cantidad].'" onClick="' . $carpeta . '">
						
						<img class="ImgLink"  src="' .imgcarpeta($datos2[cantidad]) . '" width="22px" height="22px" />
						
						</td>
						
						
					</tr>';
			} ?>
		</table>
		<div align="center"><span class="art-button-wrapper">
				
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" id="volver04" value="Volver" onClick="history.back();" class="readon art-button" />
			</span>
			<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
			<input name="proced" type="hidden" value="<?php echo $proced; ?>" />
			<input id="codigo" name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
			<input id="usuario" name="usuario" type="hidden" value="<?php echo $usuario; ?>" />
			<input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>" />
		</div>
	</fieldset>
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
		var ValorN = new Spry.Widget.ValidationTextField(ValorN, "date", {
			format: "dd-mm-yyyy",
			hint: "DD-MM-AAAA",
			validateOn: ["blur", "change"],
			useCharacterMasking: true
		});
	}

	function openModalDocument(documentName, link) {	
		console.log(documentName, link)	
		$("#myModalDocument").show();
		$("#titleDocument").html(documentName);
		var contenido = '<embed src="' + link + '" type="application/pdf" width="100%" height="800px"><noembed><p>Su navegador no admite archivos PDF.<a href="' + link + '">Descargue el archivo en su lugar</a></p></noembed></embed>';
		$("#modal_documento_cont").html(contenido);
		/* 	
			$("#modal_documento_cont").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
			$.ajax({
				type: 'GET',
				url: link,
				responseType: 'arraybuffer',
				success: function (data){
					let blob = new Blob([data], { type: 'application/pdf'} );
					let url = window.URL.createObjectURL(blob);
					var contenido = '<embed src="' + url + '" type="application/pdf" width="100%" height="800px"><noembed><p>Su navegador no admite archivos PDF.<a href="' + link + '">Descargue el archivo en su lugar</a></p></noembed></embed>';
					$("#modal_documento_cont").html(contenido);	
				},
				error: function() {
					console.log("Error");
				}
			}); 
		*/
	}

	function showMessage(message) {
		$(".messages").html("").show();
		$(".messages").html(message);
	}

	function BorrarDocumento(codigoDoc,links) {
		if (confirm(links)) {	
			var codigo = $("#codigo").val();
			var usuario = $("#usuario").val();
			var parametros = {
				"link":links,
				"ficha": codigo,
				"doc": codigoDoc,
				"borrar": true,
				"usuario": usuario
			};

			$.ajax({
				url: 'upload/documentos.php',
				type: 'POST',
				data: parametros,
				success: function (data) {
					location.reload();
				},
				//si ha ocurrido un error
				error: function () {
					message = $("<span class='error'>Ha ocurrido un error.</span>");
					showMessage(message);
				}
			});
		}
	}

	function _base64ToArrayBuffer(base64) {
		var binary_string = window.atob(base64);
		var len = binary_string.length;
		var bytes = new Uint8Array(len);
		for (var i = 0; i < len; i++) {
			bytes[i] = binary_string.charCodeAt(i);
		}
		return bytes.buffer;
	}

	function cerrarModalDocument(refresh) {
		$("#myModalDocument").hide();
	}
</script>