document.addEventListener("DOMContentLoaded", function () {

    var options = {
        series: [25, 20, 15, 10, 10, 10, 5],
        chart: {
        width: 340,
        type: 'pie',
      },
      labels: ['Pinturas', 'Telas', 'Papel', 'Herramientas', 'Hilos y lanas', 'Arcilla', 'Pegamentos'],
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 100
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
      };

      var categoria = new ApexCharts(document.querySelector("#categoria"), options);
      categoria.render();

      // Supongamos que obtienes los datos desde tu backend en formato JSON
const datosDesdeBackend = [
    { mes: 1, ventas: 1200 },
    { mes: 2, ventas: 1500 },
    { mes: 3, ventas: 1800 },
    { mes: 4, ventas: 2100 },
    { mes: 5, ventas: 1700 },
    { mes: 6, ventas: 2000 },
    { mes: 7, ventas: 2300 },
    { mes: 8, ventas: 2500 },
    { mes: 9, ventas: 2200 },
    { mes: 10, ventas: 2400 },
    { mes: 11, ventas: 2600 },
    { mes: 12, ventas: 2800 }
];

const ventasMensuales = datosDesdeBackend.map(item => item.ventas);

    var options = {
        series: [{
            name: 'Ventas',
            data: ventasMensuales
        }],
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                    position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return "€" + val;
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        xaxis: {
            categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            position: 'top',
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                }
            },
            tooltip: {
                enabled: true,
            }
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: true,
                formatter: function (val) {
                    return "€" + val;
                }
            }
        },
        title: {
            text: 'Ventas Mensuales - Tienda de Manualidades (2023)',
            floating: true,
            offsetY: 330,
            align: 'center',
            style: {
                color: '#444'
            }
        }
    };


    var ventas = new ApexCharts(document.querySelector("#ventas"), options);
    ventas.render();

    var options = {
        series: [{
            name: 'Ventas', // Serie 1: Ventas
            data: [1200, 1500, 1800, 2100, 1700, 2000, 2300] // Datos de ventas diarias/semanales
        }, {
            name: 'Pedidos', // Serie 2: Pedidos
            data: [50, 70, 60, 80, 75, 90, 85] // Datos de pedidos diarios/semanales
        }],
        chart: {
            height: 350,
            type: 'area', // Tipo de gráfico
        },
        dataLabels: {
            enabled: false // Deshabilitar etiquetas de datos
        },
        stroke: {
            curve: 'smooth' // Curva suave para las líneas
        },
        xaxis: {
            type: 'datetime', // Eje X de tipo fecha
            categories: [
                "2023-10-01T00:00:00.000Z", 
                "2023-10-02T00:00:00.000Z", 
                "2023-10-03T00:00:00.000Z", 
                "2023-10-04T00:00:00.000Z", 
                "2023-10-05T00:00:00.000Z", 
                "2023-10-06T00:00:00.000Z", 
                "2023-10-07T00:00:00.000Z"
            ], // Fechas de ejemplo
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm' // Formato de la fecha en el tooltip
            },
        },
        colors: ['#36A2EB', '#FF6384'], // Colores personalizados para las series
        title: {
            text: 'Evolución de Ventas y Pedidos - Octubre 2023', // Título del gráfico
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold',
                color: '#444'
            }
        },
        yaxis: {
            title: {
                text: 'Cantidad', // Título del eje Y
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#444'
                }
            }
        }
    };
    
    var ventasPedidos = new ApexCharts(document.querySelector("#ventasPedidos"), options);
    ventasPedidos.render();

});