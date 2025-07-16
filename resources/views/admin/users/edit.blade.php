@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">
        <i class="fas fa-user-edit me-2"></i>Editar Usuario
    </h2>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-form me-2"></i>Información del Usuario
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- User Avatar -->
                <div class="col-12 mb-4">
                    <div class="text-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=9042db&color=fff&size=100" 
                             class="rounded-circle mb-2" width="100" height="100" alt="{{ $user->name }}">
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <p class="text-muted">Editando perfil</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h6>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" 
                                    {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Rol actual: 
                            <span class="badge bg-{{ $user->getRoleNames()->first() === 'admin' ? 'danger' : ($user->getRoleNames()->first() === 'teacher' ? 'info' : 'success') }}">
                                {{ ucfirst($user->getRoleNames()->first()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Security & Location -->
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-lock me-2"></i>Seguridad y Ubicación
                    </h6>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-key me-1"></i>
                            Deja en blanco para mantener la contraseña actual
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" 
                               name="password_confirmation">
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">País</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                               id="country" name="country" value="{{ old('country', $user->country) }}">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="state" class="form-label">Estado/Provincia</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state', $user->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">Ciudad</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $user->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas del Usuario
                    </h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-primary">{{ $user->created_at->format('d/m/Y') }}</h5>
                                    <small class="text-muted">Fecha de Registro</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-success">{{ $user->payments()->count() }}</h5>
                                    <small class="text-muted">Pagos Realizados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-info">{{ $user->enrollments()->count() }}</h5>
                                    <small class="text-muted">Cursos Inscritos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-warning">{{ $user->lessons()->wherePivot('completed', true)->count() }}</h5>
                                    <small class="text-muted">Lecciones Completadas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Actualizar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($user->getRoleNames()->first() === 'student')
<!-- Student Specific Information -->
<div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="fas fa-graduation-cap me-2"></i>Información de Estudiante
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Cursos Inscritos</h6>
                @if($user->enrollments()->count() > 0)
                    <div class="list-group">
                        @foreach($user->enrollments()->with('course')->get() as $enrollment)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $enrollment->course->title }}</h6>
                                        <small class="text-muted">{{ $enrollment->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $user->getCourseProgress($enrollment->course_id) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No hay cursos inscritos</p>
                @endif
            </div>
            <div class="col-md-6">
                <h6>Información Adicional</h6>
                @if($user->child_name)
                    <p><strong>Nombre del hijo:</strong> {{ $user->child_name }}</p>
                @endif
                @if($user->date_of_birth)
                    <p><strong>Fecha de nacimiento:</strong> {{ $user->date_of_birth }}</p>
                @endif
                @if($user->timezone)
                    <p><strong>Zona horaria:</strong> {{ $user->timezone }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Confirm password validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password && password !== confirmation) {
        this.setCustomValidity('Las contraseñas no coinciden');
    } else {
        this.setCustomValidity('');
    }
});

// Only require confirmation if password is provided
document.getElementById('password').addEventListener('input', function() {
    const confirmation = document.getElementById('password_confirmation');
    if (this.value) {
        confirmation.required = true;
    } else {
        confirmation.required = false;
        confirmation.setCustomValidity('');
    }
});
</script>
@endpush
