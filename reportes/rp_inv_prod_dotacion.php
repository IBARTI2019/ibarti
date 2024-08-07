<?php
session_start(); 
define("SPECIALCONSTANT", true);
require "../autentificacion/aut_config.inc.php";
include "../".Funcion;
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();
require_once('../'.ConfigDomPdf);

//$codigo='99';
$codigo      = $_POST["codigo"];
$usuario =  $_SESSION['usuario_cod'];

$sql = "SELECT prod_dotacion.codigo, prod_dotacion.fec_dotacion,
prod_dotacion.descripcion,
v_ficha.rol, v_ficha.cod_ficha,
v_ficha.cedula, v_ficha.ap_nombre AS trabajador,men_usuarios.cedula AS cedulausuario, concat(men_usuarios.nombre ,' ',men_usuarios.apellido) as nombreusuario,
prod_dotacion.descripcion,  control.nota_unif
FROM v_ficha , prod_dotacion, control, men_usuarios 
WHERE v_ficha.cod_ficha = prod_dotacion.cod_ficha AND prod_dotacion.codigo = '". $codigo ."' AND men_usuarios.codigo='$usuario'";
//query Cliente
$queryc = $bd->consultar($sql);

$sql02 = "SELECT IF(prod_sub_lineas.talla = 'T', CONCAT(productos.descripcion,' ',tallas.descripcion), productos.descripcion ) producto, prod_lineas.descripcion AS linea,
prod_sub_lineas.descripcion AS sub_linea, prod_dotacion_det.cantidad
FROM prod_dotacion_det ,  productos, prod_lineas , prod_sub_lineas,tallas
WHERE  prod_dotacion_det.cod_dotacion = '".$codigo."'
AND prod_dotacion_det.cod_producto = productos.item
AND productos.cod_linea = prod_lineas.codigo
AND productos.cod_sub_linea = prod_sub_lineas.codigo
AND productos.cod_talla = tallas.codigo";
//query Producto
$queryp = $bd->consultar($sql02);

if ($row = $bd->obtener_name($queryc))
{
  ob_start();
  $titulo= 'DOTACIÓN DE UNIFORMES Y EQUIPOS DE PROTECCIÓN PERSONAL';
  
  require_once('../'.PlantillaDOM.'/unicas/prod_dotacion_ibarti.php');
 //$dompdf = new DOMPDF();
 //$dompdf->load_html(ob_get_clean(),'UTF-8');
 //$dompdf->render();
 //$pdf=$dompdf->output();
 //$dompdf->stream('prod_dotacion_ibarti.pdf', array('Attachment' => 1));


// // instantiate and use the dompdf class
// $dompdf = new Dompdf\Dompdf();
// $dompdf->loadHtml('hello world');

// // (Optional) Setup the paper size and orientation
// $dompdf->setPaper('A4', 'landscape');

// // Render the HTML as PDF
// $dompdf->render();

// // Output the generated PDF to Browser
// $dompdf->stream();

}else{
  echo "<h3>Error</h3>";
}
