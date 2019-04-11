"use strict";

class Grafica {
    constructor(colores) {
        this.ctx = null;
        this.datos = [];
        this.labels = [];
        this.codigos = [];
        this.configTorta = {};
        this.torta = null;
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
    Torta(id_contenedor, data, titulo) {
        this.datos = [];
        this.labels = [];
        this.codigos = [];

        data.forEach((d) => {
            this.datos.push(Number(d.valor));
            this.labels.push(d.titulo);
            this.codigos.push(d.codigo);
        });

        if (data.length > 0) {
            if (this.torta) {
                this.torta.data.datasets.forEach((dataset) => {
                    dataset.data.pop();
                });
                this.torta.data.labels.pop();
                this.torta.data.datasets[0].data = this.datos;
                this.torta.data.labels = this.labels;
                this.torta.options.title.text = titulo;
                this.torta.update();
            } else {
                this.configTorta = {
                    type: 'pie',
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
                                    console.log(data);
                                    var label = data.labels[tooltipItem.index] || '';
                                    var total = 0;
                                    data.datasets[tooltipItem.datasetIndex].data.forEach((d) => {
                                        total += d
                                    });

                                    if (label) {
                                        label += ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + ' : \n';
                                    }
                                    label += parseFloat((data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]*100) / total).toFixed(2);
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
            }
        }
    }
}