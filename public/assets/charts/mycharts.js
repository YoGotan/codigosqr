$(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    const dataPieCupones = {
        labels: labelsPieCupones,
        datasets: [{
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1,
            data: usersPieCupones,
        }]
    };

    const dataBarUsuarios = {
        labels: labelsBarUsuarios,
        datasets: [{
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1,
            data: usersBarUsuarios,
        }]
    };

    const dataBarClientes = {
        labels: labelsBarClientes,
        datasets: [{
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1,
            data: usersBarClientes,
        }]
    };

    const configD = {
        type: 'doughnut',
        data: dataBarUsuarios,
        options: {}
    };
    const configPi = {
        type: 'pie',
        data: dataPieCupones,
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Numero: ' + context.parsed;
                        }
                    }
                },
                legend: {
                    display: true,
                    labels: {
                        padding: 50
                    },
                    position: 'bottom'
                },
                datalabels: {
                    color: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ]
                }
            }
        }
    };
    const configPo = {
        type: 'polarArea',
        data: dataBarUsuarios,
        options: {}
    };
    const configB = {
        type: 'bar',
        data: dataBarUsuarios,
        plugins: [ChartDataLabels],
        options: {
            scales: {
                y: {
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Numero de usuarios: ' + context.parsed.y;
                        }
                    }
                },
                legend: {
                    display: false
                },
                datalabels: {
                    color: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ]
                }
            }
        }
    };
    const configL = {
        type: 'bar',
        data: dataBarClientes,
        plugins: [ChartDataLabels],
        options: {
            scales: {
                y: {
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Numero de clientes: ' + context.parsed.y;
                        }
                    }
                },
                legend: {
                    display: false
                },
                datalabels: {
                    color: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ]
                }
            }
        }
    };
    const myChart = new Chart(
        document.getElementById('doughnut'),
        configD
    );
    const pieCupones = new Chart(
        document.getElementById('PieCupones'),
        configPi
    );
    const myChart2 = new Chart(
        document.getElementById('polarArea'),
        configPo
    );
    const barUsuarios = new Chart(
        document.getElementById('barUsuarios'),
        configB
    );
    const barClientes = new Chart(
        document.getElementById('barClientes'),
        configL
    );

    /* Peticion actualizar datos graficos */
    $('#fechaBarUsuarios').change(function() {
        const mes = $(this).val();
        const params = {
            mes: mes
        };
        axios
            .post("/estadisticas/actualizar/usuarios", params)
            .then(respuesta => {
                if (respuesta.data) {;
                    barUsuarios.data.labels = respuesta.data.labels;
                    barUsuarios.data.datasets[0].data = respuesta.data.data;
                    barUsuarios.update();
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    });


    $('#fechaBarClientes').change(function() {
        const mes = $(this).val();
        const params = {
            mes: mes
        };
        axios
            .post("/estadisticas/actualizar/clientes", params)
            .then(respuesta => {
                if (respuesta.data) {;
                    barClientes.data.labels = respuesta.data.labels;
                    barClientes.data.datasets[0].data = respuesta.data.data;
                    barClientes.update();
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    });

    $('#fechaPieCupones').change(function() {
        const mes = $(this).val();
        const params = {
            mes: mes
        };
        axios
            .post("/estadisticas/actualizar/cupones", params)
            .then(respuesta => {
                if (respuesta.data) {;
                    pieCupones.data.labels = respuesta.data.labels;
                    pieCupones.data.datasets[0].data = respuesta.data.data;
                    pieCupones.update();
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    });
});