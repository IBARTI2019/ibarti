<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>
<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
//include_once('../funciones/mensaje_error.php');

$codigo      = $_POST["codigo"];
$descripcion = $_POST["descripcion"];
$tipo        = $_POST["tipo"];
$activo      = statusbd($_POST['activo']);

$href     = $_POST['href'];
$usuario  = $_POST['usuario']; 
$proced   = $_POST['proced']; 
$metodo   = $_POST['metodo'];

// Recibimos el array de roles seleccionados desde el formulario
$roles_destino = isset($_POST['roles_destino']) ? $_POST['roles_destino'] : array();

// 1. Guardar o modificar el registro del calendario ejecutando tu procedimiento habitual
$sql   = "$SELECT $proced('$metodo', '$codigo', '', '$descripcion', '$tipo', '$usuario',  '$activo')";                 
$query = $bd->consultar($sql);                  

// 2. Si el proceso fue agregar, obtenemos el último código generado para asociar los roles
if ($metodo == 'agregar') {
    $sql_id = "SELECT MAX(codigo) AS ultimo_id FROM nom_calendario WHERE cod_us_ing = '$usuario';";
    $query_id = $bd->consultar($sql_id);
    $res_id = $bd->obtener_fila($query_id, 0);
    $codigo = $res_id['ultimo_id'];
}

// 3. Registrar la relación con los múltiples roles seleccionados
if (!empty($codigo)) {
    // Primero limpiamos las relaciones anteriores para evitar duplicados en modificaciones
    $sql_delete = "DELETE FROM roles_calendario WHERE cod_calendario = '$codigo';";
    $bd->consultar($sql_delete);
    
    // Insertamos la relación para cada rol seleccionado
    foreach ($roles_destino as $cod_rol) {
        $cod_rol_escape = mysql_real_escape_string($cod_rol); // Para compatibilidad nativa en PHP 5
        $sql_insert_rol = "INSERT INTO roles_calendario (cod_calendario, cod_rol, cod_us_ing, fec_us_ing) 
                           VALUES ('$codigo', '$cod_rol_escape', '$usuario', CURRENT_DATE);";
        $bd->consultar($sql_insert_rol);
    }
}

require_once('../funciones/sc_direccionar.php');  
?>
<body>
</body>
</html>