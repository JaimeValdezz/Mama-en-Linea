@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Apoyos Gubernamentales</h2>
        <p class="text-muted">Programas y beneficios vigentes para las familias en Durango</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-book-fill fs-4"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">Becas Benito Juárez</h4>
                </div>
                <p class="text-muted">Apoyo económico para estudiantes de educación básica y media superior para hijos de madres trabajadoras.</p>
                <a href="#" class="btn btn-outline-primary mt-auto w-50">Más información</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success text-white rounded-circle p-3 me-3">
                        <i class="bi bi-house-heart-fill fs-4"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">Programa Jefas de Familia</h4>
                </div>
                <p class="text-muted">Créditos a palabra y apoyos directos para emprendimientos liderados por madres solteras en Durango.</p>
                <a href="#" class="btn btn-outline-success mt-auto w-50">Solicitar Apoyo</a>
            </div>
        </div>
    </div>
</div>
@endsection