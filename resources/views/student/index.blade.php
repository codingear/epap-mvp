@extends('student.layouts.app')

@section('content')
<div class="welcome-screen bg-primary bg-gradient min-vh-100 position-relative p-4 overflow-hidden">
    <!-- Floating User Profile Button -->
    <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1000">
        <div class="dropdown">
            <button class="btn btn-light rounded-pill shadow-sm dropdown-toggle d-flex align-items-center px-3 py-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=9042db&color=fff" class="rounded-circle me-2" width="30" height="30" alt="Avatar">
                <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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

    <div class="decorative-elements">
        <div class="position-absolute top-0 start-0 mt-5 ms-5 opacity-75 fs-2">⭐</div>
        <div class="position-absolute start-0 top-50 ms-5 opacity-75 fs-2">♡</div>
        <div class="position-absolute top-0 end-0 mt-5 me-5 opacity-75 fs-2">🏆</div>
        <div class="position-absolute end-0 top-75 me-5 opacity-75 fs-2">✨</div>
        <div class="position-absolute bottom-0 start-0 mb-5 ms-5 opacity-75 fs-2">🚀</div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg rounded-4">
                    <div class="card-header bg-white rounded-top-4">
                        <h4 class="mb-0">Dashboard Estudiante</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card text-white bg-gradient stat-card">
                                    <div class="card-body position-relative">
                                        <div class="stat-icon">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                        <h5 class="card-title">Cursos</h5>
                                        <p class="card-text display-4">5</p>
                                        <a href="#" class="btn btn-light btn-sm rounded-pill shadow-sm">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                                    </div>
                                    <div class="card-overlay gradient-primary"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <div class="card text-white bg-gradient stat-card">
                                    <div class="card-body position-relative">
                                        <div class="stat-icon">
                                            <i class="fas fa-tasks fa-2x"></i>
                                        </div>
                                        <h5 class="card-title">Tareas</h5>
                                        <p class="card-text display-4">12</p>
                                        <a href="#" class="btn btn-light btn-sm rounded-pill shadow-sm">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                                    </div>
                                    <div class="card-overlay gradient-success"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <div class="card text-white bg-gradient stat-card">
                                    <div class="card-body position-relative">
                                        <div class="stat-icon">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                        <h5 class="card-title">Calificaciones</h5>
                                        <p class="card-text display-4">85%</p>
                                        <a href="#" class="btn btn-light btn-sm rounded-pill shadow-sm">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                                    </div>
                                    <div class="card-overlay gradient-info"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video Calls Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-light border-0">
                                        <h5 class="mb-0">
                                            <i class="fas fa-video me-2 text-primary"></i> 
                                            Mis Videollamadas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if($videoCalls->count() > 0)
                                            <div class="row">
                                                @foreach($videoCalls as $call)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card h-100 video-call-card">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                                <h6 class="card-title mb-0">
                                                                    @if($call->type === 'welcome')
                                                                        <i class="fas fa-hand-wave text-warning me-2"></i>
                                                                        Videollamada de Bienvenida
                                                                    @else
                                                                        <i class="fas fa-chalkboard-teacher text-info me-2"></i>
                                                                        Clase Personal
                                                                    @endif
                                                                </h6>
                                                                <span class="badge bg-{{ $call->status === 'scheduled' ? 'success' : ($call->status === 'canceled' ? 'danger' : 'secondary') }}">
                                                                    {{ ucfirst($call->status) }}
                                                                </span>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <p class="card-text mb-1">
                                                                    <i class="fas fa-calendar me-2 text-muted"></i>
                                                                    <strong>{{ $call->getFormattedDateTime() }}</strong>
                                                                </p>
                                                                <p class="card-text mb-1">
                                                                    <i class="fas fa-clock me-2 text-muted"></i>
                                                                    {{ $call->timezone }}
                                                                </p>
                                                                @if($call->teacher)
                                                                    <p class="card-text mb-1">
                                                                        <i class="fas fa-user-tie me-2 text-muted"></i>
                                                                        {{ $call->teacher->name }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="d-flex gap-2">
                                                                @if($call->status === 'scheduled' && $call->google_meet_link)
                                                                    <a href="{{ $call->google_meet_link }}" 
                                                                       target="_blank" 
                                                                       class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-video me-1"></i>
                                                                        Unirse
                                                                    </a>
                                                                @endif
                                                                
                                                                @if($call->status === 'scheduled')
                                                                    <button class="btn btn-outline-danger btn-sm" 
                                                                            onclick="cancelCall({{ $call->id }})">
                                                                        <i class="fas fa-times me-1"></i>
                                                                        Cancelar
                                                                    </button>
                                                                @endif
                                                                
                                                                @if($call->google_meet_link)
                                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                                            onclick="copyMeetLink('{{ $call->google_meet_link }}')">
                                                                        <i class="fas fa-copy me-1"></i>
                                                                        Copiar
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-video-slash text-muted" style="font-size: 3rem;"></i>
                                                <p class="mt-3 mb-2 text-muted">No tienes videollamadas agendadas</p>
                                                <a href="{{ route('student.calendar') }}" class="btn btn-primary">
                                                    <i class="fas fa-calendar-plus me-2"></i>
                                                    Agendar mi Primera Videollamada
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-light border-0">
                                        <h5 class="mb-0"><i class="fas fa-history me-2"></i> Actividades Recientes</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
                                                <span><i class="fas fa-clipboard-check text-primary me-2"></i> Nueva tarea asignada</span>
                                                <span class="badge bg-primary rounded-pill">Hoy</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
                                                <span><i class="fas fa-award text-success me-2"></i> Calificación recibida</span>
                                                <span class="badge bg-primary rounded-pill">Ayer</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                                <span><i class="fas fa-book text-info me-2"></i> Material de curso actualizado</span>
                                                <span class="badge bg-primary rounded-pill">3 días</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        Acciones Rápidas
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary" type="button">Ver Mis Cursos</button>
                                            <button class="btn btn-success" type="button">Entregar Tarea</button>
                                            <button class="btn btn-info" type="button">Ver Calificaciones</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<style>
    .welcome-screen {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%) !important;
    }
    
    .rounded-4 {
        border-radius: 15px !important;
    }
    
    .top-75 {
        top: 75% !important;
    }
    
    .btn-warning {
        background: linear-gradient(to right, #fd7e14, #ffc107);
        border: none;
        transition: transform 0.2s;
    }
    
    .btn-warning:hover {
        transform: translateY(-3px);
    }
    
    /* Stat Card Styling */
    .stat-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        height: 100%;
        position: relative;
        transition: transform .3s, box-shadow .3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
    }
    
    .stat-card .card-body {
        position: relative;
        z-index: 1;
    }
    
    .stat-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        opacity: 0.8;
    }
    
    .gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    }
    
    .gradient-success {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    }
    
    .gradient-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
    }
    
    /* Enhanced card styles */
    .card {
        border-radius: 12px;
        transition: transform .3s, box-shadow .3s;
        overflow: hidden;
    }
    
    .card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
        font-weight: 500;
    }
    
    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px;
        padding-left: 0;
    }
    
    /* User dropdown styling */
    #userDropdown {
        transition: all 0.2s;
    }
    
    #userDropdown:hover {
        background-color: #f8f9fa;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15) !important;
    }
    
    .z-index-1000 {
        z-index: 1000;
    }
    
    .video-call-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .video-call-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        transform: translateY(-2px);
    }
    
    .video-call-card .btn {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    /* Responsive adjustments for dropdown */
    @media (max-width: 576px) {
        .dropdown-menu {
            width: 200px;
        }
        
        #userDropdown {
            padding: 8px !important;
        }
    }
    
    /* Dashboard card styling */
    .card {
        transition: transform .3s;
        margin-bottom: 15px;
    }
    
    .card:hover {
        /* Remove this line to eliminate the hover displacement effect */
        /* transform: translateY(-5px); */
    }
    
    .rounded-top-4 {
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize any Select2 dropdowns if needed
    });
    
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