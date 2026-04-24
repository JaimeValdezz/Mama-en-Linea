@extends('layouts.app')
@section('content')
<div class="container text-center py-5">
    <div class="logo-circle mx-auto mb-4" style="width: 150px; height: 150px; border-radius: 50%; background: #f3e8ff; border: 6px solid #c084fc; overflow: hidden;">
        <img src="{{ asset('img/logo-mama.png') }}" alt="Mamá en línea" style="width: 80%; height: 80%; object-fit: contain; margin-top: 15px;">
    </div>

    <h1 class="text-pink fw-bold mb-4" style="font-family: 'Pacifico', cursive; font-size: 3.5rem;">Mamá en línea</h1>

    <p class="lead mb-5">Apoyo para madres: vacantes, ayuda psicológica y gubernamental</p>

    <!-- Formulario de registro -->
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Nombre y apellidos:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Teléfono:</label>
                    <input type="tel" name="phone" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-lg btn-pink w-100" style="background: #c084fc; color: white;">REGISTRARSE</button>
            </form>

            <p class="text-center mt-3 text-muted">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-pink">Iniciar sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection