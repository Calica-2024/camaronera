
@php
    use Carbon\Carbon;
@endphp

<style>
    .fixed-column {
        position: sticky;
        left: 0;
        z-index: 2;
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
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 750px;">
                <table class="table table-head-fixed text-nowrap table-bordered table-striped table-hover">
                    <thead>
                        <tr style="font-size: 14px">
                            <th class="fixed-column" style="width: 2%">#Día</th>
                            <th style="width: 2%">Fecha</th>
                            <th style="width: 2%">Día</th>
                            <th style="width: 2%">Peso Proy.</th>
                            <th style="width: 2%">Peso (g)</th>
                            <th style="width: 2%">Crecimiento</th>
                            <th>Alim.</th>
                            <th>Tipo Alim.</th>
                            <th>Alim. Calc.</th>
                            <th>% P.C.</th>
                            <th>Dens. Cons.</th>
                            <th>Dens. Muest.</th>
                            <th>Dens. Actual.</th>
                            <th>% Sup.</th>
                            <th>Dens. Ral.</th>
                            <th>BM Ral.</th>
                            <th>BM Actual</th>
                            <th>Recom Alim.</th>
                            <th>Alim. Acum.</th>
                            <th>FCA</th>
                            {{-- <th>ROI</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produccionItems as $item)
                            @php
                                $itemProy = $proyectoItems->where('num_dia', $item->num_dia)->first();
                            @endphp
                            <tr>
                                <td class="fixed-column"><a href="#" data-toggle="modal" data-target="#prodReal{{ $item->id }}"><i class="fas fa-edit"></i> {{ $item->num_dia }}</a></td>
                                <td>{{ Carbon::parse($item->fecha)->format('d/m/y') }}</td>
                                <td>{{ mb_substr($item->dia, 0, 3, 'UTF-8') }}</td>
                                <td>{{ $itemProy->peso_proyecto }}</td>
                                <td>{{ $item->peso_real }}</td>
                                <td>{{ $item->peso_real_anterior }}</td>
                                <td>{{ $item->alimento }}</td>
                                <td>{{ $item->balanceado->nombre }}</td>
                                <td>{{ $item->alimento_calculo }}</td>
                                <td>{{ $item->peso_corporal }}%</td>
                                <td>{{ $item->densidad_consumo }}</td>
                                <td>{{ $item->densidad_muestreo }}</td>
                                <td>{{ $item->densidad_actual }}</td>
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
                                            <h5 class="modal-title" id="prodReal{{ $item->id }}Label">Actualizar Registro {{ $item->num_dia }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ url('proyectoReal/upd', $item->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-6 form-group">
                                                        <label for="peso_real" style="font-size: 13px">Peso Real</label>
                                                        <input type="text" name="peso_real" class="form-control @error('peso_real') is-invalid @enderror" id="peso_real" autofocus value="{{ $item->peso_real }}" oninput="decimales(this)">
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
                                                        <input type="text" name="alimento" class="form-control @error('alimento') is-invalid @enderror" id="alimento" autofocus value="{{ $item->alimento }}" oninput="decimales(this)">
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
                                                        <input type="text" name="alimento_calculo" class="form-control @error('alimento_calculo') is-invalid @enderror" id="alimento_calculo" autofocus value="{{ $item->alimento_calculo }}" oninput="decimales(this)">
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
                                                        <input type="text" name="densidad_muestreo" class="form-control @error('densidad_muestreo') is-invalid @enderror" id="densidad_muestreo" autofocus value="{{ $item->densidad_muestreo }}" oninput="decimales(this)">
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
                                                        <input type="text" name="densidad_actual" class="form-control @error('densidad_actual') is-invalid @enderror" id="densidad_actual" autofocus value="{{ $item->densidad_raleada }}" oninput="decimales(this)">
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
                                                        <input type="text" name="densidad_raleada" class="form-control @error('densidad_raleada') is-invalid @enderror" id="densidad_raleada" autofocus value="{{ $item->densidad_raleada }}" oninput="decimales(this)">
                                                        @if ($errors->has('densidad_raleada'))
                                                            <div class="invalid-feedback" style="display: inline !important">
                                                                @foreach ($errors->get('densidad_raleada') as $error)
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
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-estimado" role="tabpanel" aria-labelledby="pills-estimado-tab">
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
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 750px;">
                <table class="table table-head-fixed text-nowrap table-bordered table-striped table-hover">
                    <thead>
                        <tr style="font-size: 14px">
                            <th class="fixed-column">#Día</th>
                            <th>Fecha</th>
                            <th>Día</th>
                            <th>Peso (g)</th>
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
                            <td>{{ $item->peso_proyecto }}</td>
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
                                                    <input type="text" name="densidad_raleada" class="form-control @error('densidad_raleada') is-invalid @enderror" id="densidad_raleada" placeholder="Densidad Raleada" oninput="decimales(this)" autofocus value="{{ $item->densidad_raleada }}"/>
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