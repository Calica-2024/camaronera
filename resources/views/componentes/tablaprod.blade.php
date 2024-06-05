
@php
    use Carbon\Carbon;
@endphp

<style>
    .fixed-column {
        position: sticky;
        left: 0;
        background-color: white;
        z-index: 2;
    }
</style>

<div class="card">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="pills-real-tab" data-toggle="pill" href="#pills-real" role="tab" aria-controls="pills-real" aria-selected="false">Registro Real</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="pills-estimado-tab" data-toggle="pill" href="#pills-estimado" role="tab" aria-controls="pills-estimado" aria-selected="true">Proyecto Estimado</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade" id="pills-real" role="tabpanel" aria-labelledby="pills-real-tab">
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
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Param1</th>
                            <th>Date</th>
                            <th>param2</th>
                            <th>param3</th>
                            <th>param4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>183</td>
                            <td>pool1</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-success">Approved</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                            <th>param4</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade show active" id="pills-estimado" role="tabpanel" aria-labelledby="pills-estimado-tab">
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
            <div class="card-body table-responsive p-0" style="height: 900px;">
                <table class="table table-head-fixed text-nowrap table-bordered">
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