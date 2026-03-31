@extends('layouts.app')

@section('content')
<style>
    /* Hero Section con Imagen Fija */
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                    url('{{asset("images/hero-home.jpg")}}');
        background-size: cover;
        background-position: center;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        border-radius: 0 0 50px 10px; /* Curva elegante al final de la imagen */
        margin-top: -24px; /* Compensa el padding del layout */
    }

    /* Estilo de las Cards de Navegación */
    .nav-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        background: #fdfaff;
        border: 1px solid #f3e8ff;
    }

    .nav-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(168, 85, 247, 0.15);
        border-color: #a855f7;
    }

    .card-icon-circle {
        width: 70px;
        height: 70px;
        background: #f3e8ff;
        color: #a855f7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.8rem;
    }

    .btn-acceso {
        background-color: #a855f7;
        color: white;
        border-radius: 12px;
        padding: 8px 20px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        display: inline-block;
        margin-top: 1rem;
    }

    .btn-acceso:hover {
        background-color: #7e22ce;
        color: white;
    }
</style>

{{-- Sección Hero --}}
<div class="hero-section shadow-sm">
    <div class="container">
        <h1 class="display-4 fw-bold">Bienvenida a Mamá en Línea</h1>
        <p class="fs-4">Tu red de apoyo para el crecimiento profesional y personal.</p>
    </div>
</div>

{{-- Sección Objetivo --}}
<div class="container text-center mb-5 py-5 bg-light rounded-4 shadow-sm">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="fw-bold mb-3" style="color: #a855f7;">Nuestro Objetivo</h2>
            <p class="fs-5 text-muted mb-4">
                En <strong>Mamá en Línea</strong>, nos dedicamos a empoderar a las madres duranguenses facilitando el acceso a herramientas que impulsen su desarrollo. 
                Nuestra misión es ser el puente entre tus metas y las oportunidades reales, ofreciendo un espacio seguro donde el crecimiento profesional no sacrifique tu bienestar familiar.
            </p>
            <hr class="w-25 mx-auto mb-4" style="opacity: 0.2;">
            <h5 class="fst-italic text-secondary">
                Explora los servicios que hemos diseñado pensando en ti:
            </h5>
        </div>
    </div>
</div>

{{-- Sección de Cards  --}}
<div class="container my-5 pb-5">
    <div class="row g-4">
        
        {{-- Card 1: Vacantes --}}
        <div class="col-md-4">
            <div class="card nav-card p-4 text-center">
                <div class="card-icon-circle">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <h4 class="fw-bold">Bolsa de Trabajo</h4>
                <p class="text-muted">Encuentra vacantes flexibles diseñadas para equilibrar tu vida laboral y familiar.</p>
                <a href="{{ route('vacantes.index') }}" class="btn-acceso">Ver Vacantes</a>
            </div>
        </div>

        {{-- Card 2: Apoyo Psicológico --}}
        <div class="col-md-4">
            <div class="card nav-card p-4 text-center">
                <div class="card-icon-circle">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <h4 class="fw-bold">Apoyo Psicológicos</h4>
                <p class="text-muted">Accede a recursos y especialistas que te acompañarán en tu bienestar emocional.</p>
                <a href="{{ route('apoyos.psicologicos') }}" class="btn-acceso">Solicitar Apoyo</a>
            </div>
        </div>

        {{-- Card 3: Apoyo Programas de Gobierno --}}
        <div class="col-md-4">
            <div class="card nav-card p-4 text-center">
                <div class="card-icon-circle">
                    <i class="bi bi-bank2"></i>
                </div>
                <h4 class="fw-bold">Programas de Gobierno</h4>
                <p class="text-muted">Información sobre programas sociales y becas vigentes para madres de familia.</p>
                <a href="{{ route('apoyos.gubernamentales')}}" class="btn-acceso">Ver Programas</a>
            </div>
        </div>

    </div> {{-- Cierre de la fila horizontal --}}
</div>

@endsection