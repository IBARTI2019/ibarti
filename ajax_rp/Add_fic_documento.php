<?php
define("SPECIALCONSTANT",true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();

$region        = $_POST['region'];
$cliente        = $_POST['cliente'];
$ubicacion        = $_POST['ubicacion'];
$contrato      = $_POST['contrato'];
$documento     = $_POST['documento'];
$doc_check     = $_POST['doc_check'];
$doc_vencimiento     = $_POST['doc_vencimiento'];

$status        = $_POST['status'];
$trabajador    = $_POST['trabajador'];

	$where = "    WHERE ficha.cod_ficha = ficha_documentos.cod_ficha
                    AND ficha_documentos.cod_documento = documentos.codigo
					AND documentos.`status` = 'T'
					AND ficha.cod_ficha = trab_roles.cod_ficha
					AND trab_roles.cod_rol = roles.codigo
					AND ficha.cod_region = regiones.codigo
					AND ficha.cod_estado = estados.codigo
					AND ficha.cod_ciudad = ciudades.codigo
					AND ficha.cod_contracto = contractos.codigo
					AND ficha.cod_ficha_status = ficha_status.codigo ";

	if($region != "TODOS"){
		$where .= " AND regiones.codigo = '$region' ";
	}

	if($cliente != "TODOS"){
		$where .= " AND ficha.cod_cliente = '$cliente' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($ubicacion != "TODOS"){
		$where  .= " AND ficha.cod_ubicacion = '$ubicacion' ";
	}

	if($contrato != "TODOS"){
		$where  .= " AND contractos.codigo = '$contrato' ";
	}

	if($documento != "TODOS"){
		$where  .= " AND ficha_documentos.cod_documento = '$documento' ";
	}

	if($doc_check != "TODOS"){
		$where  .= " AND ficha_documentos.checks = '$doc_check' ";
	}
	if($doc_vencimiento != "TODOS"){
		$where  .= " AND ficha_documentos.vencimiento = '$doc_vencimiento' ";
	}


	if($status != "TODOS"){
		$where .= " AND ficha.cod_ficha_status = '$status' ";
	}
	if($trabajador != NULL){
		$where  .= " AND ficha.cod_ficha = '$trabajador' ";
	}

	// QUERY A MOSTRAR //
        $sql = "SELECT roles.descripcion AS rol, regiones.descripcion AS region ,
                       ficha.cod_ficha, ficha.cedula,
					             CONCAT(ficha.apellidos,' ', ficha.nombres) AS ap_nombre, contractos.descripcion AS contrato,
						           ficha_status.descripcion AS `status`, ficha_documentos.cod_documento,
					             documentos.descripcion AS doc, StatusD(ficha_documentos.checks) checks,
						           StatusD(ficha_documentos.vencimiento) vencimiento, ficha.fec_ingreso
                  FROM ficha , trab_roles, ficha_documentos , documentos , roles, regiones, estados, ciudades,
			          		   contractos, ficha_status
                $where
			  ORDER BY 1, 5 ASC   ";

?><table width="100%" border="0" align="center">
		<tr class="fondo00">
            <th width="20%" class="etiqueta"><?php echo $leng['rol']?></th>
            <th width="22%" class="etiqueta"><?php echo $leng['ficha']?>  - <?php echo $leng['ci']?></th>
            <th width="20%" class="etiqueta"><?php echo $leng['trabajador']?></th>
            <th width="20%" class="etiqueta">Documento</th>
            <th width="9%" class="etiqueta">Check</th>
			<th width="9%" class="etiqueta">Vencimiento</th>
			<th width="9%" class="etiqueta">Fec. Ingreso</th>
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
			      <td class="texto">'.$datos["rol"].'</td>
				  <td class="texto">'.$datos["cod_ficha"].' - '.$datos["cedula"].' </td>
				  <td class="texto">'.longitud($datos["ap_nombre"]).'</td>
				  <td class="texto">'.longitud($datos["doc"]).'</td>
				  <td class="texto">'.$datos["checks"].'</td>
				  <td class="texto">'.$datos["vencimiento"].'</td>
				  <td class="texto">'.$datos["fec_ingreso"].'</td>
           </tr>';
        };?>
    </table>
