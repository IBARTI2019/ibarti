<script type="text/javascript" src="packages/planif/life_line_act/controllers/life_line_actCtrl.js"></script>
<?php
	$Nmenu = '4411';
	if(isset($_SESSION['usuario_cod'])){
		require_once('autentificacion/aut_verifica_menu.php');
		$us = $_SESSION['usuario_cod'];
	}else{
		$us = $_POST['usuario'];
	}
?>
<div id="Cont_life_line_act"></div>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />
