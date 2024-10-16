<?php
	if (isset($_SESSION['usuario_cod'])) {
		$usuario = $_SESSION['usuario_cod'];
	} else {
		$usuario = $_POST['usuario'];
	}
	$titulo  = " PASO DE VENTA";
?>

<div align="center" class="etiqueta_title"> <?php echo $titulo;?></div>
<div id="precliente_paso_venta"></div>
<input type="hidden" id="precliente" value="<?php echo $_POST['codigo'];?>">
<input name="usuario" id="usuario" type="hidden"  value="<?php echo $usuario;?>"/>

<script type="text/javascript" src="packages/precliente/paso_venta/controllers/pasoVentaCtrl.js"></script>