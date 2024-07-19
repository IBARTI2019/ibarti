<link rel="stylesheet" href="css/pestanas.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/pestanas.js"></script>
<?php
require "../modelo/ubicacion_modelo.php";
require "../../../../" . Leng;
$ubicacion = new Ubicacion;

$metodo = $_POST['metodo'];
$titulo = " " . $leng['cliente'] . " " . $leng['ubicacion'] . "";
$codigo = $_POST['codigo'];
$cliente = $_POST['cliente'];
$proced      = "p_clientes_ubic";

if ($metodo == 'modificar') {
  $titulo   = "Modificar " . $leng['ubicacion'];
  $ubic     =  $ubicacion->editar("$codigo");
} else {
  $titulo    = "Agregar " . $leng['ubicacion'];
  $ubic      =  $ubicacion->inicio();
}

$cargo     = $ubicacion->get_cargo("");
$region     = $ubicacion->get_region($ubic['cod_region']);
$estado     = $ubicacion->get_estado($ubic['cod_estado']);
$ciudad     = $ubicacion->get_ciudad($ubic['cod_ciudad'], $ubic['cod_estado']);
$calendario = $ubicacion->get_calendario($ubic['cod_calendario']);
$zona       = $ubicacion->get_zona($ubic['cod_zona']);
?>
<div class="tab">
  <button class="tablinks" onclick="openTap(0)"><?php echo $leng['ubicacion'] ?></button>
  <button class="tablinks" onclick="openTap(1)">Datos Adiccionales</button>
  <button class="tablinks" onclick="openTap(2)">Puesto de Trabajo</button>
  <button class="tablinks" onclick="openTap(3)">Capta Huellas</button>
  <button class="tablinks" onclick="openTap(4)">Alcance</button>
  <button class="tablinks" onclick="openTap(5)">Uniforme</button>
</div>
<div class="tabcontent" id="tab_cont01">
  <?php include('p_ubic.php'); ?>
</div>
<div class="tabcontent" id="tab_cont02">
  <?php include('p_ubic_ad.php'); ?>
</div>
<div class="tabcontent" id="tab_cont03">
  <?php if ($metodo == "modificar") {
    include('../../cl_ubic_puesto/index.php');
  } ?>
</div>
<div class="tabcontent" id="tab_cont04">
  <?php if ($metodo == "modificar") {
    include('p_ubic_ch.php');
  }
  ?>
</div>
<div class="tabcontent" id="tab_cont05">
  <?php if ($metodo == "modificar") {
    include('p_ubic_alcance.php');
  }
  ?>
</div>
<div class="tabcontent" id="tab_cont06">
  <?php if ($metodo == "modificar") {
    include('p_ubic_uniforme.php');
  }
  ?>
</div>