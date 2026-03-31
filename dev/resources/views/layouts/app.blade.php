<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mamá en Línea</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            background: #ffffff; 
            font-family: 'Inter', sans-serif; 
        }
        /* Estilo para que el logo se vea pro */
        .navbar-brand img {
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container d-flex container-fluid">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ url('/') }}">
                <img src="{{ asset('images/hero-home.jpg') }}" 
                     alt="Logo" 
                     width="55" 
                     height="55" 
                     class="d-inline-block align-text-top">
                <span class="ms-2 fs-3" style="letter-spacing: -1px; color: #000;">Mamá en Línea</span>
            </a>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            {{-- Mensajes de Retroalimentación (Importantes para QA) --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="footer-help mt-5">
        <div class="container text-center">
            <hr class="my-4" style="opacity: 0.1; max-width: 600px; margin: auto;">
            <div class="py-3">
                <p class="mb-0 text-muted">
                    <a href="{{ route('empresa.login') }}" class="fw-bold ms-1" style="color: #070707; text-decoration: none; border-bottom: 2px solid transparent; transition: 0.3s;" onmouseover="this.style.borderBottom='2px solid #a855f7'" onmouseout="this.style.borderBottom='2px solid transparent'">
                        Empleadores
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>