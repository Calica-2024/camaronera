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
                        <li class="breadcrumb-item"><a href="{{ url('balanceados') }}">Balanceados</a></li>
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
                            <h3 class="card-title">Creación de Balanceados <small>Llenar el formulario</small></h3>
                        </div>
                        <form class="forms-sample" action="{{ url('balanceados') }}" method="POST">
                            @method('POST')
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre Del Balanceado</label>
                                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" id="nombre" placeholder="Nombre Del Balanceado" maxlength="25" autofocus value="{{ old('nombre') }}">
                                    @if ($errors->has('nombre'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('nombre') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="tipo">Tipo Del Balanceado</label>
                                    <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror" id="tipo" placeholder="Tipo Del Balanceado" maxlength="25" autofocus value="{{ old('tipo') }}">
                                    @if ($errors->has('tipo'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('tipo') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="marca">Marca</label>
                                        <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror" id="marca" placeholder="Marca Del Balanceado" maxlength="25" value="{{ old('marca') }}">
                                        @if ($errors->has('marca'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('marca') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="etapa">Etapa</label>
                                        <input type="text" name="etapa" class="form-control @error('etapa') is-invalid @enderror" id="etapa" placeholder="Etapa Del Balanceado" maxlength="50" value="{{ old('etapa') }}">
                                        @if ($errors->has('etapa'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('etapa') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nomenclatura">Nomenclatura</label>
                                    <input type="text" name="nomenclatura" class="form-control @error('nomenclatura') is-invalid @enderror" id="nomenclatura" placeholder="Nomenclatura" maxlength="50" value="{{ old('nomenclatura') }}">
                                    @if ($errors->has('nomenclatura'))
                                        <div class="invalid-feedback" style="display: inline !important">
                                            @foreach ($errors->get('nomenclatura') as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="unidad_medida">Unidad Medida</label>
                                        <input type="text" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" id="unidad_medida" placeholder="Unidad De Medida" maxlength="15" value="{{ old('unidad_medida') }}">
                                        @if ($errors->has('unidad_medida'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('unidad_medida') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="presentacion">Presentación</label>
                                        <input type="text" name="presentacion" class="form-control @error('presentacion') is-invalid @enderror" id="presentacion" placeholder="Presentación" maxlength="100" value="{{ old('presentacion') }}">
                                        @if ($errors->has('presentacion'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('presentacion') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="precio">Precio</label>
                                        <input type="text" name="precio" class="form-control @error('precio') is-invalid @enderror" id="precio" placeholder="Presentación" maxlength="100" value="{{ old('precio') }}">
                                        @if ($errors->has('precio'))
                                            <div class="invalid-feedback" style="display: inline !important">
                                                @foreach ($errors->get('precio') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Balanceado</button>
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