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
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Camaronera</th>
                            <th class="text-center">Piscinas</th>
                            <th class="text-center">Producciones Activas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($camaroneras as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td><a href="{{ url('/producciones/camaronera',$item) }}">{{ $item->nombre }} <i class="fas fa-person-booth"></i></a></td>
                                <td class="text-center">{{ count($pisinas->where('id_camaronera', $item->id)) }}</td>
                                @php
                                    $piscinasId = $pisinas->where('id_camaronera', $item->id)->pluck('id');
                                @endphp
                                <td class="text-center">{{ count($producciones->whereIn('id_piscina', $piscinasId)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection