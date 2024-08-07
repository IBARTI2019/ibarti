<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');

$archivo = "ficha3";
$tabla      = "";
$rol        = $_POST['rol'];
$status     = $_POST['status'];
$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];

	$vinculo = "inicio.php?area=pestanas/add_$archivo&Nmenu=$Nmenu&mod=$mod";

	$where =" WHERE ficha.cod_ficha_status = ficha_status.codigo
                AND ficha.cod_contracto = control.contracto_eventuales
                AND ficha.cod_ficha = trab_roles.cod_ficha
                AND trab_roles.cod_rol = roles.codigo ";

	if($rol   != "TODOS"){
	   $where .= " AND  trab_roles.cod_rol  = '$rol' ";
	}
	if($status != "TODOS"){
		$where .= "  AND ficha.cod_ficha_status = '$status' ";
	}

		$sql = "SELECT ficha.cod_ficha, ficha.cedula,
	                   CONCAT( ficha.apellidos, ' ', ficha.nombres ) AS nombres,
					roles.descripcion AS rol,
				       ficha.fec_ingreso,  ficha.fec_us_ing,
				       ficha.fec_us_mod, ficha.cod_ficha_status,
				       ficha_status.descripcion AS status
	              FROM ficha, ficha_status, control, trab_roles, roles
	            $where
		   ORDER BY  3, 2 DESC ";

?><table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="17%" class="etiqueta"><?php echo $leng["rol"];?></th>
    		<th width="10%" class="etiqueta"><?php echo $leng["ficha"];?></th>
			<th width="25%" class="etiqueta">Nombre</th>
  			<th width="10%" class="etiqueta">Fecha De Ingreso</th>
  			<th width="10%" class="etiqueta">Fecha Ult. <br />Actualizacion</th>
            <th width="10%" class="etiqueta">Status</th>
		    <th width="8%" align="center"><a href="inicio.php?area=formularios/Bus_ficha_add3&Nmenu=<?php echo $Nmenu.'&mod='.$mod;?>"><img src="imagenes/nuevo.bmp" alt="Agregar" title="Agregar Registro" width="22px" height="22px" border="null" class="imgLink"/></a></th>
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

	// $Modificar = "Add_Mod01('".$datos[0]."', 'modificar')";
	   $Borrar = "Borrar01('".$datos[0]."')";
        echo '<tr class="'.$fondo.'">
                   <td>'.$datos["rol"].'</td>
 			      <td>'.$datos["cod_ficha"].'</td>
                  <td>'.longitud($datos["nombres"]).'</td>
				  <td>'.$datos["fec_us_ing"].'</td>
				  <td>'.$datos["fec_us_mod"].'</td>
				  <td>'.$datos["status"].'</td>
				  <td align="center"><a href="'.$vinculo.'&codigo='.$datos[0].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/></td>
            </tr>';
        }
     echo '<input type="hidden" name="tabla" id="tabla" value="'.$tabla.'"/>';
	mysql_free_result($query);?>
    </table>
