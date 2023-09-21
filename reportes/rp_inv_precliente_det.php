
<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 736;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

$cliente         = $_POST['cliente'];
$region          = $_POST['region'];
$estatu          = $_POST['estatu'];
$reporte         = $_POST['reporte'];

$archivo         = "rp_cs_precliente_" . $fecha . "";
$titulo          = "  REPORTE DE PRE-CLIENTES \n";

if (isset($reporte)) {
       

	$where = "WHERE preclientes.cod_cl_tipo = clientes_tipos.codigo ";


	if ($cliente != "TODOS") {
		$where .= " preclientes.codigo = '$cliente' ";
	}

	if ($region != "TODOS") {
		$where .= " AND regiones.codigo = '$region' ";
	}

	if ($estatu != "TODOS"){
		$where .= " AND preclientes.status = '$estatu'";
		
	}
	
	// QUERY A MOSTRAR //
	$sql = " SELECT
				regiones.codigo AS cod_region,
				regiones.descripcion AS region,
				clientes_tipos.codigo AS cod_tipo,
				clientes_tipos.descripcion AS cliente_tipo,
				preclientes.codigo,
				preclientes.cod_vendedor,
				vendedores.nombre AS vendedor,
				preclientes.abrev,
				preclientes.rif,
				preclientes.nit,
				preclientes.nombre,
				preclientes.telefono,
				preclientes.fax,
				preclientes.direccion,
				preclientes.dir_entrega,
				preclientes.email,
				preclientes.website,
				preclientes.contacto,
				preclientes.observacion,
				IF(preclientes.juridico = 'T', 'SI', 'NO') juridico,
				IF(preclientes.contribuyente = 'T', 'SI', 'NO') contribuyente,
				IF(preclientes.lunes = 'T', 'SI', 'NO') lunes,
				IF(preclientes.martes = 'T', 'SI', 'NO') martes,
				IF(preclientes.miercoles = 'T', 'SI', 'NO') miercoles,
				IF(preclientes.jueves = 'T', 'SI', 'NO') jueves,
				IF(preclientes.viernes = 'T', 'SI', 'NO') viernes,
				IF(preclientes.sabado = 'T', 'SI', 'NO') sabado,
				IF(preclientes.domingo = 'T', 'SI', 'NO') domingo,
				preclientes.limite_cred,
				preclientes.plazo_pago,
				preclientes.desc_global,
				preclientes.desc_p_pago,
				preclientes.campo01,
				preclientes.campo02,
				preclientes.campo03,
				preclientes.campo04,
				preclientes.cod_us_ing,
				CONCAT( us_registro.nombre, ' ', us_registro.apellido ) usuario_registro,
				preclientes.fec_us_ing,
				preclientes.cod_us_mod,
				CONCAT( us_modificacion.nombre, ' ', us_modificacion.apellido ) usuario_modificacion,
				preclientes.fec_us_mod,
				IF(preclientes.status = 'T', 'Activo', 'Inactivo') status,
				preclientes.latitud,
				preclientes.longitud,
				preclientes.direccion_google,
				preclientes.responsable,
				preclientes.empresa_actual,
				preclientes.cantidad_hombres,
				preclientes.problema_identificado,
				IF(preclientes.venta_cerrada = 'T', 'SI', 'NO') venta_cerrada,
				preclientes.cod_us_venta_cerrada,
				preclientes.fec_venta_cerrada,
				CONCAT( us_cierre.nombre, ' ', us_cierre.apellido ) usuario_venta_cerrada 
			FROM
				preclientes
				LEFT JOIN men_usuarios AS us_cierre ON preclientes.cod_us_venta_cerrada = us_cierre.codigo
				LEFT JOIN men_usuarios AS us_registro ON preclientes.cod_us_venta_cerrada = us_registro.codigo
				LEFT JOIN men_usuarios AS us_modificacion ON preclientes.cod_us_venta_cerrada = us_modificacion.codigo
				INNER JOIN regiones ON preclientes.cod_region = regiones.codigo
				LEFT JOIN vendedores ON preclientes.cod_vendedor = vendedores.codigo,
				clientes_tipos
			$where
			ORDER BY 1 ASC";

	if ($reporte == 'excel' ) {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";
		echo "<tr>
			<th> Código Región </th>
			<th> " . $leng['region'] . " </th>
			<th> Código Tipo </th>
			<th> Tipo </th>
			<th> Código </th>
			<th> Código Vendedor </th>
			<th> Vendedor </th>
			<th> Abreviatura </th>
			<th> Rif </th>
			<th> Nit </th>
			<th> Nombre </th>
			<th> Teléfono </th>
			<th> Fax </th>
			<th> Dirección </th>
			<th> Dirección Entrega </th>
			<th> Correo </th>
			<th> Sitio Web </th>
			<th> Contacto </th>
			<th> Observación </th>
			<th> Jurídico </th>
			<th> Contribuyente </th>
			<th> Lunes </th>
			<th> Martes </th>
			<th> Miércoles </th>
			<th> Jueves </th>
			<th> Viernes </th>
			<th> Sábado </th>
			<th> Domingo </th>
			<th> Límite de Crédito </th>
			<th> Plazo de Pago </th>
			<th> Descuento Global </th>
			<th> Desc. P. Pago </th>
			<th> Campo 01 </th>
			<th> Campo 02 </th>
			<th> Campo 03 </th>
			<th> Campo 04 </th>
			<th> Código Usuario de Registro </th>
			<th> Usuario de Registro </th>
			<th> Fecha de Registro </th>
			<th> Código Usuario Modificación </th>
			<th> Usuario Modificación </th>
			<th> Fecha  de Modificación </th>
			<th> Estatus </th>
			<th> Latitud </th>
			<th> Longitud </th>
			<th> Dirección Google </th>
			<th> Responsable </th>
			<th> Empresa Actual </th>
			<th> Cantidad de Hombres </th>
			<th> Problema Identificado </th>
			<th> Venta Cerrada </th>
			<th> Código Usuario de Cierre de Venta </th>
			<th> Fecha de Cierre de Venta  </th>
			<th> Usuario de Cierre de Venta </th>
		</tr>";


		while ($row01 = $bd->obtener_num($query01)) {
			echo "<tr>
			<td > " . $row01[0] . " </td>
			<td>" . $row01[1] . "</td>
			<td>" . $row01[2] . "</td>
			<td>" . $row01[3] . "</td>
			<td>" . $row01[4] . "</td>
			<td>" . $row01[5] . "</td>
			<td>" . $row01[6] . "</td>
			<td>" . $row01[7] . "</td>
			<td>" . $row01[8] . "</td>
			<td>" . $row01[9] . "</td>
			<td>" . $row01[10] . "</td>
			<td>" . $row01[11] . "</td>
			<td>" . $row01[12] . "</td>
			<td>" . $row01[13] . "</td>
			<td>" . $row01[14] . "</td>
			<td>" . $row01[15] . "</td>
			<td>" . $row01[16] . "</td>
			<td>" . $row01[17] . "</td>
			<td>" . $row01[18] . "</td>
			<td>" . $row01[19] . "</td>
			<td>" . $row01[20] . "</td>
			<td>" . $row01[21] . "</td>
			<td>" . $row01[22] . "</td>
			<td>" . $row01[23] . "</td>
			<td>" . $row01[24] . "</td>
			<td>" . $row01[25] . "</td>
			<td>" . $row01[26] . "</td>
			<td>" . $row01[27] . "</td>
			<td>" . $row01[28] . "</td>
			<td>" . $row01[29] . "</td>
			<td>" . $row01[30] . "</td>
			<td>" . $row01[31] . "</td>
			<td>" . $row01[32] . "</td>
			<td>" . $row01[33] . "</td>
			<td>" . $row01[34] . "</td>
			<td>" . $row01[35] . "</td>
			<td>" . $row01[36] . "</td>
			<td>" . $row01[37] . "</td>
			<td>" . $row01[38] . "</td>
			<td>" . $row01[39] . "</td>
			<td>" . $row01[40] . "</td>
			<td>" . $row01[41] . "</td>
			<td>" . $row01[42] . "</td>
			<td>" . $row01[43] . "</td>
			<td>" . $row01[44] . "</td>
			<td>" . $row01[45] . "</td>
			<td>" . $row01[46] . "</td>
			<td>" . $row01[47] . "</td>
			<td>" . $row01[48] . "</td>
			<td>" . $row01[49] . "</td>
			<td>" . $row01[50] . "</td>
			<td>" . $row01[51] . "</td>
			<td>" . $row01[52] . "</td>
			<td>" . $row01[53] . "</td>
		</tr>";
		}
		echo "</table>";
	}

	if ($reporte == 'pdf') {
     
        
		require_once('../' . ConfigDomPdf);
		$dompdf = new DOMPDF();
		$query  = $bd->consultar($sql);
        
		ob_start();

		require('../' . PlantillaDOM . '/header_ibarti_2.php');
		include('../' . pagDomPdf . '/paginacion_ibarti.php');

		echo "<br><div>
		<table>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='15%'>Region</th>
		<th width='15%'>Nombre</th>
		<th width='20%'>Telefono</th>
		<th width='20%'>Fax</th>
		<th width='10%'>Tipo</th>
		<th width='10%'>Rif</th>
		<th width='10%'>Nit</th>
		<th width='35%'>Latitud</th>
		<th width='20%'>Longitud</th>
		<th width='20%'>Direccion</th>
		<th width='20%'>Direccion de Entrega</th>
		<th width='20%'>Email</th>
		<th width='20%'>Estatu</th>
		</tr>";

		$f = 0;
		while ($row = $bd->obtener_num($query)) {
			if ($f % 2 == 0) {
				echo "<tr>";
			} else {
				echo "<tr class='class= odd_row'>";
			}
			if ($row[12] == 'T'){
				$activo ="Activo";
			} else {
                $activo="Inactivo";
			}
			echo   "<td width='15%'>" . $row[0] . "</td>
			<td width='20%'>" . $row[1] . "</td>
			<td width='20%'>" . $row[2] . "</td>
			<td width='10%'>" . $row[3] . "</td>
			<td width='10%'>" . $row[4] . "</td>
			<td width='35%'>" . $row[5] . "</td>
			<td width='35%'>" . $row[6] . "</td>
			<td width='35%'>" . $row[7] . "</td>
			<td width='35%'>" . $row[8] . "</td>
			<td width='35%'>" . $row[9] . "</td>
			<td width='35%'>" . $row[10] . "</td>	
			<td width='35%'>" . $row[11] . "</td>
			<td width='35%'>$activo</td>
			</tr>";

			$f++;
		}

		echo "</tbody>
		</table>
		</div>
		</body>
		</html>";

		$dompdf->load_html(ob_get_clean(), 'UTF-8');
		$dompdf->set_paper('letter', 'landscape');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
	}
}
