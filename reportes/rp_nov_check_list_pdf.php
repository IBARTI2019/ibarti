<?php
define("SPECIALCONSTANT", true);
require "../autentificacion/aut_config.inc.php";
require "../" . class_bdI;
require "../" . Leng;
include "../" . Funcion;
$bd = new DataBase();
require_once('../' . ConfigDomPdf);
$dompdf = new DOMPDF();
$codigo = $_POST['codigo'];
$logo = "../imagenes/logo.jpg";

$sql_datos = "SELECT
nov_check_list.codigo,
nov_check_list.fec_us_ing,
CONCAT(
    ficha.apellidos,
    ' ',
    ficha.nombres
) AS trabajador,
CONCAT(
    fe.apellidos,
    ' ',
    fe.nombres
) AS evaluado,
nov_clasif.descripcion AS clasif,
nov_tipo.descripcion AS tipo,
clientes.nombre AS cliente,
clientes_ubicacion.descripcion AS ubicacion,
nov_check_list.nombre_adiestramiento,
nov_status.descripcion AS `status`,
nov_tipo.campo01 basc,
nov_tipo.campo02 rev ,
nov_clasif.campo04,
CONCAT(men_usuarios.apellido,' ',men_usuarios.nombre) AS us_mod
FROM
nov_check_list 
LEFT JOIN men_usuarios ON nov_check_list.cod_us_ing = men_usuarios.codigo
LEFT JOIN ficha fe ON nov_check_list.cod_ficha_trab = fe.cod_ficha,
nov_clasif,
clientes,
clientes_ubicacion,
ficha,
nov_status,
nov_tipo
WHERE
nov_check_list.cod_nov_clasif = nov_clasif.codigo
AND nov_check_list.cod_nov_tipo = nov_tipo.codigo
AND nov_check_list.cod_cliente = clientes.codigo
AND nov_check_list.cod_ubicacion = clientes_ubicacion.codigo
AND nov_check_list.cod_ficha = ficha.cod_ficha
AND nov_check_list.cod_nov_status = nov_status.codigo
AND nov_check_list.codigo = '$codigo'";

$query_datos = $bd->consultar($sql_datos);
$datos = $bd->obtener_name($query_datos);
$tomo = $datos['basc'] . " <br> " . $datos['rev'];
$campo04 = $datos['campo04'];
$usuario = $datos['us_mod'];
$supervisor =  $datos['trabajador'];

ob_start();

if (file_exists($logo)) {
    $logo_img =  '<img src="' . $logo . '1" />';
} else {
    $logo_img  =  '<img src="../imagenes/img_no_disp.jpg"/>';
}
require('../' . PlantillaDOM . '/header_oesvica_check.php');
include('../' . pagDomPdf . '/paginacion_ibarti.php');

echo '
	
	<div style="border:1px solid black;margin:0;padding:0;text-align:center;background:#0f7517;color:white;font-size:16px;">' . $datos['tipo'] . '</div>
	<div style="border:1px solid black;margin:0;padding:0;text-align:center;">
	
	<table width="100%" style="padding-top: 10px;">

	<tr>
	<td width="40%" style="border:1px solid"><b>Empresa: </b> ' . $datos['cliente'] . '</td>
	<td width="40%" style="border:1px solid"><b>Ubicacion: </b>' . $datos['ubicacion'] . '</td>
	<td width="20%" style="border:1px solid"><b>Fecha: </b>' . $datos['fec_us_ing'] . '</td>
	</tr>';
    if($campo04 == 'E'){     echo '<tr>
        <td style="border:1px solid"><b>Jefe Inmediato: </b> ' . $supervisor . '</td>
        <td style="border:1px solid" colspan="2"><b>Trabajador: </b> ' . $datos['evaluado'] . '</td>
        </tr>
        <tr>
        <td style="border:1px solid" colspan="3"><b>Nombre del adiestramiento: </b>' . $datos['nombre_adiestramiento'] . '</td>
        </tr>';
    }else{
        echo '<tr>
        <td style="border:1px solid"><b>Supervisor: </b> ' . $datos['trabajador'] . '</td>
        <td style="border:1px solid" colspan="2"><b>Observacion: </b>' . $datos['observacion'] . '</td>
        </tr>';
    }
    

echo '</table>
    <table width="100%" style="padding-top: 10px;" border="1px">
	<tr>
		<td class="etiqueta" width="45%" align="center">Check List</td>
		<td class="etiqueta" width="10%" align="center">Valor</td>
        <td class="etiqueta" width="5%" align="center">%</td>
		<td class="etiqueta" width="40%" align="center">Observación</td>
	</tr>';
$sql = "SELECT
        novedades.codigo,
        novedades.descripcion,
        nov_check_list_det.observacion,
        nov_check_list_det.cod_valor,
        nov_valores.descripcion,
        nov_check_list_det.valor,
        nov_check_list_det.valor_max,
        CONVERT (
            (
                (
                    nov_check_list_det.valor * 100
                ) / nov_check_list_det.valor_max
            ),
            SIGNED
        ) porcentaje,
        nov_valores.factor
        FROM
        novedades,
        nov_check_list_det,
        nov_valores
        WHERE
        novedades.`status` = 'T'
        AND nov_check_list_det.cod_novedades = novedades.codigo
        AND nov_check_list_det.cod_valor = nov_valores.codigo
        AND nov_check_list_det.cod_check_list = '$codigo'";

$query = $bd->consultar($sql);
$total = 0;
$total_max = 0;
while ($datos = $bd->obtener_fila($query, 0)) {
    echo '<tr>
      <td>' . $datos[1] . '</td>
	  <td width="15%" align="center">' . $datos[4] . '  <br> (' . $datos[5] . ' de ' . $datos[6] . ')</td>
      <td align="center" width="5%">' . $datos[7] . ' %</td>
      <td>' . $datos[2] . '</td>
    </tr>';
    $total += $datos[5];
    $total_max += $datos[6];
}

echo '
<tr>
<td colspan="4" style="text-align: right;">
<b>Valor total: </b>' . $total . ' de ' . $total_max . '<br>
<b>Porcentaje promedio: </b>' . round(($total * 100) / $total_max) . ' %
</td>
</tr>
<tr>
<td colspan="4" style="padding: 0px !important;">
<table width="100%" style="margin: 0px !important;padding: 0px !important;">
<tr>';

if($campo04 == 'E'){
    echo '
    <td width="50%" style="text-align:center;">Supervisor:<br><br>
        <b>'. $supervisor .'</b>
        <br>
    </td> 
    <td width="50%" style="border-left:1px solid; text-align:center;">Evaluador:<br><br>
            <b>'. $usuario .'</b>
            <br>
    </td>
    </tr>';
}else{
   echo '
   <td width="50%" style="text-align:center;">Firma de Supervisor:<br><br><br></td> 
   <td width="50%" style="border-left:1px solid; text-align:center;">Firma de representante de la empresa:<br><br><br></td>
    </tr>';
}

echo '</table>
</td>
</table>
</div>

';

echo '
</body>
</html>';

$dompdf->load_html(ob_get_clean(), 'UTF-8');
//$dompdf->set_paper('letter','landscape');
$dompdf->render();
$dompdf->stream($archivo, array('Attachment' => 0));
