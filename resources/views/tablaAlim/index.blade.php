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
                    <div class="float-sm-right btn-group" role="group" aria-label="Basic outlined example">
                        <a class="btn btn-primary" href="{{ url('tabla_alimentacion/create') }}"><i class="fas fa-plus-circle"></i> Nueva Tabla</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>Peso</th>
                            <th>TA1</th>
                            <th>TA2</th>
                            <th>TA3</th>
                            <th>TA4</th>
                            <th>TA5</th>
                            <th style="width: 40px"><i class="fas fa-cogs"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="text-center">
                                <td>{{ $item->pesos }}</td>
                                <td>{{ $item->ta1 }}</td>
                                <td>{{ $item->ta2 }}</td>
                                <td>{{ $item->ta3 }}</td>
                                <td>{{ $item->ta4 }}</td>
                                <td>{{ $item->ta5 }}</td>
                                <td>
                                    <a class="" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('/tabla_alimentacion/'.$item->id.'/edit') }}">Editar</a>
                                        <a class="dropdown-item" href="{{ url('/tabla_alimentacion/'.$item->id) }}">Consultar</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delModal{{ $item['id'] }}">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="delModal{{ $item['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Eliminar Tabla</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h2 class="text-wrap">La tabla: <strong>{{ $item->pesos }}</strong>, se eliminará</h2>
                                            <h2 class="text-danger">¿Desea Continuar?</h2>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <form action="{{ url('/tabla_alimentacion/'.$item->id) }}" method="POST">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection