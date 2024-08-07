// Suponiendo que las variables produccionItems y proyectoItems están disponibles globalmente
var produccionItems = window.produccionItems;
var proyectoItems = window.proyectoItems;

// Filtrar los elementos con día "domingo"
var registro = {
    produccion: {
        items: produccionItems.filter(item => item.dia === 'domingo')
    },
    proyecto: {
        items: proyectoItems.filter(item => item.dia === 'domingo')
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

registro.produccion.items.forEach(prodItem => {
    const projItem = registro.proyecto.items.find(proj => proj.fecha === prodItem.fecha);
    if (projItem) {
        labels.push(prodItem.fecha);
        produccionPesoData.push(prodItem.peso_real);
        proyectoPesoData.push(projItem.peso_proyecto);
        produccionCrecimientoData.push(prodItem.peso_real_anterior);
        proyectoCrecimientoData.push(projItem.crecimiento_lineal);
        produccionDensidadData.push(prodItem.densidad_actual);
        proyectoDensidadData.push(projItem.densidad);
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
            label: 'Proyecto (Peso)',
            data: proyectoPesoData,
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
            label: 'Proyecto (Crecimiento)',
            data: proyectoCrecimientoData,
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
            label: 'Proyecto (Densidad)',
            data: proyectoDensidadData,
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