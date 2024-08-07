<?php
require('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require("../".class_bd);
$bd = new DataBase();
$proced      = "p_usuario";
//require ("../autentificacion/aut_config.inc.php");
// chequear p�gina que lo llama para devolver errores a dicha p�gina.

$url = explode("?",$_SERVER['HTTP_REFERER']);
$pag_referida=$url[0];
$redir=$pag_referida;

// chequear si se llama directo al script.
/*
if ($_SERVER['HTTP_REFERER'] == ""){

die ("<script language='javascript'>
					alert('Error: Acceso Incorrecto   URL...');
					history.back(-1);
	</script>");
exit;
}*/
// Chequeamos si se est� autentificandose un usuario por medio del formulario Login
if (isset($_POST['login']) && isset($_POST['pass'])) {
// Conexi�n base de datos.
// si no se puede conectar a la BD salimos del script con error 0 y
// redireccionamos a la pagina de error.

	$login = strtoupper($_POST['login']);
	$tb_usuario = "men_usuarios";
	$tb_perfil  = "men_perfil_menu";
/*
     $query = "SELECT codigo, cod_perfil, cedula, nombre,
	                  apellido, login, pass, pass_old, email,
					  DATEDIFF(NOW(), fec_mod_pass) AS dias_caduca,
                      status
                      FROM $tb_usuario WHERE $tb_usuario.login = '$login'"; */

                      $query = "SELECT $tb_usuario.codigo, $tb_usuario.cod_perfil, $tb_usuario.cedula, $tb_usuario.nombre,
                      $tb_usuario.apellido, $tb_usuario.login, $tb_usuario.pass, $tb_usuario.pass_old,
                      $tb_usuario.email, DATEDIFF(NOW(), $tb_usuario.fec_mod_pass) AS dias_caduca,
                      $tb_usuario.r_cliente, $tb_usuario.r_rol, $tb_usuario.status, clientes.abrev, clientes.nombre AS cl_nombre,
                      control.ficha_preingreso, control.administrador, control.cod_pais, control.ficha_activo,
                      control.oesvica AS cl_principal


                      FROM $tb_usuario, control, clientes WHERE $tb_usuario.login = '$login'
                      AND clientes.codigo = control.oesvica";

                      $usuario_consulta = $bd->consultar($query) or die(header ("Location:  $redir?error_login=1"));

                      /*                   OJO REVISAR ELTO DEL PERFIL NO TIENE SENTIDO    */
                      $perfil_id = $usuario_consulta['cod_perfil'];

                      $query = "SELECT cod_menu FROM men_perfil_menu WHERE cod_men_perfil = '$perfil_id'";
                      $query_perfil = $bd->consultar($query) or die(header ("Location:  $redir?error_login=8"));

                      if ($bd->num_fila($usuario_consulta) != 0){
	// echo $_POST['pass'],'</br>';
	//echo md5($_POST['pass']);
                      	$password = md5($_POST['pass']);
	//$password = $_POST['pass'];
    // almacenamos datos del Usuario en un array para empezar a chequear.

                      	$usuario_datos = $bd->obtener_fila($usuario_consulta,0);
    // liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
 //  pg_free_result($usuario_consulta);
    // cerramos la Base de dtos.
//   pg_close($cnn);

    // chequeamos el nombre del usuario otra vez contrastandolo con la BD

                      	if ($login != $usuario_datos['login']){

                      		Header("Location: $redir?error_login=4");
                      		exit;}

    // si el password no es correcto ..
    // salimos del script con error 3 y redireccinamos hacia la p�gina de error

                      		if ($password != $usuario_datos['pass']) {
                      			$cod  =	$usuario_datos['codigo'];

                      			$ip  = get_client_ip();
                      			$_GET[""];

                      			$sql    = "$SELECT $proced('error', '$cod', '', '',
                      			'', '','', '',
                      			'', '','', '',
                      			'', '', '')";

                      			$query = $bd->consultar($sql);
                      			Header ("Location: $redir?error_login=3");
                      			exit;}
                      			if ($usuario_datos['status']!= 'T'){

                      				Header("Location: $redir?error_login=7");
                      				exit;}

    // Paranoia: destruimos las variables login y password usadas
                      				unset($login);
                      				unset ($password);

    // En este punto, el usuario ya esta validado.
    // Grabamos los datos del usuario en una sesion.

     // le damos un nombre a la sesion.
    //session_name($usuarios_sesion);
     // incia sessiones
                      				session_start();
    // Paranoia: decimos al navegador que no "cachee" esta p�gina.
                      				session_cache_limiter('nocache,private');

    // Asignamos variables de sesi�n con datos del Usuario para el uso en el
    // resto de p�ginas autentificadas.
/*
	  if(strtoupper($_POST["captcha"]) == $_SESSION["captcha"]){
		 // REMPLAZO EL CAPTCHA USADO POR UN TEXTO LARGO PARA EVITAR QUE SE VUELVA A INTENTAR
		 $_SESSION["captcha"] = md5(rand()*time());
	 	 // INSERTA EL C�DIGO EXITOSO AQUI

	  }else{
		 // REMPLAZO EL CAPTCHA USADO POR UN TEXTO LARGO PARA EVITAR QUE SE VUELVA A INTENTAR
		 $_SESSION["captcha"] = md5(rand()*time());
	 	 // INSERTA EL C�DIGO DE ERROR AQU�
		Header("Location: $redir?error_login=10");
	exit;
	  }

*/

	  $_SESSION['captcha'] = $usuario_datos['login'];

	  $_SESSION['usuario_cod']    = $usuario_datos['codigo'];
	  $_SESSION['usuario_cedula'] = $usuario_datos['cedula'];
	  $_SESSION['usuario_login']  = $usuario_datos['login'];
    //definimos usuario_password con el password del usuario de la sesi�n actual (formato md5 encriptado)
	  $_SESSION['usuario_password'] = $usuario_datos['pass'];
	  $_SESSION['usuarioN']     = $usuario_datos['nombre'];
	  $_SESSION['usuarioA']     = $usuario_datos['apellido'];
	  $_SESSION['cod_perfil']   = $usuario_datos['cod_perfil'];
	  $_SESSION['cl_abrev']     = $usuario_datos['abrev'];
	  $_SESSION['cl_nombre']    = $usuario_datos['cl_nombre'];
	  $_SESSION['dias_caduca']  = $usuario_datos['dias_caduca'];
	  $_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");

	  $_SESSION['ficha_preingreso']  = $usuario_datos['ficha_preingreso'];
	  $_SESSION['cod_administrador'] = $usuario_datos['administrador'];
	  $_SESSION['cod_pais']          = $usuario_datos['cod_pais'];
	  $_SESSION['cod_ficha_activo']  = $usuario_datos['cod_ficha_activo'];
	  $_SESSION['cl_principal']      = $usuario_datos['cl_principal'];
	  $_SESSION['r_cliente']         = $usuario_datos['r_cliente'];
	  $_SESSION['r_rol']             = $usuario_datos['r_rol'];




    // Hacemos una llamada a si mismo (scritp) para que queden disponibles
    // las variables de session en el array asociado $HTTP_...

   /* $pag=$_SERVER['PHP_SELF'];
    Header ("Location: $pag?");
    exit;*/

    $ip       = get_client_ip();
    $captcha  =  $_SESSION["captcha"];
    $cod  =	$usuario_datos['codigo'];
    $_SESSION['ip'] = $usuario_datos['ip'];

    $sql     = "$SELECT $proced('conexion', '$cod', '', '',
    '', '','', '',
    '', '','', '',
    '$ip', '$captcha', '', '', '')";
    $query = $bd->consultar($sql);


    Header("Location: ../inicio.php?area=formularios/index&mod=000&Nmenu=000");
//Header("Location: ../pl_inicio.php?nivel=5&area=formularios/index");

} else {
       // si no esta el nombre de usuario en la BD o el password ..
      // se devuelve a pagina q lo llamo con error
	Header("Location: $redir?error_login=2");
	exit;}
} else {

// -------- Chequear sesi�n existe -------

// usamos la sesion de nombre definido.
	session_name($usuarios_sesion);
// Iniciamos el uso de sesiones
	session_start();

// Chequeamos si estan creadas las variables de sesi�n de identificaci�n del usuario,
// El caso mas comun es el de una vez "muerta" la sesion se intenta volver hacia atras
// con el navegador.


	if (!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
	// Borramos la sesion creada por el inicio de session anterior

		die ("<script language='javascript'>
			alert('Error: Acceso Incorrecto Seccion...');
			</script>!");
		require("aut_logout.php");
	}
}
   // liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
//   mysql_free_result($row_perfil);
?>
