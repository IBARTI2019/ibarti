<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$cliente       =  $_POST['cliente'];	
$ubicacion     =  $_POST['ubicacion'];	
$nov_clasif    =  $_POST['nov_clasif'];	
$nov_tipo      =  $_POST['nov_tipo'];	

	$where = " WHERE novedades.`status` = 'T' ";

	if($nov_clasif != "TODOS"){		
		$where .= " AND novedades.cod_nov_clasif = '$nov_clasif' ";
	}		

	if($nov_tipo != "TODOS"){		
		$where .= " AND novedades.cod_nov_tipo = '$nov_tipo' ";  // cambie AND asistencia.co_cont = '$contracto'
	}	

    $sql = "SELECT novedades.codigo, novedades.descripcion,
	               IFNULL(nov_cl_ubicacion.cod_cl_ubicacion, 'NO') AS existe 
              FROM novedades LEFT JOIN nov_cl_ubicacion ON novedades.codigo = nov_cl_ubicacion.cod_novedad 
			                       AND nov_cl_ubicacion.cod_cl_ubicacion = '$ubicacion'
            $where
             ORDER BY 2 ASC ";

   $query = $bd->consultar($sql);
    
	echo'<table width="100%" align="center">
			<tr>
				<td width="6%" class="etiqueta"> CODIGO </td>
				<td width="80%" class="etiqueta"> NOVEDADES CHECK LIST </td>
			    <td><input type="checkbox" name="todos" id="todos" value="0" onclick="marcar(this)";/>
		   </tr>
			<tr> 
         	   <td height="8" colspan="2" align="center"><hr></td>
    		</tr>';
	 $valor = 0;		
	 	 $i = 0;
    while($row03=$bd->obtener_fila($query,0)){

	 $campo_id = $row03[0];										  
	 $i++;

		if($row03['existe'] != 'NO'){ 
		$check = 'checked="checked"';
		}else{
		$check = '';	
		}
		
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}		

		echo'<tr class="'.$fondo.'">
				<td class="etiqueta">'.$row03[0].'</td>
				<td class="etiqueta">'.$row03[1].'</td>
				<td><input type="checkbox" name="novedad[]" id="check'.$i.'" value="'.$row03[0].'" style="width:auto" '.$check.' />
				</td>
		   </tr>';		  		
 	}	
	echo'</table>';	 
	mysql_free_result($query);?>