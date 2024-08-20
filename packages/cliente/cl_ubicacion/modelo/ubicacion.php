<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
  $variables = "\$".$nombre_campo."='".$valor."';";
  eval($variables);
}
$codigo  = htmlentities($codigo);
	if(isset($_POST['proced'])){
		try {
      if ($metodo == 'agregar') {
        $sql = "    INSERT INTO clientes_ubicacion (codigo, cod_cliente, cod_estado, cod_ciudad, cod_calendario, cod_region, cod_zona, descripcion,
                                    contacto, cargo, telefono, email, direccion, observacion, latitud, longitud,
                                    campo01, campo02, campo03, campo04, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, `status`,
                                   contacto1,cargo1,telefono1,email1,contacto2,cargo2,telefono2,email2,contacto3,cargo3,telefono3,email3)
                            VALUES (NULL, '$cliente', '$estado', '$ciudad',  '$calendario', '$region', '$zona', '$nombre',
                                     '$contacto', '$cargo', '$telefono', '$email', '$direccion', '$observ', '$latitud', '$longitud',                                    
                                    '$campo01', '$campo02', '$campo03', '$campo04', '$usuario', CURRENT_DATE, '$usuario', CURRENT_DATE,   
                                    '$status','$contacto1','$cargo1','$telefono1','$email1','$contacto2','$cargo2','$telefono2','$email2','$contacto3','$cargo3','$telefono3','$email3');";
       }else if($metodo == 'modificar') {
          $sql = "     UPDATE clientes_ubicacion SET      
                                  cod_estado = '$estado',  cod_ciudad   = '$ciudad',
                                  cod_region = '$region',  cod_zona     = '$zona',
                                  cod_calendario = '$calendario',
                                  descripcion = '$nombre', contacto   = '$contacto',
                                  cargo      = '$cargo',
                                  telefono   = '$telefono', email = '$email',
                                  contacto1   = '$contacto1',
                                  cargo1      = '$cargo1',
                                  telefono1  = '$telefono1', email1 = '$email1',
                                  contacto2   = '$contacto2',
                                  cargo2      = '$cargo2',
                                  telefono2   = '$telefono2', email2 = '$email2',
                                  contacto3   = '$contacto3',
                                  cargo3      = '$cargo3',
                                  telefono3   = '$telefono3', email3 = '$email3',
                                  direccion  = '$direccion',     observacion = '$observ',
                                  latitud= '$latitud', longitud='$longitud',
                                  campo01    = '$campo01',    campo02 = '$campo02',
                                  campo03    = '$campo03',    campo04 = '$campo04',
                                  cod_us_mod = '$usuario', fec_us_mod = CURRENT_DATE,
                                  `status`   = '$status'
                            WHERE codigo     = $codigo
                              AND cod_cliente = '$cliente';";
       }
   	 $query = $bd->consultar($sql);
     $result['sql'] = $sql;

 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;
       $bd->log_error("Aplicacion", "sc_ubicacion.php",  "$usuario", "$error", "$sql");
   }
	}
	print_r(json_encode($result));
	return json_encode($result);

?>
