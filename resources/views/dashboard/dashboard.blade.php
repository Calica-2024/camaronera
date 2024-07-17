@extends('template.template')
@section('contenido')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Resumen Producciones</h1>
              </div>
              <div class="col-sm-6">
                  {{-- 
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                      <li class="breadcrumb-item active">Dashboard</li>
                  </ol>
                  --}}
              </div>
          </div>
      </div>
      <div>
        @php
          // Obtener el ID de la primera camaronera
          // Obtener el ID de la camaronera de la solicitud, si existe
          $idCamaroneraRequest = request('camaronera');
          $idCamaroneraDefault = $camaronerasUser ? $camaronerasUser->first()->camaronera->id : null;
    
          // Obtener la fecha de la solicitud, si existe, de lo contrario usar la fecha actual
          $fechaRequest = request('fecha') ? request('fecha') : date('Y-m-d');
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
              <label for="fecha">Fecha</label>
              <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $fechaRequest }}" onchange="document.getElementById('autoSubmitForm').submit()">
            </div>
          </div>
        </form>        
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body table-responsive p-0" style="height: 300px;">
              <table class="table table-head-fixed text-nowrap table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">#PS</th>
                    <th class="text-center">Tipo Bal</th>
                    <th class="text-center">ha</th>
                    <th class="text-center">Días</th>
                    <th class="text-center">Peso<br>Transf</th>
                    <th class="text-center">Peso<br>Act</th>
                    {{-- <th class="text-center">Gr/día</th> --}}
                    <th class="text-center">Increm</th>
                    <th class="text-center">Inc. Prom.<br>3sem</th>
                    <th class="text-center">Kg/ha<br>prom</th>
                    <th class="text-center">ind/M2 M</th>
                    <th class="text-center">Alerta <br> Alim</th>
                    <th class="text-center">Dens<br>bio</th>
                    <th class="text-center">Pobl.</th>
                    {{-- <th class="text-center">Proy<br> Anim. M</th> --}}
                    <th class="text-center">Dens <br> Proy</th>
                    <th class="text-center">Desvio</th>
                    <th class="text-center">Lbs/ha</th>
                    <th class="text-center">lbs/total</th>
                    <th class="text-center">raleo</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
                    @php
                      $proyecto = $proyectoItems->where('id_produccion', $item->id_produccion)->where('num_dia', $item->num_dia)->first();
                      $clasePeso = $item->peso_real > $proyecto->peso_proyecto ? 'text-success' : 'text-danger';
                      $iconoPeso = $item->peso_real > $proyecto->peso_proyecto ? 'fas fa-check' : 'fas fa-arrow-up';
                      //$anterior = $itemAnteriores->where('id_produccion', $item->id_produccion)->first();
                    @endphp
                    <tr>
                      <td><a href="{{ url('producciones/'.$item->id_produccion) }}">{{ $item->produccion->piscina->numero }} <i class="fas fa-sign-in-alt"></i></a></td>
                      <td>{{ $item->balanceado->nombre }}</td>
                      <td>{{ $item->produccion->piscina->area_ha }}</td>
                      <td>{{ $item->num_dia }}</td>
                      <td>{{ $item->produccion->peso_transferencia }}</td>
                      <td class="{{ $clasePeso }}"><i class="{{ $iconoPeso }}"></i> {{ $item->peso_real . '/' . $proyecto->peso_proyecto }}</td>
                      {{-- <td>x</td> --}}
                      <td>{{ $item->peso_real_anterior }}</td>
                      <td class="{{ $item->inc3sem < $item->peso_real_anterior ? 'text-success' : 'text-danger' }}">
                        @if ($item->inc3sem < $item->peso_real_anterior)
                            <span>&uarr;</span> <!-- Flecha hacia arriba -->
                        @elseif ($item->inc3sem > $item->peso_real_anterior)
                            <span>&darr;</span> <!-- Flecha hacia abajo -->
                        @endif
                          {{ number_format($item->inc3sem, 2) }}
                      </td>
                      <td>{{ number_format($item->alimento/$item->produccion->piscina->area_ha, 2) }}</td>
                      <td>{{ $item->densidad_consumo }}</td>
                      <td>
                        @if ($proyecto->alimento_dia != 0)
                          @php
                            $diferencia = (($item->alimento - $proyecto->alimento_dia) / $proyecto->alimento_dia) * 100;
                            $clase = $diferencia < 0 ? 'text-danger' : 'text-success';
                            $icono = $diferencia < 0 ? 'fa-arrow-up' : 'fa-check';
                          @endphp
                          <span class="{{ $clase }}">
                            <i class="fas {{ $icono }}"></i>
                            {{ number_format($diferencia, 2) }}%
                          </span>
                        @else
                          N/A
                        @endif
                      </td>
                      <td>{{ $item->densidad_actual }}</td>
                      <td>{{ $item->densidad_muestreo }}</td>
                      {{-- <td>{{ $proyecto->biomasa }}</td> --}}
                      <!-- aqui se resta con nuevo rpoy d -->
                      <td>{{ $proyecto->densidad }}</td>
                      <td class="{{ $proyecto->densidad - $item->densidad_actual > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($proyecto->densidad - $item->densidad_actual, 2) }}</td>
                      <td>{{ $item->biomasa_actual }}</td>
                      <td>{{ $item->alimento }}</td>
                      <td>{{ $item->densidad_raleada }}</td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </section>
  
@endsection