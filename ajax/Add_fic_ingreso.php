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

 $sql = " SELECT v_preingreso.fec_us_ing AS fec_sistema, v_preingreso.fec_preingreso,
				v_preingreso.cod_estado, v_preingreso.estado, 
				v_preingreso.cod_ciudad, v_preingreso.ciudad,
				v_preingreso.cedula, v_preingreso.apellidos, v_preingreso.nombres, 
				v_preingreso.cod_nacionalidad, v_preingreso.nacionalidad,
				v_preingreso.cod_estado_civil, v_preingreso.estado_civil, 
				v_preingreso.fec_nacimiento, v_preingreso.lugar_nac, Sexo(v_preingreso.sexo) sexo,
				v_preingreso.telefono, v_preingreso.celular,
				v_preingreso.correo, v_preingreso.direccion,
				v_preingreso.ocupacion, v_preingreso.cargo,
				v_preingreso.nivel_academico, v_preingreso.pantalon,
				v_preingreso.camisa, v_preingreso.zapato,
				v_preingreso.fec_psic, v_preingreso.psic_observacion,
				Valores(v_preingreso.psic_apto) AS psic_apto, v_preingreso.fec_pol,
				v_preingreso.pol_observacion, Valores(v_preingreso.pol_apto) AS pol_apto,
				v_preingreso.observacion, v_preingreso.cod_status, v_preingreso.`status`
			FROM v_preingreso
				ORDER BY 2 ASC;";
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
