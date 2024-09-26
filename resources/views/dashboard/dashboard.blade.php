@extends('template.template')
@section('contenido')
    @php
        use Carbon\Carbon;
    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
      {{--  
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Resumen Producciones</h1>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                      <li class="breadcrumb-item active">Dashboard</li>
                  </ol>
              </div>
          </div>
      </div>
      --}}
      <div>
        @php
          // Obtener el ID de la primera camaronera
          // Obtener el ID de la camaronera de la solicitud, si existe
          $idCamaroneraRequest = request('camaronera');
          $idCamaroneraDefault = $camaronerasUser ? $camaronerasUser->first()->camaronera->id : null;
    
          // Obtener la fecha de la solicitud, si existe, de lo contrario usar la fecha actual
          $fechaRequest = request('fecha') ? request('fecha') : date('Y-m-d');
          $tablaRequest = request('tabla');
        @endphp
        <form method="get" action="" id="autoSubmitForm">
          <div class="row">
            <div class="form-group col-lg-2">
              <label for="camaronera">Camaronera</label>
              <select class="form-control" id="camaronera" name="camaronera" onchange="document.getElementById('autoSubmitForm').submit()">
                @foreach ($camaronerasUser as $item)
                  <option value="{{ $item->camaronera->id }}"
                      @if($idCamaroneraRequest)
                          {{ $item->camaronera->id == $idCamaroneraRequest ? 'selected' : '' }}
                      @else
                          {{ $item->camaronera->id == $idCamaroneraDefault ? 'selected' : '' }}
                      @endif>
                      {{ $item->camaronera->nombre }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-lg-2">
              <label for="camaronera">Tabla BW</label>
              <select class="form-control" id="tabla" name="tabla" onchange="document.getElementById('autoSubmitForm').submit()">
                <option value="">
                  Todas
                </option>
                <option value="ta1" {{ 'ta1' == $tablaRequest ? 'selected' : '' }}>
                  TA1
                </option>
                <option value="ta2" {{ 'ta2' == $tablaRequest ? 'selected' : '' }}>
                  TA2
                </option>
                <option value="ta3" {{ 'ta3' == $tablaRequest ? 'selected' : '' }}>
                  TA3
                </option>
                <option value="ta4" {{ 'ta4' == $tablaRequest ? 'selected' : '' }}>
                  TA4
                </option>
                <option value="ta5" {{ 'ta5' == $tablaRequest ? 'selected' : '' }}>
                  TA5
                </option>
                <option value="ta6" {{ 'ta6' == $tablaRequest ? 'selected' : '' }}>
                  TA6
                </option>
                <option value="ta7" {{ 'ta7' == $tablaRequest ? 'selected' : '' }}>
                  TA7
                </option>
              </select>
            </div>
            <div class="form-group col-lg-2">
              <label for="fecha">Fecha</label>
              <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $fechaRequest }}" onchange="document.getElementById('autoSubmitForm').submit()">
            </div>
          </div>
        </form>        
      </div>
    </section>
    @php
      $producciones_json = json_encode($produccionesItems);
      $items_json = json_encode($items);
    @endphp
    <!-- Botón para generar el PDF -->
    <section class="content">
      @php
          $items = $items;
          $itemAnteriores = $itemAnteriores;
          $proyectoItems = $proyectoItems;
          $produccionesItems = $produccionesItems;
      @endphp

      <!-- Formulario para enviar los datos al controlador -->
      <form id="pdfForm" action="{{ url('/resumen') }}" method="POST" style="display:none;">
          @csrf
          <input type="hidden" name="items" value="{{ json_encode($items) }}">
          <input type="hidden" name="itemAnteriores" value="{{ json_encode($itemAnteriores) }}">
          <input type="hidden" name="proyectoItems" value="{{ json_encode($proyectoItems) }}">
          <input type="hidden" name="produccionesItems" value="{{ json_encode($produccionesItems) }}">
      </form>
      <a class="btn btn-primary" href="#" id="descargarPdf">Descargar PDF</a>

      @include('componentes.graficosConsumo')

      <div class="row">
        <div class="col-md-2">
          <div id="pesotransf" class=""></div>
        </div>
        <div class="col-md-2">
          <div id="denstransf" class=""></div>
        </div>
        <div class="col-md-2">
          <div id="increm" class=""></div>
        </div>
        <div class="col-md-2">
          <div id="sobrevivencia" class=""></div>
        </div>
        <div class="col-md-2">
          <div id="biomasa" class=""></div>
        </div>
        <div class="col-md-2">
          <div id="densidadprom" class=""></div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div>
            <label>
              <input type="checkbox" id="filtroMortalidad"> Mostrar solo mortalidad mayor a 8
            </label>
          </div>
          <div class="card">
            <div class="card-body table-responsive p-0" id="miDiv">
              <table class="table table-head-fixed text-nowrap table-bordered" id="grid" >
                <thead>
                  <tr class="text-uppercase">
                    <th data-type="number" style="background-color: #429dff">#PS <i class="fas"></i></th>
                    <th style="background-color: #429dff"><i class="fas fa-chart-bar"></i></th>
                    <th data-type="string" style="background-color: #429dff">Sem. <i class="fas"></i></th>
                    <th data-type="string" style="background-color: #429dff">Tipo Bal <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ffdd79">ha <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ffdd79" onclick="sortGrid(3, 'number')">Días <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ffdd79">Peso<br>Transf <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ffdd79">Dens<br>Siembra <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #4fb17887">Peso<br>Act <i class="fas"></i></th>
                    <th data-type="string" style="background-color: #4fb17887">Increm <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #4fb17887">Inc. Prom.<br>3sem <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #4fb17887">Kg/ha<br>prom <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #4fb17887">ind/M2 M <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #4fb17887">Alerta <br> Alim <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">Dens<br>BIO <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">Dens<br>ADM <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">Dens<br>ATA <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">Dens <br> Proy <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">FCA <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">FCA <br>Proy <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">Desvio <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">% SOB. <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">Lbs/ha <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">lbs/total <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">raleo <i class="fas"></i></th>
                    <th data-type="number" style="background-color: #ff7878a6">mortalidad <i class="fas"></i></th>
                  </tr>
                </thead>
                <tbody style="font-size: 20px; font-weight: bold;">
                  @foreach ($items as $item)
                    @php
                      $proyecto = $proyectoItems->where('id_produccion', $item->id_produccion)->where('num_dia', $item->num_dia)->first();
                      $clasePeso = $item->peso_real > $proyecto->peso_proyecto ? 'text-success' : 'text-danger';
                      $iconoPeso = $item->peso_real > $proyecto->peso_proyecto ? 'fas fa-check' : 'fas fa-arrow-up';
                      //$anterior = $itemAnteriores->where('id_produccion', $item->id_produccion)->first();
                    @endphp
                    <tr onclick="selectRow(this)" aria-label="{{ $item->mortalidad ?? 0 }}">
                      <td>{{ $item->produccion->piscina->numero }}<a href="{{ url('producciones/'.$item->id_produccion) }}"><i class="fas fa-sign-in-alt fs-5"></i></a></td>
                      <td>
                        <button type="button" class="btn btn-link p-0" onclick="openModal('{{ $item->id_produccion }}')">
                          <i class="fas fa-chart-bar"></i>
                        </button>
                      </td>
                      <td>{{ Carbon::parse($item->fecha)->weekOfYear }}</td>
                      <td>{{ $item->balanceado->nombre }}</td>
                      <td>{{ $item->produccion->piscina->area_ha }}</td>
                      <td class="sortable">{{ $item->num_dia }}</td>
                      <td>{{ $item->produccion->peso_transferencia }}</td>
                      <td>{{ $item->produccion->densidad }}</td>
                      <td class="{{ $clasePeso }}"> {{ $item->peso_real }} <i class="{{ $iconoPeso }}"></i></td>
                      {{-- <td>x</td> --}}
                      <td>{{ $item->peso_real_anterior }}</td>
                      <td class="{{ $item->inc3sem < $item->peso_real_anterior ? 'text-success' : 'text-danger' }}">
                          {{ number_format($item->inc3sem, 2) }}
                          @if ($item->inc3sem < $item->peso_real_anterior)
                              <span>&uarr;</span> <!-- Flecha hacia arriba -->
                          @elseif ($item->inc3sem > $item->peso_real_anterior)
                              <span>&darr;</span> <!-- Flecha hacia abajo -->
                          @endif
                      </td>
                      <td>{{ number_format($item->alimento/$item->produccion->piscina->area_ha, 2) }}</td>
                      <td>{{ $item->densidad_consumo }}</td>
                      @if ($proyecto->alimento_dia != 0)
                        @php
                          $diferencia = (($item->alimento - $proyecto->alimento_dia) / $proyecto->alimento_dia) * 100;
                          $clase = $diferencia < 0 ? 'text-danger' : 'text-success';
                          $icono = $diferencia < 0 ? 'fa-arrow-up' : 'fa-check';
                        @endphp
                        <td class="{{ $clase }}">
                            {{ number_format($diferencia, 2) }}%
                            <i class="fas {{ $icono }}"></i>
                        </td>
                      @else
                        <td>N/A</td>
                      @endif
                      <td>{{ $item->densidad_actual }}</td>
                      <td>{{ $item->densidad_oficina }}</td>
                      <td>{{ $item->densidad_muestreo }}</td>
                      {{-- <td>{{ $proyecto->biomasa }}</td> --}}
                      <!-- aqui se resta con nuevo rpoy d -->
                      <td>{{ $proyecto->densidad }}</td>
                      <td>{{ $item->fca }}</td>
                      <td>{{ $proyecto->fca }}</td>
                      <td class="{{ $proyecto->densidad - $item->densidad_actual > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($proyecto->densidad - $item->densidad_actual, 2) }}</td>
                      <td>{{ $item->supervivencia }}%</td>
                      <td>{{ $item->biomasa_actual }}</td>
                      <td>{{ $item->alimento }}</td>
                      <td>{{ $item->densidad_raleada }}</td>
                      <td class="mortalidad-cell {{ $item->mortalidad > 8 ? 'text-danger' : 'text-success' }}" data-mortalidad="{{ $item->mortalidad }}">{{ $item->mortalidad > 8 ? 'Mortalidad' : 'Normal' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Modal fuera del foreach -->
            <div class="modal fade" id="produccionMOdal" tabindex="-1" aria-labelledby="produccionMOdalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl custom-modal">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="produccionMOdalLabel">Detalles de la Producción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </div>
                  <div class="modal-body">
                    <!-- Contenido del modal -->
                    <p id="modalContent">Metricas.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar cambios</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
      <style>
        /* Estilo para el modal con altura del 70% de la pantalla */
        .custom-modal .modal-dialog {
          height: 70vh; /* 70% de la altura de la ventana del usuario */
          margin: auto;
        }
      
        .custom-modal .modal-content {
          height: 100%;
          display: flex;
          flex-direction: column;
        }
      
        .custom-modal .modal-body {
          overflow-y: auto; /* Permite el scroll si el contenido es más largo que la altura del modal */
        }

      .selected-row {
          background-color: rgb(179, 179, 179);
      }
      .ocultar-fila {
          display: none;
      }
      
      </style>
    </section>

    <script>
      function selectRow(row) {
        // Obtener todas las filas de la tabla
        var rows = document.querySelectorAll('tbody tr');

        // Quitar la clase 'selected-row' de todas las filas
        rows.forEach(function(r) {
            r.classList.remove('selected-row');
        });

        // Añadir la clase 'selected-row' a la fila seleccionada
        row.classList.add('selected-row');
      }

      document.addEventListener('DOMContentLoaded', function() {
    var filtroMortalidad = document.getElementById('filtroMortalidad');
    
    filtroMortalidad.addEventListener('change', function() {
        var filtroActivo = this.checked; // Estado del checkbox
        console.log("Filtro activo:", filtroActivo); // Depuración
        
        var filas = document.querySelectorAll('#grid tbody tr');
        
        filas.forEach(function(fila) {
            var mortalidadStr = fila.getAttribute('aria-label');
            console.log("Valor original de aria-label:", mortalidadStr); // Depuración
            
            var mortalidad = parseFloat(mortalidadStr);
            console.log("Mortalidad convertida:", mortalidad); // Depuración

            if (!isNaN(mortalidad)) {
                if (filtroActivo) {
                    // Si el filtro está activo, añade la clase si mortalidad <= 8
                    if (mortalidad > 8) {
                        fila.classList.remove('ocultar-fila');
                    } else {
                        fila.classList.add('ocultar-fila');
                    }
                } else {
                    // Si el filtro no está activo, elimina la clase para mostrar todas las filas
                    fila.classList.remove('ocultar-fila');
                }
            } else {
                // Si mortalidad no es un número válido, asegura que la fila sea visible
                fila.classList.remove('ocultar-fila');
            }
        });
    });
});


    </script>

    <script>
      function openModal(productionId) {
        // Muestra el modal
        var modal = new bootstrap.Modal(document.getElementById('produccionMOdal'));
        modal.show();
    
        // Imprime un mensaje en la consola
        console.log('se ejecuto');
    
        // Muestra un loader mientras se cargan los datos
        document.getElementById('modalContent').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden"></span></div><p>Cargando datos...</p></div>';
    
        // Genera la URL usando la función 'url()' de Laravel
        const url = `{{ url('graficosProd') }}/${productionId}`;
    
        // Ejecuta la solicitud AJAX GET a la ruta generada
        fetch(url)
          .then(response => {
            if (!response.ok) {
              throw new Error('Error al cargar los datos');
            }
            return response.json(); // Asegúrate de que la respuesta se maneje como JSON
          })
          .then(data => {
            // Actualiza las variables globales con los datos recibidos
            window.produccionItems = data.produccionItems;
            window.proyectoItems = data.proyectoItems;
    
            // Reemplaza el loader con el contenido del modal
            document.getElementById('modalContent').innerHTML = `
              <div>
                <div id="chartContainer" class="row">
                  <div class="col-md-6 col-lg-12">
                    <canvas id="balanceado" width="400" height="200"></canvas>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <canvas id="comparativeChart" width="400" height="200"></canvas>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <canvas id="growthChart" width="400" height="200"></canvas>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <canvas id="densityChart" width="400" height="200"></canvas>    
                  </div>
                </div>
              </div>
            `;
    
            // Carga y ejecuta el script de gráficos después de que el contenido del modal se haya actualizado
            const script = document.createElement('script');
            script.src = '{{ asset('public/assets/js/graficosprod.js') }}'; // Cambia la ruta según corresponda
            document.body.appendChild(script);
            
            // Opcional: Puedes escuchar cuando el script se carga y ejecutar la función de actualización de gráficos si es necesario
            script.onload = () => {
              // Llama a la función de actualización de gráficos si es necesario
              if (typeof updateCharts === 'function') {
                updateCharts();
              }
            };
          })
          .catch(error => {
            // Maneja el error y muestra un mensaje de error en lugar del loader
            console.error('Error al cargar los datos:', error);
            document.getElementById('modalContent').innerHTML = 'Error al cargar los datos';
          });
      }
    </script>

    <script>
        document.getElementById('descargarPdf').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('pdfForm').submit();
        });
    </script>

    <script>
      const grid = document.getElementById('grid');
  
      grid.addEventListener('click', function(e) {
        if (e.target.tagName !== 'TH') return;
  
        const th = e.target;
        // Si TH, entonces ordena
        sortGrid(th.cellIndex, th.dataset.type, th);
      });
  
      function sortGrid(colNum, type, th) {
        const tbody = grid.querySelector('tbody');
        const rowsArray = Array.from(tbody.rows);
        const isAscending = th.dataset.order === 'asc' || !th.dataset.order; // Determina si el orden es ascendente o descendente
        th.dataset.order = isAscending ? 'desc' : 'asc'; // Alterna la dirección de ordenamiento
  
        // Comparador basado en el tipo de datos
        let compare;
        switch (type) {
          case 'number':
            compare = (rowA, rowB) => (parseFloat(rowA.cells[colNum].innerHTML) - parseFloat(rowB.cells[colNum].innerHTML)) * (isAscending ? 1 : -1);
            break;
          case 'string':
            compare = (rowA, rowB) => rowA.cells[colNum].innerHTML.localeCompare(rowB.cells[colNum].innerHTML) * (isAscending ? 1 : -1);
            break;
        }
  
        // Ordenar las filas
        rowsArray.sort(compare);
  
        // Reinsertar las filas ordenadas en el tbody
        tbody.append(...rowsArray);
  
        // Quitar iconos de todas las cabeceras
        grid.querySelectorAll('th i').forEach(icon => {
          icon.className = 'fas';
          icon.classList.remove('text-primary');
        });
  
        // Agregar el icono de orden en la cabecera actual
        const icon = th.querySelector('i');
        icon.className = isAscending ? 'fas fa-arrow-up text-primary' : 'fas fa-arrow-down text-primary';
      }
    </script>
  

    <!-- Incluir la biblioteca html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <!-- Script para generar el PDF -->
    <script>
      //document.getElementById('descargarPdf').addEventListener('click', () => {
      //    // Seleccionar el div que quieres convertir a PDF
      //    var elemento = document.getElementById('miDiv');
//
      //    // Opciones para html2pdf
      //    var opciones = {
      //        margin: 0.1, // Márgenes reducidos a 0.25 pulgadas en todos los lados
      //        filename: 'documento.pdf',
      //        image: { type: 'jpeg', quality: 0.98 },
      //        html2canvas: { scale: 2 },
      //        jsPDF: { unit: 'in', format: 'A4', orientation: 'landscape' } // Cambiar a orientación horizontal
      //    };
//
      //    // Generar el PDF
      //    html2pdf().set(opciones).from(elemento).save();
      //});
  </script>
  <script>
      // Convertir el JSON PHP a un array de JavaScript
      var producciones = <?php echo $producciones_json; ?>;
      
      // Verificar los datos de producciones
      console.log("Producciones:", producciones);

      // Inicializar variables
      var total = 0;
      var count = 0;

      // Asegurarse de que todos los valores sean números y filtrar valores no numéricos
      producciones = producciones.map(item => {
          return {peso_transferencia: Number(item.peso_transferencia)};
      }).filter(item => !isNaN(item.peso_transferencia));

      // Obtener el valor máximo y mínimo
      var max = Math.max(...producciones.map(item => item.peso_transferencia));
      var min = Math.min(...producciones.map(item => item.peso_transferencia));

      // Calcular el total de los pesos de transferencia
      producciones.forEach(item => {
          total += item.peso_transferencia;
          count++;
      });

      // Calcular el promedio
      var value = total / count;

      // Verificar los valores calculados
      console.log("Max:", max);
      console.log("Min:", min);
      console.log("Promedio:", value);

      // Crear el gráfico JustGage
      var g = new JustGage({
        id: "pesotransf",
        value: value,
        min: min,
        max: max,
        title: "Peso Transferencia Promedio",
        decimals: 2, // Mostrar dos decimales
      });
  </script>
  <script>
      // Convertir el JSON PHP a un array de JavaScript
      var producciones = <?php echo $producciones_json; ?>;
      
      // Verificar los datos de producciones
      console.log("Producciones:", producciones);

      // Inicializar variables
      var total = 0;
      var count = 0;

      // Asegurarse de que todos los valores sean números y filtrar valores no numéricos
      producciones = producciones.map(item => {
          return {densidad: Number(item.densidad)};
      }).filter(item => !isNaN(item.densidad));

      // Obtener el valor máximo y mínimo
      var max = Math.max(...producciones.map(item => item.densidad));
      var min = Math.min(...producciones.map(item => item.densidad));

      // Calcular el total de los pesos de transferencia
      producciones.forEach(item => {
          total += item.densidad;
          count++;
      });

      // Calcular el promedio
      var value = total / count;

      // Verificar los valores calculados
      console.log("Max:", max);
      console.log("Min:", min);
      console.log("Promedio:", value);


      // Crear el gráfico JustGage
      var g = new JustGage({
        id: "denstransf",
        value: value,
        min: min,
        max: max,
        title: "Densidad Transferencia Promedio",
        decimals: 2, // Mostrar dos decimales
      });
  </script>
  <script>
      // Convertir el JSON PHP a un array de JavaScript
      var items = <?php echo $items_json; ?>;
      console.log("items", items)
      // Inicializar variables
      var total = 0;
      var count = 0;

      // Asegurarse de que todos los valores sean números y filtrar valores no numéricos
      items = items.map(item => {
          return {peso_real_anterior: Number(item.peso_real_anterior)};
      }).filter(item => !isNaN(item.peso_real_anterior));

      // Obtener el valor máximo y mínimo
      var max = Math.max(...items.map(item => item.peso_real_anterior));
      var min = Math.min(...items.map(item => item.peso_real_anterior));

      // Calcular el total de los pesos de transferencia
      items.forEach(item => {
          total += item.peso_real_anterior;
          count++;
      });

      // Calcular el promedio
      var value = total / count;

      // Verificar los valores calculados
      console.log("Max:", max);
      console.log("Min:", min);
      console.log("Promedio:", value);


      // Crear el gráfico JustGage
      var g = new JustGage({
        id: "increm",
        value: value,
        min: min,
        max: max,
        title: "Prom. Increm.",
        decimals: 2, // Mostrar dos decimales
      });
  </script>
  <script>
      // Convertir el JSON PHP a un array de JavaScript
      var items = <?php echo $items_json; ?>;
      console.log("items", items)
      // Inicializar variables
      var total = 0;
      var count = 0;

      // Asegurarse de que todos los valores sean números y filtrar valores no numéricos
      items = items.map(item => {
          return {supervivencia: Number(item.supervivencia)};
      }).filter(item => !isNaN(item.supervivencia));

      // Obtener el valor máximo y mínimo
      var max = Math.max(...items.map(item => item.supervivencia));
      var min = Math.min(...items.map(item => item.supervivencia));

      // Calcular el total de los pesos de transferencia
      items.forEach(item => {
          total += item.supervivencia;
          count++;
      });

      // Calcular el promedio
      var value = total / count;

      // Verificar los valores calculados
      console.log("Max:", max);
      console.log("Min:", min);
      console.log("Promedio:", value);


      // Crear el gráfico JustGage
      var g = new JustGage({
        id: "sobrevivencia",
        value: value,
        min: min,
        max: max,
        title: "Sob Prom,",
        decimals: 2, // Mostrar dos decimales
      });
  </script>
  <script>
      // Convertir el JSON PHP a un array de JavaScript
      var items = <?php echo $items_json; ?>;
      console.log("items", items)
      // Inicializar variables
      var total = 0;
      var count = 0;

      // Asegurarse de que todos los valores sean números y filtrar valores no numéricos
      items = items.map(item => {
          return {biomasa_actual: Number(item.biomasa_actual)};
      }).filter(item => !isNaN(item.biomasa_actual));

      // Obtener el valor máximo y mínimo
      var max = Math.max(...items.map(item => item.biomasa_actual));
      var min = Math.min(...items.map(item => item.biomasa_actual));

      // Calcular el total de los pesos de transferencia
      items.forEach(item => {
          total += item.biomasa_actual;
          count++;
      });

      // Calcular el promedio
      var value = total / count;

      // Verificar los valores calculados
      console.log("Max:", max);
      console.log("Min:", min);
      console.log("Promedio:", value);


      // Crear el gráfico JustGage
      var g = new JustGage({
        id: "biomasa",
        value: value,
        min: min,
        max: max,
        title: "BM Prom,",
        decimals: 2, // Mostrar dos decimales
      });
  </script>
  <script>
    // Convertir el JSON PHP a un array de JavaScript
    var items = <?php echo $items_json; ?>;
    console.log("items", items)
    // Inicializar variables
    var total = 0;
    var count = 0;

    // Asegurarse de que todos los valores sean números y filtrar valores no numéricos
    items = items.map(item => {
        return {densidad_actual: Number(item.densidad_actual)};
    }).filter(item => !isNaN(item.densidad_actual));

    // Obtener el valor máximo y mínimo
    var max = Math.max(...items.map(item => item.densidad_actual));
    var min = Math.min(...items.map(item => item.densidad_actual));

    // Calcular el total de los pesos de transferencia
    items.forEach(item => {
        total += item.densidad_actual;
        count++;
    });

    // Calcular el promedio
    var value = total / count;

    // Verificar los valores calculados
    console.log("Max:", max);
    console.log("Min:", min);
    console.log("Promedio:", value);


    // Crear el gráfico JustGage
    var g = new JustGage({
      id: "densidadprom",
      value: value,
      min: min,
      max: max,
      title: "Dens Prom,",
      decimals: 2, // Mostrar dos decimales
    });
  </script>
  
@endsection