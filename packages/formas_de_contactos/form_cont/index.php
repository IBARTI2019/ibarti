<script type="text/javascript" src="packages/formas_de_contactos/form_cont/controllers/proyectoCtrl.js"></script>
<?php
	$Nmenu = '737';
	if(isset($_SESSION['usuario_cod'])){
		require_once('autentificacion/aut_verifica_menu.php');
		$us = $_SESSION['usuario_cod'];
	}else{
		$us = $_POST['usuario'];
	}
?>
<div id="Cont_proyecto"></div>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />
