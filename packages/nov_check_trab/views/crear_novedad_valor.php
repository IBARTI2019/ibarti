<?php

require "../modelo/nov_check_trab_modelo.php";
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$clasif = $_POST['clasif'];
$tipo = $_POST['tipo'];
$html = "";

$c_l      = new check_list;
$novedad = $c_l->obtener_novedad($clasif, $tipo);
if ($codigo != "") {
    $existentes = $c_l->obtener_existentes($codigo);
} else {
    $existentes = array();
}

$html .= '<table width="100%" border="1">
<tr style="border:1px solid; text-align:center;" >
    <td width="40%" style="border:1px solid; text-align:center;"><b>Novedad:</b></td>
    <td width="30%" style="border:1px solid; text-align:center;"><b>Valor</b></td>
    <td width="30%" style="border:1px solid; text-align:center;"><b>Observacion</b></td>
</tr>
<tr>';
$nueva  = "{";
$i = 0;
foreach ($novedad as $index => $valor) {

    if (empty($existentes)) {
        $novedad_actual = '';
        $valor_actual   = '';
        $cod_agrupacion = '';
    } else {
        $novedad_actual = $existentes[$index]['cod_novedades'];
        $valor_actual   = $existentes[$index]['cod_valor'];
    }
    $html .= "<tr style='border:1px solid; text-align:center;'>
            <td style='border:1px solid; text-align: justify;'>" . $valor[1] . "</td>
            <td style='border:1px solid; text-align:center;' id='contenedor_" . $valor[0] . "' value='' onmouseenter='func(this)'>";
    $valores = $c_l->obtener_valor($valor[0]);
    $nueva .= (($i > 0) ? "," : "") . '"' . $valor[0] . '":' . json_encode($valores);
    $i++;
    $html .= '<input type="hidden" id="cod_agrupacion_' . $valor[0] . '" name="cod_agrupacion_' . $valor[0] . '" value="'.$valor[2].'"><input type="hidden" id="cod_' . $valor[0] . '" name="valores[' . $valor[0] . ']" value=""><p>SIN SELECCION</p>';
    /*foreach ($valores as $index2 => $valor2) {
                $check = (($valor[0] == $novedad_actual) && ($valor2[0] == $valor_actual)) ? 'checked' : '';
                //$html.= '<div style="float:left;" title="' . $valor2[2] . '">' . $valor2[1] . '<input type="radio" required="required" name="valores[' . $valor[0] . ']" value="' . $valor2[0] . '" ' . $check . ' ></div> ';
                
            }*/
    $html .= "</td><td style='border:1px solid; text-align:center;'><textarea name='obs[" . $valor[0] . "]' style='width:95%'></textarea></td></tr>";
}
$nueva .= "}";
$html .= "</tr></table>";
$retur = array(
    "datos" => $nueva,
    "htmla" => $html
);

echo json_encode($retur);
