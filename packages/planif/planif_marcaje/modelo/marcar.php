<?php
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;

$bd = new DataBase();

$result = array();
$vectorR=array();
$result['error'] = false;
foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}
$vectorA = json_decode($vector, true);
$linksA = isset($links) ? json_decode($links, true) : [];

if (isset($codigo)) {

  try {

    // Get main file link (this will be used for mandatory activities)
    $mainFileLink = isset($linksA[$codigo]) ? $linksA[$codigo] : '';

    // First, get all activities that are marked and their obligatory status
    $where = " WHERE
    p.codigo = pd.cod_planif_cl_trab
    AND pd.cod_proyecto = pp.codigo
    AND pd.cod_actividad = pa.codigo
    ANd p.cod_ubicacion = cu.codigo
    AND DATE_FORMAT(p.fecha_inicio, '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')
    AND p.cod_ficha = '$cod_ficha'  AND p.cod_cliente = '$cod_cliente' AND p.cod_ubicacion = '$cod_ubicacion'
    AND  pp.codigo='$cod_proyecto'
    AND pd.codigo IN (" . implode(',', array_map(function($item) { return "'$item'"; }, $vectorA)) . ")";

    $sql1 = "SELECT
    pd.codigo, pa.obligatoria
    FROM
        planif_clientes_superv_trab p,
        planif_clientes_superv_trab_det pd,
        planif_proyecto pp,
        planif_actividad pa,
        clientes_ubicacion cu
        " . $where . " ORDER BY pd.codigo ASC";

    $query2 = $bd->consultar($sql1);

    // Update each activity with the appropriate link
    for ($i = 0; $i < count($vectorA); $i++) {
      $cod = $vectorA[$i];

      // Check if this activity has its own specific file
      if (isset($linksA[$cod])) {
        // Activity has its own file
        $link = $linksA[$cod];
      } else {
        // Check if this activity is mandatory (obligatoria = 'T')
        $obligatoria = 'F'; // default
        $query2->data_seek(0); // Reset pointer
        while ($row = $query2->fetch_assoc()) {
          if ($row['codigo'] == $cod) {
            $obligatoria = $row['obligatoria'];
            break;
          }
        }

        // If activity is mandatory, use main file link; otherwise empty
        $link = ($obligatoria == 'T') ? $mainFileLink : '';
      }

      $sql = "UPDATE planif_clientes_superv_trab_det SET realizado = 'T',link='$link', cod_us_marcaje = '$usuario' WHERE codigo = '$cod'";
      $query3 = $bd->consultar($sql);
    }

    // The main file record should also be marked as completed
    $sql = "UPDATE planif_clientes_superv_trab_det SET realizado = 'T',link='$mainFileLink', cod_us_marcaje = '$usuario' WHERE codigo = '$codigo'";
    $query = $bd->consultar($sql);
    $result['sql'] = $sql;

  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sc_marcaje_supervisor.php",  "$usuario", "$error", "$sql");
  }
}
 
print_r(json_encode($result));
return json_encode($result);
