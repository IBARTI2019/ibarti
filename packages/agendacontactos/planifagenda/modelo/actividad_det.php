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
        $sql  = "INSERT INTO agendas_contactos_fc
                             (cod_cliente, cod_ubicacion,fecha_inicio, fecha_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                     VALUES ('$cliente', '$ubicacion','$fecha_inicio', '$fecha_fin', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
        $result['sql'] = $sql;
        $query = $bd->consultar($sql);

        $sql = "SELECT MAX(codigo) codigo FROM agendas_contactos_fc
          WHERE  cod_cliente ='$cliente'
          AND cod_ubicacion = '$ubicacion' AND cod_us_ing = '$usuario';";
        $result['sql'] = $sql;
        $query = $bd->consultar($sql);
        $codigo = $bd->obtener_fila($query);
        $result["codigo"] = $codigo[0];
        
        foreach($_POST["actividades"] as $key => $actividad){
          $cod_forma=$actividad['cod_proyecto'];
          $codactividad=$actividad['codigo'];
          $sql  = "INSERT INTO agendas_contactos_fc_actividades
          (cod_agenda,cod_cliente,cod_ubicacion,cod_formcontacto, cod_actividad, fecha_inicio,hora,hora_fin,fecha_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
          VALUES ('$codigo[0]','$cliente', '$ubicacion','$cod_forma','$codactividad', '$fecha_inicio','$hora_inicio','$hora_fin',
                    '$fecha_fin', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
                  $result['sql'] = $sql;
          $query = $bd->consultar($sql);
        }
 
      }elseif ($metodo == "modificar") {
        $sql  = "UPDATE agendas_contactos_fc
                    SET cod_cliente = '$cliente',   cod_ubicacion ='$ubicacion', 
                    fecha_inicio = '$fecha_inicio',  fecha_fin = '$fecha_fin', cod_us_mod  = '$usuario',    
                    fec_us_mod = CURRENT_TIMESTAMP  WHERE codigo = '$codigo'";
                 $query = $bd->consultar($sql);
                $result['sql'] = $sql;
                if(isset($_POST["actividades"])){
                $sql  = "DELETE FROM agendas_contactos_fc_actividades
                WHERE codigo = $codigo";
                $query = $bd->consultar($sql);
                foreach($_POST["actividades"] as $key => $actividad){
                  $cod_forma=$actividad['cod_proyecto'];
                  $codactividad=$actividad['codigo'];
                  $sql  = "INSERT INTO agendas_contactos_fc_actividades
                  (cod_agenda,cod_cliente,cod_ubicacion,cod_formcontacto, cod_actividad, fecha_inicio, fecha_fin,hora,hora_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                  VALUES ('$codigo[0]','$cliente', '$ubicacion','$cod_forma','$codactividad', '$fecha_inicio',
                           '$fecha_fin','$hora_inicio','$hora_fin', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
                      $result['sql'] = $sql;
             
                      $result['sql'] = $sql;
                      $query = $bd->consultar($sql);
                    }
                  }
      }elseif ($metodo == "borrar") {
        $sql  = "DELETE FROM agendas_contactos_fc_actividades
        WHERE codigo = $codigo";
        $result['sql'] = $sql;
        $query = $bd->consultar($sql);
        $sql = "DELETE FROM agendas_contactos_fc
                 WHERE codigo = '$codigo' ";
                 
                 $result['sql'] = $sql;
                 	 $query = $bd->consultar($sql);
      }

$result['error']=false;
 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;
       $bd->log_error("Aplicacion", "sc_planificacion_agenda_contacto.php",  "$usuario", "$error", "$sql");
   }

	}
	print_r(json_encode($result));
	return json_encode($result);
