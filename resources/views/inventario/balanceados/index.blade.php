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
                            <th>Camaronera</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($camaronerasUser as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td style="font-size: 25px"><a href="{{ url('/inventario/balanceados', $item->camaronera->id) }}">{{ $item->camaronera->nombre }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection