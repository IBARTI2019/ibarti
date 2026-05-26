<?php
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;

$bd = new DataBase();

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$archivo    = $_POST['archivo']."&Nmenu=$Nmenu&mod=$mod";
$vinculo    = "inicio.php?area=formularios/add_$archivo&archivo=$archivo";

$filtro     = $_POST['filtro'];
$ficha      = $_POST['ficha'];

	$where = " WHERE 1=1 ";

	if(($filtro != "TODOS") and ($ficha != "")){
		$where .= "  AND prod_asignacion.cod_ficha = '$ficha' ";
	}

    if(isset($_POST['fec_desde']) && isset($_POST['fec_hasta']) && $_POST['fec_desde'] != "" && $_POST['fec_hasta'] != "" && $_POST['fec_desde'] != "undefined" && $_POST['fec_hasta'] != "undefined"){
        $fec_desde = conversion($_POST['fec_desde']);
        $fec_hasta = conversion($_POST['fec_hasta']);
        $where .= " AND prod_asignacion.fecha BETWEEN '$fec_desde' AND '$fec_hasta' ";
    }

    if(isset($_POST['tipo']) && $_POST['tipo'] != "TODOS" && $_POST['tipo'] != "undefined"){
        $tipo_filter = $_POST['tipo'];
        $where .= " AND prod_asignacion.tipo = '$tipo_filter' ";
    }

    if(isset($_POST['cliente']) && $_POST['cliente'] != "TODOS" && $_POST['cliente'] != "undefined"){
        $cliente_filter = $_POST['cliente'];
        $where .= " AND clientes_ubicacion.cod_cliente = '$cliente_filter' ";
    }

    if(isset($_POST['ubicacion']) && $_POST['ubicacion'] != "TODOS" && $_POST['ubicacion'] != "undefined" && $_POST['ubicacion'] != ""){
        $ubicacion_filter = $_POST['ubicacion'];
        $where .= " AND prod_asignacion.cod_ubicacion = '$ubicacion_filter' ";
    }

 $sql = " SELECT prod_asignacion.codigo, prod_asignacion.fecha, prod_asignacion.tipo,
                 clientes_ubicacion.descripcion AS ubicacion,
                 v_ficha.cod_ficha,
				 v_ficha.ap_nombre AS trabajador,
				 prod_asignacion.descripcion
            FROM prod_asignacion 
            JOIN clientes_ubicacion ON clientes_ubicacion.codigo = prod_asignacion.cod_ubicacion
            LEFT JOIN v_ficha ON v_ficha.cod_ficha = prod_asignacion.cod_ficha
          $where
		   ORDER BY 1 DESC";
		   
   $query = $bd->consultar($sql);
   if(!$query){
       echo "SQL Error: ".mysql_error()."<br>Query: ".$sql;
       exit;
   }

		echo '<table width="100%" border="0" class="fondo00">
			<tr>
				<th width="8%" class="etiqueta">Codigo</th>
				<th width="8%" class="etiqueta">Fecha</th>
            	<th width="12%" class="etiqueta">Tipo</th>
            	<th width="15%" class="etiqueta">Ubicacion</th>
				<th width="8%" class="etiqueta">'.$leng["ficha"].'</th>
            	<th width="20%" class="etiqueta">'.$leng["trabajador"].'</th>
            	<th width="25%" class="etiqueta">Descripcion</th>
				<th width="4%"><a href="'.$vinculo.'&metodo=agregar"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" width="20px" height="20px" title="Agregar Registro" border="null" /></a></th></tr>';
		 $valor = 0;
	    while($row02=$bd->obtener_fila($query,0)){

		   $Borrar = "Borrar01('".$row02[0]."')";
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo'<tr class="'.$fondo.'">
			  <td class="texto">'.$row02["codigo"].'</td>
			  <td class="texto">'.$row02["fecha"].'</td>
			  <td class="texto">'.longitud($row02["tipo"]).'</td>
			  <td class="texto">'.longitud($row02["ubicacion"]).'</td>
			  <td class="texto">'.$row02["cod_ficha"].'</td>
			  <td class="texto">'.longitud($row02["trabajador"]).'</td>
			  <td class="texto">'.longitud($row02["descripcion"]).'</td>
			  <td class="texto"><a href="'.$vinculo.'&codigo='.$row02['codigo'].'&metodo=modificar"><img src="imagenes/detalle.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null"/></a>&nbsp;</td>
		</tr>';
		}
echo '</table>'; ?>
