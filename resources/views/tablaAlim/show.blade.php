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
                        <li class="breadcrumb-item"><a href="{{ url('tabla_alimentacion') }}">Tabala Alimentación</a></li>
                        <li class="breadcrumb-item active">Consultar</li>
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
                            <h3 class="card-title">Información Tabala Alimentación</h3>
                        </div>
                        <form class="forms-sample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="peso">Peso</label>
                                        <input type="text" name="pesos" class="form-control @error('pesos') is-invalid @enderror" id="peso" placeholder="Peso" maxlength="15" value="{{ $tabla->pesos }}" readonly>
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
                                        <input type="text" name="ta1" class="form-control @error('ta1') is-invalid @enderror" id="ta1" placeholder="TA1" maxlength="100" value="{{ $tabla->ta1 }}" readonly>
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
                                        <input type="text" name="ta2" class="form-control @error('ta2') is-invalid @enderror" id="ta2" placeholder="TA2" maxlength="100" value="{{ $tabla->ta2 }}" readonly>
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
                                        <input type="text" name="ta3" class="form-control @error('ta3') is-invalid @enderror" id="ta3" placeholder="TA3" maxlength="100" value="{{ $tabla->ta3 }}" readonly>
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
                                        <input type="text" name="ta4" class="form-control @error('ta4') is-invalid @enderror" id="ta4" placeholder="TA4" maxlength="100" value="{{ $tabla->ta4 }}" readonly>
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
                                        <input type="text" name="ta5" class="form-control @error('ta5') is-invalid @enderror" id="ta5" placeholder="TA5" maxlength="100" value="{{ $tabla->ta5 }}" readonly>
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
                                <a href="{{ url('tabla_alimentacion/'.$tabla->id.'/edit') }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
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
