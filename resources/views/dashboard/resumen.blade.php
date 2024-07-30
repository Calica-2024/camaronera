<!DOCTYPE html>
<html>
<head>
    <title>Resumen</title>
    <style>
        @page{
            margin: 0.3cm 0.3cm 0.3cm;
        }
        .thead{
            font-size: 10px;
        }
        .mayusculas{
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse; /* Asegura que los bordes se fusionen */
        }
        th, td {
            border: 1px solid black; /* Borde de 1px para todas las celdas */
            padding: 5px; /* Espacio dentro de las celdas */
            text-align: left; /* Alineación del texto en las celdas */
        }
        thead {
            background-color: #f2f2f2; /* Color de fondo para el encabezado */
        }
    </style>
</head>
<body>
    @php
        use Carbon\Carbon;
    @endphp
    <div class="card-body table-responsive p-0" id="miDiv">
        <table class="table table-head-fixed text-nowrap table-bordered mayusculas" id="grid" >
            <thead class="thead">
                <tr>
                    <th data-type="number">#PS <i class="fas"></i></th>
                    <th data-type="string">Sem. <i class="fas"></i></th>
                    <th data-type="string">Fecha <i class="fas"></i></th>
                    <th data-type="string">Tipo Bal <i class="fas"></i></th>
                    <th data-type="number">ha <i class="fas"></i></th>
                    <th data-type="number" onclick="sortGrid(3, 'number')">Días <i class="fas"></i></th>
                    <th data-type="number">Peso<br>Transf <i class="fas"></i></th>
                    <th data-type="string">Peso Act <i class="fas"></i></th>
                    <th data-type="string">Increm <i class="fas"></i></th>
                    <th data-type="string">Inc. Prom.<br>3sem <i class="fas"></i></th>
                    <th data-type="number">Kg/ha<br>prom <i class="fas"></i></th>
                    <th data-type="number">ind/M2 M <i class="fas"></i></th>
                    <th data-type="string">Alerta <br> Alim <i class="fas"></i></th>
                    <th data-type="number">Dens<br>bio <i class="fas"></i></th>
                    <th data-type="number">Dens<br>ADM <i class="fas"></i></th>
                    <th data-type="number">Pobl. <i class="fas"></i></th>
                    <th data-type="number">Dens <br> Proy <i class="fas"></i></th>
                    <th data-type="number">Desvio <i class="fas"></i></th>
                    <th data-type="number">Lbs/ha <i class="fas"></i></th>
                    <th data-type="number">lbs/total <i class="fas"></i></th>
                    <th data-type="number">raleo <i class="fas"></i></th>
                    <th data-type="number">FCA <i class="fas"></i></th>
                    <th data-type="number">FCA <br>Proy <i class="fas"></i></th>
                </tr>
            </thead>
            <tbody class="thead">
                @foreach ($items as $item)
                @php
                    $proyecto = collect($proyectoItems)->where('id_produccion', $item['id_produccion'])->where('num_dia', $item['num_dia'])->first();
                    $colorPeso = $item['peso_real'] > $proyecto['peso_proyecto'] ? 'green' : 'red';
                    $iconoPeso = $item['peso_real'] > $proyecto['peso_proyecto'] ? 'fas fa-check' : 'fas fa-arrow-up';
                    //$anterior = $itemAnteriores->where('id_produccion', $item->id_produccion)->first();
                @endphp
                <tr>
                    <td style="font-size: 9px !important">{{ $item['produccion']['piscina']['nombre'] }}</td>
                    <td>{{ Carbon::parse($item['fecha'])->weekOfYear }}</td>
                    <td style="font-size: 10px !important">{{ $item['fecha'] }}</td>
                    <td style="font-size: 9px !important">{{ $item['balanceado']['nombre'] }}</td>
                    <td>{{ $item['produccion']['piscina']['area_ha'] }}</td>
                    <td>{{ $item['num_dia'] }}</td>
                    <td>{{ $item['produccion']['peso_transferencia'] }}</td>
                    <td style="color: {{ $colorPeso }}"> {{ $item['peso_real'] . '/' . $proyecto['peso_proyecto'] }}</td>
                    {{-- <td>x</td> --}}
                    <td>{{ $item['peso_real_anterior'] }}</td>
                    <td style="color: {{ $item['inc3sem'] < $item['peso_real_anterior'] ? 'green' : 'red' }}">
                        {{ number_format($item['inc3sem'], 2) }}
                    </td>
                    <td>{{ number_format($item['alimento']/$item['produccion']['piscina']['area_ha'], 2) }}</td>
                    <td>{{ $item['densidad_consumo'] }}</td>
                    <td>
                    @if ($proyecto['alimento_dia'] != 0)
                        @php
                        $diferencia = (($item['alimento'] - $proyecto['alimento_dia']) / $proyecto['alimento_dia']) * 100;
                        $color = $diferencia < 0 ? 'red' : 'green';
                        @endphp
                        <span style="color: {{ $color }}">
                        {{ number_format($diferencia, 2) }}%
                        </span>
                    @else
                        N/A
                    @endif
                    </td>
                    <td>{{ $item['densidad_actual'] }}</td>
                    <td>{{ $item['densidad_oficina'] }}</td>
                    <td>{{ $item['densidad_muestreo'] }}</td>
                    {{-- <td>{{ $proyecto->biomasa }}</td> --}}
                    <!-- aqui se resta con nuevo rpoy d -->
                    <td>{{ $proyecto['densidad'] }}</td>
                    <td style="color: {{ $proyecto['densidad'] - $item['densidad_actual'] > 0 ? 'red' : 'green' }}">{{ number_format($proyecto['densidad'] - $item['densidad_actual'], 2) }}</td>
                    <td>{{ $item['biomasa_actual'] }}</td>
                    <td>{{ $item['alimento'] }}</td>
                    <td>{{ $item['densidad_raleada'] }}</td>
                    <td>{{ $item['fca'] }}</td>
                    <td>{{ $proyecto['fca'] }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
