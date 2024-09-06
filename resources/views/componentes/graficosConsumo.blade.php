
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
Consumo <i class="fas fa-chart-area"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Consumo de Balanceado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="piscinas">Piscinas:</label>
                <div id="piscinas">
                    @php
                        $sortedItems = $items->sortBy(function($item) {
                        return $item->produccion->piscina->numero;
                        });
                    @endphp
                    @foreach ($sortedItems as $item)
                        <button type="button" class="btn btn-primary m-1 piscina-btn" 
                                onclick="selectPiscina(this, {{ $item->id_produccion }}, '{{ $item->produccion->piscina->numero }}')">
                        Piscina {{ $item->produccion->piscina->numero }}
                        </button>
                    @endforeach
                </div>
                <div class="">
                    <canvas id="alimento" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variable global para el gráfico
    let alimentoChart = null;
    let selectedPiscinas = {}; // Objeto para almacenar las piscinas seleccionadas con sus datos

    // Función para inicializar el gráfico
    function initializeChart() {
        const ctxAlimento = document.getElementById('alimento').getContext('2d');
        
        // Inicializar el gráfico solo si no ha sido creado antes
        if (!alimentoChart) {
            alimentoChart = new Chart(ctxAlimento, {
                type: 'line',
                data: {
                    labels: [], // Etiquetas para el eje X (Fechas)
                    datasets: [] // Conjuntos de datos, se llenarán con la información de cada piscina seleccionada
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
        }
    }

    async function selectPiscina(button, itemId, numeroPiscina) {
        console.log('ID de item seleccionado:', itemId);
        console.log('Número de piscina mostrado:', numeroPiscina);

        // Alternar color entre azul y gris
        if (button.classList.contains('btn-primary')) {
            button.classList.remove('btn-primary');
            button.classList.add('btn-secondary');

            // Genera la URL usando la función 'url()' de Laravel directamente en Blade
            const url = `{{ url('alimentoPiscinas') }}/${itemId}`;

            // Verificar si los datos ya están cargados
            if (!selectedPiscinas[numeroPiscina]) {
                try {
                    // Ejecuta la solicitud AJAX GET a la ruta generada
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error('Error al cargar los datos');
                    }
                    const data = await response.json();
                    console.log('Datos recibidos:', data);

                    // Verifica si los datos recibidos son un array y contienen los campos esperados
                    if (Array.isArray(data) && data.every(item => item.alimento !== undefined && item.fecha !== undefined)) {
                        // Extraer fechas y alimentos de los datos recibidos
                        const fechas = data.map(item => item.fecha);
                        const alimentos = data.map(item => item.alimento);
            
                        // Inicializar el gráfico si no se ha creado
                        initializeChart();
            
                        // Agregar el dataset para la piscina seleccionada
                        selectedPiscinas[numeroPiscina] = {
                            label: `Piscina #${numeroPiscina}`,
                            data: alimentos,
                            borderColor: getRandomColor(), // Color aleatorio para cada serie
                            borderWidth: 1,
                            fill: false,
                            backgroundColor: getRandomColor(),
                            tension: 0,
                            fechas: fechas // Guardar fechas asociadas
                        };
                        // Agregar el nuevo dataset al gráfico
                        alimentoChart.data.datasets.push(selectedPiscinas[numeroPiscina]);
            
                        // Actualizar etiquetas (fechas) para que correspondan a las fechas de las piscinas seleccionadas
                        updateChartLabels();
            
                        // Actualizar el gráfico
                        alimentoChart.update();
                    } else {
                        console.error('Datos recibidos no tienen el formato esperado:', data);
                    }
                } catch (error) {
                    console.error('Error al obtener los datos:', error);
                }
            } else {
                // Si los datos ya están cargados, solo agregar el dataset al gráfico si no está ya en el gráfico
                if (!alimentoChart.data.datasets.find(dataset => dataset.label === `Piscina #${numeroPiscina}`)) {
                    alimentoChart.data.datasets.push(selectedPiscinas[numeroPiscina]);
                    updateChartLabels();
                    alimentoChart.update();
                }
            }
        } else {
            button.classList.remove('btn-secondary');
            button.classList.add('btn-primary');
    
            // Eliminar el dataset correspondiente al número de piscina deseleccionado
            const index = alimentoChart.data.datasets.findIndex(dataset => dataset.label === `Piscina #${numeroPiscina}`);
            if (index !== -1) {
            alimentoChart.data.datasets.splice(index, 1);
            delete selectedPiscinas[numeroPiscina];
    
            // Actualizar las etiquetas (fechas) después de eliminar un dataset
            updateChartLabels();
    
            // Actualizar el gráfico después de eliminar el dataset
            alimentoChart.update();
            }
        }
    }

    // Función para actualizar las etiquetas (fechas) en el gráfico
    function updateChartLabels() {
        // Obtener todas las fechas de los datasets seleccionados
        const allDates = [];
        for (const dataset of Object.values(selectedPiscinas)) {
            allDates.push(...dataset.fechas);
        }
    
        // Eliminar fechas duplicadas
        const uniqueDates = [...new Set(allDates)];
    
        // Asignar fechas únicas a las etiquetas del gráfico
        alimentoChart.data.labels = uniqueDates;
    }

    // Función para generar un color aleatorio
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Detectar el cierre del modal
    $('#exampleModal').on('hidden.bs.modal', function () {
        console.log('modal cerrado');
        // Deseleccionar todos los botones
        document.querySelectorAll('#piscinas .piscina-btn').forEach(function(button) {
            button.classList.remove('btn-secondary');
            button.classList.add('btn-primary');
        });
    
        // Limpiar el objeto de piscinas seleccionadas
        selectedPiscinas = {};
        
        // Limpiar el gráfico al cerrar el modal
        if (alimentoChart) {
            alimentoChart.data.labels = [];
            alimentoChart.data.datasets = []; // Limpiar todos los datasets
            alimentoChart.update();
        }
    });
</script>