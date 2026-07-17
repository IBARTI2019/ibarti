<?php
define("SPECIALCONSTANT", true);
session_start();
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

$archivo = "rp_cs_cliente_activos_tasas_" . $fecha;

$sql = " SELECT clientes.codigo AS cod_cliente, clientes.nombre AS cliente
            FROM clientes
            WHERE clientes.status = 'T'
            ORDER BY cod_cliente ASC";

if (ob_get_length()) ob_end_clean();

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"rp_$archivo.xls\"");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ' . "\n";
echo ' xmlns:o="urn:schemas-microsoft-com:office:office" ' . "\n";
echo ' xmlns:x="urn:schemas-microsoft-com:office:excel" ' . "\n";
echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" ' . "\n";
echo ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

echo ' <Styles>' . "\n";
echo '  <Style ss:ID="HeaderStyle">' . "\n";
echo '   <Font ss:Bold="1" ss:Color="#FFFFFF"/>' . "\n";
echo '   <Interior ss:Color="#4CAF50" ss:Pattern="Solid"/>' . "\n";
echo '  </Style>' . "\n";
echo '  <Style ss:ID="TextStyle">' . "\n";
echo '   <NumberFormat ss:Format="@"/>' . "\n"; 
echo '  </Style>' . "\n";
echo ' </Styles>' . "\n";

echo ' <Worksheet ss:Name="Tasas">' . "\n";
echo '  <Table>' . "\n";

echo '   <Column ss:Width="80"/>' . "\n";
echo '   <Column ss:Width="300"/>' . "\n";
echo '   <Column ss:Width="80"/>' . "\n";

// Fila 1: Cabeceras
echo '   <Row>' . "\n";
echo '    <Cell ss:StyleID="HeaderStyle"><Data ss:Type="String">Codigo</Data></Cell>' . "\n";
echo '    <Cell ss:StyleID="HeaderStyle"><Data ss:Type="String">' . htmlspecialchars($leng['cliente'], ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
echo '    <Cell ss:StyleID="HeaderStyle"><Data ss:Type="String">Tasa</Data></Cell>' . "\n";
echo '   </Row>' . "\n";

$query01 = $bd->consultar($sql);

while ($row01 = $bd->obtener_fila($query01)) {
    $codigoClean = htmlspecialchars($row01["cod_cliente"], ENT_QUOTES, 'UTF-8');
    $clienteClean = htmlspecialchars($row01["cliente"], ENT_QUOTES, 'UTF-8');
    
    echo '   <Row>' . "\n";
    echo '    <Cell ss:StyleID="TextStyle"><Data ss:Type="String">' . $codigoClean . '</Data></Cell>' . "\n";
    echo '    <Cell><Data ss:Type="String">' . $clienteClean . '</Data></Cell>' . "\n";
    
    // --- AQUÍ ESTÁ EL CAMBIO ---
    // Al dejar el <Cell></Cell> vacío sin etiqueta <Data>, Excel reserva el espacio en blanco y limpio
    echo '    <Cell></Cell>' . "\n"; 
    
    echo '   </Row>' . "\n";
}

echo '  </Table>' . "\n";
echo ' </Worksheet>' . "\n";
echo '</Workbook>' . "\n";
exit();
?>