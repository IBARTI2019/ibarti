<script language="javascript">
	$("#superv_ap_form").on('submit', function(evt){
		evt.preventDefault();
		save_supervision_det($("#superv_ap_fecha").val());
	});
</script>

<div id="superv_supervision"></div>
<input type="hidden" id="superv_cliente" value="<?php echo $_POST['codigo'];?>">

<script type="text/javascript" src="packages/cliente/cl_agendacontactos/controllers/supervisionCtrl.js"></script>