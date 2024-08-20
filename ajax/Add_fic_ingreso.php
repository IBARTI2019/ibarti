<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');

$archivo    = $_POST['archivo'];
$status     = $_POST['status'];
$estado     = $_POST['estado'];
$psic       = $_POST['psic'];
$pol        = $_POST['pol'];

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];

$filtro     = $_POST['filtro'];
$cedula     = $_POST['cedula'];

$vinculo = "inicio.php?area=pestanas/Add_$archivo&Nmenu=$Nmenu&mod=$mod&archivo=$archivo";

	$where =" WHERE preingreso.cod_estado = estados.codigo
                AND preingreso.`status` = preing_status.codigo ";

	if($estado != "TODOS"){
		$where .= " AND  estados.codigo  = '$estado' ";
	}
	if($status != "TODOS"){
		$where .= "  AND preingreso.`status` = '$status' ";
	}
	if($psic != "TODOS"){
		$where .= "  AND preingreso.psic_apto = '$psic' ";
	}
	if($pol != "TODOS"){
		$where .= "  AND preingreso.pol_apto = '$pol' ";
	}

	if(($filtro != "TODOS") and ($cedula) != ""){
		$where .= "  AND preingreso.cedula = '$cedula' ";
	}

 $sql = " SELECT preingreso.cod_estado, estados.descripcion AS estados,
                 preingreso.cedula, CONCAT(preingreso.apellidos,' ', preingreso.nombres) AS nombres,
				 preingreso.fec_preingreso,
				 preingreso.fec_psic, preingreso.psic_apto,
				 preingreso.fec_pol, preingreso.pol_apto,
				 preingreso.fec_us_ing,
			 	 preingreso.`status` AS cod_status, preing_status.descripcion AS status
            FROM preingreso , estados, preing_status
		  $where
		   ORDER BY fec_us_ing DESC ";
?>
<table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="20%" class="etiqueta"><?php echo $leng["estado"];?></th>
			<th width="10%" class="etiqueta"><?php echo $leng["ci"];?></th>
			<th width="30%" class="etiqueta">Nombre</th>
  			<th width="11%" class="etiqueta">Fec. Sistema</th>
            <th width="11%" class="etiqueta">Fec. Ingreso</th>
            <th width="10%" class="etiqueta">Status</th>
		    <th width="8%" align="center"><a href="<?php echo $vinculo."&codigo=''&metodo=agregar";?>"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null"/></a><a href="inicio.php?area=formularios/Bus_ingreso&Nmenu=<?php echo $Nmenu.'&mod='.$mod;?>"><img src="imagenes/buscar.bmp" alt="Buscar" title="Buscar Registro" width="20px" height="20px" border="null" class="imgLink"/></a></th>
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
			     <td>'.$datos["estados"].'</td>
				  <td>'.$datos["cedula"].'</td>
                  <td>'.$datos["nombres"].'</td>
				  <td>'.$datos["fec_us_ing"].'</td>
				  <td>'.$datos["fec_preingreso"].'</td>
				  <td>'.$datos["status"].'</td>
				   <td align="center"><a href="'.$vinculo.'&codigo='.$datos["cedula"].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/></td>
            </tr>';
        }mysql_free_result($query);?>
    </table>
