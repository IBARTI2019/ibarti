<!--<link rel="stylesheet" type="text/css" href="packages/grafica/css/grafica.css">
<script type="text/javascript" src="packages/grafica/js/d3.js"></script>
<script type="text/javascript" src="packages/grafica/js/ib-graficasES5.js"></script>
<link rel="stylesheet" type="text/css" href="libs/highcharts/highcharts.css">-->

<?php
$Nmenu = '456';
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
}else{
	$us = $_POST['usuario'];
}
?>

<div id="Cont_gNovedades"></div>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />

<!--<script type="text/javascript" src="libs/highcharts/highcharts.js"></script>-->
<script type="text/javascript" src="libs/chartjs/Chart.min.js"></script>
<script type="text/javascript" src="libs/chartjs/Chart.bundle.min.js"></script>
<script type="text/javascript" src="packages/grafica/js/ib_chart.js"></script>
<script type="text/javascript" src="packages/grafica/novedades/controllers/gNovedadesCtrl.js"></script>