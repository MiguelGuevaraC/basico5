@extends('layouts.guest')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h4 mb-4 fw-bold text-center">
                            <i class="bi bi-box-seam me-2"></i>{{ config('app.name') }}
                        </h1>
                        <h2 class="h5 mb-3">Iniciar sesión</h2>
                        <form method="post" action="{{ route('login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="username">Usuario</label>
                                <input class="form-control @error('username') is-invalid @enderror"
                                       type="text"
                                       id="username"
                                       name="username"
                                       value="{{ old('username') }}"
                                       required
                                       autocomplete="username">
                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Contraseña</label>
                                <input class="form-control @error('password') is-invalid @enderror"
                                       type="password"
                                       id="password"
                                       name="password"
                                       required
                                       autocomplete="current-password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Entrar</button>
                        </form>
                        <div class="text-muted small mt-3">
                            Usuario: administrador / Contraseña: admin123
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
