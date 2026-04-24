@extends('layouts.app')

@section('content')
<style>
    .vacante-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        height: 100%;
        transition: all 0.3s ease;
    }
    .vacante-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .btn-postular-lila {
        background-color: #f3e8ff;
        color: #a855f7;
        border: none;
        border-radius: 20px;
        padding: 6px 20px;
        font-size: 0.85rem;
        font-weight: 700;
        transition: 0.2s;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-postular-lila:hover {
        background-color: #e9d5ff;
        color: #7e22ce;
    }
    .btn-postulado {
        background-color: #d1fae5;
        color: #059669;
        pointer-events: none;
    }
    .card-img-placeholder {
        background-color: #f3f4f6;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .card-img-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .price-tag {
        font-size: 1.25rem;
        font-weight: 800;
        color: #000;
    }
    .footer-help {
        background-color: #fdf0f6;
        padding: 2.5rem;
        margin-top: 4rem;
        border-radius: 8px;
    }
</style>

<div class="container mt-4">
    {{-- 1. Botón para empresas: Solo visible para rol empresa --}}
    @if(Session::has('firebase_user') && Session::get('firebase_user.rol') === 'empresa')
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('vacantes.crear') }}" class="btn-postular-lila shadow-sm py-2 px-4">
                <i class="bi bi-plus-circle me-2"></i>Publicar Nueva Vacante
            </a>
        </div>
    @endif

    {{-- 2. Listado de Vacantes --}}
    <div class="row g-4">
        @forelse($vacantes as $vacante)
            <div class="col-md-4">
                <div class="vacante-card shadow-sm">
                    <div class="card-img-placeholder">
                        @if(!empty($vacante['foto_url']))
                            <img src="{{ $vacante['foto_url'] }}" alt="{{ $vacante['nombre_empresa'] ?? 'Empresa' }}">
                        @else
                            <i class="bi bi-briefcase" style="font-size: 3rem; color: #d1d5db;"></i>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        {{-- Información de Empresa y Lugar --}}
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <p class="text-muted small mb-0">{{ $vacante['nombre_empresa'] ?? 'Empresa Registrada' }}</p>
                            <span class="badge bg-light text-dark fw-normal" style="font-size: 0.7rem;">
                                <i class="bi bi-geo-alt"></i> {{ $vacante['lugar'] ?? 'Ubicación no especificada' }}
                            </span>
                        </div>

                        {{-- Título y Sueldo --}}
                        <h5 class="fw-bold mb-1 text-dark">{{ $vacante['titulo'] ?? 'Título de Vacante' }}</h5>
                        <h4 class="price-tag mb-3">${{ number_format($vacante['sueldo'] ?? 0, 0) }}</h4>
                        
                        {{-- Descripción con límite de líneas --}}
                        <div class="small text-secondary mb-3" style="height: 60px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                            {{ $vacante['descripcion'] ?? 'Sin descripción detallada disponible.' }}
                        </div>

                        {{-- Datos de Contacto (Solo si está logueado) --}}
                        @if(Session::has('firebase_user'))
                            <div class="alert alert-light p-2 mb-3 border-0" style="font-size: 0.8rem; background-color: #f8fafc;">
                                <strong><i class="bi bi-person-lines-fill"></i> Contacto:</strong> {{ $vacante['contacto'] ?? 'Ver en empresa' }}
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            @if(Session::has('firebase_user'))
                                <a href="javascript:void(0)" 
                                   onclick="this.innerHTML='¡Postulado!'; this.classList.add('btn-postulado')" 
                                   class="btn-postular-lila">
                                    Postularse
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-postular-lila">
                                    Inicia sesión para postularte
                                </a>
                            @endif
                            
                            <button class="btn btn-link text-dark p-0">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-search" style="font-size: 3rem; color: #d1d5db;"></i>
                </div>
                <p class="text-muted">No hay vacantes aprobadas por el momento. ¡Vuelve pronto!</p>
            </div>
        @endforelse
    </div>
</div>

@endsection