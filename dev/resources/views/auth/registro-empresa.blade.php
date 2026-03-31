@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 5rem;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/hero-home.jpg') }}" 
                             alt="Logo Mamá en línea" 
                             class="shadow-sm mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 4px solid #fdf2f8;">
                        
                        <h3 class="fw-bold">Registro de Empresa</h3>
                        <p class="text-muted small">Crea tu cuenta para publicar vacantes</p>
                    </div>

                    <form action="{{ route('register.empresa.post') }}" method="POST">
                        @csrf
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold text-secondary">Nombre de la Empresa</label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6" placeholder="Ej. Pollo Feliz" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold text-secondary">Teléfono (10 dígitos)</label>
                            <input type="tel" name="phone" class="form-control form-control-lg fs-6" placeholder="6181234567" pattern="[0-9]{10}" required>
                        </div>

                        <div class="mb-4 text-start">
                            <label class="form-label small fw-bold text-secondary">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg fs-6" placeholder="Minimo 6 caracteres" required>
                        </div>

                        <button type="submit" class="btn w-100 py-3 fw-bold" style="background: #db2777; color: white; border-radius: 12px;">
                            REGISTRAR EMPRESA
                        </button>

                        <div class="text-center mt-4">
                            <p class="small">¿Ya tienes cuenta? 
                                <a href="{{ route('empresa.login') }}" class="text-decoration-none fw-bold" style="color: #db2777;">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection