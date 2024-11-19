<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/modal.js"></script>
<script type="text/javascript" src="packages/planif/life_line/controllers/life_lineCtrl.js"></script>
<!-- <script type="text/javascript" src="libs/planificacionRP.js"></script> -->
<?php
$Nmenu = '4412';
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
}else{
	$us = $_POST['usuario'];
}
?>
<div id="myModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="CloseModal()" >&times;</span>
			<span id="modal_titulo"></span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido"></div>
		</div>
	</div>
</div>

<div id="modalRP" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="$('#modalRP').hide()" >&times;</span>
			<span>Linea de Vida</span>
		</div>
		<div class="modal-body">
			<div id="RP"></div>
			<div id="modal_contenidoRP"></div>
			<form action="packages/planif/life_line/views/rp_planif_serv.php" method="post" name="add_planif_serv_modal" id="add_planif_serv_modal" method="post" target="_blank">
				<input type="hidden" name="contratacion" id="cod_contratacion_serv" value="">
				<input type="hidden" name="ubicacion" id="cod_ubic_serv" value="">
				<input type="hidden" name="body_planif" id="body_planif" value="">
				<input type="hidden" name="reporte" id="reporte_serv" value="">
			</form>
		</div>
	</div>
</div>
<div id="Cont_life_line"></div>
<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />