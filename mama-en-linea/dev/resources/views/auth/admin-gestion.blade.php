@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold" style="color: #333;">Gestión de Vacantes</h2>
            <p class="text-muted">Panel de administración para validar y autorizar ofertas laborales.</p>
        </div>
        <div class="stats-mini d-flex gap-3">
            <div class="p-3 bg-white shadow-sm rounded-3 border-start border-primary border-4">
                <small class="text-muted d-block">Total Vacantes</small>
                <span class="fw-bold h5">{{ count($vacantes) }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Información de la Vacante</th>
                            <th>Empresa y Ubicación</th>
                            <th class="text-center">Estado de Visibilidad</th>
                            <th class="text-center pe-4">Acciones de Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacantes as $vacante)
                        <tr>
                            <td class="ps-4">
                                {{-- Mostramos el Título y el Sueldo que vienen de Firebase --}}
                                <div class="fw-bold text-dark">{{ $vacante['titulo'] ?? 'Sin título' }}</div>
                                <div class="text-primary fw-bold small">
                                    ${{ isset($vacante['sueldo']) ? number_format($vacante['sueldo'], 2) : '0.00' }}
                                </div>
                                <small class="text-muted">ID: {{ $vacante['id'] }}</small>
                            </td>
                            <td>
                                {{-- Mostramos el Nombre de la Empresa y el Lugar --}}
                                <div class="fw-bold">{{ $vacante['nombre_empresa'] ?? 'Empresa N/A' }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> {{ $vacante['lugar'] ?? 'No especificado' }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if(isset($vacante['is_approved']) && $vacante['is_approved'])
                                    <span class="badge rounded-pill bg-success-light text-success px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i> Visble
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-warning-light text-warning px-3">
                                        <i class="bi bi-hourglass-split me-1"></i> No Visible
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <form action="{{ route('admin.vacantes.toggle', $vacante['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH') {{-- Mantenemos el PATCH para evitar el error 405 --}}
                                    
                                    <button type="submit" class="btn btn-sm {{ (isset($vacante['is_approved']) && $vacante['is_approved']) ? 'btn-outline-danger' : 'btn-primary' }} rounded-pill px-4 shadow-sm">
                                        {{ (isset($vacante['is_approved']) && $vacante['is_approved']) ? 'Ocultar Vacante' : 'Aprobar ahora' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-light { background-color: #d1e7dd; color: #0f5132; }
    .bg-warning-light { background-color: #fff3cd; color: #664d03; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; transition: 0.3s; }
    .btn-primary { background-color: #9333ea; border: none; color: white; }
    .btn-primary:hover { background-color: #7e22ce; color: white; }
</style>
@endsection