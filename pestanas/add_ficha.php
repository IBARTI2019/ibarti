<link rel="stylesheet" type="text/css" href="libs/toastr.css">
<script type="text/javascript" src="libs/toastr.js"></script>

<?php
$Nmenu = 409;
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');

$codigo = $_GET['codigo'];
$metodo = $_GET['metodo'];
$area   = $_GET['area'];
$mod    = $_GET['mod'];

if ($metodo == 'agregar') {

	$sql    = " SELECT preingreso.nombres  FROM preingreso WHERE cedula = '$codigo' ";
} else {
	$sql    = " SELECT v_ficha.nombres  FROM v_ficha WHERE v_ficha.cod_ficha = '$codigo' ";
}

if ($_SESSION['ficha_preingreso'] == "S") {
	$query = $bd->consultar($sql);
	$row00 = $bd->obtener_fila($query, 0);
	$trab = 	" TRABAJADOR: " . $row00[0] . " (" . $codigo . ")";
} else {
	$trab = "";
}

if (isset($_GET['pagina'])) {
	$pag = $_GET['pagina'];
} else {
	$pag = 0;
}
?>
<div align="center" class="etiqueta_title">
	<?php echo $trab; ?>
</div>
<div class="TabbedPanels" id="tp1">
	<ul class="TabbedPanelsTabGroup">
		<li class="TabbedPanelsTab"> Ficha </li>
		<li class="TabbedPanelsTab"> Historial Contrato </li>
		<li class="TabbedPanelsTab"> Dotación </li>
		<li class="TabbedPanelsTab"> Carga Familiar </li>
		<li class="TabbedPanelsTab"> Recepción de Documentos </li>
		<li class="TabbedPanelsTab"> Egreso </li>
		<li class="TabbedPanelsTab"> Huellas </li>
		<li class="TabbedPanelsTab"> COVID-19 </li>
		<li class="TabbedPanelsTab"> Cargar Fotos </li>
		<li class="TabbedPanelsTab"> Datos Adicionales </li>
	</ul>
	<?php if (($metodo == 'modificar') or ($metodo == 'consultar')) {    ?>
		<div class="TabbedPanelsContentGroup">
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_01.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_07.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_02.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_03.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_04.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_05.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_06.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_08.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include('formularios/add_adicionales_trab.php'); ?></div>
			<div class="TabbedPanelsContent"><?php include('formularios/add_adicionales_trab.php'); ?></div>
		</div>


	<?php } else { ?>

		<div class="TabbedPanelsContentGroup">
			<div class="TabbedPanelsContent"><?php include("formularios/add_ficha_01.php"); ?></div>
			<div class="TabbedPanelsContent">&nbsp;</div>
			<div class="TabbedPanelsContent">&nbsp;</div>
			<div class="TabbedPanelsContent">&nbsp;</div>
			<div class="TabbedPanelsContent">&nbsp;</div>
		</div>
	<?php	} ?>
</div>
<input type="hidden" value="<?php echo $_GET['metodo']; ?>" id="metodoX" />



<script language="JavaScript" type="text/javascript">
	var tp1 = new Spry.Widget.TabbedPanels("tp1", {
		defaultTab: <?php echo $pag; ?>
	});
	var TabbedPanels = new Spry.Widget.TabbedPanels("TabbedPanels");

	var metodo = $("#metodoX").val();

	if (metodo == 'consultar') {
		$(':selected, :input').prop("disabled", true);
		$('#volver').prop("disabled", false);
		$('#volver04').prop("disabled", false);
		$('#volver05').prop("disabled", false);
	}
</script>

<form id="pdf" name="pdf" action="" method="post" target="_blank">
	<input type="hidden" name="codigo" value="<?php echo $_GET["codigo"] ?>">
</form>