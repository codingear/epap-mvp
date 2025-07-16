@extends('student.layouts.app')

@section('content')
<div class="student-dashboard min-vh-100 bg-light">
    <!-- Header -->
    <div class="bg-white shadow-sm border-bottom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h1 class="h3 mb-1 text-primary fw-bold">¡Bienvenido, {{ auth()->user()->name }}!</h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ now()->format('l, j F Y') }}
                    </p>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=6366f1&color=fff" class="rounded-circle me-2" width="32" height="32" alt="Avatar">
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('student.profile.edit') }}"><i class="fas fa-user me-2"></i>Editar Perfil</a></li>
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

    <div class="container-fluid py-4">
        <!-- Welcome Call Status -->
        @if($welcomeCall)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-2">
                                    <i class="fas fa-video me-2"></i>
                                    Tu Llamada de Bienvenida
                                </h4>
                                <p class="mb-2 opacity-90">
                                    <strong>Fecha:</strong> {{ $welcomeCall->getFormattedDateTime() ?? 'Por confirmar' }}
                                </p>
                                <p class="mb-0 opacity-90">
                                    <strong>Estado:</strong> 
                                    <span class="badge bg-light text-dark ms-1">
                                        @switch($welcomeCall->status)
                                            @case('scheduled')
                                                <i class="fas fa-clock me-1"></i>Programada
                                                @break
                                            @case('completed')
                                                <i class="fas fa-check-circle me-1"></i>Completada
                                                @break
                                            @case('canceled')
                                                <i class="fas fa-times-circle me-1"></i>Cancelada
                                                @break
                                            @default
                                                <i class="fas fa-question-circle me-1"></i>{{ ucfirst($welcomeCall->status) }}
                                        @endswitch
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                @if($welcomeCall->status === 'scheduled' && $welcomeCall->google_meet_link)
                                    <a href="{{ $welcomeCall->google_meet_link }}" target="_blank" class="btn btn-light btn-lg fw-bold">
                                        <i class="fas fa-external-link-alt me-2"></i>
                                        Unirse a la Llamada
                                    </a>
                                @elseif($welcomeCall->status === 'scheduled')
                                    <div class="text-center">
                                        <div class="spinner-border text-light mb-2" role="status">
                                            <span class="visually-hidden">Generando enlace...</span>
                                        </div>
                                        <p class="small mb-0">Preparando tu enlace de videollamada</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dashboard Stats -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                <i class="fas fa-book-open fa-2x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Cursos Disponibles</h5>
                        <h3 class="text-primary mb-3">5</h3>
                        <a href="{{ route('student.courses.index') }}" class="btn btn-outline-primary btn-sm">
                            Ver Cursos <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                <i class="fas fa-trophy fa-2x text-success"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Progreso</h5>
                        <h3 class="text-success mb-3">75%</h3>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            Ver Detalles <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                <i class="fas fa-tasks fa-2x text-warning"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Tareas Pendientes</h5>
                        <h3 class="text-warning mb-3">3</h3>
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            Ver Tareas <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                <i class="fas fa-video fa-2x text-info"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Videollamadas</h5>
                        <h3 class="text-info mb-3">{{ $videoCalls->count() }}</h3>
                        <a href="#videocalls-section" class="btn btn-outline-info btn-sm">
                            Ver Historial <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="row g-4">
            <!-- Recent Courses -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                Mis Cursos en Progreso
                            </h5>
                            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Placeholder for when courses are implemented -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-book-reader fa-3x text-muted opacity-50"></i>
                            </div>
                            <h6 class="text-muted">¡Próximamente!</h6>
                            <p class="text-muted small mb-3">Aquí verás tus cursos una vez que estén disponibles.</p>
                            <a href="{{ route('student.courses.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Explorar Cursos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-bolt me-2 text-warning"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('student.courses.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                                <i class="fas fa-book-open me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Ver Cursos</div>
                                    <small class="text-muted">Explora el contenido disponible</small>
                                </div>
                            </a>
                            
                            <a href="{{ route('student.profile.edit') }}" class="btn btn-outline-secondary d-flex align-items-center">
                                <i class="fas fa-user-edit me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Editar Perfil</div>
                                    <small class="text-muted">Actualiza tu información</small>
                                </div>
                            </a>

                            @if($welcomeCall && $welcomeCall->status === 'scheduled' && $welcomeCall->google_meet_link)
                            <a href="{{ $welcomeCall->google_meet_link }}" target="_blank" class="btn btn-success d-flex align-items-center">
                                <i class="fas fa-video me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Videollamada</div>
                                    <small class="text-white">Unirse a la sesión</small>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Calls History -->
        @if($videoCalls->count() > 0)
        <div class="row mt-4" id="videocalls-section">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2 text-info"></i>
                            Historial de Videollamadas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Fecha y Hora</th>
                                        <th>Estado</th>
                                        <th>Profesor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videoCalls as $call)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-{{ $call->type === 'welcome' ? 'hand-wave' : 'graduation-cap' }} me-1"></i>
                                                {{ ucfirst($call->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $call->getFormattedDateTime() ?? 'Por confirmar' }}</div>
                                            @if($call->timezone)
                                                <small class="text-muted">{{ $call->timezone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($call->status)
                                                @case('scheduled')
                                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Programada</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Completada</span>
                                                    @break
                                                @case('canceled')
                                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Cancelada</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($call->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($call->teacher)
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ $call->teacher->name }}&background=6c757d&color=fff" class="rounded-circle me-2" width="24" height="24" alt="Teacher">
                                                    {{ $call->teacher->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">Por asignar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($call->status === 'scheduled')
                                                @if($call->google_meet_link)
                                                    <a href="{{ $call->google_meet_link }}" target="_blank" class="btn btn-sm btn-success me-1">
                                                        <i class="fas fa-video"></i>
                                                    </a>
                                                @endif
                                                <form method="POST" action="{{ route('student.video-calls.cancel', $call) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de cancelar esta videollamada?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.student-dashboard {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Cancel video call function
    function cancelCall(callId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción cancelará tu videollamada agendada',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/dashboard/student/video-calls/${callId}/cancel`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Cancelada',
                            'Tu videollamada ha sido cancelada exitosamente',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error',
                            data.message || 'No se pudo cancelar la videollamada',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error',
                        'Hubo un problema al cancelar la videollamada',
                        'error'
                    );
                });
            }
        });
    }
    
    // Copy meet link function
    function copyMeetLink(link) {
        navigator.clipboard.writeText(link).then(function() {
            Swal.fire({
                title: '¡Enlace copiado!',
                text: 'El enlace de Google Meet ha sido copiado al portapapeles',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }, function(err) {
            console.error('Error al copiar: ', err);
            Swal.fire(
                'Error',
                'No se pudo copiar el enlace',
                'error'
            );
        });
    }
</script>
@endpush