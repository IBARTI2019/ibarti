<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();


$fila1   = $_POST['fila1'];
$fila2   = $_POST['fila2'];
$fila3   = $_POST['fila3'];
$fila4   = $_POST['fila4'];
$sql = "SELECT control_rfid.codigo 
FROM control_rfid
WHERE control_rfid.cod_concepto_viene= '$fila1'
AND control_rfid.cod_concepto_planif= '$fila2'
AND control_rfid.feriado='$fila3'
AND control_rfid.cod_concepto_registro='$fila4'";
 $query = $bd->consultar($sql);
 $result = $bd->obtener_fila($query,0);
echo json_encode($result);
//mysql_free_result($query);
?>
