@extends('layouts.app')

@section('content')
<style>
    /* Estilos personalizados para lograr el efecto de la imagen */
    .publish-container {
        max-width: 600px; /* Ancho controlado para centrarlo */
        margin: 4rem auto; /* Margen superior e inferior para despejarlo */
        background: #fff;
        padding: 3rem; /* Espaciado interno generoso */
        border-radius: 25px; /* Bordes muy redondeados como la imagen */
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); /* Sombra muy sutil */
    }

    .form-control-custom {
        border-radius: 10px; /* Bordes suaves en los inputs */
        padding: 12px;
        border: 1px solid #e2e8f0; /* Color de borde suave */
        margin-bottom: 1.2rem; /* Espaciado entre campos */
    }

    .form-control-custom:focus {
        border-color: #f3e8ff;
        box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
    }

    .btn-publish {
        background-color: #f3e8ff; /* Color lila suave de la imagen */
        color: #9333ea; /* Texto lila más oscuro para contraste */
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: bold;
        width: 100%;
        transition: 0.3s;
        margin-top: 1rem;
    }

    .btn-publish:hover {
        background-color: #e9d5ff;
    }
</style>

<div class="container">
    <div class="publish-container">
        <div class="text-center mb-5">
            <h3 class="fw-bold mb-1">Publicar Vacante</h3>
            <p class="text-muted small">Ingresa los detalles de la nueva oportunidad laboral</p>
        </div>

        <form action="{{ route('vacantes.guardar') }}" method="POST">
            @csrf
            
            <div class="text-start">
                <label class="form-label small fw-bold text-secondary">Nombre de la Empresa</label>
                <input type="text" name="nombre_empresa" class="form-control form-control-custom" 
                       placeholder="EJ. Pollo feliz" required>
            </div>

            <div class="text-start">
                <label class="form-label small fw-bold text-secondary">Nombre de la Vacante</label>
                <input type="text" name="titulo" class="form-control form-control-custom" 
                       placeholder="Ej. Mesera" required>
            </div>

            <div class="row">
                <div class="col-md-6 text-start">
                    <label class="form-label small fw-bold text-secondary">Sueldo Mensual</label>
                    <input type="number" name="sueldo" class="form-control form-control-custom" 
                           placeholder="2,000" required>
                </div>

                <div class="col-md-6 text-start">
                    <label class="form-label small fw-bold text-secondary">Lugar de la Vacante</label>
                    <input type="text" name="lugar" class="form-control form-control-custom" 
                           placeholder="Ej. Durango" required>
                </div>
            </div>

            <div class="text-start">
                <label class="form-label small fw-bold text-secondary">Descripción de la Vacante</label>
                <textarea name="descripcion" class="form-control form-control-custom" 
                          rows="4" placeholder="Describe actividades y requisitos..." required></textarea>
            </div>

            <div class="text-start">
                <label class="form-label small fw-bold text-secondary">Datos de Contacto</label>
                <input type="text" name="contacto" class="form-control form-control-custom" 
                       placeholder="Email o Teléfono" required>
            </div>

            <button type="submit" class="btn btn-publish">
                PUBLICAR VACANTE
            </button>
        </form>
    </div>
</div>
@endsection