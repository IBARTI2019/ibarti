<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

$bd = new DataBase();

$result = array();
$vectorR = array();
$result['error'] = false;

foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}

$vectorA = json_decode($vector, true);
$linksA = isset($links) ? json_decode($links, true) : [];

try {
    // Para cada actividad marcada, actualizamos directamente
    for ($i = 0; $i < count($vectorA); $i++) {
        $cod = $vectorA[$i];
        
        // Verificar si esta actividad tiene su propio archivo
        if (isset($linksA[$cod])) {
            // La actividad tiene su propio archivo
            $link = $linksA[$cod];
            
            // Actualizar la actividad con su archivo específico
            $sql = "UPDATE planif_clientes_superv_trab_det 
                    SET realizado = 'T', 
                        link = '$link', 
                        cod_us_marcaje = '$usuario' 
                    WHERE codigo = '$cod'";
            $query = $bd->consultar($sql);
            $result['sql'][] = $sql;
        } else {
            // Si no tiene archivo, registrar error
            $result['error'] = true;
            $result['mensaje'] = "La actividad $cod no tiene archivo asociado";
            break;
        }
    }
    
    if (!$result['error']) {
        $result['mensaje'] = 'Marcaje completado exitosamente';
        $result['marcados'] = count($vectorA);
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "marcar.php", "$usuario", "$error", "");
}

print_r(json_encode($result));
return json_encode($result);
?>