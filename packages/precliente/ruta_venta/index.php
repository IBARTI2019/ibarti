<script language="javascript">
	$("#ruta_venta_form").on('submit', function(evt){
		evt.preventDefault();
		save_rutaventa_det($("#ruta_venta_fecha").val());
	});
</script>

<div id="precliente_rutaventa"></div>
<input type="hidden" id="precliente" value="<?php echo $_POST['codigo'];?>">

<script type="text/javascript" src="packages/precliente/ruta_venta/controllers/rutaVentaCtrl.js"></script>