<?php
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;

$bd = new DataBase();
//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$archivo    = $_POST['archivo']."&Nmenu=$Nmenu&mod=$mod";
$vinculo    = "inicio.php?area=pestanas/add_$archivo&archivo=$archivo";

$periodo    = $_POST['periodo'];
$rol        = $_POST['rol'];
$filtro     = $_POST['filtro'];
$ficha      = $_POST['ficha'];

	$where = " WHERE  v_ficha.cod_ficha = prod_dotacion.cod_ficha ";

	if($periodo != "TODOS"){
		$where .= " AND prod_dotacion.periodo  = '$periodo' ";
	}

	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if(($filtro != "TODOS") and ($ficha) != ""){
		$where .= "  AND prod_dotacion.cod_ficha = '$ficha' ";
	}

 $sql = " SELECT prod_dotacion.codigo, prod_dotacion.fec_dotacion,
                 v_ficha.rol, v_ficha.cod_ficha,
				 v_ficha.cedula, v_ficha.ap_nombre AS trabajador,
				 prod_dotacion.descripcion
            FROM v_ficha , prod_dotacion
          $where
		   ORDER BY 1 ASC";
   $query = $bd->consultar($sql);

		echo '<table width="100%" border="0" class="fondo00">
			<tr>
				<th width="8%" class="etiqueta">Codigo</th>
				<th width="8%" class="etiqueta">Fecha</th>
            	<th width="20%" class="etiqueta">'.$leng["rol"].'</th>
				<th width="8%" class="etiqueta">'.$leng["ficha"].'</th>
            	<th width="26%" class="etiqueta">'.$leng["trabajador"].'</th>
            	<th width="26%" class="etiqueta">Descripcion</th>
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
			  <td class="texto">'.$row02["fec_dotacion"].'</td>
			  <td class="texto">'.longitud($row02["rol"]).'</td>
			  <td class="texto">'.$row02["cod_ficha"].'</td>
			  <td class="texto">'.longitud($row02["trabajador"]).'</td>
			  <td class="texto">'.longitud($row02["descripcion"]).'</td>
			  <td class="texto"><a href="'.$vinculo.'&codigo='.$row02['codigo'].'&metodo=modificar"><img src="imagenes/detalle.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/></td>
		</tr>';
		}
echo '</table>'; mysql_free_result($query);?>
