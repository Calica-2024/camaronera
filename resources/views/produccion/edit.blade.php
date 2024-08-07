<div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
</div>
@extends('template.template')
@section('contenido')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $vista }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('producciones') }}">Producciones</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8 offset-md-2">
                <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Información De La Producción</h3>
                        </div>
                        <form class="forms-sample" action="{{ url('producciones',$produccion) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="fecha">Fecha Inicio</label>
                                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" id="fecha" autofocus value="{{ $produccion->fecha }}">
                                        @if ($errors->has('fecha'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('fecha') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="dias_ciclo">Días De Ciclo</label>
                                        <input type="text" name="dias_ciclo" class="form-control @error('dias_ciclo') is-invalid @enderror" id="dias_ciclo" placeholder="Días De Ciclo" value="{{ $produccion->dias_ciclo }}">
                                        @if ($errors->has('dias_ciclo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('dias_ciclo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="densidad">Densidad Siembra (1/m2)</label>
                                        <input type="text" name="densidad" class="form-control @error('densidad') is-invalid @enderror" id="densidad" placeholder="Densidad Siembra" maxlength="15" value="{{ $produccion->densidad }}">
                                        @if ($errors->has('densidad'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('densidad') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="peso_transferencia">Peso Transferencia</label>
                                        <input type="text" name="peso_transferencia" class="form-control @error('peso_transferencia') is-invalid @enderror" id="peso_transferencia" placeholder="Peso Transferencia" maxlength="15" value="{{ $produccion->peso_transferencia }}">
                                        @if ($errors->has('peso_transferencia'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('peso_transferencia') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="costo_larva">Costo larva/juvenil ($/millar)</label>
                                        <input type="text" name="costo_larva" class="form-control @error('costo_larva') is-invalid @enderror" id="costo_larva" placeholder="Costo larva/juvenil ($/millar)" maxlength="15" value="{{ $produccion->costo_larva }}">
                                        @if ($errors->has('costo_larva'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('costo_larva') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="multiplo_redondeo">Multiplo Redondeo Alimento</label>
                                        <input type="text" name="multiplo_redondeo" class="form-control @error('multiplo_redondeo') is-invalid @enderror" id="multiplo_redondeo" placeholder="Multiplo Redondeo Alimento" maxlength="15" value="{{ $produccion->multiplo_redondeo }}">
                                        @if ($errors->has('multiplo_redondeo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('multiplo_redondeo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="supervivencia_30">Supervivencia a 30 días %</label>
                                        <input type="text" name="supervivencia_30" class="form-control @error('supervivencia_30') is-invalid @enderror" id="supervivencia_30" placeholder="Supervivencia a 30 días %" maxlength="15" value="{{ $produccion->supervivencia_30 }}">
                                        @if ($errors->has('supervivencia_30'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('supervivencia_30') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="supervivencia_fin">Supervivencia Final %</label>
                                        <input type="text" name="supervivencia_fin" class="form-control @error('supervivencia_fin') is-invalid @enderror" id="supervivencia_fin" placeholder="Supervivencia Final %" maxlength="15" value="{{ $produccion->supervivencia_fin }}">
                                        @if ($errors->has('supervivencia_fin'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('supervivencia_fin') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="capacidad_carga">Capacidad carga (Lb/Ha)</label>
                                        <input type="text" name="capacidad_carga" class="form-control @error('capacidad_carga') is-invalid @enderror" id="capacidad_carga" placeholder="Capacidad carga (Lb/Ha)" maxlength="15" value="{{ $produccion->capacidad_carga }}">
                                        @if ($errors->has('capacidad_carga'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('capacidad_carga') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="tabla_alimentacion">Tabla de Alimentación</label>
                                        <select name="tabla_alimentacion" class="form-control @error('tabla_alimentacion') is-invalid @enderror" id="tabla_alimentacion">
                                            <option value="">Seleccione una opción</option>
                                            <option value="ta1" {{ $produccion->tabla_alimentacion == 'ta1' ? 'selected' : '' }}>TA1</option>
                                            <option value="ta2" {{ $produccion->tabla_alimentacion == 'ta2' ? 'selected' : '' }}>TA2</option>
                                            <option value="ta3" {{ $produccion->tabla_alimentacion == 'ta3' ? 'selected' : '' }}>TA3</option>
                                            <option value="ta4" {{ $produccion->tabla_alimentacion == 'ta4' ? 'selected' : '' }}>TA4</option>
                                            <option value="ta5" {{ $produccion->tabla_alimentacion == 'ta5' ? 'selected' : '' }}>TA5</option>
                                            <option value="ta6" {{ $produccion->tabla_alimentacion == 'ta6' ? 'selected' : '' }}>TA6</option>
                                            <option value="ta7" {{ $produccion->tabla_alimentacion == 'ta7' ? 'selected' : '' }}>TA7</option>
                                        </select>
                                        @if ($errors->has('tabla_alimentacion'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('tabla_alimentacion') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="costo_fijo">Costo fijo ($/Ha/día)</label>
                                        <input type="text" name="costo_fijo" class="form-control @error('costo_fijo') is-invalid @enderror" id="costo_fijo" placeholder="Costo fijo ($/Ha/día)" maxlength="15" value="{{ $produccion->costo_fijo }}">
                                        @if ($errors->has('costo_fijo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('costo_fijo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="crecimiento1">Crecimiento Proyecto Dia 0 - 15</label>
                                    <input type="text" name="crecimiento1" class="form-control @error('crecimiento1') is-invalid @enderror" id="crecimiento1" placeholder="Crecimiento Proyecto Dia 0 - 15" maxlength="15" value="{{ $produccion->crecimiento1 }}">
                                    @if ($errors->has('crecimiento1'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('crecimiento1') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="crecimiento2">Crecimiento Proyecto Dia 15 - 30</label>
                                    <input type="text" name="crecimiento2" class="form-control @error('crecimiento2') is-invalid @enderror" id="crecimiento2" placeholder="Crecimiento Proyecto Dia 15 - 30" maxlength="15" value="{{ $produccion->crecimiento2 }}">
                                    @if ($errors->has('crecimiento2'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('crecimiento2') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="crecimiento3">Crecimiento Proyecto Dia 30 - 45</label>
                                    <input type="text" name="crecimiento3" class="form-control @error('crecimiento3') is-invalid @enderror" id="crecimiento3" placeholder="Crecimiento Proyecto Dia 30 - 45" maxlength="15" value="{{ $produccion->crecimiento3 }}">
                                    @if ($errors->has('crecimiento3'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('crecimiento3') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="crecimiento4">Crecimiento Proyecto Dia 45 - 60</label>
                                    <input type="text" name="crecimiento4" class="form-control @error('crecimiento4') is-invalid @enderror" id="crecimiento4" placeholder="Crecimiento Proyecto Dia 45 - 60" maxlength="15" value="{{ $produccion->crecimiento4 }}">
                                    @if ($errors->has('crecimiento4'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('crecimiento4') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="crecimiento5">Crecimiento Proyecto Dia > 60</label>
                                    <input type="text" name="crecimiento5" class="form-control @error('crecimiento5') is-invalid @enderror" id="crecimiento5" placeholder="Crecimiento Proyecto Dia > 60" maxlength="15" value="{{ $produccion->crecimiento5 }}">
                                    @if ($errors->has('crecimiento5'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('crecimiento5') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="estado" class="custom-control-input" id="exampleCheck1" {{ $produccion->estado == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="exampleCheck1">Activo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
{{-- 
<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
--}}

