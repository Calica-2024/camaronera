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
                        <a class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-plus-circle"></i> Ingreso De Inventario
                        </a>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Ingreso de Inventario</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('inventario/store/bal', [$camaronera, $balanceado]) }}" method="POST">
                                            @csrf <!-- Token CSRF para protección -->
                                            <div class="form-group">
                                                <label for="tipo_movimiento">Tipo de Movimiento</label>
                                                <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <option value="entrada">Entrada</option>
                                                    <option value="salida">Salida</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="test" class="form-control" id="cantidad" name="cantidad" placeholder="Ingrese la cantidad" required oninput="decimales(this)">
                                            </div>
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese una descripción" maxlength="250" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1>{{ $balanceado->nombre }}  {{ $balanceado->marca }} {{ $balanceado->presentacion . ' ' . $balanceado->unidad_medida  }} <strong>Stock: {{ $stock }}KG - Sacos: {{ $stock/$balanceado->presentacion }}</strong></h1>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Movimiento</th>
                            <th>Dia Producción</th>
                            <th>Cantidad</th>
                            <th>Sacos</th>
                            <th>KG</th>
                            <th>Fecha</th>
                            <th>Creado/Actualizado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movimientos as $item)
                            <tr>
                                <td>{{ $item->tipo_movimiento }}</td>
                                <td>
                                    @if ($item->id_p_real)
                                        <a href="{{ url('/producciones', $item->dia_real->id_produccion) }}">
                                            Dia: {{ $item->dia_real->num_dia }} : PS: {{ $item->dia_real->produccion->piscina->nombre }}
                                        </a>
                                    @else
                                        <!-- Puedes poner un texto alternativo o dejar la celda vacía -->
                                        Movimiento Manual
                                    @endif
                                </td>
                                <td>{{ $item->cantidad }}KG</td>
                                <td>{{ $item->cantidad/$balanceado->presentacion }}</td>
                                <td>{{ $balanceado->presentacion }}KG</td>
                                <td>{{ $item->fecha_movimiento }}</td>
                                <td>{{ $item->created_at . '/' . $item->updated_at  }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection