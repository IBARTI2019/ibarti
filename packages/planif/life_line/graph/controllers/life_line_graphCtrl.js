/*var g = new GraficasD3();
var g2 = new GraficasD3(d3.schemePaired);*/
var g = new Grafica();
var f = new Date();
var fec_desde = f.getFullYear() + "-" + /*pad((f.getMonth() + 1), 2) +*/ "01-01";
var fec_hasta = f.getFullYear() + "-" + pad((f.getMonth() + 1), 2) + "-" + pad(f.getDate(), 2);
var chartColors = ['rgb(255, 99, 132)', 'rgb(255, 159, 64)', 'rgb(255, 205, 86)', 'rgb(75, 192, 192)',
    'rgb(54, 162, 235)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)'
];
var graph;
var ubicSelect = "";
//Se geneara automaticamente al cargar el script
$(function () {
    iniciar();
});

function Add_filtroX() {
    var usuario = $("#usuario").val();
    var ubicacion = $("#ubicacion").val();
    var error = 0,
    errorMessage = ' ';
    if (error == 0 && ubicacion != "TODOS" && ubicacion != "") {
        var parametros = {
            "ubicacion": ubicacion,
            "usuario": usuario
        };
        $.ajax({
            data: parametros,
            url: 'packages/planif/life_line/graph/modelo/getData.php',
            type: 'post',
            success: function (response) {
                console.log(response);
                var resp = JSON.parse(response);
                if (resp.length > 0) {
                    $('.brs').show();
                    $('#sin_data').hide();
                    $('#grafica').show();
                    $('#division').show();
                    if (graph && ubicSelect == ubicacion) {
                    //     console.log('actualizar');
                        graph = g.actualizarLifeLine(graph, resp, true)
                    } else {
                        ubicSelect = ubicacion;
                        graph = g.LifeLine('chart-area', resp);
                    }

                } else {
                    $('.brs').hide();
                    $('#sin_data').show();
                    $('#grafica').hide();
                    $('#division').hide();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    }
}

function iniciar() {
    var error = 0;
    var errorMessage = ' ';
    var parametros = {};
	parametros['usuario'] = $("#usuario").val();
	parametros['r_cliente'] = $("#r_cliente").val();
    if (error == 0) {
        $.ajax({
            data: parametros,
            url: 'packages/planif/life_line/graph/views/inicio.php',
            type: 'POST',
            success: function (response) {
                $("#Cont_graph").html(response);
                // Add_filtroX();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        }, () => {});

    } else {
        alert(errorMessage);
    }
}

function novStatusDet(status, titulo) {
    var usuario = $("#usuario").val();
    var error = 0;
    var errorMessage = ' ';
    if (error == 0) {
        var parametros = {
            "fec_desde": fec_desde,
            "fec_hasta": fec_hasta,
            "status": status
        };
        $.ajax({
            data: parametros,
            url: 'packages/grafica/novedades/modelo/getGraficaStatusDet.php',
            type: 'post',
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.length > 5) {
                    g2.addEventCrearGrafica(JSON.parse(response), status, 'grafica', 'nov1', 'grafica', 'nov2', 'barra', 450, false, 'top', 'col-xs-6', 'Status: ' + titulo, false);
                } else {
                    g2.addEventCrearGrafica(JSON.parse(response), status, 'grafica', 'nov1', 'grafica', 'nov2', 'torta', 450, true, 'top', 'col-xs-6', 'Status: ' + titulo, false, true);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    } else {
        alert(errorMessage);
    }
}

function novStatusDet_inic(status, titulo) {
    var error = 0;
    var errorMessage = ' ';
    if (error == 0) {
        var parametros = {
            "fec_desde": fec_desde,
            "fec_hasta": fec_hasta,
            "status": status
        };
        $.ajax({
            data: parametros,
            url: 'packages/grafica/novedades/modelo/getGraficaStatusDet.php',
            type: 'post',
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.length > 5) {
                    g2.crearGraficaBarra(resp, 450, 'grafica', 'nov2', false, false, 'top', 'col-xs-6', 'Status: ' + titulo);
                } else
                    g2.crearGraficaTorta(resp, 450, 'grafica', 'nov2', true, true, 'top', 'col-xs-6', 'Status: ' + titulo);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    } else {
        alert(errorMessage);
    }
}

function pad(n, length) {
    var n = n.toString();
    while (n.length < length)
        n = "0" + n;
    return n;
}