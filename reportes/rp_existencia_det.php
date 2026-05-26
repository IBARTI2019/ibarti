<?php
define("SPECIALCONSTANT", true);
require "../autentificacion/aut_config.inc.php";
include_once "../".Funcion;
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
  $variables = "\$".$nombre_campo."='".$valor."';";
  eval($variables);
}

$archivo         = "rp_existencia_".$fecha;
$titulo          = " Reporte De Existencia ";

if(isset($generar_tipo)){

  $where = " WHERE a.cod_producto = b.item AND a.cod_almacen = c.codigo
            AND b.cod_linea = d.codigo AND b.cod_sub_linea = e.codigo  ";

  if($linea != "TODOS"){
    $where  .= " AND b.cod_linea = '$linea' ";
  }

  if($sub_linea != "TODOS"){
    $where  .= " AND b.cod_sub_linea = '$sub_linea' ";
  }

  if($producto != "TODOS"){
    $where  .= " AND a.cod_producto = '$producto' ";
  }

  if($almacen != "TODOS"){
    $where  .= " AND a.cod_almacen = '$almacen' ";
  }

  $sql = " SELECT a.cod_producto,b.descripcion producto,d.descripcion linea, e.descripcion sub_linea,c.descripcion almacen,      (SELECT d.importe FROM ajuste_reng d
    WHERE  d.cod_almacen = a.cod_almacen 
    AND d.cod_producto = a.cod_producto
    ORDER BY d.cod_ajuste DESC, d.reng_num DESC LIMIT 1) importe,
    (SELECT e.cos_promedio FROM ajuste_reng e
    WHERE e.cod_almacen = a.cod_almacen 
    AND e.cod_producto = a.cod_producto
    ORDER BY e.cod_ajuste DESC, e.reng_num DESC LIMIT 1) cos_prom_actual,IF(b.ean = 'T', 1, a.stock_actual) stock_actual,
  pe.cod_ean, a.stock_reservado
  FROM stock a
LEFT JOIN prod_ean pe ON  a.cod_producto = pe.cod_producto AND a.cod_almacen = pe.cod_almacen AND pe.inStock = 'T',
 productos b, almacenes c,prod_lineas d,prod_sub_lineas e
  $where";

  if($generar_tipo == "excel"){
    echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition:  filename=\"$archivo.xls\";");


    $query  = $bd->consultar($sql);
    echo "<table border=1>
    <tr><th colspan='9'>".$titulo."</th></tr>
    <tr><th> Serial </th><th>".$leng["producto"]." <th>Linea</th><th> Sub Linea</th><th>Almacen</th><th>Importe</th><th>Ultimo Costo Promedio</th> <th>Stock</th><th>Reservado</th><th>EAN</th> </tr>";

    while ($dato = $bd->obtener_fila($query)){
     echo "<tr><td>".$dato[0]."</td><td>".$dato[1]."</td><td>".$dato[2]."</td><td>".$dato[3]."</td>
     <td>".$dato[4]."</td><td>".$dato[5]."</td><td>".$dato[6]."</td><td>".$dato[7]."</td><td>".$dato[9]."</td><td>".$dato[8]."</td></tr>";
   }
   echo "</table>";
 }

 if($generar_tipo == "pdf"){

  require_once('../'.ConfigDomPdf);
  $dompdf= new DOMPDF();
  $query  = $bd->consultar($sql);
  ob_start();
  require('../'.PlantillaDOM.'/header_ibarti.php');
  include('../'.pagDomPdf.'/paginacion_ibarti.php');

  echo "<table width='100%'>
  <tbody>
  <tr style='background-color: #4CAF50;'>
  <th width:'10%'>Serial</th>
  <th width:'30%'>".$leng['producto']."</th>
  <th width:'15%'>Linea</th>
  <th width:'15%'>Sub Linea</th>
  <th width:'20%'>Almacen</th>
  <th width:'10%'>Stock</th>
  <th width:'10%'>Asignado / Reservado</th>
  </tr>";

  $f=0;
  while ($row = $bd->obtener_num($query)){
   if ($f%2==0){
    echo "<tr>";
  }else{
    echo "<tr class='odd_row'>";
  }
  echo   "<td width:'10%'>".$row[0]."</td>
  <td width:'30%'>".$row[1]."</td>
  <td width:'15%'>".$row[2]."</td>
  <td width:'15%'>".$row[3]."</td>
  <td width:'20%'>".$row[4]."</td>
  <td width:'10%'>".$row[7]."</td>
  <td width:'10%'>".$row[9]."</td></tr>";

  $f++;
}

echo "</tbody>
</table>
</div>
</body>
</html>";

$dompdf->load_html(ob_get_clean(),'UTF-8');
$dompdf->set_paper('letter','landscape');
$dompdf->render();
$dompdf->stream($archivo, array('Attachment' => 0));
}
}
?>
