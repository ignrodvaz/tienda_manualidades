document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.row').forEach(row => {
        new Sortable(row, {
            group: 'shared', // Permite mover entre filas
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function (evt) {
                console.log('Elemento movido:', evt.item.id);
            },
        });
    });

    const optionsCategoria = {
        series: [25, 20, 15, 10, 10, 10, 5],
        chart: {
          width: 500,
          height: 500,
          type: 'pie',
        },
        labels: ['Pinturas', 'Telas', 'Papel', 'Herramientas', 'Hilos y lanas', 'Arcilla', 'Pegamentos'],
        legend: {
          position: 'right',
          offsetY: -20,
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 500,
              height: 500,
            },
            legend: {
              position: 'center',
              offsetY: -10,
            },
          },
        }],
      };
      
    const categoria = new ApexCharts(document.querySelector('#categoria'), optionsCategoria);
    categoria.render();
    

      // Supongamos que obtienes los datos desde tu backend en formato JSON
    const datosDesdeBackend = [
        { mes: 1, ventas: 1200 },
        { mes: 2, ventas: 1500 },
        { mes: 3, ventas: 1800 },
        { mes: 4, ventas: 2100 },
        { mes: 5, ventas: 1700 },
        { mes: 6, ventas: 2000 }
    ];

    const ventasMensuales = datosDesdeBackend.map(item => item.ventas);

        var optionsVentasMensuales = {
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
                    return val + "€";
                },
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],
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
                        return val + "€";
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


    var ventas = new ApexCharts(document.querySelector("#ventas"), optionsVentasMensuales);
    ventas.render();

    var optionsVentasPedidos = {
        series: [{
            name: 'Ventas', // Serie 1: Ventas
            data: [1200, 1500, 1800, 2100, 1700, 2000, 2300] // Datos de ventas diarias/semanales
        }, {
            name: 'Pedidos', // Serie 2: Pedidos
            data: [500, 700, 600, 800, 750, 900, 850] // Datos de pedidos diarios/semanales
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
                "2023-11-01T00:00:00.000Z", 
                "2023-12-01T00:00:00.000Z", 
                "2024-01-01T00:00:00.000Z", 
                "2024-02-01T00:00:00.000Z", 
                "2024-03-01T00:00:00.000Z", 
                "2024-04-01T00:00:00.000Z"
            ], // Fechas de ejemplo
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm' // Formato de la fecha en el tooltip
            },
        },
        colors: ['#36A2EB', '#FF6384'], // Colores personalizados para las series
        title: {
            text: 'Evolución de Ventas y Pedidos - Año 23/24', // Título del gráfico
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
    
    var ventasPedidos = new ApexCharts(document.querySelector("#ventasPedidos"), optionsVentasPedidos);
    ventasPedidos.render();

    var optionsCategoriaProducto = {
        series: [{
        name: 'Inflation',
        data: [2.3, 3.1, 4.0, 10.1, 4.0]
      }],
        chart: {
        height: 350,
        type: 'bar',
      },
      plotOptions: {
        bar: {
          borderRadius: 10,
          dataLabels: {
            position: 'top', // top, center, bottom
          },
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val + "%";
        },
        offsetY: -20,
        style: {
          fontSize: '12px',
          colors: ["#304758"]
        }
      },
      
      xaxis: {
        categories: ["Pinturas", "Algodón", "Papel", "Herramientas", "Arcilla"],
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
          show: false,
          formatter: function (val) {
            return val + "%";
          }
        }
      
      },
      title: {
        text: 'Categoria y Productos - Año 2023',
        floating: true,
        offsetY: 330,
        align: 'center',
        style: {
          color: '#444'
        }
      }
      };

      var chart = new ApexCharts(document.querySelector("#chart"), optionsCategoriaProducto);
      chart.render();

});