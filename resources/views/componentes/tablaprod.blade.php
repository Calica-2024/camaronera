
@php
    use Carbon\Carbon;
@endphp

<style>
    .fixed-column {
        position: sticky;
        left: 0;
        z-index: 2;
    }
    .hidden {
        display: none;
    }
</style>

<div class="card">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-real-tab" data-toggle="pill" href="#pills-real" role="tab" aria-controls="pills-real" aria-selected="false">Producción Real</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-estimado-tab" data-toggle="pill" href="#pills-estimado" role="tab" aria-controls="pills-estimado" aria-selected="true">Proyecto Estimado</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-real" role="tabpanel" aria-labelledby="pills-real-tab">
            {{--  
            <div class="card-header">
                <h3 class="card-title">Items</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Piscina
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            --}}
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap table-bordered table-striped table-hover">
                    <thead>
                        <tr style="font-size: 14px">
                            <th class="fixed-column" style="width: 20px">Sem.</th>
                            <th class="fixed-column" style="width: 2%">#Día</th>
                            <th style="width: 20px">Fecha</th>
                            <th style="width: 20px">Día</th>
                            <th style="width: 20px">Peso <br> Proy.</th>
                            <th style="width: 20px" class="bg-warning">Peso <br> (g)</th>
                            <th style="width: 20px">Crecimiento</th>
                            <th class="bg-warning" style="width: 20px">Alim.</th>
                            <th style="width: 40px">Tipo <br> Alim.</th>
                            <th class="bg-warning" style="width: 20px">Alim. <br> Calc.</th>
                            <th style="width: 20px">% P.C.</th>
                            <th style="width: 20px">Dens. <br> Cons.</th>
                            <th style="width: 20px" class="bg-info">Dens. <br> Muest.</th>
                            <th style="width: 20px" class="bg-warning">Dens. <br> Actual.</th>
                            <th style="width: 20px">% Sup.</th>
                            <th style="width: 20px">Dens. <br> Ral.</th>
                            <th style="width: 20px">BM <br> Ral.</th>
                            <th style="width: 20px">BM <br> Actual</th>
                            <th style="width: 20px">Recom <br> Alim.</th>
                            <th style="width: 20px">Alim. <br> Acum.</th>
                            <th style="width: 20px">FCA</th>
                            {{-- <th>ROI</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php

                            // Agrupar los registros por semanas (lunes a domingo)
                            $groupedItems = [];
                            foreach ($produccionItems as $item) {
                                // Obtener el inicio de la semana (lunes)
                                $startOfWeek = Carbon::parse($item->fecha)->startOfWeek()->format('Y-m-d');
                                
                                // Agrupar por el inicio de la semana
                                if (!isset($groupedItems[$startOfWeek])) {
                                    $groupedItems[$startOfWeek] = [];
                                }
                                $groupedItems[$startOfWeek][] = $item;
                            }
                        @endphp

                        @foreach ($groupedItems as $startOfWeek => $items)
                            @php
                                // Convertir array a colección para usar métodos de colección
                                $itemsCollection = collect($items);
                                $startDate = Carbon::parse($startOfWeek);
                                $endDate = $startDate->copy()->endOfWeek();
                                $lastItemDate = $itemsCollection->max('fecha'); // Obtener la fecha más reciente
                            @endphp

                            @foreach ($items as $item)
                                @php
                                    $itemProy = $proyectoItems->where('num_dia', $item->num_dia)->first();
                                    // Verificar si el registro es el último de la semana
                                    $isLastItem = Carbon::parse($item->fecha)->equalTo(Carbon::parse($lastItemDate));
                                    $isHidden = !$isLastItem; // Determinar si la fila debe estar oculta
                                    
                                    $isHoy = $item->fecha === now()->format('Y-m-d');
                                    $esDomingo = $item->dia === 'domingo';
                                @endphp
                                <tr class="{{ $isHidden ? 'hidden' : '' }}" data-week="{{ $startOfWeek }}" style="background-color: {{ $isHoy ? '#ffe9a6' : ($esDomingo ? '#54a7ff' : 'transparent') }}">
                                    <td>
                                        {{ Carbon::parse($item->fecha)->weekOfYear }}
                                        @if ($isLastItem)
                                            <button class="btn btn-success btn-sm toggle-btn" data-week="{{ $startOfWeek }}" title="Mostrar/Ocultar">
                                                <i class="fas fa-plus"></i> <!-- Botón con ícono "+" -->
                                            </button>
                                        @endif
                                    </td>
                                    <!-- Las demás columnas -->
                                    <td class="fixed-column"><a href="#" data-toggle="modal" data-target="#prodReal{{ $item->id }}"><i class="fas fa-edit"></i> {{ $item->num_dia }}</a></td>
                                    <td>{{ Carbon::parse($item->fecha)->format('d/m/y') }}</td>
                                    <td>{{ mb_substr($item->dia, 0, 3, 'UTF-8') }}</td>
                                    <td>{{ $itemProy->peso_proyecto }}</td>
                                    <td class="bg-warning">{{ $item->peso_real }}</td>
                                    <td>{{ $item->peso_real_anterior }}</td>
                                    <td class="bg-warning">{{ $item->alimento }}</td>
                                    <td>{{ $item->balanceado->nombre }}</td>
                                    <td class="bg-warning">{{ $item->alimento_calculo }}</td>
                                    <td>{{ $item->peso_corporal }}%</td>
                                    <td>{{ $item->densidad_consumo }}</td>
                                    <td class="bg-info">{{ $item->densidad_muestreo }}</td>
                                    <td class="bg-warning">{{ $item->densidad_actual }}</td>
                                    <td>{{ number_format($item->supervivencia, 1) }}%</td>
                                    <td>{{ $item->densidad_raleada }}</td>
                                    <td>{{ $item->biomasa_raleada }}</td>
                                    <td>{{ $item->biomasa_actual }}</td>
                                    <td>{{ $item->recomendacion_alimento }}</td>
                                    <td>{{ $item->alimento_acumulado }}</td>
                                    <td>{{ $item->fca }}</td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="prodReal{{ $item->id }}" tabindex="-1" aria-labelledby="prodReal{{ $item->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="prodReal{{ $item->id }}Label">Actualizar Registro #{{ $item->num_dia }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('proyectoReal/upd', $item->id) }}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <label for="id_balanceado">Balanceado</label>
                                                            <select name="id_balanceado" class="form-control @error('id_balanceado') is-invalid @enderror" id="id_balanceado">
                                                                <option value="">Seleccione una opción</option>
                                                                @foreach ($balanceados as $balanceado)
                                                                    <option value="{{ $balanceado->id }}" {{ $balanceado->id == $item->id_balanceado ? 'selected' : '' }}>{{ $balanceado->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('id_balanceado'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('id_balanceado') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="peso_real" style="font-size: 13px">Peso Real</label>
                                                            <input type="text" name="peso_real" class="form-control @error('peso_real') is-invalid @enderror" id="peso_real"  value="{{ $item->peso_real }}" oninput="decimales(this)">
                                                            @if ($errors->has('peso_real'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('peso_real') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="alimento" style="font-size: 13px">Alimento Real</label>
                                                            <input type="text" name="alimento" class="form-control @error('alimento') is-invalid @enderror" id="alimento"  value="{{ $item->alimento }}" oninput="decimales(this)">
                                                            @if ($errors->has('alimento'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('alimento') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="alimento_calculo" style="font-size: 13px">Alim. Calculo</label>
                                                            <input type="text" name="alimento_calculo" class="form-control @error('alimento_calculo') is-invalid @enderror" id="alimento_calculo"  value="{{ $item->alimento_calculo }}" oninput="decimales(this)">
                                                            @if ($errors->has('alimento_calculo'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('alimento_calculo') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="densidad_muestreo" style="font-size: 13px">Dens. Muestreo</label>
                                                            <input type="text" name="densidad_muestreo" class="form-control @error('densidad_muestreo') is-invalid @enderror" id="densidad_muestreo"  value="{{ $item->densidad_muestreo }}" oninput="decimales(this)">
                                                            @if ($errors->has('densidad_muestreo'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('densidad_muestreo') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="densidad_actual" style="font-size: 13px">Dens. Actual</label>
                                                            <input type="text" name="densidad_actual" class="form-control @error('densidad_actual') is-invalid @enderror" id="densidad_actual"  value="{{ $item->densidad_actual }}" oninput="decimales(this)">
                                                            @if ($errors->has('densidad_actual'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('densidad_actual') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="densidad_raleada" style="font-size: 13px">Dens. Raleada</label>
                                                            <input type="text" name="densidad_raleada" class="form-control @error('densidad_raleada') is-invalid @enderror" id="densidad_raleada"  value="{{ $item->densidad_raleada }}" oninput="decimales(this)">
                                                            @if ($errors->has('densidad_raleada'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('densidad_raleada') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 form-group">
                                                            <label for="densidad_oficina" style="font-size: 13px">Dens. Oficina</label>
                                                            <input type="text" name="densidad_oficina" class="form-control @error('densidad_oficina') is-invalid @enderror" id="densidad_oficina"  value="{{ $item->densidad_oficina }}" oninput="decimales(this)">
                                                            @if ($errors->has('densidad_oficina'))
                                                                <div class="invalid-feedback" style="display: inline !important">
                                                                    @foreach ($errors->get('densidad_oficina') as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-estimado" role="tabpanel" aria-labelledby="pills-estimado-tab">
            {{-- 
            <div class="card-header">
                <h3 class="card-title">Proyecto Productivo</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Piscina
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            --}}
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap table-bordered table-striped table-hover">
                    <thead>
                        <tr style="font-size: 14px">
                            <th class="fixed-column">#Día</th>
                            <th>Fecha</th>
                            <th>Día</th>
                            <th class="bg-warning">Peso (g)</th>
                            <th>Crec. L.</th>
                            <th>Sup %</th>
                            <th>Dens.</th>
                            <th>Dens. Ral.</th>
                            <th>DBM. Ral.</th>
                            <th>DBM.</th>
                            <th>% Peso Corp.</th>
                            <th>Alim. kg/día</th>
                            <th>Alim. kg/ha</th>
                            <th>Tipo Alim.</th>
                            <th>Alim. Acum.</th>
                            <th>FCA.</th>
                            {{-- <th>ROI</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proyectoItems as $item)
                        <tr>
                            <td class="fixed-column"><a href="#" data-toggle="modal" data-target="#regProy{{ $item->id }}"><i class="fas fa-edit"></i> {{ $item->num_dia }}</a></td>
                            <td>{{ Carbon::parse($item->fecha)->format('d/m/y') }}</td>
                            <td>{{ mb_substr($item->dia, 0, 3, 'UTF-8') }}</td>
                            <td class="bg-warning">{{ $item->peso_proyecto }}</td>
                            <td>{{ $item->crecimiento_lineal }}</td>
                            <td>{{ number_format($item->supervivencia_base, 1) }}%</td>
                            <td>{{ $item->densidad }}</td>
                            <td>{{ $item->densidad_raleada }}</td>
                            <td>{{ $item->biomasa_raleada }}</td>
                            <td>{{ number_format($item->biomasa, 0) }}</td>
                            <td>{{ $item->peso_corporal }}%</td>
                            <td>{{ $item->alimento_dia }}</td>
                            <td>{{ $item->alimento_area }}</td>
                            <td>{{ $item->balanceado->nombre }}</td>
                            <td>{{ $item->alimento_aculumado }}</td>
                            <td>{{ $item->fca }}</td>
                            {{-- <td>{{ $item->roi }}</td> --}}
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="regProy{{ $item->id }}" tabindex="-1" aria-labelledby="regProy{{ $item->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="regProy{{ $item->id }}Label">Actualizar Registro {{ $item->num_dia }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                        <div class="modal-body">
                                            <form action="{{ url('updProyItem', $item) }}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <div class="form-group">
                                                    <label for="densidad_raleada">Densidad Raleada</label>
                                                    <input type="text" name="densidad_raleada" class="form-control @error('densidad_raleada') is-invalid @enderror" id="densidad_raleada" placeholder="Densidad Raleada" oninput="decimales(this)"  value="{{ $item->densidad_raleada }}"/>
                                                    @if ($errors->has('densidad_raleada'))
                                                        <div class="invalid-feedback" style="display: inline !important">
                                                            @foreach ($errors->get('densidad_raleada') as $error)
                                                                {{ $error }}<br>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary">Registrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para manejar el clic en el botón "+" -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', function () {
                const week = this.getAttribute('data-week');
                const rows = document.querySelectorAll(`tr[data-week="${week}"]`);
                
                rows.forEach(row => {
                    if (!row.querySelector('.toggle-btn')) { // Asegurarse de no ocultar la última fila
                        if (row.classList.contains('hidden')) {
                            row.classList.remove('hidden');
                            this.querySelector('i').classList.remove('fa-plus');
                            this.querySelector('i').classList.add('fa-minus');
                        } else {
                            row.classList.add('hidden');
                            this.querySelector('i').classList.remove('fa-minus');
                            this.querySelector('i').classList.add('fa-plus');
                        }
                    }
                });
            });
        });
    });
</script>