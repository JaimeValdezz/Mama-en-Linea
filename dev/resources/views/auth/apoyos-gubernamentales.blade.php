@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8f9fa; }
    .info-card { border: none; border-radius: 15px; transition: transform 0.2s; height: 100%; }
    .info-card:hover { transform: translateY(-5px); }
    .icon-box { width: 60px; height: 60px; background-color: #f3ccff; color: #9333ea; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
    .protected-card { border-radius: 20px; max-width: 600px; border: none; }
    .btn-login-custom { background-color: #f3e5f5; color: #9333ea; border: none; font-weight: bold; padding: 12px; border-radius: 10px; }
</style>

<div class="container py-5">
    
    {{-- VISTA PARA PROGRAMAS DE GOBIERNO --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold">Programas de Gobierno y Apoyos</h2>
        <p class="text-muted">Instituciones en Durango con programas vigentes para tu beneficio.</p>
    </div>

    <div class="row g-4">
        {{-- Tarjeta 1: Tarjeta Madre --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Tarjeta Madre</h5>
                <p class="text-muted small">Programa social de la Secretaría de Bienestar (SEBISED)
                 que brinda apoyo económico directo a mujeres de 18 a 64 años, madres solteras o jefas de familia en los 39 municipios, priorizando pobreza y marginación. </p>
                <hr class="my-3" style="opacity: 0.1;">
            </div>
        </div>

        {{-- Tarjeta 2: Programa Esmeralda  --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Programa Esmeralda</h5>
                <p class="text-muted small">Servicio telefónico y de atención inmediata especializado en violencia familiar y de género.</p>
                <hr class="my-3" style="opacity: 0.1;">
            </div>
        </div>

        {{-- Tarjeta 3:Apoyo a Emprendedoras --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Apoyo a Emprendedoras</h5>
                <p class="text-muted small">A través del Instituto Estatal de las Mujeres (IEM), se articulan créditos productivos para fortalecer negocios dirigidos por mujeres.</p>
                <hr class="my-3" style="opacity: 0.1;">
            </div>
        </div>

        {{-- Tarjeta 4: Proyectos PAIMEF --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Proyectos PAIMEF</h5>
                <p class="text-muted small"> El IEM implementa proyectos anuales del Programa de Apoyo a las Instancias de Mujeres en las Entidades Federativas (PAIMEF) para prevenir la violencia y promover la igualdad.</p>
                <hr class="my-3" style="opacity: 0.1;">
            </div>
        </div>

        {{-- Tarjeta 5: STPS --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Secretaría del Trabajo (STPS)</h5>
                <p class="text-muted small">Apoyo para el empleo, vinculación laboral y capacitación técnica.</p>
                <hr class="my-3" style="opacity: 0.1;">
            </div>
        </div>

        {{-- Tarjeta 6: INMUJERES --}}
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Instituto Estatal de las Mujeres</h5>
                <p class="text-muted small">Créditos para emprendedoras y microcréditos "Mujeres con Valor".</p>
                <hr class="my-3" style="opacity: 0.1;">
            </div>
        </div>
    </div>
</div>
@endsection