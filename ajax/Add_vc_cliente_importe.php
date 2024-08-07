<?php
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;


$bd = new DataBase();
$cliente   = $_POST['cliente'];
$turno     = $_POST['turno'];
$Nmenu     = $_POST['Nmenu'];
$mod       = $_POST['mod'];
$archivo   = "vc_cliente_importe";
$titulo    = " CLIENTE IMPORTE ";
$vinculo   = "inicio.php?area=maestros/add_$archivo&Nmenu=$Nmenu&mod=$mod&archivo=$archivo";

	$where = " WHERE clientes_importe.cod_cliente = clientes.codigo
			     AND clientes_importe.cod_ubicacion = clientes_ubicacion.codigo
                 AND clientes_importe.cod_cargo = cargos.codigo
                 AND clientes_importe.cod_turno = turno.codigo ";

	if($cliente != "TODOS"){
		$where .= " AND clientes.codigo = '$cliente' ";
	}

	if($turno != "TODOS"){
		$where .= " AND turno.codigo = '$turno' ";
	}

   	 $sql = " SELECT clientes_importe.codigo, clientes_importe.fecha,
	                 clientes_importe.cod_cliente,  clientes.abrev AS cl_abrev,
					 clientes.nombre AS cliente, clientes_ubicacion.descripcion AS ubicacion,
                 	 clientes_importe.cod_cargo, cargos.descripcion AS cargo,
                     clientes_importe.cod_turno, turno.descripcion AS turno,
					 clientes_importe.importe, clientes_importe.observacion,
					 clientes_importe.fec_us_mod, clientes_importe.fec_us_ing
                FROM clientes_importe , clientes ,  clientes_ubicacion, cargos , turno
                $where
		        ORDER BY 3 DESC";
?>

<table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="8%" class="etiqueta">Fecha</th>
            <th width="15%" class="etiqueta"><?php echo $leng['cliente'];?></th>
            <th width="22%" class="etiqueta"><?php echo $leng['ubicacion'];?></th>
			<th width="20%" class="etiqueta">Cargo</th>
  			<th width="20%" class="etiqueta">Turno</th>
            <th width="10%" class="etiqueta">Importe</th>
		    <th width="5%" align="center"><a href="<?php echo $vinculo."&codigo=''&metodo=agregar&codigo=";?>"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null"/></a></th>
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
	//</a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/>
	   $Borrar = "Borrar01('".$datos[0]."')";
        echo '<tr class="'.$fondo.'">
		          <td class="texo">'.$datos["fecha"].'</td>
                  <td class="texo">'.$datos["cl_abrev"].'</td>
				  <td class="texo">'.longitud($datos["ubicacion"]).'</td>
                  <td class="texo">'.longitud($datos["cargo"]).'</td>
				  <td class="texo">'.longitud($datos["turno"]).'</td>
				  <td class="texo">'.$datos["importe"].'</td>
				  <td align="center"><a href="'.$vinculo.'&codigo='.$datos["codigo"].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></td>
            </tr>';
        };
		mysql_free_result($query);?>
    </table>
