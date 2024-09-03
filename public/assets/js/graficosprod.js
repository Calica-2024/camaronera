// Suponiendo que las variables produccionItems y proyectoItems están disponibles globalmente
var produccionItems = window.produccionItems;
var proyectoItems = window.proyectoItems;

// Filtrar los elementos con día "domingo"
var registro = {
    produccion: {
        items: produccionItems.filter(item => item.dia === 'domingo')
    },
    balanceado: {
        items: produccionItems.filter(item => item.dia === 'domingo')
    }
};

// Preparar los datos para el gráfico de peso
var labels = [];
var produccionPesoData = [];
var proyectoPesoData = [];
var produccionCrecimientoData = [];
var proyectoCrecimientoData = [];
var produccionDensidadData = [];
var proyectoDensidadData = [];
var balanceadoReal = [];

registro.produccion.items.forEach(prodItem => {
    const projItem = registro.balanceado.items.find(proj => proj.fecha === prodItem.fecha);
    if (projItem) {
        labels.push(prodItem.fecha);
        produccionPesoData.push(prodItem.peso_real);
        balanceadoReal.push(projItem.alimento);
        produccionCrecimientoData.push(prodItem.peso_real_anterior);
        balanceadoReal.push(projItem.alimento);
        produccionDensidadData.push(prodItem.densidad_actual);
        balanceadoReal.push(projItem.alimento);
    }
});

// Crear el gráfico de peso
var ctxPeso = document.getElementById('comparativeChart').getContext('2d');
var comparativeChart = new Chart(ctxPeso, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Producción (Peso)',
            data: produccionPesoData,
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            fill: false
        },
        {
            label: 'Balanceado (kg)',
            data: balanceadoReal,
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            fill: false
        }]
    },
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Peso'
                }
            }
        }
    }
});

// Crear el gráfico de crecimiento
var ctxCrecimiento = document.getElementById('growthChart').getContext('2d');
var growthChart = new Chart(ctxCrecimiento, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Producción (Crecimiento)',
            data: produccionCrecimientoData,
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            fill: false
        },
        {
            label: 'Balanceado (kg)',
            data: balanceadoReal,
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            fill: false
        }]
    },
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Crecimiento'
                }
            }
        }
    }
});

// Crear el gráfico de densidad
var ctxDensidad = document.getElementById('densityChart').getContext('2d');
var densityChart = new Chart(ctxDensidad, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Producción (Densidad)',
            data: produccionDensidadData,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 1,
            fill: true
        },
        {
            label: 'Producción (Peso)',
            data: produccionPesoData,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderWidth: 1,
            fill: true
        }]
    },
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Densidad'
                }
            }
        }
    }
});

// Crear el gráfico de balanceado
var ctxBalanceado = document.getElementById('balanceado').getContext('2d');
var balanceado = new Chart(ctxBalanceado, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Balanceado (kg)',
            data: balanceadoReal,
            borderColor: 'rgb(157, 166, 0)',
            borderWidth: 1,
            fill: false, // Rellena el área bajo la línea
            backgroundColor: 'rgb(157, 166, 0)', // Color de relleno
            tension: 0 // Asegura que las líneas sean rectas
        }]
    },
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'KG'
                }
            }
        }
    }
});