@extends('layouts.app')

@section('content')
<style>
    .login-container {
        max-width: 400px;
        margin: 5rem auto;
        padding: 2.5rem;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    /* Estilo actualizado para el logo circular */
    .login-logo {
        width: 110px;
        height: 110px;
        margin: 0 auto 1.5rem;
        display: block;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f3e8ff; /* Un borde lila sutil para resaltar */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-empresa {
        background-color: #a855f7;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-empresa:hover {
        background-color: #9333ea;
        color: white;
    }
</style>

<div class="container">
    <div class="login-container text-center">
        <img src="{{ asset('images/hero-home.jpg') }}" alt="Mamá en Línea Logo" class="login-logo">

        <h3 class="fw-bold mb-1">Acceso Empresas</h3>
        <p class="text-muted small mb-4">Inicia sesión para gestionar tus vacantes</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold text-secondary">Teléfono de la empresa</label>
                <input type="tel" name="phone" class="form-control form-control-lg fs-6" 
                       placeholder="6181234567" pattern="[0-9]{10}" required>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label small fw-bold text-secondary">Contraseña</label>
                <input type="password" name="password" class="form-control form-control-lg fs-6" 
                       placeholder="********" required>
            </div>

            <button type="submit" class="btn btn-empresa w-100 mb-3">
                ENTRAR COMO EMPRESA
            </button>

            <p class="small text-muted">
                ¿Aún no publicas con nosotros? <br>
                <a href="{{ route('register.empresa') }}" class="text-decoration-none fw-bold" style="color: #d63384;">
                    Crea una cuenta de empresa aquí
                </a>
            </p>
        </form>
    </div>
</div>
@endsection