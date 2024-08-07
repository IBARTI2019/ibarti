<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$codigo   = $_POST['codigo'];
$relacion = $_POST['relacion'];
$cod_talla = "";
	$sql = " SELECT a.codigo, a.descripcion,productos.cod_talla FROM almacenes a ,stock b,productos
    WHERE b.cod_almacen = a.codigo 
    AND b.cod_producto = '$codigo' 
    AND productos.item =b.cod_producto
    ORDER BY 2 ASC ";
   $query = $bd->consultar($sql);
//    onchange="cantidad_maxima(this.value,'.$relacion.')" 
	echo'<select name="almacen_'.$relacion.'" id="almacen_'.$relacion.'" onchange="cantidad_maxima(this.value, '.$relacion.')" style="width:200px" required>
			     <option value="">Seleccione...</option>';
			     $i=0;
			  	 while($datos=$bd->obtener_fila($query,0)){
			  	 	if($i==0){
			  	 		$cod_talla =$datos[2];
			  	 	}
		echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
				}
		  echo'</select>
		  <input type="hidden" value="'.$cod_talla.'" id="cod_talla_'.$relacion.'" />';
		  mysql_free_result($query);
?>
