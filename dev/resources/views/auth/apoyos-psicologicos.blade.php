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
    
        {{-- VISTA PARA USUARIOS LOGUEADOS --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold">Apoyo Psicológico Gratuito</h2>
            <p class="text-muted">Instituciones en Durango comprometidas con tu bienestar.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card info-card shadow-sm p-4">
                    <h5 class="fw-bold">Línea de la Vida</h5>
                    <p class="text-muted small">Atención en crisis las 24 horas.</p>
                    <hr class="my-3" style="opacity: 0.1;">
                    <strong>800 911 2000</strong>
                </div>
            </div>
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Instituto Estatal de las mujeres (Durango)</h5>
                <p class="text-muted small">Ofrecce asesoría psicologica individual y grupal localizada en Zaragoza 528 Sur, Centro, Durango.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="mb-3">
                    <small class="d-block text-muted">Telefono:</small>
                    <strong>618 137 4600</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Mujeres en la comunidad</h5>
                <p class="text-muted small">Programa del IEM que lleva la atención psicologica y legal en diversas colonias.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="mb-3">
                    <small class="d-block text-muted">Telefono</small>
                    <strong> 618 455 0653</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-5">
                <h5 class="fw-bold">Centro de Justicia para las Mujeres</h5>
                <p class="text-muted small">Ofrece atención psicologica gratuita, seguimiento individual y grupal.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="mb-2">
                    <small class="d-block text-muted">Telefono:</small>
                    <strong>618 137 3478</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">DIF Estatal Durango</h5>
                <p class="text-muted small">Ofrece psicoterapia individual, familiar y grupal en su centro de psicoterapia Familiar.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="mb-3">
                    <small class="d-block text-muted">Telefono:</small>
                    <strong>618 137 9101</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card shadow-sm p-4">
                <h5 class="fw-bold">Instituto Municipal de la Mujer</h5>
                <p class="text-muted small">Apoyo psicologico y emocional para mujeres enfatizando la reconstruccion tras la violencia.</p>
                <hr class="my-3" style="opacity: 0.1;">
                <div class="mb-3">
                    <small class="d-block text-muted">Telefono:</small>
                    <strong>618 137 81 97</strong>
                </div>
            </div>
        </div>
    </div>
        </div>
</div>
@endsection {{-- Y cerrar la sección correctamente --}}