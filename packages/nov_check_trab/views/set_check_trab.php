<?php
include_once('../../../funciones/funciones.php');
require("../../../autentificacion/aut_config.inc.php");
require_once("../../../" . class_bd);
$bd = new DataBase();

$metodo = (isset($_POST['metodo'])) ? $_POST['metodo'] : "agregar";
$clasificacion = $_POST['clasif'];
$tipo = $_POST['tipo'];
$cedula = $_POST['codigo_trabajador'];
$ficha = $_POST['codigo_supervisor'];
$observacion = $_POST['observacion'];
$respuesta = '';
$usuario = $_POST['usuario'];
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$valores = $_POST['valores'];
$obs = $_POST['obs'];
$codigos = (isset($_POST['cod'])) ? $_POST['cod'] : "";



$sql = "CALL p_nov_check_list_trab('$metodo','$codigos','$clasificacion','$tipo','$ficha','$cedula','$observacion','$respuesta','$usuario')";
$query01 = $bd->consultar($sql);

if ($metodo != "modificar") {
    $sql = "SELECT MAX(codigo) from nov_check_list_trab";
    $query01 = $bd->consultar($sql);
    while ($row01 = $bd->obtener_fila($query01, 0)) {
        $codigo = $row01[0];
    }
} else {
    $codigo = $codigos;
    $sql = "DELETE from nov_check_list_trab_det WHERE cod_check_list='$codigo'";
    $query01 = $bd->consultar($sql);
}


foreach ($valores as $novedad => $valor) {
    $agrupacion = $_POST['cod_agrupacion_'.$novedad];
    $sql = "CALL p_nov_check_list_trab_det('agregar','$codigo','$novedad', '$agrupacion', '$valor','$obs[$novedad]')";
    $query01 = $bd->consultar($sql);
}
