@extends('template.login')
@section('contenido')

    <div class="register-box">
        <div class="register-logo">
            <a href="#"><b>Calica</b>Control</a>
        </div>
        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Iniciar Sesión</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 m-auto">
                            <button type="submit" class="btn btn-primary btn-block">Acceder</button>
                        </div>
                    </div>
                </form>
                {{-- <a href="{{ route('login') }}" class="text-center">Iniciar Sesión</a> --}}
            </div>
        </div>
    </div>

@endsection