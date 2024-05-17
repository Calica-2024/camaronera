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
                            <th>Piscina</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Producción</th>
                            <th class="text-center">Producciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producciones as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td><a href="{{ url('/producciones/piscina',$item) }}">{{ $item->nombre }} <i class="fas fa-person-booth"></i></a></td>
                                <td class="text-center">{{ $item->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                                @php
                                    $produccionActiva = $producciones->where('id_piscina', $item->id)->where('estado', 1)->first();
                                @endphp
                                <td class="text-center">{{ $produccionActiva ? 'SI' : 'NO' }}</td>
                                <td class="text-center">{{ count($producciones->where('id_piscina', $item->id)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection