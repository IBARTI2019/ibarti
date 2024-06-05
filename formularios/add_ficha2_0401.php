
<?php
//	require_once('autentificacion/aut_verifica_menu.php');
$ci           = $_GET["ci"]; 
$doc          = $_GET["doc"];
$ficha        = $_GET["ficha"]; 
$nombre =     $_GET["nombre"]; 
$metodo = 'modificar';
$proced      = "p_fichas_04";
$archivo = "$area&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=3&metodo=modificar";

$admin_rrhh	    = $_SESSION['admin_rrhh'];

$add_doc = '<a href="inicio.php?area=formularios/add_imagenes_doc&ficha='.$ficha.'&ci='.$ci.'&doc='.$doc.'"><img src="imagenes/nuevo.bmp" alt="Agregar" title="Agregar Registro" width="22px" height="22px" border="null" class="imgLink"/></a>';
	
			
?>

<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />

<form action="scripts/sc_ficha_04.php" method="post" name="add" id="add">
	<fieldset class="fieldset">
		<legend>Carpeta Documentos:<?php echo   $doc."". $nombre ?> </legend>
		
		<table width="98%" align="center">
			<tr>
			<td width="10%" class="etiqueta">Nro</td>
			    <td width="10%" class="etiqueta">Titulo</td>
				<td width="10%" class="etiqueta">Check</td>
				<td width="18%" class="etiqueta">observación</td>
				<td width="22%" class="etiqueta">Venc- Fecha</td>
				<td width="16%" class="etiqueta">Fec. Ult. Mod.</td>
				<td width="8%" class="etiqueta">Acciones</td>
				<th width="8%" align="center"><?php echo $add_doc; ?></th>
			</tr>
			<?php

			$sql = "SELECT
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
			ficha_documentos.cod_ficha = '$ficha' 
			AND ficha_documentos.cod_documento='$doc'
			AND ficha_documentos.cod_documento = documentos.codigo 
			AND documentos.`status` = 'T'
                
			 ORDER BY documentos.orden ASC";


			$query = $bd->consultar($sql);
            $i=0;
			while ($datos = $bd->obtener_fila($query, 0)) {
				$i++;
				extract($datos);
				$img_src = $link;
				$borrarDoc = "";
				if ($img_src) {
					$img_ext =  imgExtension($img_src);
					$img_src = 	'<a target="_blank" href="' . $img_src . '"><img class="imgLink" src="' . $img_ext . '" width="22px" height="22px" /></a>';
					//$img_src = 	'<img src="' . $img_ext . '" onclick="openModalDocument(\'' . $descripcion . '\', \'' . $link . '\')" width="22px" height="22px"  />';
					if($admin_rrhh == 'T'){
						$eliminar = '<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Documento" width="22" height="22" border="null" onclick="BorrarDocumento(\''.$ficha.'\',\''.$cod_documento.'\',\''.$link.'\')"/>';
					}
				} else {
					$img_src = 	'<img src="imagenes/img-no-disponible_p.png" width="22px" height="22px" />';
				}
				$modificar= '<img src="imagenes/guardar.bmp" alt="Borrar" title="Modificar Documento" width="22" height="22" border="null" onclick="ModificarDocumento(\''.$ficha.'\',\''.$cod_documento.'\',\''.$link.'\','.$i.')"/>';
				
				$subir = "Vinculo('inicio.php?area=formularios/add_imagenes_doc&ficha=$cod_ficha&ci=$cedula&doc=$cod_documento')";
				
				// 	<td>oookoko  xxx </td>
				//
				echo '
					<tr>
					    
					    <td>' .$i. '' . $img_src . ' </td>
						<td class="texto">' . longitudMax($descripcion) . '</td>
						<td class="texto">SI <input type = "radio" name="documento' . $i . '"  value = "S" style="width:auto" disabled="disabled"
						                            ' . CheckX($checks, 'S') . '/>NO <input type = "radio" name="documento' . $i . '"
													value = "N" style="width:auto" disabled="disabled" ' . CheckX($checks, 'N') . '/><input type="hidden" name="documento_old' . $cod_documento . '" value = "' . $checks . '"/></td>
						<td><textarea name="observ_doc' . $i . '" id="observ_docu' . $i . '" cols="20" rows="1">' . $observacion . '</textarea></td>
						
						<td class="texto">SI <input type = "radio" id="vencimiento' . $i . '" name="vencimiento' . $i . '"  value = "S" style="width:auto"
																				' . CheckX($vencimiento, 'S') . '/>NO <input type = "radio"
																				name="vencimiento' . $i . '" value = "N" style="width:auto"
													' . CheckX($vencimiento, 'N') . '/><input type="date" name="fecha_venc' . $i . '"
											    id="fecha_venc' . $i . '"  value="' . $venc_fecha . '"/><input type="hidden" "
													name="fecha_venc_old' . $i . '" value = "' . $venc_fecha . '"/>
						</td>

					<td class="texto">' . $fec_us_mod . '</td>
					<td>' . $img_src. ' </td>
					<td>' . $eliminar. ' </td>
					<td>' . $modificar. ' </td>
					</tr>';
			} ?>
		</table>
		<div align="center"><span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" id="volver04" value="Atraz" onClick="history.back();" class="readon art-button" />
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

	function BorrarDocumento(ficha,codigoDoc,links) {
		if (confirm("¿Esta seguro de eliminar la imagen de este documento ?")) {	
			var codigo = $("#codigo").val();
			var usuario = $("#usuario").val();
						
			var parametros = {
				"link": links,
				"ficha": ficha,
				"doc": codigoDoc,
				"metodo": 'borrar',
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
	function ModificarDocumento(ficha,codigoDoc,links,auto) {
		if (confirm("¿Esta seguro de Modificar el Documento ?")) {	
			var codigo = $("#codigo").val();
			var usuario = $("#usuario").val();
			
			var observacionx = document.getElementById("observ_docu"+auto+"").value;
		    var fecha_vencimiento = document.getElementById("fecha_venc"+auto+"").value;
			var vencimiento = document.getElementById("vencimiento"+auto+"").value;
			
			var parametros = {
				"link": links,
				"ficha": ficha,
				"doc": codigoDoc,
				"metodo": 'modificar',
				"observacion":observacionx,
				"fecha_vencimiento":fecha_vencimiento,
				"vencimiento":vencimiento,
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