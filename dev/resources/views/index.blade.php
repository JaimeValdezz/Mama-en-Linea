@extends("layouts.master")
@section("content")

<!-- Fuente manuscrita idéntica a la imagen -->
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">


<div class="container my-5">
  <!-- Sección principal "Mamá en línea" -->
  <div class="mamalen-card bg-white rounded-4 shadow overflow-hidden border-0">
    
    <!-- Contenido principal -->
    <div class="p-4 p-md-5">
      <div class="row align-items-center">
        
        <!-- Columna izquierda: texto -->
        <div class="col-lg-6">
          <h1 class="mamalen-title mb-1">
            Mamá en línea
            <i class="bi bi-wifi ms-3" style="font-size: 3rem; color: #e91e63; vertical-align: middle;"></i>
          </h1>
          
          <div class="mamalen-red-dot mt-1 mb-4"></div>

          <p class="text-muted fs-5 lh-lg mb-4">
            Consulta de los diferentes apoyos que brinda el gobierno ya sean económicos, psicológicos, entre otros. 
            También sobre las diferentes vacantes de trabajo, contamos con ayuda vía llamada 24/7.
          </p>

          <div>
            <strong class="d-block fs-5 mb-2">¿Necesitas Hablar con alguien?</strong>
            <p class="fs-4 fw-bold mb-0" style="color: #e91e63;">
              800-MAMÁ-LINEA 
              <span class="text-dark fw-normal fs-5">(800-000-0000)</span>
            </p>
            <p class="text-muted fs-5">o nuestro chat en vivo 24/7</p>
          </div>
        </div>

        <!-- Columna derecha: imagen -->
        <div class="col-lg-6 text-center text-lg-end mt-5 mt-lg-0">
          <img src="/storage/mama-en-linea.jpg" 
               alt="Mamá con niño" 
               class="img-fluid rounded-3 shadow"
               style="max-height: 420px; object-fit: cover; width: 100%;">
        </div>
      </div>
    </div>

    <!-- Franja inferior rosa (exactamente como en la imagen) -->
    <div class="p-4 text-center text-md-start" style="background: #fff0f5; border-top: 2px solid #fdd4e4;">
      <div class="row align-items-center justify-content-between">
        <div class="col-md-6 mb-3 mb-md-0">
          <strong class="fs-4">¿Necesitas Hablar con alguien?</strong>
        </div>
        <div class="col-md-6 text-md-end">
          <span class="fw-bold fs-4 text-pink">800-MAMÁ-LINEA</span>
          <span class="text-dark fs-5"> (800-000-0000) o nuestro chat en vivo 24/7</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Estilos ajustados para máxima fidelidad -->
<style>
  .mamalen-title {
    font-family: 'Pacifico', cursive;
    font-size: 4.2rem;
    color: #212529;
    line-height: 1.1;
  }

  .mamalen-red-dot {
    width: 14px;
    height: 14px;
    background: #e91e63;
    border-radius: 50%;
    display: inline-block;
  }

  .text-pink {
    color: #e91e63 !important;
  }

  .nav-link {
    font-size: 1.1rem;
    transition: color 0.2s;
  }

  .nav-link:hover {
    color: #e91e63 !important;
  }

  @media (max-width: 991px) {
    .mamalen-title {
      font-size: 3.4rem;
    }
    .nav-link {
      font-size: 1rem;
    }
  }

  @media (max-width: 576px) {
    .mamalen-title {
      font-size: 2.8rem;
    }
    .mamalen-title i {
      font-size: 2.4rem !important;
    }
  }
</style>

@endsection