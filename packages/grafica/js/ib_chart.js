"use strict";

class Grafica {
    constructor(colores) {
        //contenedor
        this.ctx = null;
        //datos basicos de grafica
        this.datos = [];
        this.labels = [];
        this.codigos = [];
        //configuracion torta
        this.torta = null;
        this.configTorta = {};
        //configuracion barra
        this.barra = null;
        this.configBarra = {};

        //configuracion linea
        this.linea = null;
        this.configLinea = {};
        this.fechaActual = moment(new Date()).format("YYYY-MM-DD")

        if (colores) {
            this.chartColors = colores;
        } else {
            this.chartColors = [
                'rgb(255, 99, 132)', 'rgb(255, 159, 64)', 'rgb(255, 205, 86)',
                'rgb(75, 192, 192)', 'rgb(54, 162, 235)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)'
            ];
        }
    }
    /**
     *
     *
     * @param {*} id_contenedor
     * @param {*} data
     * @memberof Grafica
     */
    Torta(id_contenedor, data, titulo,dona) {
        this.datos = [];
        this.labels = [];
        this.codigos = [];

        data.forEach((d) => {
            this.datos.push(Number(d.valor));
            this.labels.push(d.titulo);
            this.codigos.push(d.codigo);
        });

        if (data.length > 0) {
            this.configTorta = {
                type: dona?'doughnut':'pie',
                data: {
                    datasets: [{
                        data: this.datos,
                        backgroundColor: this.chartColors,
                    }],
                    labels: this.labels
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: titulo
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                
                                var label = data.labels[tooltipItem.index] || '';
                                var total = 0;
                                data.datasets[tooltipItem.datasetIndex].data.forEach((d) => {
                                    total += d
                                });

                                if (label) {
                                    label += ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + ' : \n';
                                }
                                label += parseFloat((data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] * 100) / total).toFixed(2);
                                label += ' %';
                                return label;
                            }
                        }
                    },
                    angleLines: {
                        display: true
                    }

                }
            };

            this.ctx = document.getElementById(id_contenedor).getContext('2d');
            this.torta = new Chart(this.ctx, this.configTorta);
            this.torta.codigos = this.codigos;
        }
        return this.torta;
    }

    actualizarTorta(obj, data, titulo,dona) {
        this.datos = [];
        this.labels = [];
        this.codigos = [];

        data.forEach((d) => {
            this.datos.push(Number(d.valor));
            this.labels.push(d.titulo);
            this.codigos.push(d.codigo);
        });

        obj.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        obj.type=dona?'doughnut':'pie';
        obj.data.labels.pop();
        obj.data.datasets[0].data = this.datos;
        obj.data.labels = this.labels;
        obj.options.title.text = titulo;
        obj.update();
        obj.codigos = this.codigos;
        return obj;
    }

    Barra(id_contenedor,data,titulo){
        this.datos = [];
        this.labels = [];
        this.codigos = [];

        data.forEach((d) => {
            this.datos.push(Number(d.valor));
            this.labels.push(d.titulo);
            this.codigos.push(d.codigo);
        });
    }

    LifeLine(id_contenedor, data) {
        this.datos = [];
        this.labels = [];
        this.codigos = [];
        const pointRadius = 2;
        const colorNA = '#CB3234';

        if (data.length > 0) {
            var y = 0;
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                const format = 'DD-MM-YYYY HH:mm'; 
                var horaFinMayor = element.hora_fin;
                if(index > 0){
                    var dataFilter = data.filter(d => {
                        return (
                            (
                                moment(this.fechaActual + " " + element.hora_fin, format).isAfter(
                                moment(this.fechaActual + " " + d.hora_inicio, format)) 
                            )
                            || 
                            (
                                moment(this.fechaActual + " " + d.hora_fin, format).isAfter(
                                moment(this.fechaActual + " " + element.hora_fin, format)) &&
                                moment(this.fechaActual + " " + element.hora_inicio, format).isAfter(
                                moment(this.fechaActual + " " + d.hora_inicio, format))
                            )
                        );
                    });

                    var d = dataFilter.reduce((previous, current) => {
                        return moment(this.fechaActual + " " + previous.hora_fin, format).isAfter(
                            moment(this.fechaActual + " " + current.hora_fin, format))
                            ? previous : current; 
                    });
                    horaFinMayor = d.hora_fin;
                }
                
                if(index == 0){
                    const simulateToken = moment(this.fechaActual + " 00:00", format)
                    const simulateNow   = moment(this.fechaActual + " " + element.hora_inicio, format)
                    if(simulateNow.isAfter(simulateToken)){
                        var dataA = [];
                        dataA.push({x:this.fechaActual + " 00:00",y:-1});
                        dataA.push({x:this.fechaActual + " " + element.hora_inicio,y:-1});
                        this.datos.push({
                            data: dataA,      
                            fill: false,
                            label: 'Sin Actividad',
                            backgroundColor:  colorNA,
                            borderColor:  colorNA,
                            pointRadius: pointRadius,
                            spanGaps: false,
                            cubicInterpolationMode: 'monotone',
                        });
                    }
                }

                var dataA = [];
                this.codigos.push(element.cod_actividad);
                y = this.codigos.findIndex((d) => d == element.cod_actividad);
                dataA.push({x:this.fechaActual + " " + element.hora_inicio,y:y});
                dataA.push({x:this.fechaActual + " " + element.hora_fin,y:y});
                this.datos.push({
                    data: dataA,      
                    fill: false,
                    label: element.actividad,
                    backgroundColor: element.color,
                    borderColor: element.color,
                    pointRadius: pointRadius,
                    spanGaps: false,
                    cubicInterpolationMode: 'monotone',
                });

                if(index < (data.length - 1)){
                    const simulateToken = moment(this.fechaActual + " " + horaFinMayor, format)
                    const simulateNow   = moment(this.fechaActual + " " + data[(index+1)].hora_inicio, format)
                    if(simulateNow.isAfter(simulateToken)){
                        var dataA = [];
                        dataA.push({x:this.fechaActual + " " + horaFinMayor,y:-1});
                        dataA.push({x:this.fechaActual + " " + data[(index+1)].hora_inicio,y:-1});
                        this.datos.push({
                            data: dataA,      
                            fill: false,
                            label: 'Sin Actividad',
                            backgroundColor:  colorNA,
                            borderColor:  colorNA,
                            pointRadius: pointRadius,
                            spanGaps: false,
                            cubicInterpolationMode: 'monotone',
                        });
                    }
                }else if(index == (data.length - 1)){
                    const simulateToken = moment(this.fechaActual + " " + horaFinMayor, format)
                    const simulateNow   = moment(this.fechaActual + " 24:00", format)
                    if(simulateNow.isAfter(simulateToken)){
                        var dataA = [];
                        dataA.push({x:this.fechaActual + " " + horaFinMayor,y:-1});
                        dataA.push({x:this.fechaActual + " 24:00",y:-1});
                        this.datos.push({
                            data: dataA,      
                            fill: false,
                            label: 'Sin Actividad',
                            backgroundColor:  colorNA,
                            borderColor:  colorNA,
                            pointRadius: pointRadius,
                            spanGaps: false,
                            cubicInterpolationMode: 'monotone',
                        });
                    }
                }
            }

        
            const DATA_COUNT = 24;
            for (let i = 0; i < DATA_COUNT; ++i) {
                if(i > 9){
                    this.labels.push(this.fechaActual + " " +i+":30");
                }else{
                    this.labels.push(this.fechaActual + " " +'0'+i+":30");
                }
            }
            var dataGroup = Object.groupBy(data, ({ cod_actividad }) => cod_actividad);
            var maxL =  (Object.keys(dataGroup).map((k) => k).length) - 0.5;
            this.configLinea = {
                type: 'line',
                data: {
                    datasets: this.datos,
                    labels: this.labels
                },
                options: {
                    spanGaps: true,
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 20,
                            bottom: 0
                        }
                    },
                    interaction: {
                        intersect: false,
                    },
                    tooltips: {
                        // enabled: false,
                    },

                    // offset: true,
                    scales: {
                        xAxes: [{
                            type: 'time',
                            // distribution: 'series',
                            distribution: 'linear',
                            time: {
                              // Luxon format string
                            //   minUnit: 'minute',
                              min: this.fechaActual + " 00:00",
                              max: this.fechaActual + " 24:00",
                              unit: 'minute'
                            },
                            parser: "HH:mm"
                          }],
                          yAxes: [{
                            display: false,
                            ticks: {
                                suggestedMin: -1.5,
                                suggestedMax: maxL,
                            }
                        }]
                    },
                    fill: false,
                    responsive: true,
                    legend: {
                        labels: {
                            generateLabels: chart => Object.keys(dataGroup).map((l, i) => ({
                                datasetIndex: i,
                                text: dataGroup[l][0].actividad,
                                fillStyle: dataGroup[l][0].color,
                                strokeStyle: dataGroup[l][0].backgroundColor,
                                hidden: chart.getDatasetMeta(this.datos.findIndex(ds => ds.label == dataGroup[l][0].actividad)).hidden,
                            })),
                        },
                        onClick: function (e, legendItem) {
                            var index = legendItem.datasetIndex;
                            let ci = this.chart;
                            ci.data.datasets.forEach((ds, i) => {
                                if(ds.label == legendItem.text){
                                    var meta = ci.getDatasetMeta(i);
                                    meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                                }
                            })
                            ci.update();
                         }
                    }
                }
            };

            this.ctx = document.getElementById(id_contenedor).getContext('2d');
            this.linea = new Chart(this.ctx, this.configLinea);
            // this.linea.codigos = this.codigos;
        }
        return this.linea;
    }

    actualizarLifeLine(obj, data) {
        this.datos = [];
        this.codigos = [];
        var y = 0;
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
            var dataA = [];
            this.codigos.push(element.cod_actividad);
            y = this.codigos.findIndex((d) => d == element.cod_actividad);
            dataA.push({x:this.fechaActual + " " + element.hora_inicio,y:y});
            dataA.push({x:this.fechaActual + " " + element.hora_fin,y:y});
            this.datos.push({
                data: dataA,      
                fill: false,
                label: element.actividad,
                backgroundColor: element.color,
                borderColor: element.color,
                pointRadius: pointRadius,
                spanGaps: false,
                cubicInterpolationMode: 'monotone',
            });
        }
        var dataGroup = Object.groupBy(data, ({ cod_actividad }) => cod_actividad);

        obj.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });

        obj.data.datasets = this.datos;
        obj.options.legend = {
            labels: {
                generateLabels: chart => Object.keys(dataGroup).map((l, i) => ({
                    datasetIndex: i,
                    text: dataGroup[l][0].actividad,
                    fillStyle: dataGroup[l][0].color,
                    strokeStyle: dataGroup[l][0].backgroundColor,
                    hidden: chart.getDatasetMeta(this.datos.findIndex(ds => ds.label == dataGroup[l][0].actividad)).hidden,
                })),
            },
            onClick: function (e, legendItem) {
                var index = legendItem.datasetIndex;
                let ci = this.chart;
                ci.data.datasets.forEach((ds, i) => {
                    if(ds.label == legendItem.text){
                        var meta = ci.getDatasetMeta(i);
                        meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                    }
                })
                ci.update();
             }
        };
        obj.update();
        return obj;
    }
}