@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8f9fa; }
    /* Estilo de las tarjetas blancas con hover */
    .info-card { border: none; border-radius: 15px; transition: transform 0.2s; height: 100%; background-color: #ffffff; }
    .info-card:hover { transform: translateY(-5px); }
    
    /* Icono circular - Usaremos un azul/celeste para gobierno */
    .icon-box { 
        width: 60px; 
        height: 60px; 
        background-color: #e0f2fe; 
        color: #0284c7; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 1.5rem; 
        margin-bottom: 20px; 
    }
    .btn-detalle { 
        background-color: #f0f9ff; 
        color: #0284c7; 
        border: none; 
        font-weight: bold; 
        padding: 8px 15px; 
        border-radius: 8px; 
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    .btn-detalle:hover { background-color: #0284c7; color: white; }
</style>

<div class="container py-5">
    
    {{-- ENCABEZADO --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold">Apoyos Gubernamentales</h2>
        <p class="text-muted">Programas de asistencia y beneficios vigentes para las familias en Durango.</p>
    </div>

    <div class="row g-4">
        
        {{-- Tarjeta 1: Tarjeta Madre --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <div class="icon-box"><i class="bi bi-card-checklist"></i></div>
                <h5 class="fw-bold">Tarjeta Madre</h5>
                <p class="text-muted small">Apoyo económico directo de SEBISED para mujeres de 18 a 64 años en situación de vulnerabilidad.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary font-bold">Secretaría de Bienestar</small>
                    <a href="#" class="btn-detalle">Ver más</a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Programa Esmeralda --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <div class="icon-box"><i class="bi bi-shield-check"></i></div>
                <h5 class="fw-bold">Programa Esmeralda</h5>
                <p class="text-muted small">Atención ciudadana y acompañamiento especializado para la seguridad de las mujeres y sus familias.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary font-bold">Seguridad Pública</small>
                    <a href="#" class="btn-detalle">Solicitar</a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Apoyo a Emprendedoras --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <div class="icon-box"><i class="bi bi-shop"></i></div>
                <h5 class="fw-bold">Apoyo a Emprendedoras</h5>
                <p class="text-muted small">Créditos productivos a través del IEM para fortalecer pequeños negocios dirigidos por mujeres.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary font-bold">Inst. de la Mujer</small>
                    <a href="#" class="btn-detalle">Créditos</a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 4: Proyectos PAIMEF --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <div class="icon-box"><i class="bi bi-people-fill"></i></div>
                <h5 class="fw-bold">Proyectos PAIMEF</h5>
                <p class="text-muted small">Programas anuales para la prevención de violencia y promoción de la igualdad de género.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary font-bold">Nivel Federal/Estatal</small>
                    <a href="#" class="btn-detalle">Información</a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 5: Secretaría del Trabajo (STPS) --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <div class="icon-box"><i class="bi bi-briefcase-fill"></i></div>
                <h5 class="fw-bold">Bolsa de Trabajo STPS</h5>
                <p class="text-muted small">Vinculación laboral, capacitación técnica y ferias de empleo para jefas de familia.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary font-bold">STPS Durango</small>
                    <a href="#" class="btn-detalle">Vacantes</a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 6: Becas Benito Juárez --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <div class="icon-box"><i class="bi bi-mortarboard-fill"></i></div>
                <h5 class="fw-bold">Becas Estudiantiles</h5>
                <p class="text-muted small">Apoyo económico para estudiantes de educación básica y media superior en zonas prioritarias.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary font-bold">Gobierno Federal</small>
                    <a href="#" class="btn-detalle">Requisitos</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection