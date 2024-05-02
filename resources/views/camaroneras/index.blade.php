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
                        <a class="btn btn-primary" href="{{ url('camaroneras/create') }}"><i class="fas fa-plus-circle"></i> Nueva Camaronera</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

    </section>

@endsection