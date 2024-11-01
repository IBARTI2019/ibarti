<?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();

$result = array();

  foreach($_POST as $nombre_campo => $valor){
    $variables = "\$".$nombre_campo."='".$valor."';";
    eval($variables);
  }

	if(isset($_POST['metodo'])){
		try {
      if ($metodo == "agregar") {
        $sql  = "INSERT INTO planif_clientes_trab_det
                             (codigo, cod_planif_cl, cod_planif_cl_trab, cod_cliente,
                              cod_ubicacion, cod_puesto_trabajo, cod_turno, cod_ficha,
                              fecha,
                              cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                     VALUES (NULL, '$apertura', '$planif_cl_trab', '$cliente',
                            '$ubicacion','$puesto_trab', '$turno',  '$ficha',
                            '$fecha',
                            '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP);";
      }elseif ($metodo == "modificar") {
        $sql  = "UPDATE planif_clientes_trab_det
                    SET cod_cliente = '$cliente',   cod_ubicacion ='$ubicacion',
                        cod_turno   = '$turno',     cod_puesto_trabajo = '$puesto_trab',
                        cod_ficha   = '$ficha',
                        cod_us_mod  = '$usuario',    fec_us_mod = CURRENT_TIMESTAMP
                 WHERE codigo = '$codigo' AND fecha >= CURRENT_DATE";
      }elseif ($metodo == "borrar") {
        $sql = "DELETE FROM planif_clientes_trab_det
                 WHERE codigo = '$codigo' AND fecha >= CURRENT_DATE ";
      }

	 $query = $bd->consultar($sql);
   $result['sql'] = $sql;
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
