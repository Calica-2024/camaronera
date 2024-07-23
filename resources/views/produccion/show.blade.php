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
                        <li class="breadcrumb-item active">Consultar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <p>
                            <a class="btn btn-info" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Ver Parametros De Producción
                            </a>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        @php 
                            use Carbon\Carbon;
                            $ultimoRegistro = $produccionItems->last(); 
                            $fechaProxima = $ultimoRegistro ? Carbon::parse($ultimoRegistro->fecha)->addDay()->toDateString() : $produccion->fecha;
                        @endphp
                        <p class="float-sm-right">
                            <a class="btn btn-primary" href="#"  data-toggle="modal" data-target="#piscinas">
                            Añadir Registro #{{ $ultimoRegistro ? $ultimoRegistro->num_dia + 1 : 1 }}
                            </a>
                        </p>
                        <!-- Modal -->
                        <div class="modal fade" id="piscinas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Añadir Registro Día #{{ $ultimoRegistro ? $ultimoRegistro->num_dia + 1 : 1 }} Fecha: {{ $fechaProxima }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form class="forms-sample" action="{{ url('/proyectoReal', $produccion) }}" method="POST">
                                        @method('POST')
                                        @csrf
                                        <input type="hidden" name="num_dia" value="{{ $ultimoRegistro ? $ultimoRegistro->num_dia + 1 : 1 }}">
                                        <input type="hidden" name="fecha" value="{{ $fechaProxima }}">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="id_balanceado">Balanceado</label>
                                                    <select name="id_balanceado" class="form-control @error('id_balanceado') is-invalid @enderror" id="id_balanceado">
                                                        <option value="">Seleccione una opción</option>
                                                        @foreach ($balanceados as $balanceado)
                                                            <option value="{{ $balanceado->id }}">{{ $balanceado->nombre }}</option>
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
                                                    <input type="text" name="peso_real" class="form-control @error('peso_real') is-invalid @enderror" id="peso_real" value="{{ old('peso_real') ?? 0 }}" oninput="decimales(this)" autofocus>
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
                                                    <input type="text" name="alimento" class="form-control @error('alimento') is-invalid @enderror" id="alimento" value="{{old('alimento') ?? 0 }}" oninput="decimales(this)">
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
                                                    <input type="text" name="alimento_calculo" class="form-control @error('alimento_calculo') is-invalid @enderror" id="alimento_calculo" value="{{old('alimento_calculo')?? 0 }}" oninput="decimales(this)">
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
                                                    <input type="text" name="densidad_muestreo" class="form-control @error('densidad_muestreo') is-invalid @enderror" id="densidad_muestreo" value="{{old('densidad_muestreo') ?? 0 }}" oninput="decimales(this)">
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
                                                    <input type="text" name="densidad_actual" class="form-control @error('densidad_actual') is-invalid @enderror" id="densidad_actual" value="{{old('densidad_actual') ?? 0 }}" oninput="decimales(this)">
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
                                                    <input type="text" name="densidad_raleada" class="form-control @error('densidad_raleada') is-invalid @enderror" id="densidad_raleada" value="{{old('densidad_raleada') ?? 0 }}" oninput="decimales(this)">
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
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Registro</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 collapse" id="collapseExample">
                    <div class="card card-primary">
                        <form class="forms-sample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="fecha">Fecha Inicio</label>
                                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" id="fecha" disabled value="{{ $produccion->fecha }}">
                                        @if ($errors->has('fecha'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('fecha') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="dias_ciclo">Días De Ciclo</label>
                                        <input type="text" name="dias_ciclo" class="form-control @error('dias_ciclo') is-invalid @enderror" id="dias_ciclo" placeholder="Días De Ciclo" disabled value="{{ $produccion->dias_ciclo }}">
                                        @if ($errors->has('dias_ciclo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('dias_ciclo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="densidad">Densidad Siembra (1/m2)</label>
                                        <input type="text" name="densidad" class="form-control @error('densidad') is-invalid @enderror" id="densidad" placeholder="Densidad Siembra" maxlength="15" disabled value="{{ $produccion->densidad }}">
                                        @if ($errors->has('densidad'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('densidad') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="peso_transferencia">Peso Transferencia</label>
                                        <input type="text" name="peso_transferencia" class="form-control @error('peso_transferencia') is-invalid @enderror" id="peso_transferencia" placeholder="Peso Transferencia" maxlength="15" disabled value="{{ $produccion->peso_transferencia }}">
                                        @if ($errors->has('peso_transferencia'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('peso_transferencia') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="costo_larva">Costo larva/juvenil ($/millar)</label>
                                        <input type="text" name="costo_larva" class="form-control @error('costo_larva') is-invalid @enderror" id="costo_larva" placeholder="Costo larva/juvenil ($/millar)" maxlength="15" disabled value="{{ $produccion->costo_larva }}">
                                        @if ($errors->has('costo_larva'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('costo_larva') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="multiplo_redondeo">Multiplo Redondeo Alimento</label>
                                        <input type="text" name="multiplo_redondeo" class="form-control @error('multiplo_redondeo') is-invalid @enderror" id="multiplo_redondeo" placeholder="Multiplo Redondeo Alimento" maxlength="15" disabled value="{{ $produccion->multiplo_redondeo }}">
                                        @if ($errors->has('multiplo_redondeo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('multiplo_redondeo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="supervivencia_30">Supervivencia a 30 días %</label>
                                        <input type="text" name="supervivencia_30" class="form-control @error('supervivencia_30') is-invalid @enderror" id="supervivencia_30" placeholder="Supervivencia a 30 días %" maxlength="15" disabled value="{{ $produccion->supervivencia_30 }}">
                                        @if ($errors->has('supervivencia_30'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('supervivencia_30') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="supervivencia_fin">Supervivencia Final %</label>
                                        <input type="text" name="supervivencia_fin" class="form-control @error('supervivencia_fin') is-invalid @enderror" id="supervivencia_fin" placeholder="Supervivencia Final %" maxlength="15" disabled value="{{ $produccion->supervivencia_fin }}">
                                        @if ($errors->has('supervivencia_fin'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('supervivencia_fin') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="capacidad_carga">Capacidad carga (Lb/Ha)</label>
                                        <input type="text" name="capacidad_carga" class="form-control @error('capacidad_carga') is-invalid @enderror" id="capacidad_carga" placeholder="Capacidad carga (Lb/Ha)" maxlength="15" disabled value="{{ $produccion->capacidad_carga }}">
                                        @if ($errors->has('capacidad_carga'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('capacidad_carga') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="costo_fijo">Costo fijo ($/Ha/día)</label>
                                        <input type="text" name="costo_fijo" class="form-control @error('costo_fijo') is-invalid @enderror" id="costo_fijo" placeholder="Costo fijo ($/Ha/día)" maxlength="15" disabled value="{{ $produccion->costo_fijo }}">
                                        @if ($errors->has('costo_fijo'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('costo_fijo') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="estado" class="custom-control-input" id="exampleCheck1" {{ $produccion->estado == 1 ? 'checked' : '' }} disabled>
                                            <label class="custom-control-label" for="exampleCheck1">En Progreso</label>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ url('producciones/'.$produccion->id.'/edit') }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--  
    <section class="content">
        @include('componentes.graficosprod')
    </section>
    --}}
    <section class="content">
        @include('componentes.tablaprod')
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const decimalInputs = document.querySelectorAll('.decimal');

            decimalInputs.forEach(input => {
                input.addEventListener('input', function (e) {
                    let value = this.value;

                    // Allow only digits and a single decimal point
                    const regex = /^[0-9]*\.?[0-9]{0,2}$/;
                    if (!regex.test(value)) {
                        this.value = value.slice(0, -1);
                    }

                    // Limit to 2 decimal places
                    const parts = value.split('.');
                    if (parts.length > 1 && parts[1].length > 2) {
                        this.value = value.slice(0, value.length - 1);
                    }
                });

                input.addEventListener('keydown', function (e) {
                    // Allow: backspace, delete, tab, escape, enter, and .
                    if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                        // Allow: Ctrl/cmd+A
                        (e.keyCode === 65 && (e.ctrlKey || e.metaKey)) ||
                        // Allow: Ctrl/cmd+C
                        (e.keyCode === 67 && (e.ctrlKey || e.metaKey)) ||
                        // Allow: Ctrl/cmd+X
                        (e.keyCode === 88 && (e.ctrlKey || e.metaKey)) ||
                        // Allow: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) &&
                        (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }

                    // Prevent multiple decimal points
                    if ((e.keyCode === 110 || e.keyCode === 190) && this.value.includes('.')) {
                        e.preventDefault();
                    }

                    // Prevent more than two decimals
                    const value = this.value;
                    const parts = value.split('.');
                    if (parts.length > 1 && parts[1].length >= 2) {
                        if (e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 96 && e.keyCode <= 105) {
                            e.preventDefault();
                        }
                    }
                });
            });
        });
    </script>
@endsection
{{-- 
<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
--}}
