@extends('template.template')
@section('contenido')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $vista }}</h1>
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
                            <th>Nombre</th>
                            <th>Marca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($balanceados as $item)
                            <tr>
                                <td ><a href="{{ url('/inventario/bal', [$camaronera, $item]) }}">{{ $loop->index+1 }}</a></td>
                                <td ><a href="{{ url('/inventario/bal', [$camaronera, $item]) }}">{{ $item->nombre }}</a></td>
                                <td ><a href="{{ url('/inventario/bal', [$camaronera, $item]) }}">{{ $item->marca }}</a></td>
                                <td ><a href="{{ url('/inventario/bal', [$camaronera, $item]) }}">{{ $item->presentacion . ' ' . $item->unidad_medida  }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection