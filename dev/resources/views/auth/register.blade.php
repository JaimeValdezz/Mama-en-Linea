@extends('layouts.app')

@section('content')
<style>
    body { background-color: #ffffff; }
    
    .register-card {
        max-width: 450px;
        border: 1px solid #f3e8ff; /* Borde lila suave consistente */
        border-radius: 20px;
        padding: 40px;
        background: #fff;
        margin-top: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .logo-container {
        width: 120px;
        height: 120px;
        background-color: #fff;
        border: 1px solid #e9d5ff;
        border-radius: 50%;
        margin: 0 auto 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 12px;
        margin-bottom: 5px;
    }

    .btn-register {
        background-color: #f3ccff; 
        color: #9333ea;
        border: none;
        border-radius: 10px;
        padding: 12px;
        width: 100%;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 15px;
        transition: 0.3s;
    }

    .btn-register:hover {
        background-color: #e9d5ff;
        transform: translateY(-1px);
    }

    .label-custom {
        font-size: 0.85rem;
        color: #4b5563;
        margin-bottom: 5px;
        display: block;
        font-weight: 500;
    }

    .footer-link {
        font-size: 0.85rem;
        color: #333;
        text-align: center;
        margin-top: 20px;
    }

    .footer-link a {
        color: #db2777; /* Rosa fuerte para consistencia */
        text-decoration: none;
        font-weight: 600;
    }

    .empresa-link {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f3e8ff;
        font-size: 0.85rem;
    }
</style>

<div class="container d-flex justify-content-center">
    <div class="register-card shadow-sm">
        
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm mb-4" style="border-radius: 10px;">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="logo-container">
            <img src="{{ asset('images/hero-home.jpg') }}" alt="Logo Mamá en Línea" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="hidden" name="rol" value="usuario">

            <div class="mb-3 text-start">
                <label class="label-custom">Nombre y apellido:</label>
                <input type="text" name="name" class="form-control form-control-custom" value="{{ old('name') }}" required placeholder="Tu nombre completo">
            </div>

            <div class="mb-3 text-start">
                <label class="label-custom">Teléfono:</label>
                <input type="text" name="phone" class="form-control form-control-custom" value="{{ old('phone') }}" required placeholder="Ej: 6181234567">
            </div>

            <div class="mb-3 text-start">
                <label class="label-custom">Contraseña:</label>
                <input type="password" name="password" class="form-control form-control-custom" required placeholder="Crea una contraseña">
            </div>

            <button type="submit" class="btn-register shadow-sm">REGISTRARSE</button>

            <div class="footer-link">
                <div>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></div>
                
                <div class="empresa-link">
                    <span class="text-muted">¿Eres una empresa?</span><br>
                    <a href="{{ route('register.empresa') }}" style="color: #9333ea; font-weight: bold; text-decoration: none;">
                        <i class="bi bi-briefcase-fill me-1"></i> ¡Regístrate como empresa aquí!
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection