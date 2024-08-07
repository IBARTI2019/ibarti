<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase(); 
//require_once('../autentificacion/aut_config.inc.php'); 
//include_once('../funciones/mensaje_error.php');	
	
$codigo        = $_POST['codigo'];

	$sql = " SELECT productos.descripcion AS producto, prod_lineas.descripcion AS linea, 
                    prod_sub_lineas.descripcion AS sub_linea, prod_dotacion_det.cantidad, 
                   (SELECT MAX(a.fec_dotacion) FROM prod_dotacion a, prod_dotacion_det b  
                     WHERE a.cod_ficha = prod_dotacion.cod_ficha  AND a.anulado = 'F' 
					 AND a.codigo = b.cod_dotacion  AND b.cod_producto = prod_dotacion_det.cod_producto) AS fec_dotacion
               FROM prod_dotacion , prod_dotacion_det, productos, prod_lineas, prod_sub_lineas
              WHERE prod_dotacion.codigo = prod_dotacion_det.cod_dotacion 
                AND prod_dotacion_det.cod_producto = productos.codigo
                AND prod_dotacion_det.cod_linea = prod_lineas.codigo
                AND prod_dotacion_det.cod_sub_linea = prod_sub_lineas.codigo
				AND prod_dotacion_det.anulado = 'F'
                AND prod_dotacion.anulado = 'F'
                AND prod_dotacion.cod_ficha = '$codigo'
           GROUP BY prod_dotacion.cod_ficha, prod_dotacion_det.cod_producto
           ORDER BY 1 DESC ";
    ?>	
    <fieldset class="fieldset">
  <legend>Ultima Dotacion Trabajador: </legend>
<table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="12%" class="etiqueta">Fecha Ultima <br /> Dotacion</th>
			<th width="20%" class="etiqueta">Linea</th>
    		<th width="20%" class="etiqueta">Sublinea</th>
			<th width="40%" class="etiqueta">Producto</th>
            <th width="10%" class="etiqueta">Cantida</th>
	</tr>
   <?php       
	$valor = 0;
		$query = $bd->consultar($sql);
		while($datos=$bd->obtener_fila($query,0)){
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
        echo '<tr class="'.$fondo.'"> 
                  <td>'.$datos["fec_dotacion"].'</td>
				  <td>'.longitudMin($datos["linea"]).'</td>
				  <td>'.longitudMin($datos["sub_linea"]).'</td> 
                  <td>'.longitudMax($datos["producto"]).'</td>		
				  <td>'.$datos["cantidad"].'</td>
            </tr>'; 
        }?>
    </table>
<?php
  $sql02 = " SELECT preing_camisas.descripcion AS camisa, preing_pantalon.descripcion AS pantalon,
                    preing_zapatos.descripcion AS zapato 
               FROM ficha , preing_camisas , preing_pantalon , preing_zapatos
              WHERE ficha.cod_ficha = '$codigo' 
                AND ficha.cod_t_camisas = preing_camisas.codigo
                AND ficha.cod_t_pantalon = preing_pantalon.codigo
                AND ficha.cod_n_zapatos =  preing_zapatos.codigo ";
    ?>	
  </fieldset>
        <fieldset class="fieldset">
  <legend>Referencia Talla Trabajador: </legend>
<table width="100%" border="0" align="center">
	
   <?php       
	$valor = 0;
		$query = $bd->consultar($sql02);
		while($datos=$bd->obtener_fila($query,0)){
	echo'
	<tr class="fondo00">
			<th width="33%" class="etiqueta">Talla Camisa:  '.longitudMax($datos[0]).' </th>
			<th width="33%" class="etiqueta">Talla Pantalon:  '.longitudMax($datos[1]).'</th>
    		<th width="34%" class="etiqueta">Numero Zapato:  '.longitudMax($datos[2]).'</th>
	</tr>';
        } mysql_free_result($query);?>
    </table>
  </fieldset>
  