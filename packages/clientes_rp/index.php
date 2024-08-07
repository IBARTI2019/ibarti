<?php
$Nmenu   = 574;
$mod     =  $_GET['mod'];
$titulo  = " REPORTE CLIENTES";
//$archivo = "packages/clientes_rp/views/Set_clientes_reporte.php?Nmenu=$Nmenu&mod=$mod";
$archivo = "reportes/rp_inv_cliente_det.php?Nmenu=$Nmenu&mod=$mod";
?>

<div align="center" class="etiqueta_title"> <?php echo $titulo;?></div>

<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>" method="post" target="_blank">
</form>
<script src="packages/clientes_rp/controllers/cliente_rp_ctrl.js"></script>