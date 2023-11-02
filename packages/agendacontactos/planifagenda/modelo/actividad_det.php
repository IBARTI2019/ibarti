<?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();

$result = array();

  foreach($_POST as $nombre_campo => $valor){
    if($nombre_campo != "actividades"){
      $variables = "\$".$nombre_campo."='".$valor."';";
      eval($variables);
    }
  }

	if(isset($_POST['metodo'])){
		try {
      $result["codigo"] = $codigo;
      if ($metodo == "agregar") {
         $sql  = "INSERT INTO planif_clientes_superv_trab
                             (cod_planif_cl, cod_cliente, cod_ubicacion, cod_ficha, fecha_inicio, fecha_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                     VALUES ('$apertura', '$cliente', '$ubicacion', '$ficha',
                            '$fecha_inicio', '$fecha_fin', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
          $result['sql'] = $sql;
            $query = $bd->consultar($sql);

            $sql = "SELECT MAX(codigo) codigo FROM planif_clientes_superv_trab 
              WHERE cod_planif_cl = '$apertura' AND  cod_cliente ='$cliente'
              AND cod_ubicacion = '$ubicacion' AND cod_ficha = '$ficha' AND cod_us_ing = '$usuario';";
$result['sql'] = $sql;
            $query = $bd->consultar($sql);
            $codigo = $bd->obtener_fila($query);
            $result["codigo"] = $codigo[0];
            foreach($_POST["actividades"] as $key => $actividad){
              $sql  = "INSERT INTO planif_clientes_superv_trab_det
              (cod_planif_cl_trab, cod_proyecto, cod_actividad, fecha_inicio, fecha_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
              VALUES (".$codigo[0].", ".$actividad['cod_proyecto'].", ".$actividad['codigo'].",'".$actividad['fecha_inicio']."','
                      ".$actividad['fecha_fin']."', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
                      $result['sql'] = $sql;
              $query = $bd->consultar($sql);
            }
 
      }elseif ($metodo == "modificar") {
        $sql  = "UPDATE planif_clientes_superv_trab
                    SET cod_cliente = '$cliente',   cod_ubicacion ='$ubicacion', cod_ficha   = '$ficha',
                    fecha_inicio = '$fecha_inicio',  fecha_fin = '$fecha_fin', cod_us_mod  = '$usuario',    
                    fec_us_mod = CURRENT_TIMESTAMP  WHERE codigo = '$codigo'";
                 $query = $bd->consultar($sql);
                $result['sql'] = $sql;
                if(isset($_POST["actividades"])){
                $sql  = "DELETE FROM planif_clientes_superv_trab_det
                WHERE cod_planif_cl_trab = $codigo";
                $query = $bd->consultar($sql);
                    foreach($_POST["actividades"] as $key => $actividad){
                      $sql  = "INSERT INTO planif_clientes_superv_trab_det
                      (cod_planif_cl_trab, cod_proyecto, cod_actividad, fecha_inicio, fecha_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                      VALUES ($codigo, ".$actividad['cod_proyecto'].", ".$actividad['codigo'].",'".$actividad['fecha_inicio']."','
                      ".$actividad['fecha_fin']."', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
                      $result['sql'] = $sql;
                      $query = $bd->consultar($sql);
                    }
                  }
      }elseif ($metodo == "borrar") {
        $sql  = "DELETE FROM planif_clientes_superv_trab_det
        WHERE cod_planif_cl_trab = $codigo";
        $result['sql'] = $sql;
        $query = $bd->consultar($sql);
        $sql = "DELETE FROM planif_clientes_superv_trab
                 WHERE codigo = '$codigo' ";
                 
                 $result['sql'] = $sql;
                 	 $query = $bd->consultar($sql);
      }

$result['error']=false;
 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;
       $bd->log_error("Aplicacion", "sc_planificacion_trab_det.php",  "$usuario", "$error", "$sql");
   }

	}
	print_r(json_encode($result));
	return json_encode($result);
