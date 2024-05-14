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
                        <li class="breadcrumb-item"><a href="{{ url('camaroneras') }}">Camaroneras</a></li>
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
                            <h3 class="card-title">Información De La Camaronera</h3>
                        </div>
                        <form class="forms-sample" action="{{ url('camaroneras') }}" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre De La Camaronera</label>
                                    <input type="text" name="nombre" class="form-control" id="nombre" value="{{ $camaronera->nombre }}" maxlength="25" disabled autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" name="direccion" class="form-control" id="direccion" value="{{ $camaronera->direccion }}" maxlength="100" disabled>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="estado" class="custom-control-input" id="exampleCheck1" {{ $camaronera->estado == 1 ? 'checked' : '' }} disabled>
                                        <label class="custom-control-label" for="exampleCheck1">Activo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ url('camaroneras/'.$camaronera->id.'/edit') }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios</h3>
                        <div class="card-tools">
                            <a href="" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModal"><i class="fas fa-user-plus"></i> Asignar</a>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Asignar Usuarios</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">#</th>
                                                        <th>Usuario</th>
                                                        <th>Asignar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $item)
                                                        <tr>
                                                            <td>{{ $loop->index+1 }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td><a href="{{ url('asignarUser',[$camaronera, $item]) }}">Asignnar</a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Usuario</th>
                                    <th class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $usuario->usuario->name }}</td>
                                        <td class="text-center"><a href="{{ url('deleteUserCam',$usuario) }}"><i class="fas fa-user-times"></i> Eliminar</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <h3 class="card-title">Piscinas</h3>
                        <div class="card-tools">
                            <a href="" class="btn btn-primary" data-toggle="modal" data-target="#piscinas"><i class="fas fa-water"></i> Añadir Piscina</a>
                            <!-- Modal -->
                            <div class="modal fade" id="piscinas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Añadir Piscina</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form class="forms-sample" action="{{ url('piscinas') }}" method="POST">
                                            @method('POST')
                                            @csrf
                                            <input type="hidden" name="id_camaronera" value="{{ $camaronera->id }}">
                                            <div class="modal-body">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="numero">Número de Piscina</label>
                                                        <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" placeholder="Número De Piscina" maxlength="3" autofocus>
                                                        @if ($errors->has('numero'))
                                                            <div class="invalid-feedback" style="display: inline !important">
                                                                @foreach ($errors->get('numero') as $error)
                                                                    {{ $error }}<br>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre/Codigo de Piscina</label>
                                                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" id="nombre" placeholder="Nombre/Codigo De La Piscina" maxlength="15">
                                                        @if ($errors->has('nombre'))
                                                            <div class="invalid-feedback" style="display: inline !important">
                                                                @foreach ($errors->get('nombre') as $error)
                                                                    {{ $error }}<br>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="area_ha">Area (HA)</label>
                                                        <input type="text" name="area_ha" class="form-control @error('area_ha') is-invalid @enderror" id="area_ha" placeholder="Area (HA)" maxlength="10" autofocus>
                                                        @if ($errors->has('area_ha'))
                                                            <div class="invalid-feedback" style="display: inline !important">
                                                                @foreach ($errors->get('area_ha') as $error)
                                                                    {{ $error }}<br>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Piscina</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">Num.</th>
                                    <th>Nombre</th>
                                    <th>Area (HA)</th>
                                    <th class="text-center">Editar</th>
                                    <th class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($piscinas as $piscina)
                                    <tr>
                                        <td>{{ $piscina->numero }}</td>
                                        <td>{{ $piscina->nombre }}</td>
                                        <td>{{ $piscina->area_ha }}</td>
                                        <td class="text-center">
                                            <a href="#" data-toggle="modal" data-target="#piscinasEdit">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <form id="deleteForm{{ $piscina->id }}" action="{{ url('/piscinas/'.$piscina->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" onclick="document.getElementById('deleteForm{{ $piscina->id }}').submit(); return false;" class="text-danger">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    <div class="modal fade" id="piscinasEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Editar Piscina</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="forms-sample" action="{{ url('piscinas',$piscina) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="id_camaronera" value="{{ $camaronera->id }}">
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="numero">Número de Piscina</label>
                                                                <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" placeholder="Número De Piscina" maxlength="3" value="{{ $piscina->numero }}" autofocus>
                                                                @if ($errors->has('numero'))
                                                                    <div class="invalid-feedback" style="display: inline !important">
                                                                        @foreach ($errors->get('numero') as $error)
                                                                            {{ $error }}<br>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="nombre">Nombre/Codigo de Piscina</label>
                                                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" id="nombre" placeholder="Nombre/Codigo De La Piscina" value="{{ $piscina->nombre }}" maxlength="15">
                                                                @if ($errors->has('nombre'))
                                                                    <div class="invalid-feedback" style="display: inline !important">
                                                                        @foreach ($errors->get('nombre') as $error)
                                                                            {{ $error }}<br>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="area_ha">Area (HA)</label>
                                                                <input type="text" name="area_ha" class="form-control @error('area_ha') is-invalid @enderror" id="area_ha" placeholder="Area (HA)" maxlength="10" value="{{ $piscina->area_ha }}">
                                                                @if ($errors->has('area_ha'))
                                                                    <div class="invalid-feedback" style="display: inline !important">
                                                                        @foreach ($errors->get('area_ha') as $error)
                                                                            {{ $error }}<br>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-0">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="estado" class="custom-control-input" id="exampleCheck1" {{ $piscina->estado == 1 ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="exampleCheck1">Activo</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Piscina</button>
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
            </div>
        </div>
    </section>

@endsection
{{-- 
<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
--}}
