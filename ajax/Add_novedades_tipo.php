<?php
$clasificacion = $_POST['clasificacion'];
if(isset($_POST['campo04'])){
    $campo04 = $_POST['campo04'];
}
$inicial = $_POST['inicial'];
define("SPECIALCONSTANT", true);
require("../autentificacion/aut_config.inc.php");
require_once "../" . Funcion;
session_start();
include_once('../funciones/funciones.php');

require_once("../" . class_bdI);

$bd = new DataBase();
$wheree = '';
if ($clasificacion != 'TODOS' && $clasificacion != '') {
    $wheree .= ' AND nov_clasif.codigo="' . $clasificacion . '"';
}

if(isset($_POST['campo04'])){
    if ($campo04 != 'TODOS' && $campo04 != '') {
        $wheree .= 'AND nov_clasif.campo04 ="' . $campo04 . '"';
    }
}

$sql = 'SELECT nov_tipo.codigo,nov_tipo.descripcion
FROM nov_tipo,novedades,nov_clasif
WHERE novedades.cod_nov_tipo = nov_tipo.codigo
AND novedades.cod_nov_clasif = nov_clasif.codigo
AND nov_tipo.status = "T" ' . $wheree . '
GROUP BY nov_tipo.codigo';

$query = $bd->consultar($sql);

if ($inicial == "S") {
    echo '<option value="">Seleccione...</option>';
} else {
    echo '<option value="' . $inicial . '">TODOS</option>';
}
while ($datos = $bd->obtener_fila($query)) {
    echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
}



/*
while($row02){
    echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
}
*/
