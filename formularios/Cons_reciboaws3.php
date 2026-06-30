<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$Nmenu = 482;
$titulo = " Enviar Recibos y Alimentación";

require_once('autentificacion/aut_verifica_menu.php');
$bd = new DataBase();

// --- LÓGICA DE PERIODOS PERMITIDOS ---
$fecha_actual = new DateTime();
$anio_actual = $fecha_actual->format('Y');
$mes_actual = (int)$fecha_actual->format('m');
$anio_anterior = (int)$anio_actual - 1;

$periodos = array(); // Cambiado [] por array() para PHP antiguo

// Generamos los meses transcurridos del año actual
for ($m = 1; $m <= $mes_actual; $m++) {
    $periodos[] = array(
        "anio" => $anio_actual,
        "mes_num" => $m
    );
}

// Generamos los 12 meses para el año anterior completos
for ($m = 1; $m <= 12; $m++) {
    $periodos[] = array(
        "anio" => (string)$anio_anterior,
        "mes_num" => $m
    );
}

$meses_nombres = array(
    1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 
    5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 
    9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
);

$sql_contractos = "SELECT codigo, descripcion FROM contractos WHERE status = 'T' ORDER BY 2 ASC";

$sql_ejecuciones = "SELECT p.cod_contrato, c.descripcion AS contrato_desc, p.anio, p.mes, p.quincena, p.procesando, p.ultima_act, p.tipo 
                    FROM proc_recibos_n8n p
                    LEFT JOIN contractos c ON TRIM(p.cod_contrato) = TRIM(c.codigo)
                    ORDER BY p.ultima_act DESC 
                    LIMIT 20";
?>

<script language="javascript">
// Pasamos el array de periodos válidos de PHP a JavaScript de forma segura
const periodosValidos = <?php echo json_encode($periodos); ?>;
const nombresMeses = <?php echo json_encode($meses_nombres); ?>;

// Actualiza los meses disponibles dependiendo del año seleccionado
function actualizarMesesPorAnio() {
    const anioSeleccionado = document.getElementById('co_cont').value;
    const selectMes = document.getElementById('mes_cont');
    
    // Limpiar opciones anteriores
    selectMes.innerHTML = '<option value="">Seleccione Mes..</option>';
    
    if (!anioSeleccionado) return;

    // Filtrar los meses que corresponden al año seleccionado
    const mesesFiltrados = periodosValidos.filter(p => p.anio === anioSeleccionado);
    
    mesesFiltrados.forEach(p => {
        const option = document.createElement('option');
        option.value = p.mes_num;
        option.text = nombresMeses[p.mes_num];
        selectMes.appendChild(option);
    });
}

// Control visual dinámico para adaptar textos según el proceso
function alternarCamposPorProceso() {
    const tipoProceso = document.getElementById('tipo').value;
    const filaQuincena = document.getElementById('fila_quincena');
    const filaContrato = document.getElementById('fila_contrato');
    const labelRecibos = document.getElementById('label_recibos');
    const inputRecibos = document.getElementById('file_recibos');
    
    if (tipoProceso === 'alimentacion') {
        filaQuincena.style.display = 'none';
        filaContrato.style.display = 'none'; 
        document.getElementById('quincena').value = ''; 
        document.getElementById('co_contrato').value = ''; 
        
        labelRecibos.innerHTML = "Archivo Alimentación Humanis:";
        inputRecibos.accept = ".xls,.xlsx,.txt,.pdf"; 
    } else {
        filaQuincena.style.display = ''; 
        filaContrato.style.display = ''; 
        
        labelRecibos.innerHTML = "Archivo Recibos Humanis:";
        inputRecibos.accept = ".pdf";
    }
}

function enviarDatosWebhook() {
    const tipoProceso = document.getElementById('tipo').value;
    
    const anio = document.getElementById('co_cont').value;
    const mes = document.getElementById('mes_cont').value;
    const contrato = document.getElementById('co_contrato').value;
    const quincena = document.getElementById('quincena').value;
    
    const fileRecibos = document.getElementById('file_recibos').files[0];
    const fileTasas = document.getElementById('file_tasas').files[0];

    if (!tipoProceso || !anio || !mes || !fileTasas || !fileRecibos) {
        alert("Por favor, complete todos los campos requeridos, cargue las Tasas y el archivo de Humanis correspondientes.");
        return;
    }

    if (tipoProceso === 'recibos') {
        if (!contrato) {
            alert("Debe seleccionar un Contrato para el proceso de Recibos de Pago.");
            return;
        }
        if (!quincena) {
            alert("Debe seleccionar una quincena para el proceso de Recibos.");
            return;
        }
    }

    const formData = new FormData();
    formData.append('tipo', tipoProceso);
    formData.append('anio', anio);
    formData.append('mes', mes);
    formData.append('test', 'false');
    formData.append('tasas', fileTasas); 
    formData.append('humanis_file', fileRecibos); 

    if (tipoProceso === 'recibos') {
        formData.append('contrato', contrato);
        formData.append('quincena', quincena);
    }

    const url = 'http://212.56.33.4:5678/webhook/api/v1/procesar-recibos-full';

    document.getElementById('salvar').disabled = true;

    fetch(url, {
        method: 'POST',
        body: formData 
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la conexión con el servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Éxito:', data);
        alert("¡Proceso enviado y ejecutándose con éxito!");
        location.reload();
    })
    .catch((error) => {
        console.error('Error:', error);
        alert("Hubo un problema al conectar con el servicio de recibos.");
        document.getElementById('salvar').disabled = false;
    });
}

function exportarExcelClientes() {
    const urlPhp = "reportes/plantilla_tasas.php"; 

    const form = document.createElement("form");
    form.method = "POST";
    form.action = urlPhp;
    form.target = "_blank"; 

    const inputReporte = document.createElement("input");
    inputReporte.type = "hidden";
    inputReporte.name = "reporte";
    inputReporte.value = "excel";

    form.appendChild(inputReporte);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>

<br>
<div align="center" class="etiqueta_title"> <?php echo $titulo; ?> </div>
<br/>
<form name="form_recibo" id="form_recibo" enctype="multipart/form-data">
    <table width="600px" border="0" align="center">
        <tr>
            <td class="etiqueta" width="40%">Tipo de Proceso:</td>
            <td>
                <select name="tipo" id="tipo" style="width:250px;" onchange="alternarCamposPorProceso()">
                    <option value="recibos">Recibos de Pago (Nómina)</option>
                    <option value="alimentacion">Cesta Ticket (Alimentación)</option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Año:</td>
            <td id="select01">
                <select name="co_cont" id="co_cont" style="width:250px;" onchange="actualizarMesesPorAnio()">
                    <option value="">Seleccione Año..</option>
                    <?php
                        // SOLUCIÓN MANUAL PARA PHP < 5.5 (Reemplaza a array_column)
                        $anios_unicos = array();
                        foreach ($periodos as $p) {
                            if (!in_array($p['anio'], $anios_unicos)) {
                                $anios_unicos[] = $p['anio'];
                            }
                        }
                        
                        foreach ($anios_unicos as $a) {
                            echo '<option value="' . $a . '">' . $a . '</option>';
                        }
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Mes:</td>
            <td id="select02">
                <select name="mes_cont" id="mes_cont" style="width:250px;">
                    <option value="">Seleccione Mes..</option>
                    </select>
            </td>
        </tr>

        <tr id="fila_quincena">
            <td class="etiqueta">Quincena:</td>
            <td id="select03">
                <select name="quincena" id="quincena" style="width:250px;">
                    <option value="">Seleccione Quincena..</option>
                    <option value="1">1era Quincena</option>
                    <option value="2">2da Quincena</option>
                </select>
            </td>
        </tr>

        <tr id="fila_contrato">
            <td class="etiqueta">Contrato:</td>
            <td id="select04">
                <select name="co_contrato" id="co_contrato" style="width:250px;">
                    <option value="">Seleccione Contrato..</option>
                    <?php
                    $query = $bd->consultar($sql_contractos);
                    while($row02=$bd->obtener_fila($query,0)){
                        echo '<option value="'.trim($row02[0]).'">'.$row02[1].'</option>';
                    }?>
                </select>
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Archivo Tasas (HTML/Excel):</td>
            <td>
                <input type="file" name="file_tasas" id="file_tasas" accept=".xls,.xlsx" style="width:250px;" />
            </td>
        </tr>

        <tr id="fila_recibos">
            <td class="etiqueta" id="label_recibos">Archivo Recibos Humanis:</td>
            <td>
                <input type="file" name="file_recibos" id="file_recibos" accept=".pdf" style="width:250px;" />
                <br><small style="color: gray;">* Campo obligatorio para procesar la información</small>
            </td>
        </tr>
    </table>
    <br>

    <div align="center">  
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="button" name="salvar" id="salvar" value="Enviar" onclick="enviarDatosWebhook()" class="readon art-button" />
        </span>&nbsp;
        
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" onclick="setTimeout(() => { alternarCamposPorProceso(); actualizarMesesPorAnio(); }, 50)" />
        </span>&nbsp;
        
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="button" name="tasa_clientes" id="tasa_clientes" value="Generar Plantilla de Tasas" onclick="exportarExcelClientes()" class="readon art-button" />
        </span>&nbsp;

        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
        </span>
        <input type="hidden" id="usuario" value="<?php echo $usuario;?>"/>
    </div>
</form>

<br /><hr style="width: 80%; border: 1px solid #ccc;" /><br />

<div align="center">
    <div class="etiqueta_title" style="font-size: 14px; margin-bottom: 10px;"> Historial de Últimos Procesos </div>
    
    <table width="85%" class="tabla_sistema" style="border-collapse: collapse;">
        <tr>
            <td width="12%">Proceso</td>
            <td width="28%">Contrato Especificado</td>
            <td width="8%">Año</td>
            <td width="12%">Mes</td>
            <td width="15%">Quincena</td>
            <td width="15%">Última Act.</td>
            <td width="10%">Estatus</td>
        </tr>
        
        <?php
        $query_ejec = $bd->consultar($sql_ejecuciones);

        if (!$query_ejec) {
            echo '<tr style="background-color: #ffffff; text-align: center;"><td colspan="7" style="color: red; padding: 15px; font-weight:bold;">';
            echo 'Error en la consulta SQL del Historial. Asegúrate de que exista la tabla proc_recibos_n8n.';
            echo '</td></tr>';
        } else {
            $hubo_render = false;

            while ($reg = $bd->obtener_fila($query_ejec, 0)) {
                $hubo_render = true;

                $r_cod_contrato  = isset($reg['cod_contrato'])  ? $reg['cod_contrato']  : (isset($reg[0]) ? $reg[0] : '');
                $r_contrato_desc = isset($reg['contrato_desc']) ? $reg['contrato_desc'] : (isset($reg[1]) ? $reg[1] : '');
                $r_anio          = isset($reg['anio'])          ? $reg['anio']          : (isset($reg[2]) ? $reg[2] : '');
                $r_mes           = isset($reg['mes'])           ? $reg['mes']           : (isset($reg[3]) ? $reg[3] : '');
                $r_quincena      = isset($reg['quincena'])      ? $reg['quincena']      : (isset($reg[4]) ? $reg[4] : '');
                $r_procesando    = isset($reg['procesando'])    ? $reg['procesando']    : (isset($reg[5]) ? $reg[5] : '');
                $r_ultima_act    = isset($reg['ultima_act'])    ? $reg['ultima_act']    : (isset($reg[6]) ? $reg[6] : '');
                $r_tipo          = isset($reg['tipo'])          ? $reg['tipo']          : (isset($reg[7]) ? $reg[7] : '');

                $tipo_raw = trim(strtolower((string)$r_tipo));
                if ($tipo_raw === 'alimentacion' || empty($r_cod_contrato)) {
                    $tipo_print = "Alimentación";
                    $contrato_print = "N/A (Cesta Ticket)";
                    $quincena_print = "N/A (Mensual)";
                } else {
                    $tipo_print = "Nómina";
                    $contrato_print = (!empty($r_contrato_desc)) ? trim($r_cod_contrato) . " - " . $r_contrato_desc : "Contrato (" . trim($r_cod_contrato) . ")";
                    $quincena_print = (trim($r_quincena) == '1') ? "1era Quincena" : "2da Quincena";
                }

                $mes_index = (int)$r_mes;
                $mes_print = isset($meses_nombres[$mes_index]) ? $meses_nombres[$mes_index] : $r_mes;
                $fecha_print = (!empty($r_ultima_act)) ? date('d/m/Y h:i A', strtotime($r_ultima_act)) : 'N/R';

                $proc_flag = strtoupper(trim((string)$r_procesando));
                
                if ($proc_flag === 'T') {
                    $badge_color = "#2980b9";
                    $status_txt  = "PROCESANDO";
                } else {
                    $badge_color = "#27ae60";
                    $status_txt  = "COMPLETADO";
                }

                $badge_html = '<span style="background-color: '.$badge_color.'; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; display: inline-block;">'.$status_txt.'</span>';
                
                echo '<tr style="background-color: #ffffff; text-align: center; border-bottom: 1px solid #eee;">';
                echo '<td style="font-weight: bold; color: #34495e;">'.$tipo_print.'</td>';
                echo '<td align="left" style="padding-left: 10px;">'.$contrato_print.'</td>';
                echo '<td>'.$r_anio.'</td>';
                echo '<td>'.$mes_print.'</td>';
                echo '<td>'.$quincena_print.'</td>';
                echo '<td>'.$fecha_print.'</td>';
                echo '<td>'.$badge_html.'</td>';
                echo '</tr>';
            }

            if (!$hubo_render) {
                echo '<tr style="background-color: #ffffff; text-align: center;"><td colspan="7" style="color: gray; padding: 15px;">No se registran ejecuciones.</td></tr>';
            }
        }
        ?>
    </table>
</div>
</br>
<script type="text/javascript">
    var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
</script>