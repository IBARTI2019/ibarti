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
    var ubicacion =$("#ubicacion").val(); //378;
    var error = 0,
    var propuesta = $("#propuesta").val();;
    errorMessage = ' ';
    if (error == 0 && ubicacion != "TODOS" && ubicacion != "") {
        var parametros = {
            "ubicacion": ubicacion,
            "usuario": usuario,
            "propuesta": propuesta
        };
        $.ajax({
            data: parametros,
            url: 'packages/planif/life_line/graph/modelo/getData.php',
            type: 'post',
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.length > 0) {
                    $('.brs').show();
                    $('#sin_data').hide();
                    $('#grafica').show();
                    if (graph && ubicSelect == ubicacion) {
                        graph = g.LifeLine('chart-area', resp);
                        // graph = g.actualizarLifeLine(graph.graph, resp, true)
                    } else {
                        ubicSelect = ubicacion;
                        graph = g.LifeLine('chart-area', resp);
                    }
                    $("#ttc").html(`${Math.floor(graph.ttc / 60)} hrs, con ${("0"+graph.ttc % 60).slice(-2)} min`);
                    $("#mtc").html(`${Math.floor(graph.mtc.time / 60)} hrs, con ${("0"+graph.mtc.time % 60).slice(-2)} min  (${moment(graph.mtc.d.data[0].x).format("HH:mm")} - ${moment(graph.mtc.d.data[1].x).format("HH:mm")})`);
                } else {
                    $('.brs').hide();
                    $('#sin_data').show();
                    $('#grafica').hide();
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

function pad(n, length) {
    var n = n.toString();
    while (n.length < length)
        n = "0" + n;
    return n;
}