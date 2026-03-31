@extends('layouts.app')

@section('content')
<style>
    body { background-color: #ffffff; }
    
    .login-card {
        max-width: 400px;
        border: 1px solid #f3e8ff; 
        border-radius: 20px;
        padding: 40px;
        background: #fff;
        margin: 50px auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .logo-container {
        width: 110px;
        height: 110px;
        border: 1px solid #e9d5ff;
        border-radius: 50%;
        margin: 0 auto 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #fff; /* Fondo blanco por si la imagen es PNG transparente */
    }

    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 10px 15px;
        background-color: #fff;
    }

    .btn-login {
        background-color: #f3ccff; 
        color: #a855f7;
        border: none;
        border-radius: 10px;
        padding: 12px;
        width: 100%;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 10px;
        transition: 0.3s;
    }

    .btn-login:hover {
        background-color: #e9d5ff;
        transform: translateY(-1px);
    }

    .label-custom {
        font-size: 0.9rem;
        color: #4b5563;
        margin-bottom: 5px;
        display: block;
        font-weight: 500;
    }

    .footer-links {
        font-size: 0.85rem;
        text-align: center;
        margin-top: 20px;
    }

    .footer-links a {
        color: #db2777; /* Rosa fuerte para los links, igual que en registro */
        text-decoration: none;
        font-weight: 500;
    }
</style>

<div class="container">
    <div class="login-card">
        <div class="logo-container">
            <img src="{{ asset('images/hero-home.jpg') }}" alt="Logo Mamá en Línea" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3 text-start">
                <label class="label-custom">Teléfono:</label>
                <input type="text" name="phone" class="form-control form-control-custom @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required autofocus placeholder="6671234567">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 text-start">
                <label class="label-custom">Contraseña:</label>
                <input type="password" name="password" class="form-control form-control-custom @error('password') is-invalid @enderror" required placeholder="********">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-login shadow-sm">
                INICIAR SESIÓN
            </button>

            <div class="footer-links">
                <p class="mb-1">¿Olvidaste tu contraseña? <a href="#">Recuperar contraseña</a></p>
                <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
            </div>
        </form>
    </div>
</div>
@endsection