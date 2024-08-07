<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();
//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');
$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$archivo    = $_POST['archivo']."&Nmenu=$Nmenu&mod=$mod";
$vinculo    = "inicio.php?area=formularios/Add_$archivo";
$ficha 		= "";

if(isset($_POST['ficha'])){
	$ficha = $_POST['ficha'];
}

$novedad    = $_POST['novedad'];
$clasif     = $_POST['clasif'];
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$status     = $_POST['status'];
$perfil     = $_POST['perfil'];

	$where = " WHERE nov_procesos.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
	            AND nov_procesos.cod_novedad = novedades.codigo
				AND novedades.cod_nov_clasif = nov_clasif.codigo
				AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
                AND nov_perfiles.cod_perfil = '$perfil'
				AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
                AND clientes_ubicacion.cod_cliente = clientes.codigo
				AND nov_procesos.cod_nov_status = nov_status.codigo
				
				AND nov_procesos.cod_ficha = ficha.cod_ficha 
				AND novedades.cod_nov_tipo = nov_tipo.codigo
				AND nov_tipo.kanban = 'F'";

	if(!empty($_POST['tipo'])){
		$where .= " AND nov_perfiles.".$_POST['tipo']." = 'T' 
					AND nov_perfiles.status = 'T'";
	}

	if($novedad != "TODOS"){
		$where .= " AND novedades.codigo  = '$novedad' ";
	}

	if($clasif != "TODOS"){
		$where .= " AND nov_clasif.codigo = '$clasif' ";
	}

	if($cliente != "TODOS"){
		$where .= " AND clientes.codigo = '$cliente' ";
	}

	if($ubicacion != "TODOS"){
		$where .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}

	if($status != "TODOS"){
		$where .= " AND nov_status.codigo = '$status' ";
	}

	if($ficha!= ""){
		$where.= "AND nov_procesos.cod_ficha = '$ficha'";
	}

	$sql = " SELECT nov_procesos.codigo, nov_procesos.fec_us_ing,
                    novedades.descripcion AS novedades,  ficha.cod_ficha,
			 	    CONCAT(ficha.nombres, ' ',ficha.apellidos) AS trabajador, nov_clasif.descripcion AS clasif,
				    clientes.nombre AS cliente,  nov_status.descripcion  AS status, nov_procesos.cod_ficha as ficha
               FROM nov_procesos, novedades, clientes, nov_clasif,
                    nov_status, ficha,  nov_perfiles, clientes_ubicacion, nov_tipo
             $where
           ORDER BY 1 DESC ";
   $query = $bd->consultar($sql);

	echo '<table width="100%" border="0" class="fondo00">
			<tr>
            <th width="6%" class="etiqueta">Codigo</th>
            <th width="6%" class="etiqueta">Fecha</th>
			<th width="18%" class="etiqueta">Novedad</th>
			<th width="6%" class="etiqueta">Ficha</th>
            <th width="20%" class="etiqueta">'.$leng["trabajador"].'</th>
			<th width="18%" class="etiqueta">Clasificacion</th>
  			<th width="22%" class="etiqueta">'.$leng["cliente"].'</th>
            <th width="12%" class="etiqueta">Status</th>
			<td width="6%">';
			
			/*<a href="inicio.php?area=formularios/Add_'.$archivo.'&metodo=agregar"><img src="imagenes/nuevo.bmp"
		alt="Agregar Registro" width="25px" height="25px" title="Agregar Registro" border="null" /></a>*/
		echo '</td>
			</tr>';
				 $valor = 0;
	    while($row02=$bd->obtener_fila($query,0)){

		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo'<tr class="'.$fondo.'">
				<td class="texto">'.$row02["codigo"].'</td>
				<td class="texto">'.$row02["fec_us_ing"].'</td>
				<td class="texto">'.longitudMin($row02["novedades"]).'</td>
				<td class="texto">'.longitud($row02["ficha"]).'</td>
				<td class="texto">'.longitud($row02["trabajador"]).'</td>
				<td class="texto">'.longitudMin($row02["clasif"]).'</td>
				<td class="texto">'.longitud($row02["cliente"]).'</td>
				<td class="texto">'.longitud($row02["status"]).'</td>
				<td class="texto"><a href="inicio.php?area=formularios/Add_'.$archivo.'&codigo='.$row02['codigo'].'&metodo=modificar"><img src="imagenes/detalle.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null"/></a></td>
								</td>
							</tr>';
					}
echo '</table>';
mysql_free_result($query);?>
