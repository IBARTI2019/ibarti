<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
require_once("../".Leng);
$bd = new DataBase(); 
$codigo      = $_POST['codigo'];
	   $sql01 ="SELECT clientes_ub_alcance.cod_sub_linea cod_producto, prod_sub_lineas.descripcion producto, clientes_ub_alcance.cantidad,
          clientes_ub_alcance.dias, clientes_ub_alcance.vencimiento
              FROM clientes_ub_alcance, prod_sub_lineas WHERE clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo 
              AND clientes_ub_alcance.cod_cl_ubicacion = '$codigo'";
?>
<tr>
    <td colspan="2" class="etiqueta_title">Listado</td></tr>
    <?php
		$query = $bd->consultar($sql01);
		$i = 0;
		$valor = 0;
		while ($datos = $bd->obtener_fila($query, 0)) {
			$i++;
			if ($valor == 0) {
				$fondo = 'fondo01';
				$valor = 1;
			} else {
				$fondo = 'fonddo02';
				$valor = 0;
			}
			$modificar = 	 "'" . $i . "', 'modificar'";
			$borrar    = 	 "'" . $i . "', 'eliminar' ";
			echo '<tr class="' . $fondo . '">
				  <td>     
                  <input type="text" id="codigo_producto'.$i.'" value="'.$datos['producto'].'" disabled  style="width:450px"/>
                  <input type="hidden" name="producto" id="stdID'.$i.'" value="'.$datos['cod_producto'].'"/>
                </td>
                <td>
                 <input type="number" id="cantidad'.$i.'" style="width:100px"  value="'.$datos['cantidad'].'" min="1">
			   </td>
			   <td>
			   <input type="number" id="dias'.$i.'" style="width:100px"  value="'.$datos['dias'].'" min="0">
			 </td>
			 <td>
                 <input type="checkbox" id="vencimiento'.$i.'" style="width:50px" '.statusCheck($datos["vencimiento"]).'/>
			   </td>  
			   <td align="center"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" class="imgLink" onclick="ValidarAlcance('.$modificar.')" />&nbsp;
		  <img src="imagenes/borrar.bmp" alt="Detalle" title="Borrar Registro" width="25" height="25" border="null"
			   onclick="BorrarAlcance(' . $borrar . ')" class="imgLink" />
		  </td>
	</tr>';
        } mysql_free_result($query);?>