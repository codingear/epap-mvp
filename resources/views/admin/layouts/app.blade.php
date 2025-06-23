<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EPAP') }} - Panel Administrativo</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #9042db;
            --secondary-color: #c165dd;
            --gradient-bg: linear-gradient(135deg, #b887ef 0%, #f979a4 100%);
            --menu-bg: #f0f3ff;
            --card-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            --active-gradient: linear-gradient(to right, #9042db, #c165dd);
        }
        
        body {
            background-color: #f8f9fd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: white !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 0.7rem 1rem;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .nav-link {
            color: #555 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(144, 66, 219, 0.1);
        }
        
        .nav-link.active {
            color: white !important;
            background: var(--active-gradient);
            box-shadow: 0 3px 10px rgba(144, 66, 219, 0.3);
        }
        
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            border: none;
            padding: 0.5rem;
            background-color: white;
        }
        
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }
        
        .dropdown-item {
            padding: 0.7rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f0f3ff;
            color: var(--primary-color);
        }
        
        .dropdown-divider {
            margin: 0.3rem 0;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f0f3ff;
            transition: all 0.3s ease;
        }
        
        .avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 0 0 3px rgba(144, 66, 219, 0.2);
        }
        
        .main-container {
            background-color: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 40px;
            height: 4px;
            background: var(--active-gradient);
            border-radius: 2px;
        }
        
        .btn-primary {
            background: var(--active-gradient);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(144, 66, 219, 0.3);
        }
        
        footer {
            background-color: white !important;
            box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.03);
            color: #777;
        }
        
        /* Decorative elements */
        .background-stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .star {
            position: absolute;
            color: rgba(144, 66, 219, 0.2);
            font-size: 1.5rem;
        }
        
        .star-1 {
            top: 10%;
            left: 5%;
            font-size: 2rem;
        }
        
        .star-2 {
            top: 30%;
            right: 10%;
            font-size: 1.8rem;
        }
        
        .star-3 {
            bottom: 15%;
            left: 8%;
            font-size: 2.2rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="background-stars">
        <div class="star star-1">✦</div>
        <div class="star star-2">✧</div>
        <div class="star star-3">✦</div>
    </div>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <!-- Logo a la izquierda -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.index') ?? '#' }}">
                    <div class="me-2" style="width: 40px; height: 40px; background: var(--active-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book-open text-white"></i>
                    </div>
                    <span>{{ config('app.name', 'EPAP') }}</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú centrado -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') ?? '#' }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="#">
                                <i class="bi bi-people me-1"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear me-1"></i> Configuración
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-building me-2"></i> Perfil del sitio</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-sliders me-2"></i> Opciones</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-tools me-2"></i> Avanzado</a></li>
                            </ul>
                        </li>
                    </ul>
                    
                    <!-- Usuario y avatar a la derecha -->
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 text-dark">{{ Auth::user()->name ?? 'Usuario' }}</span>
                                <img class="avatar" src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/40' }}" alt="Avatar">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Editar Perfil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="py-4">
        <div class="container">
            <div class="main-container">
                @yield('content')
            </div>
        </div>
    </main>
    
    <footer class="py-3 mt-auto">
        <div class="container">
            <div class="text-center text-muted">
                &copy; {{ date('Y') }} {{ config('app.name', 'EPAP') }}. Todos los derechos reservados. <span class="text-primary">✨</span>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
