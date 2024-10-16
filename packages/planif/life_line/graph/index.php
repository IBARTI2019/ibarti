<!--<link rel="stylesheet" type="text/css" href="packages/grafica/css/grafica.css">
<script type="text/javascript" src="packages/grafica/js/d3.js"></script>
<script type="text/javascript" src="packages/grafica/js/ib-graficasES5.js"></script>
<link rel="stylesheet" type="text/css" href="libs/highcharts/highcharts.css">-->

<?php
$Nmenu = '480';
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
}else{
	$us = $_POST['usuario'];
}
?>

<div id="Cont_graph"></div>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />
<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>

<!--<script type="text/javascript" src="libs/highcharts/highcharts.js"></script>-->
<script type="text/javascript" src="libs/chartjs/Chart.min.js"></script>
<script type="text/javascript" src="libs/chartjs/Chart.bundle.js"></script>
<script type="text/javascript" src="libs/chartjs/chartjs-plugin-annotation.js"></script>
<script type="text/javascript" src="packages/grafica/js/ib_chart.js"></script>
<script type="text/javascript" src="packages/planif/life_line/graph/controllers/life_line_graphCtrl.js"></script>