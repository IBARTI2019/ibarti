<script language="javascript">
	$("#cont_ap_form").on('submit', function(evt) {
		evt.preventDefault();
		save_contratacion_det($("#cont_ap_fecha").val());
	});
</script>
<link rel="stylesheet" href="css/modal.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/modal.js"></script>
<script type="text/javascript" src="packages/cliente/cl_contratacion/controllers/contratacionCtrl.js"></script>
<div id="modal_cont_ap" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span id="close_act_cont" class="close" onclick="{$('#modal_cont_ap').hide();}">&times;</span>
			<span>Aperturas Existentes</span>
		</div>
		<div class="modal-body">
			<div id="loading_save_cont" hidden="true">
				<img border="null" width="50px" height="50px" src="imagenes/loading3.gif" title="Procesando"/>
				<h1>Este proceso puede tardar en finalizar, por favor tenga paciencia.. </h1>
				<h3>No intente hacer mas actualizaciones hasta que este proceso finalice.</h3>
			</div>
			<form action="" method="post" name="cont_ap_form" id="cont_ap_form">
				<div><span class="etiqueta">A partir de que fecha desea se aplique esta actualizacion?..</span></div>
				<br>
				<div align="center"><span>Fecha:</span>
					<input type="date" id="cont_ap_fecha" min="<?php echo date("d-m-Y"); ?>" value="<?php echo date("d-m-Y"); ?>" required>
				</div>
				<br>
				<div align="center"><span class="art-button-wrapper" id="contrato_ap">
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
						<input type="button" id="volver" value="Volver" onclick="{$('#modal_cont_ap').hide();}" class="readon art-button" />
					</span>
				</div>

			</form>
		</div>
	</div>
</div>
<div id="Cont_contratacion"></div>
<input type="hidden" id="cont_cliente" value="<?php echo $_POST['codigo']; ?>">