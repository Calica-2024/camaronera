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
                        <li class="breadcrumb-item"><a href="{{ url('tabla_alimentacion') }}">Tabla Alimentación</a></li>
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
                            <h3 class="card-title">Creación de Tabla de Alimentación <small>Llenar el formulario</small></h3>
                        </div>
                        <form class="forms-sample" action="{{ url('tabla_alimentacion') }}" method="POST">
                            @method('POST')
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="peso">Peso</label>
                                        <input type="text" name="pesos" class="form-control @error('pesos') is-invalid @enderror" id="peso" placeholder="Peso" maxlength="15" value="{{ old('pesos') }}">
                                        @if ($errors->has('peso'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('pesos') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="ta1">TA1</label>
                                        <input type="text" name="ta1" class="form-control @error('ta1') is-invalid @enderror" id="ta1" placeholder="TA1" maxlength="100" value="{{ old('ta1') }}">
                                        @if ($errors->has('ta1'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('ta1') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="ta2">TA2</label>
                                        <input type="text" name="ta2" class="form-control @error('ta2') is-invalid @enderror" id="ta2" placeholder="TA2" maxlength="100" value="{{ old('ta2') }}" readonly>
                                        @if ($errors->has('ta2'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('ta2') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="ta3">TA3</label>
                                        <input type="text" name="ta3" class="form-control @error('ta3') is-invalid @enderror" id="ta3" placeholder="TA3" maxlength="100" value="{{ old('ta3') }}">
                                        @if ($errors->has('ta3'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('ta3') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="ta4">TA4</label>
                                        <input type="text" name="ta4" class="form-control @error('ta4') is-invalid @enderror" id="ta4" placeholder="TA4" maxlength="100" value="{{ old('ta4') }}">
                                        @if ($errors->has('ta4'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('ta4') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="ta5">TA5</label>
                                        <input type="text" name="ta5" class="form-control @error('ta5') is-invalid @enderror" id="ta5" placeholder="TA5" maxlength="100" value="{{ old('ta5') }}" readonly>
                                        @if ($errors->has('ta5'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('ta5') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Tabla</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function calculateTA2(peso) {
            if (peso <= 6.5) {
                return (19.3675630190815 - 3.60782704974002 * Math.log(11.047690861407 * peso));
            } else {
                return (8.89347102450592 * Math.pow(peso, -0.40564200758529));
            }
        }

        function calculateAverage(ta3, ta4) {
            const values = [ta3, ta4].filter(value => !isNaN(value));
            if (values.length === 0) {
                return 0.00;
            }
            const sum = values.reduce((acc, value) => acc + value, 0);
            return sum / values.length;
        }

        function validateDecimalInput(event) {
            const value = event.target.value;
            const regex = /^\d*\.?\d{0,2}$/;

            if (!regex.test(value)) {
                event.target.value = value.slice(0, -1);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const pesoInput = document.getElementById('peso');
            const ta2Input = document.getElementById('ta2');
            const ta3Input = document.getElementById('ta3');
            const ta4Input = document.getElementById('ta4');
            const ta5Input = document.getElementById('ta5');
            const ta1Input = document.getElementById('ta1');

            const inputs = [pesoInput, ta2Input, ta3Input, ta4Input, ta5Input, ta1Input];

            function updateTA2() {
                const pesoValue = parseFloat(pesoInput.value);
                if (!isNaN(pesoValue)) {
                    const ta2Value = calculateTA2(pesoValue);
                    ta2Input.value = ta2Value.toFixed(2);
                } else {
                    ta2Input.value = '0.00';
                }
            }

            function updateTA5() {
                const ta3Value = parseFloat(ta3Input.value);
                const ta4Value = parseFloat(ta4Input.value);
                const ta5Value = calculateAverage(ta3Value, ta4Value);
                ta5Input.value = ta5Value.toFixed(2);
            }

            pesoInput.addEventListener('input', updateTA2);
            ta3Input.addEventListener('input', updateTA5);
            ta4Input.addEventListener('input', updateTA5);

            inputs.forEach(input => {
                input.addEventListener('input', validateDecimalInput);
                input.value = '0.00';  // Initialize all fields to 0.00
            });
        });
    </script>
@endsection
{{-- 
<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
--}}