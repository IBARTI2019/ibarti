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

if (isset($codigo)) {
  
  try {
<<<<<<< Updated upstream
=======

    $where = " WHERE
    p.codigo = pd.cod_planif_cl_trab
    AND pd.cod_proyecto = pp.codigo
    AND pd.cod_actividad = pa.codigo
    ANd p.cod_ubicacion = cu.codigo
    and pa.obligatoria='F'
    AND DATE_FORMAT(p.fecha_inicio, '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')
    -- AND TIME(pd.fecha_fin) <= CURRENT_TIME()
    AND p.cod_ficha = '$cod_ficha'  AND p.cod_cliente = '$cod_cliente' AND p.cod_ubicacion = '$cod_ubicacion'
    ";
    $sql1 = "SELECT
    pd.codigo, cu.descripcion ubicacion, pd.cod_proyecto, pp.descripcion proyecto, pd.cod_actividad, pa.descripcion actividad, 
    IF(pd.realizado = 'T', 'SI', 'NO') realizado, TIME(pd.fecha_inicio) hora_inicio, TIME(pd.fecha_fin) hora_fin,
    pa.participantes,
    (
        SELECT
            COUNT(b.codigo)
        FROM
            planif_clientes_superv_trab_det a,
            planif_clientes_superv_trab_det_observ b
        WHERE
            a.codigo = b.cod_det
        AND a.codigo = pd.codigo 
    ) observaciones,
    (SELECT
            COUNT(b.codigo)
            FROM
                planif_clientes_superv_trab_det a,
                planif_clientes_superv_trab_det_participantes b
            WHERE
                a.codigo = b.cod_det
    AND a.codigo = pd.codigo) fichas
    FROM
        planif_clientes_superv_trab p,
        planif_clientes_superv_trab_det pd,
        planif_proyecto pp,
        planif_actividad pa,
        clientes_ubicacion cu 
        " . $where . " ORDER BY hora_inicio ASC";

    $query2 = $bd->consultar($sql1);
   
   
    
    for ($i = 0; $i < count($vectorA); $i++) {
      $j=0;  
        foreach ($query2 as  $datos) {
          $numeroABuscar = $j;
          // Convertir el elemento a entero y comparar
          if ($vectorA[$i] == $numeroABuscar) {
              echo $datos["codigo"];
              $codigoV=$datos["codigo"];
              $sql    = "UPDATE planif_clientes_superv_trab_det SET realizado = 'T',link='$link', cod_us_marcaje = '$usuario' WHERE codigo = '$codigoV'";
              $query3 = $bd->consultar($sql);
           }
          $j=$j + 1;
      }
     
    }  
    
>>>>>>> Stashed changes
    $sql    = "UPDATE planif_clientes_superv_trab_det SET realizado = 'T',link='$link', cod_us_marcaje = '$usuario' WHERE codigo = '$codigo'";
    $query = $bd->consultar($sql);
    $result['sql'] = $sql;
    
    
<<<<<<< Updated upstream
=======
    
>>>>>>> Stashed changes
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sc_marcaje_supervisor.php",  "$usuario", "$error", "$sql");
  }
}
 
print_r(json_encode($result));
return json_encode($result);
