<?php
session_start(); 
define("SPECIALCONSTANT", true);
require "../autentificacion/aut_config.inc.php";
include "../".Funcion;
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();
require_once('../'.ConfigDomPdf);

$codigo      = $_POST["codigo"];
$usuario =  $_SESSION['usuario_cod'];

$sql = "SELECT prod_asignacion.codigo, prod_asignacion.fecha as fec_asignacion,
prod_asignacion.descripcion, prod_asignacion.tipo,
IFNULL(v_ficha.rol, 'N/A') as rol, IFNULL(v_ficha.cod_ficha, 'N/A') as cod_ficha,
IFNULL(v_ficha.cedula, 'N/A') as cedula, IFNULL(v_ficha.ap_nombre, 'N/A') AS trabajador,
men_usuarios.cedula AS cedulausuario, concat(men_usuarios.nombre ,' ',men_usuarios.apellido) as nombreusuario,
clientes_ubicacion.descripcion as ubicacion, clientes.nombre as cliente
FROM prod_asignacion 
LEFT JOIN v_ficha ON v_ficha.cod_ficha = prod_asignacion.cod_ficha
LEFT JOIN clientes_ubicacion ON clientes_ubicacion.codigo = prod_asignacion.cod_ubicacion
LEFT JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente
JOIN men_usuarios ON men_usuarios.codigo='$usuario'
WHERE prod_asignacion.codigo = '". $codigo ."'";
//query Cliente
$queryc = $bd->consultar($sql);

$sql02 = "SELECT IF(prod_sub_lineas.talla = 'T', CONCAT(productos.descripcion,' ',tallas.descripcion), productos.descripcion ) producto, prod_lineas.descripcion AS linea,
prod_sub_lineas.descripcion AS sub_linea, prod_asignacion_det.cantidad,
almacenes.descripcion as almacen,
(SELECT GROUP_CONCAT(cod_ean SEPARATOR ', ') FROM prod_asignacion_eans WHERE cod_asignacion = prod_asignacion_det.cod_asignacion AND cod_producto = prod_asignacion_det.cod_producto) AS eans
FROM prod_asignacion_det
JOIN productos ON prod_asignacion_det.cod_producto = productos.item
JOIN prod_lineas ON productos.cod_linea = prod_lineas.codigo
JOIN prod_sub_lineas ON productos.cod_sub_linea = prod_sub_lineas.codigo
JOIN tallas ON productos.cod_talla = tallas.codigo
JOIN almacenes ON almacenes.codigo = prod_asignacion_det.cod_almacen
WHERE prod_asignacion_det.cod_asignacion = '".$codigo."'";
//query Producto
$queryp = $bd->consultar($sql02);

if ($row = $bd->obtener_name($queryc))
{
  ob_start();
  $titulo= 'REPORTE DE ' . $row['tipo'] . ' DE INVENTARIO';
  
  require_once('../'.PlantillaDOM.'/unicas/prod_asignacion_ibarti.php');
}else{
  echo "<h3>Error</h3>";
}
