@extends('template.template')
@section('contenido')
@php
    use Carbon\Carbon;
@endphp
  <section class="content-header">
    <div>
      <form method="get" action="" id="autoSubmitForm">
      <div class="row">
        <div class="form-group col-lg-2">
          <label for="camaronera">Camaronera {{request('camaronera')}}</label>
          <select class="form-control" id="camaronera" name="camaronera" onchange="document.getElementById('autoSubmitForm').submit()">
            @foreach ($camaroneras as $item)
              <option value="{{ $item->camaronera->id }}" @selected($item->camaronera->id == request('camaronera'))>
                {{ $item->camaronera->nombre }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="piscina">Piscinas</label>
          <select class="form-control" id="piscina" name="piscina" onchange="document.getElementById('autoSubmitForm').submit()">
            <option value="">
              Todas
            </option>
            @foreach ($piscinas as $item)
              <option value="{{ $item->id }}" @selected($item->id == request('piscina'))>
                {{ $item->nombre }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="mes">Mes{{$request->mes}}</label>
          <select class="form-control" id="mes" name="mes" onchange="document.getElementById('autoSubmitForm').submit()">
            @foreach ($meses as $item)
              <option value="{{ $item['id'] }}" @selected($item['id'] == $request->mes)>
                {{ $item['nombre'] }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="anio">Año</label>
          <select class="form-control" id="anio" name="anio" onchange="document.getElementById('autoSubmitForm').submit()">
            @foreach ($anios as $item)
              <option value="{{ $item }}" @selected($item == $request->anio)>
                {{ $item }}
              </option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
  </section>

  <div class="table-responsive">
    <table class="table table-head-fixed text-nowrap table-bordered" id="grid" >
      <thead>
          <tr>
            <th>Peso Siembra Prom</th>
            <th>Densidad Transferencia</th>
            <th>Días</th>
            <th>Alim Prom Día</th>
            <th>Lbs Pescadas</th>
            <th>Lbs/Día</th>
            <th>Lbs. Pescadas</th>
            <th>Lbs/Ha Totales</th>
            <th>Fca</th>
            <th>Sobrevicencia</th>
          </tr>
      </thead>
      <tbody class="text-center">
        <tr>
          <td>{{ number_format((collect($items)->avg(fn($item) => $item->produccion->densidad ?? 0)), 2) }}</td>
          <td>{{ number_format((collect($items)->avg(fn($item) => $item->produccion->peso_transferencia ?? 0)), 2) }}</td>
          <td>{{ number_format((collect($items)->avg(fn($item) => $item->produccion->dias_ciclo ?? 0)), 2) }}</td>
          <td>{{ number_format(collect($items)->avg(fn($item) => ($item->alimento_acumulado / $item->num_dia) ?? 0), 2) }}</td>
          <td>{{ number_format(collect($items)->avg(fn($item) => $item->peso_real ?? 0), 2) }}</td>
          <td>{{ number_format(collect($items)->avg(fn($item) => ($item->peso_real / $item->num_dia) ?? 0), 2) }}</td>
          <td>{{ number_format((collect($items)->avg(fn($item) => $item->produccion->peso_pesca ?? 0)), 2) }}</td>
          @php
            $avgPesoPesca = collect($items)->avg(fn($item) => $item->produccion->peso_pesca ?? 0);
            $avgAreaPiscina = collect($items)->avg(fn($item) => $item->produccion->piscina->area_ha ?? 0);
            $resultado = $avgAreaPiscina > 0 ? $avgPesoPesca / $avgAreaPiscina : 0;
          @endphp
          <td>{{ number_format($resultado, 2) }}</td>
          <td>{{ number_format(collect($items)->avg(fn($item) => $item->fca ?? 0), 2) }}</td>
          <td>{{ number_format(collect($items)->avg(fn($item) => $item->supervivencia ?? 0), 2) }}%</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="table-responsive">
    <table class="table table-head-fixed text-nowrap table-bordered" id="grid" >
      <thead>
        <tr class="text-uppercase">
          <th data-type="number" style="background-color: #429dff">#PS <i class="fas"></i></th>
          <th data-type="string" style="background-color: #429dff">Sem. <i class="fas"></i></th>
          <th data-type="string" style="background-color: #429dff">Tipo Bal <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ffdd79">HA <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ffdd79">Fecha <br> Siembra <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ffdd79" onclick="sortGrid(3, 'number')">Días <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ffdd79">Peso<br>Transf <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ffdd79">Dens<br>Siembra <i class="fas"></i></th>
          <th data-type="number" style="background-color: #4fb17887">Alim<br>Acum <i class="fas"></i></th>
          <th data-type="number" style="background-color: #4fb17887">Alim<br>Prom <i class="fas"></i></th>
          <th data-type="number" style="background-color: #4fb17887">Peso<br>Cosecha <i class="fas"></i></th>
          <th data-type="number" style="background-color: #4fb17887">Fecha<br>Cosecha <i class="fas"></i></th>
          <th data-type="string" style="background-color: #4fb17887">Increm <i class="fas"></i></th>
          <th data-type="number" style="background-color: #4fb17887">Inc. Prom.<br>3sem <i class="fas"></i></th>
          <th data-type="number" style="background-color: #4fb17887">Kg/ha<br>prom <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ff7878a6">FCA <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ff7878a6">% SOB. <i class="fas"></i></th>
          <th data-type="number" style="background-color: #ff7878a6">Raleo <br> Acum. <i class="fas"></i></th>
        </tr>
      </thead>
      <tbody style="font-size: 20px; font-weight: bold;">
        @foreach ($items as $item)
          @php
            $proyecto = $proyectoItems->where('id_produccion', $item->id_produccion)->where('num_dia', $item->num_dia)->first();
          @endphp
          <tr onclick="selectRow(this)" aria-label="{{ $item->mortalidad ?? 0 }}">
            <td style="background-color: #d8e5f3">{{ $item->produccion->piscina->numero }}<a href="{{ url('producciones/'.$item->id_produccion) }}"><i class="fas fa-sign-in-alt fs-5"></i></a></td>
            <td style="background-color: #d8e5f3">{{ Carbon::parse($item->fecha)->weekOfYear }}</td>
            <td style="background-color: #d8e5f3">{{ $item->balanceado->nombre }}</td>
            <td style="background-color: #fff7dd">{{ $item->produccion->piscina->area_ha }}</td>
            <td style="background-color: #fff7dd">
              {{ date('d M Y', strtotime($item->produccion->fecha)) }}
            </td>
            <td style="background-color: #fff7dd" class="sortable">{{ $item->num_dia }}</td>
            <td style="background-color: #fff7dd">{{ $item->produccion->peso_transferencia }}</td>
            <td style="background-color: #fff7dd">{{ $item->produccion->densidad }}</td>
            <td style="background-color: #ceffe287" class=""> {{ $item->alimento_acumulado }} Lbs</td>
            <td style="background-color: #ceffe287" class=""> {{ number_format(($item->alimento_acumulado / $item->num_dia), 2) }}</td>
            <td style="background-color: #ceffe287" class=""> {{ $item->peso_real }}</td>
            <td style="background-color: #ceffe287">
              {{ date('d M Y', strtotime($item->fecha)) }}
            </td>
            {{-- <td>x</td> --}}
            <td style="background-color: #ceffe287">{{ $item->peso_real_anterior }}</td>
            <td style="background-color: #ceffe287" class="{{ $item->inc3sem < $item->peso_real_anterior ? 'text-success' : 'text-danger' }}">
                {{ number_format($item->inc3sem, 2) }}
                @if ($item->inc3sem < $item->peso_real_anterior)
                    <span>&uarr;</span>
                @elseif ($item->inc3sem > $item->peso_real_anterior)
                    <span>&darr;</span>
                @endif
            </td>
            <td style="background-color: #ceffe287">{{ number_format($item->alimento/$item->produccion->piscina->area_ha, 2) }}</td>
            {{-- <td>{{ $proyecto->biomasa }}</td> --}}
            <!-- aqui se resta con nuevo rpoy d -->
            <td style="background-color: #ffe3e3a6">{{ $item->fca }}</td>
            <td style="background-color: #ffe3e3a6">{{ $item->supervivencia }}%</td>
            <td style="background-color: #ffe3e3a6">{{ $item->raleada }} BM</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection