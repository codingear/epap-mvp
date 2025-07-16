@extends('student.layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" 
     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    
    <!-- Navbar - Moved outside container to be full width -->
    <div class="card shadow-sm position-fixed top-0 w-100" style="z-index: 1030;">
        <div class="card-body p-2">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Back Button -->
                    <div>
                        <a href="{{route('student.index')}}" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                    
                    <!-- Right Side: User Info and Logout -->
                    <div class="d-flex align-items-center">
                        <!-- User Avatar and Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=9042db&color=fff" class="rounded-circle me-2" width="30" height="30" alt="Avatar">
                                <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item active" href="{{ route('student.profile.edit') }}"><i class="fas fa-user me-2"></i>Editar Perfil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-gradient text-white text-center py-4 rounded-top-4" 
                         style="background: linear-gradient(135deg, #9042db, #c165dd);">
                        <div class="mb-3">
                            <div class="avatar-container mx-auto" style="width: 80px; height: 80px;">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=fff&color=9042db&size=80" 
                                     class="rounded-circle border border-3 border-white shadow" 
                                     width="80" height="80" alt="Avatar">
                            </div>
                        </div>
                        <h3 class="mb-1"><i class="fas fa-user-edit me-2"></i>Editar Mi Perfil</h3>
                        <p class="mb-0 opacity-75">Actualiza tu información personal</p>
                    </div>
                    
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.profile.update') }}" id="profileUpdateForm">
                            @csrf
                            <input type="hidden" name="type_update" value="profile_edit">
                            
                            <!-- Información Personal -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Información Personal
                                    </h5>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nombre del Tutor
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', auth()->user()->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="child_name" class="form-label">
                                        <i class="fas fa-child me-1"></i>Nombre del Estudiante
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('child_name') is-invalid @enderror" 
                                           id="child_name" 
                                           name="child_name" 
                                           value="{{ old('child_name', auth()->user()->child_name) }}">
                                    @error('child_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Correo Electrónico
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Teléfono
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', auth()->user()->phone) }}"
                                           placeholder="Ej: +52 555 123 4567">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Incluye código de país si es internacional</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">
                                        <i class="fas fa-birthday-cake me-1"></i>Fecha de Nacimiento del Estudiante
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" 
                                           name="date_of_birth" 
                                           value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="timezone" class="form-label">
                                        <i class="fas fa-clock me-1"></i>Zona Horaria
                                    </label>
                                    <select class="form-select @error('timezone') is-invalid @enderror" 
                                            id="timezone" 
                                            name="timezone">
                                        <option value="">Selecciona una zona horaria</option>
                                        <option value="America/Mexico_City" {{ old('timezone', auth()->user()->timezone) == 'America/Mexico_City' ? 'selected' : '' }}>México (UTC-6)</option>
                                        <option value="America/New_York" {{ old('timezone', auth()->user()->timezone) == 'America/New_York' ? 'selected' : '' }}>Nueva York (UTC-5)</option>
                                        <option value="America/Los_Angeles" {{ old('timezone', auth()->user()->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Los Ángeles (UTC-8)</option>
                                        <option value="America/Bogota" {{ old('timezone', auth()->user()->timezone) == 'America/Bogota' ? 'selected' : '' }}>Bogotá (UTC-5)</option>
                                        <option value="America/Lima" {{ old('timezone', auth()->user()->timezone) == 'America/Lima' ? 'selected' : '' }}>Lima (UTC-5)</option>
                                        <option value="America/Argentina/Buenos_Aires" {{ old('timezone', auth()->user()->timezone) == 'America/Argentina/Buenos_Aires' ? 'selected' : '' }}>Buenos Aires (UTC-3)</option>
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información de Ubicación -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="country" class="form-label">
                                        <i class="fas fa-flag me-1"></i>País
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('country') is-invalid @enderror" 
                                           id="country" 
                                           name="country" 
                                           value="{{ old('country', auth()->user()->country) }}">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">
                                        <i class="fas fa-map me-1"></i>Estado/Provincia
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('state') is-invalid @enderror" 
                                           id="state" 
                                           name="state" 
                                           value="{{ old('state', auth()->user()->state) }}">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="city" class="form-label">
                                        <i class="fas fa-city me-1"></i>Ciudad
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city', auth()->user()->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cambio de Contraseña -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-lock me-2"></i>Cambiar Contraseña <small class="text-muted">(Opcional)</small>
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-key me-1"></i>Nueva Contraseña
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Deja en blanco si no deseas cambiar la contraseña</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-key me-1"></i>Confirmar Contraseña
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           autocomplete="new-password">
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('student.index') }}" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #9042db, #c165dd); border: none;">
                                    <i class="fas fa-save me-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        margin: 0;
        padding: 0;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #9042db;
        box-shadow: 0 0 0 0.2rem rgba(144, 66, 219, 0.25);
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(144, 66, 219, 0.4);
    }
    
    .avatar-container {
        position: relative;
        display: inline-block;
    }
    
    .avatar-container:hover img {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .rounded-top-4 {
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }
    
    .rounded-4 {
        border-radius: 15px !important;
    }
    
    .text-primary {
        color: #9042db !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            margin-top: 70px;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
        
        .d-grid.gap-2.d-md-flex {
            display: flex !important;
            flex-direction: column !important;
        }
        
        .d-grid.gap-2.d-md-flex .btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('profileUpdateForm');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        
        // Validate password confirmation
        confirmPasswordField.addEventListener('input', function() {
            if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordField.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmPasswordField.setCustomValidity('');
            }
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            if (passwordField.value && passwordField.value !== confirmPasswordField.value) {
                e.preventDefault();
                alert('Las contraseñas no coinciden. Por favor, verifícalas.');
                return false;
            }
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
