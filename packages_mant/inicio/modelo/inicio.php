<?php
define("SPECIALCONSTANT", true);

include_once "../../../funciones/funciones.php";
require "../../../autentificacion/aut_config.inc.php";
require_once "../../../".class_bdI;
$bd = new DataBase();
$result = array();
$result["respuesta"] = "hola inicio";
$proced      = "p_usuario";





	if( (isset($_POST['l']) && (isset($_POST['p']))) ){

		try {
	$password = md5($_POST['p']);
  $l = $_POST['l'];

 	 $sql    = "SELECT COUNT(a.codigo) existe, a.codigo, a.cod_perfil, a.cedula, a.nombre,
	                   a.apellido, a.login, a.pass, a.pass_old,
					           a.email, DATEDIFF(NOW(), a.fec_mod_pass) dias_caduca,
               		   a.r_cliente, a.r_rol, a.status, c.abrev, c.nombre cl_nombre,
					           b.ficha_preingreso, b.administrador, b.cod_pais, b.ficha_activo cod_ficha_activo,
                     b.oesvica AS cl_principal, a.admin_kanban, a.admin_rrhh
		            FROM men_usuarios a, control b, clientes c
               WHERE a.login = '$l'
                 AND a.pass = '$password'
                 AND a.status = 'T'
                 AND b.oesvica =c.codigo  ";
	 $query = $bd->consultar($sql);
   $datos = $bd->obtener_fila($query);

   // destroy variables
   unset($l);
   unset ($p);

   if ($datos['existe'] == 0){

    $result['error'] = true;
    $result['mensaje'] = "Información Incorrecta";
 	}elseif($datos['existe'] == 1){
    $result['error'] = False;
    $result['codigo'] = $datos['codigo'];
    $result['admin_kanban'] = $datos['admin_kanban'];

    //session_name($usuarios_sesion);
     // incia sessiones
    session_start();
    // Paranoia: decimos al navegador que no "cachee" esta p�gina.
    session_cache_limiter('nocache,private');
    // Asignamos variables de sesi�n con datos del Usuario para el uso en el
    // resto de p�ginas autentificadas.
    $_SESSION["captcha"] = md5(rand()*time());
    // INSERTA EL C�DIGO EXITOSO AQUI

  		$_SESSION['captcha'] = $datos['login'];
      $_SESSION['usuario_cod']    = $datos['codigo'];
      $_SESSION['usuario_cedula'] = $datos['cedula'];
      $_SESSION['usuario_login']  = $datos['login'];
      //definimos usuario_password con el password del usuario de la sesi�n actual (formato md5 encriptado);
      $_SESSION['usuario_password'] = $datos['pass'];
    	$_SESSION['usuarioN']     = $datos['nombre'];
    	$_SESSION['usuarioA']     = $datos['apellido'];
    	$_SESSION['cod_perfil']   = $datos['cod_perfil'];
    	$_SESSION['cl_abrev']     = $datos['abrev'];
    	$_SESSION['cl_nombre']    = $datos['cl_nombre'];
    	$_SESSION['dias_caduca']  = $datos['dias_caduca'];
    	$_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");

    	$_SESSION['ficha_preingreso']  = $datos['ficha_preingreso'];
    	$_SESSION['cod_administrador'] = $datos['administrador'];
    	$_SESSION['cod_pais']          = $datos['cod_pais'];
    	$_SESSION['cod_ficha_activo']  = $datos['cod_ficha_activo'];
    	$_SESSION['cl_principal']      = $datos['cl_principal'];
    	$_SESSION['r_cliente']         = $datos['r_cliente'];
    	$_SESSION['r_rol']             = $datos['r_rol'];
      $_SESSION['admin_rrhh'] = $datos['admin_rrhh'];
  }


 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;

       $bd->log_error("Aplicacion", "sc_login.php",  "$usuario", "$error", "$sql");
   }


 }else {
   $result['error'] = true;
   $result['mensaje'] = "Error en sistema de autentificacion";
   $result['sql'] = $sql;

 }

	print_r(json_encode($result));
	return json_encode($result);
?>
