<?php
$Nmenu = 482;
$titulo = " Enviar Recibos y Alimentación";

require_once('autentificacion/aut_verifica_menu.php');
$bd = new DataBase();

// --- LÓGICA DE PERIODOS PERMITIDOS ---
$fecha_actual = new DateTime();
$fecha_anterior = (new DateTime())->modify('-1 month');

$periodos = [
    [
        "anio" => $fecha_actual->format('Y'),
        "mes_num" => $fecha_actual->format('n'),
    ],
    [
        "anio" => $fecha_anterior->format('Y'),
        "mes_num" => $fecha_anterior->format('n'),
    ]
];

$meses_nombres = [
    1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 
    5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 
    9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
];

$sql_contractos = "SELECT codigo, descripcion FROM contractos WHERE status = 'T' ORDER BY 2 ASC";
?>

<script language="javascript">
// Control visual dinámico para adaptar textos según el proceso
function alternarCamposPorProceso() {
    const tipoProceso = document.getElementById('tipo').value;
    const filaQuincena = document.getElementById('fila_quincena');
    const filaContrato = document.getElementById('fila_contrato');
    const labelRecibos = document.getElementById('label_recibos');
    const inputRecibos = document.getElementById('file_recibos');
    
    if (tipoProceso === 'alimentacion') {
        filaQuincena.style.display = 'none';
        filaContrato.style.display = 'none'; // Ocultamos el campo Contrato
        document.getElementById('quincena').value = ''; // Limpiamos quincena
        document.getElementById('co_contrato').value = ''; // Limpiamos contrato
        
        // Adaptamos el texto para Cestaticket
        labelRecibos.innerHTML = "Archivo Alimentación Humanis:";
        inputRecibos.accept = ".xls,.xlsx,.txt,.pdf"; 
    } else {
        filaQuincena.style.display = ''; // Muestra quincena por defecto
        filaContrato.style.display = ''; // Muestra contrato por defecto
        
        // Restauramos texto original para Recibos
        labelRecibos.innerHTML = "Archivo Recibos Humanis:";
        inputRecibos.accept = ".pdf";
    }
}

function enviarDatosWebhook() {
    const tipoProceso = document.getElementById('tipo').value;
    
    // Captura de elementos
    const anio = document.getElementById('co_cont').value;
    const mes = document.getElementById('mes_cont').value;
    const contrato = document.getElementById('co_contrato').value;
    const quincena = document.getElementById('quincena').value;
    
    const fileRecibos = document.getElementById('file_recibos').files[0];
    const fileTasas = document.getElementById('file_tasas').files[0];

    // 1. Validaciones base comunes (Se eliminó 'contrato' de la validación global obligatoria)
    if (!tipoProceso || !anio || !mes || !fileTasas || !fileRecibos) {
        alert("Por favor, complete todos los campos requeridos, cargue las Tasas y el archivo de Humanis correspondientes.");
        return;
    }

    // 2. Validaciones específicas condicionales
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

    // 3. Construcción del FormData
    const formData = new FormData();
    formData.append('tipo', tipoProceso);
    formData.append('anio', anio);
    formData.append('mes', mes);
    formData.append('test', 'false');
    formData.append('tasas', fileTasas); 
    formData.append('humanis_file', fileRecibos); 

    // Solo adjuntamos parámetros de nómina si corresponde
    if (tipoProceso === 'recibos') {
        formData.append('contrato', contrato);
        formData.append('quincena', quincena);
    }

    // 4. Endpoint de n8n
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
        document.getElementById('salvar').disabled = false;
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
                <select name="co_cont" id="co_cont" style="width:250px;">
                    <option value="">Seleccione Año..</option>
                    <?php
                        $anios_unicos = array_unique([$periodos[0]['anio'], $periodos[1]['anio']]);
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
                    <?php
                        foreach ($periodos as $p) {
                            echo '<option value="' . $p['mes_num'] . '">' . $meses_nombres[$p['mes_num']] . '</option>';
                        }
                    ?>
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
                        echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
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
            <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" onclick="setTimeout(alternarCamposPorProceso, 50)" />
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
<br />
<div align="center">
</div>
<script type="text/javascript">
    var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
</script>