<?php
require "../modelo/marcaje_modelo.php";
require "../../../../" . Leng;

$marcaje = new Marcaje;
$resultNO = array();
$ficha = $_POST['auxficha'];
$cliente = $_POST['auxcliente'];
$ubicacion = $_POST['auxubicacion'];
$proyecto = $_POST['auxproyecto'];
$codigo = $_POST['codigo'];

$resultNO = $marcaje->get_actividadesNO($ficha, $cliente, $ubicacion, $proyecto, $codigo);

foreach ($resultNO as $datos) {
    $esObligatoria = ($datos["obligatoria"] == 'T');
    $yaRealizada = ($datos["realizado"] == 'SI');
    $tieneArchivo = !empty($datos["link"]);
    $tieneParticipantes = ($datos["participantes"] == 'T');
    
    // Determinar clases CSS para la fila
    $rowClass = '';
    if ($yaRealizada) {
        $rowClass = 'realizada-row';
    } elseif ($esObligatoria) {
        $rowClass = 'obligatory-row';
    }
    
    echo '<tr class="' . $rowClass . '" data-codigo="' . $datos["codigo"] . '">';
    
    // Código
    echo '<td>' . $datos["codigo"] . '</td>';
    
    // Ubicación
    echo '<td>' . $datos["ubicacion"] . '</td>';
    
    // Proyecto
    echo '<td>' . $datos["proyecto"] . '</td>';
    
    // Actividad con indicador visual
    echo '<td>' . htmlspecialchars($datos["actividad"]) . '</td>';
    
    // Horario
    echo '<td>' . $datos["hora_inicio"] . '<br>' . $datos["hora_fin"] . '</td>';
    
    // Realizado
    echo '<td>';
    if ($yaRealizada) {
        echo '<span style="color: green;">✓ SI</span>';
    } else {
        echo '<span style="color: orange;">⏳ NO</span>';
    }
    echo '</td>';
    
    if ($yaRealizada) {
        // ========== ACTIVIDAD YA REALIZADA (SOLO LECTURA) ==========
        
        // Checkbox (marcado y deshabilitado)
        echo '<td><input type="checkbox" checked disabled style="opacity: 0.6;"></td>';
        
        // Archivo
        echo '<td>';
        if ($tieneArchivo) {
            echo '<img class="imgLink" src="imagenes/pdf.gif" alt="Ver Archivo" title="Ver Archivo" onclick="verArchivo(\'' . $datos["link"] . '\')" width="20px" height="20px" style="cursor:pointer;">';
        } else {
            echo '<span style="color: #999;">Sin archivo</span>';
        }
        echo '</td>';
        
        // Participantes
        echo '<td>';
        if ($tieneParticipantes) {
            echo '<img class="imgLink" src="imagenes/detalle.bmp" alt="Ver Participantes" title="Ver Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">';
            echo ' (' . $datos["fichas"] . ')';
        } else {
            echo 'N/A';
        }
        echo '</td>';
        
        // Observaciones
        echo '<td>';
        echo '<img class="imgLink" src="imagenes/detalle.bmp" alt="Ver Observaciones" title="Ver Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">';
        echo ' (' . $datos["observaciones"] . ')';
        echo '</td>';
        
    } else {
        // ========== ACTIVIDAD PENDIENTE ==========
        
        if ($esObligatoria) {
            // ---------- ACTIVIDAD OBLIGATORIA ----------
            // Checkbox: marcado y DESHABILITADO (no se puede desmarcar)
            echo '<td>';
            echo '<input type="checkbox" 
                         id="act_' . $datos["codigo"] . '" 
                         name="marcado[]" 
                         value="' . $datos["codigo"] . '"
                         class="obligatory-checkbox"
                         data-codigo="' . $datos["codigo"] . '"
                         data-obligatoria="true"
                         data-participantes="' . ($tieneParticipantes ? 'true' : 'false') . '"
                         checked 
                         disabled>';
            echo ' <span style="color: red; font-size: 11px;">(Obligatorio)</span>';
            echo '</td>';
            
            // Archivo: requerido
            echo '<td>';
            echo '<input type="file" 
                         name="archivo[' . $datos["codigo"] . ']" 
                         id="file_' . $datos["codigo"] . '"
                         class="obligatory-file"
                         data-codigo="' . $datos["codigo"] . '"
                         data-obligatoria="true"
                         accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                         required
                         onchange="handleFileSelect(this, \'' . $datos["codigo"] . '\', true)">';
            echo '<span id="status_' . $datos["codigo"] . '" class="file-status" style="color: red; margin-left: 10px;">⚠️ Archivo requerido</span>';
            echo '</td>';
            
            // Participantes (si aplica)
            echo '<td>';
            if ($tieneParticipantes) {
                echo '<img class="imgLink" src="imagenes/detalle.bmp" alt="Gestionar Participantes" title="Gestionar Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">';
                echo ' <span style="font-size: 11px;">(' . $datos["fichas"] . ')</span>';
            } else {
                echo 'N/A';
            }
            echo '</td>';
            
            // Observaciones
            echo '<td>';
            echo '<img class="imgLink" src="imagenes/detalle.bmp" alt="Gestionar Observaciones" title="Gestionar Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">';
            echo ' <span style="font-size: 11px;">(' . $datos["observaciones"] . ')</span>';
            echo '</td>';
            
        } else {
            // ---------- ACTIVIDAD NO OBLIGATORIA ----------
            // Checkbox: libre (se puede marcar/desmarcar)
            echo '<td>';
            echo '<input type="checkbox" 
                         id="act_' . $datos["codigo"] . '" 
                         name="marcado[]" 
                         value="' . $datos["codigo"] . '"
                         class="optional-checkbox"
                         data-codigo="' . $datos["codigo"] . '"
                         data-obligatoria="false"
                         data-participantes="' . ($tieneParticipantes ? 'true' : 'false') . '"
                         onchange="handleOptionalCheck(this)">';
            echo '</td>';
            
            // Archivo: inicialmente deshabilitado, se habilita al marcar el checkbox
            echo '<td>';
            echo '<input type="file" 
                         name="archivo[' . $datos["codigo"] . ']" 
                         id="file_' . $datos["codigo"] . '"
                         class="optional-file"
                         data-codigo="' . $datos["codigo"] . '"
                         data-obligatoria="false"
                         accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                         disabled
                         onchange="handleFileSelect(this, \'' . $datos["codigo"] . '\', false)">';
            echo '<span id="status_' . $datos["codigo"] . '" class="file-status" style="display: none;"></span>';
            echo '</td>';
            
            // Participantes (si aplica)
            echo '<td>';
            if ($tieneParticipantes) {
                echo '<img class="imgLink" src="imagenes/detalle.bmp" alt="Gestionar Participantes" title="Gestionar Participantes" onclick="openModalParticipantesNO(' . $datos["codigo"] . ')" width="15px" height="15px">';
                echo ' <span style="font-size: 11px;">(' . $datos["fichas"] . ')</span>';
            } else {
                echo 'N/A';
            }
            echo '</td>';
            
            // Observaciones
            echo '<td>';
            echo '<img class="imgLink" src="imagenes/detalle.bmp" alt="Gestionar Observaciones" title="Gestionar Observaciones" onclick="openModalObservacionesNO(' . $datos["codigo"] . ')" width="15px" height="15px">';
            echo ' <span style="font-size: 11px;">(' . $datos["observaciones"] . ')</span>';
            echo '</td>';
        }
    }
    
    echo '</tr>';
}
?>