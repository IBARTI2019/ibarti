<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../" . Leng;

$confirmaciones   = new Confirmaciones;
$result = array();
$ficha     = $_POST['ficha'];
$cliente     = $_POST['cliente'];
$ubicacion     = $_POST['ubicacion'];
$horario     = $_POST['horario'];
$result  =  $confirmaciones->get_planif($ficha, $cliente, $ubicacion, $horario);
$disabled = "";
$respuesta = [
    "html" => "",
    "confirmado" => false
];

foreach ($result as  $datos) {
    if($respuesta["confirmado"] == false){
        $respuesta["confirmado"] = $datos["cierre_confirmado"] == 'T';
    }

    // Para obtener los codigos en la confimacion de cierre de asistencia
    $respuesta["html"] .= '<input type="hidden" name="codigos[]" value="'.$datos["codigo"].'">';
    $respuesta["html"] .=  '<tr>
        <td>' . $datos["cliente"]. ' - '. $datos["cierre_confirmado"] . '</td>
        <td>' . $datos["ubicacion"] . '</td>
        <td>' . $datos["ficha"] . '</td>
        <td>' .  $datos["telefono"] . '</td>
        <td>' . $datos["ap_nombre"] . '</td>
        <td>' . $datos["cargo"] . '</td>
        <td>' . $datos["concepto"] . '</td>
        <td>' . $datos["hora_entrada"] . '</td>';
        if($datos["confirm"] == 'T'){
            $respuesta["html"] .=  '<td class="fondo02">'.$datos["fec_confirm"];
        }else{
            $respuesta["html"] .=  '<td class="fondo03">Sin confirmar';
            if($datos["cierre_confirmado"] = 'F' && $datos["reconocimiento_facial"] == 'F'){
                if($datos["observacion"] == ''){
                    $respuesta["html"] .=  '
                    <div align="center" onclick="onAddObservation('.$datos["codigo"].', false, '.$respuesta["confirmado"].')">
                        <span class="art-button-wrapper">
                            <span class="art-button-l"> </span>
                            <span class="art-button-r"> </span>';
                            if ($respuesta["confirmado"] == true){
                                $respuesta["html"] .=  ' <input type="button" value="Ver observación" class="readon art-button" />';
                            }else{
                                $respuesta["html"] .=  ' <input type="button" value="Cargar observación" class="readon art-button" />';
                            }
                            $respuesta["html"] .=  '</span>&nbsp;
                    </div>';
                }else{
                    $respuesta["html"] .=  '
                    <div align="center" onclick="onAddObservation('.$datos["codigo"].', true, '.$respuesta["confirmado"].')">
                        <span class="art-button-wrapper">
                            <span class="art-button-l"> </span>
                            <span class="art-button-r"> </span>';
                            if ($respuesta["confirmado"] == true){
                                $respuesta["html"] .=  ' <input type="button" value="Ver observación" class="readon art-button" />';
                            }else{
                                $respuesta["html"] .=  ' <input type="button" value="Editar observación" class="readon art-button" />';
                            }
                            $respuesta["html"] .=  '</span>&nbsp;
                    </div>';
                }
            }
        }
        $respuesta["html"] .=  '</td>';
        if( $datos["in_transport"] == 'T'){
            $respuesta["html"] .=  '<td class="fondo02">'.$datos["fec_in_transport"];
        }else{
            $respuesta["html"] .=  '<td class="fondo03">Sin confirmar';
        }
        $respuesta["html"] .=  '</td>';
        $respuesta["html"] .=  '</td>';
        if( $datos["asistencia"] == 'T'){
            $respuesta["html"] .=  '<td class="fondo02">'.$datos["fec_asistencia"];
        }else{
            $respuesta["html"] .=  '<td class="fondo03">Sin asistir';
            if($datos["cierre_confirmado"] = 'F' && $datos["reconocimiento_facial"] == 'T'){
                if($datos["observacion"] == ''){
                    $respuesta["html"] .=  '
                    <div align="center" onclick="onAddObservation('.$datos["codigo"].', false, '.$respuesta["confirmado"].')">
                        <span class="art-button-wrapper">
                            <span class="art-button-l"> </span>
                            <span class="art-button-r"> </span>';
                            if ($respuesta["confirmado"] == true){
                                $respuesta["html"] .=  ' <input type="button" value="Ver observación" class="readon art-button" />';
                            }else{
                                $respuesta["html"] .=  ' <input type="button" value="Cargar observación" class="readon art-button" />';
                            }
                            $respuesta["html"] .=  '</span>&nbsp;
                    </div>';
                }else{
                    $respuesta["html"] .=  '
                    <div align="center" onclick="onAddObservation('.$datos["codigo"].', true, '.$respuesta["confirmado"].')">
                        <span class="art-button-wrapper">
                            <span class="art-button-l"> </span>
                            <span class="art-button-r"> </span>';
                            if ($respuesta["confirmado"] == true){
                                $respuesta["html"] .=  ' <input type="button" value="Ver observación" class="readon art-button" />';
                            }else{
                                $respuesta["html"] .=  ' <input type="button" value="Editar observación" class="readon art-button" />';
                            }
                        $respuesta["html"] .=  '</span>&nbsp;
                    </div>';
                }
            }
        }
        $respuesta["html"] .=  '</td>';
}

print_r(json_encode($respuesta));
