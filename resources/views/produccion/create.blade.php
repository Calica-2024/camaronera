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
                        <li class="breadcrumb-item"><a href="{{ url('producciones/piscina',$piscina) }}">Piscina</a></li>
                        <li class="breadcrumb-item active">Crear</li>
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
                            <h3 class="card-title">Nueva Producción <small>Llenar el formulario</small></h3>
                        </div>
                        <form class="forms-sample" action="{{ url('producciones/store',$piscina) }}" method="POST">
                            @method('POST')
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="fecha">Fecha Inicio</label>
                                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" id="fecha" autofocus value="{{ old('fecha') }}">
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
                                        <input type="text" name="dias_ciclo" class="form-control @error('dias_ciclo') is-invalid @enderror" id="dias_ciclo" placeholder="Días De Ciclo" value="{{ old('dias_ciclo') }}">
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
                                        <input type="text" name="densidad" class="form-control @error('densidad') is-invalid @enderror" id="densidad" placeholder="Densidad Siembra" maxlength="15" value="{{ old('densidad') }}">
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
                                        <input type="text" name="peso_transferencia" class="form-control @error('peso_transferencia') is-invalid @enderror" id="peso_transferencia" placeholder="Peso Transferencia" maxlength="15" value="{{ old('peso_transferencia') }}">
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
                                        <input type="text" name="costo_larva" class="form-control @error('costo_larva') is-invalid @enderror" id="costo_larva" placeholder="Costo larva/juvenil ($/millar)" maxlength="15" value="{{ old('costo_larva') }}">
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
                                        <input type="text" name="multiplo_redondeo" class="form-control @error('multiplo_redondeo') is-invalid @enderror" id="multiplo_redondeo" placeholder="Multiplo Redondeo Alimento" maxlength="15" value="{{ old('multiplo_redondeo') }}">
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
                                        <input type="text" name="supervivencia_30" class="form-control @error('supervivencia_30') is-invalid @enderror" id="supervivencia_30" placeholder="Supervivencia a 30 días %" maxlength="15" value="{{ old('supervivencia_30') }}">
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
                                        <input type="text" name="supervivencia_fin" class="form-control @error('supervivencia_fin') is-invalid @enderror" id="supervivencia_fin" placeholder="Supervivencia Final %" maxlength="15" value="{{ old('supervivencia_fin') }}">
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
                                        <input type="text" name="capacidad_carga" class="form-control @error('capacidad_carga') is-invalid @enderror" id="capacidad_carga" placeholder="Capacidad carga (Lb/Ha)" maxlength="15" value="{{ old('capacidad_carga') }}">
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
                                            <option value="ta1">TA1</option>
                                            <option value="ta2">TA2</option>
                                            <option value="ta3">TA3</option>
                                            <option value="ta4">TA4</option>
                                            <option value="ta5">TA5</option>
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
                                        <input type="text" name="costo_fijo" class="form-control @error('costo_fijo') is-invalid @enderror" id="costo_fijo" placeholder="Costo fijo ($/Ha/día)" maxlength="15" value="{{ old('costo_fijo') }}">
                                        @if ($errors->has('costo_fijo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('costo_fijo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Producción</button>
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
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
--}}