<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$archivo    = "reportes/rp_nov_novedad_printer.php?Nmenu=$Nmenu&mod=$mod";

//$vinculo    = "inicio.php?area=$archivo";
$vinculo    = "$archivo";


$novedad         = $_POST['novedad'];
$clasif          = $_POST['clasif'];
$status          = $_POST['status'];
$cliente         = $_POST['cliente'];
$ubicacion       = $_POST['ubicacion'];
$trabajador     = $_POST['trabajador'];
$cod_nov 		= $_POST['cod_nov'];
$detalle        = $_POST['detalle'];

if($cod_nov!=''){
	$where  = "WHERE nov_procesos.cod_novedad = novedades.codigo
	AND nov_procesos.codigo = '".$cod_nov."'
	AND novedades.cod_nov_tipo = nov_tipo.codigo
	AND nov_tipo.kanban != 'T'
	AND novedades.cod_nov_clasif = nov_clasif.codigo
	AND nov_clasif.campo04 = 'F'
	AND nov_procesos.cod_cliente = clientes.codigo
	AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
	AND nov_procesos.cod_ficha = ficha.cod_ficha
	AND nov_procesos.cod_nov_status = nov_status.codigo ";
}else{
	$fecha_D   = conversion($_POST['fecha_desde']);
	$fecha_H   = conversion($_POST['fecha_hasta']);
	
	$where = " WHERE nov_procesos.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
                     AND nov_procesos.cod_novedad = novedades.codigo
					 AND novedades.cod_nov_tipo = nov_tipo.codigo
					 AND nov_tipo.kanban != 'T'
					 AND novedades.cod_nov_clasif = nov_clasif.codigo
					 AND nov_clasif.campo04 = 'F'
					 AND nov_procesos.cod_cliente = clientes.codigo
					 AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
					 AND nov_procesos.cod_ficha = ficha.cod_ficha
					 AND nov_procesos.cod_nov_status = nov_status.codigo ";

	if($novedad != "TODOS"){
		$where .= " AND novedades.codigo   = '$novedad' ";
	}

	if($clasif != "TODOS"){
		$where .= " AND novedades.cod_nov_clasif   = '$clasif' ";
	}

	if($status != "TODOS"){
		$where  .= " AND nov_status.codigo = '$status' ";
	}

	if($cliente != "TODOS"){
		$where  .= " AND clientes.codigo = '$cliente' ";
	}

	if($ubicacion != "TODOS"){
		$where  .= " AND clientes_ubicacion.codigo  = '$ubicacion' ";
	}

	if($trabajador != NULL){
		$where  .= " AND ficha.cod_ficha = '$trabajador' ";
	}

}

			// QUERY A MOSTRAR //
    	$sql = "SELECT nov_procesos.codigo, nov_procesos.fec_us_ing, novedades.descripcion AS novedad,
					   clientes.abrev AS cliente, clientes_ubicacion.descripcion AS ubicacion,
                       nov_procesos.cod_ficha, ficha.cedula,
					   CONCAT(ficha.apellidos ,' ',ficha.nombres) AS trabajador ,
					   nov_procesos.observacion,
                       nov_procesos.repuesta, nov_status.descripcion AS status
                  FROM nov_procesos , novedades , nov_tipo, nov_clasif,  clientes ,
				       clientes_ubicacion , ficha , nov_status
                $where
              ORDER BY 1 ASC";

?><table width="100%" border="0" align="center">
		<tr class="fondo00">
            <th width="8%" class="etiqueta">Codigo</th>
  			<th width="8%" class="etiqueta">Fecha</th>
            <th width="16%" class="etiqueta">Novedad</th>
		    <th width="15%" class="etiqueta"><?php echo $leng['cliente']?></th>
            <th width="15%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
            <th width="22%" class="etiqueta"><?php echo $leng['trabajador']?></th>
            <th width="8%" class="etiqueta">Status </th>
            <th width="4%" class="etiqueta">&nbsp; </th>
    	</tr>
    <?php
	$valor = 0;
   $query = $bd->consultar($sql);

		while ($datos=$bd->obtener_fila($query,0)){
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
        echo '<tr class="'.$fondo.'">
			      <td class="texto">'.$datos["codigo"].'</td>
				  <td class="texto">'.$datos["fec_us_ing"].'</td>
				  <td class="texto">'.longitudMin($datos["novedad"]).'</td>
				  <td class="texto">'.longitudMin($datos["cliente"]).'</td>
				  <td class="texto">'.longitudMin($datos["ubicacion"]).'</td>
				  <td class="texto">'.longitud($datos["trabajador"]).'</td>
				  <td class="texto">'.$datos["status"].'</td>
				  <td align="center"><a target="_blank" href="'.$vinculo.'&codigo='.$datos[0].'&reporte=printer"><img src="imagenes/printer.png" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a></td>
           </tr>';
        };?>
    </table>
