<?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();
$result = array();

$codigo       = isset($_POST['codigo']) ? mysql_real_escape_string($_POST['codigo']) : '';
$ubicacion    = mysql_real_escape_string($_POST['ubicacion']);
$cargo        = mysql_real_escape_string($_POST['cargo']);
$horario      = mysql_real_escape_string($_POST['horario']);
$hora_entrada = mysql_real_escape_string($_POST['hora_entrada']);
$ventana_ini  = isset($_POST['ventana_ini']) ? mysql_real_escape_string($_POST['ventana_ini']) : '';
$ventana_fin  = isset($_POST['ventana_fin']) ? mysql_real_escape_string($_POST['ventana_fin']) : '';
$usuario      = mysql_real_escape_string($_POST['usuario']);

$ventana_ini_sql = ($ventana_ini !== '') ? "'$ventana_ini'" : 'NULL';
$ventana_fin_sql = ($ventana_fin !== '') ? "'$ventana_fin'" : 'NULL';

try {
    if ($codigo == "") {
      $sql = "INSERT INTO horario_cl_ubicacion (cod_cl_ubicacion, cod_cargo, cod_horario, hora_entrada, inicio_marc_entrada, fin_marc_entrada, usuario)
              VALUES ('$ubicacion', '$cargo', '$horario', '$hora_entrada', $ventana_ini_sql, $ventana_fin_sql, '$usuario')";
      $query = $bd->consultar($sql);
    }else{
      $sql = "UPDATE horario_cl_ubicacion
                 SET cod_cl_ubicacion = '$ubicacion', cod_cargo = '$cargo', cod_horario = '$horario',
                     hora_entrada = '$hora_entrada', inicio_marc_entrada = $ventana_ini_sql, fin_marc_entrada = $ventana_fin_sql, usuario = '$usuario'
               WHERE codigo = '$codigo';";
     $query = $bd->consultar($sql);
    }
  $result['sql'] = $sql;
  }catch (Exception $e) {
     $error =  $e->getMessage();
     $result['error'] = true;
     $result['mensaje'] = $error;
     $bd->log_error("Aplicacion", "sc_confirmaciones_horarios.php",  "$usuario", "$error", "$sql");
 }

	print_r(json_encode($result));
	return json_encode($result);

?>
