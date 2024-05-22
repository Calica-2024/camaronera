@extends('template.template')
@section('contenido')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $vista }}</h1>
                </div>
                <div class="col-sm-6 ">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('producciones') }}">Producciones</a></li>
                        <li class="breadcrumb-item active">Piscinas</li>
                    </ol>
                    {{-- 
                        <div class="float-sm-right btn-group" role="group" aria-label="Basic outlined example">
                            <a class="btn btn-primary" href="{{ url('balanceados/create') }}"><i class="fas fa-plus-circle"></i> Nuevo Balanceado</a>
                        </div>
                    --}}
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Producciones</h3>
                <div class="card-tools">
                    <a href="{{ url('producciones/create', $piscina) }}" class="btn btn-primary"><i class="fas fa-water"></i> Nueva Producción</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Fecha Inicio</th>
                            <th class="text-center">Días Ciclo</th>
                            <th class="text-center">Densidad Siembra</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Producciones</th>
                            <th style="width: 10px"><i class="fas fa-cogs"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producciones as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td><a href="{{ url('producciones',$item) }}">{{ $item->fecha }} <i class="fas fa-person-booth"></i></a></td>
                                <td class="text-center">{{ $item->dias_ciclo }}</td>
                                <td class="text-center">{{ $item->densidad }}</td>
                                <td class="text-center">{{ $item->estado == 1 ? 'Activo' : 'Finalizada' }}</td>
                                <td class="text-center">{{ count($producciones->where('id_piscina', $item->id)) }}</td>
                                <td>
                                    <a class="" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('/producciones/'.$item->id.'/edit') }}">Editar</a>
                                        <a class="dropdown-item" href="{{ url('/producciones/'.$item->id) }}">Consultar</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delModal{{ $item['id'] }}">Eliminar</a>
                                    </div>
                                </td>
                                <div class="modal fade" id="delModal{{ $item['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Eliminar producción</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h2 class="text-wrap">La producción: <strong>{{ $item->fecha . ' - ' . $item->fecha . ' días' }}</strong>, se eliminará</h2>
                                                <h2 class="text-danger">¿Desea Continuar?</h2>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <form action="{{ url('/producciones/'.$item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger boton-procesar">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection