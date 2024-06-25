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
          $idCamaroneraDefault = $camaronerasUser->first()->camaronera->id;
          // Obtener el ID de la camaronera de la solicitud, si existe
          $idCamaroneraRequest = request('camaronera');
    
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
                    <th class="text-center">Gr/día</th>
                    <th class="text-center">Increm</th>
                    <th class="text-center">Inc. Prom.<br>3sem</th>
                    <th class="text-center">Kg/ha<br>prom</th>
                    <th class="text-center">ind/M2 M</th>
                    <th class="text-center">Alerta Alim</th>
                    <th class="text-center">Dens<br>bio</th>
                    <th class="text-center">Pobl.</th>
                    <th class="text-center">Proy<br> Anim. M</th>
                    <th class="text-center">Nuevo <br> Proy D</th>
                    <th class="text-center">Lbs/ha</th>
                    <th class="text-center">lbs/total</th>
                    <th class="text-center">raleo</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
                    <tr>
                      <td>{{ $item->produccion->piscina->numero }}</td>
                      <td>{{ $item->balanceado->nombre }}</td>
                      <td>{{ $item->produccion->piscina->area_ha }}</td>
                      <td>{{ $item->num_dia }}</td>
                      <td>{{ $item->produccion->peso_transferencia }}</td>
                      <td>{{ $item->peso_real }}</td>
                      <td>x</td>
                      <td>x</td>
                      <td>x</td>
                      <td>{{ number_format($item->alimento/$item->produccion->piscina->area_ha, 2) }}</td>
                      <td>x</td>
                      <td>x</td>
                      <td>{{ $item->densidad_actual }}</td>
                      <td>x</td>
                      <td>x</td>
                      <!-- aqui se resta con nuevo rpoy d -->
                      <td>{{ $item->densidad_actual - 0 }}</td>
                      <td>{{ $item->biomasa_actual }}</td>
                      <td>{{ $item->biomasa_actual * $item->produccion->piscina->area_ha }}</td>
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