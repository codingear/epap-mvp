@extends('admin.layouts.app')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-12">
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Total Usuarios</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['total_users'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-primary bg-opacity-25">
                        <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm">Ver todos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Estudiantes</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['total_students'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-success bg-opacity-25">
                        <a href="{{ route('admin.users', ['role' => 'student']) }}" class="btn btn-light btn-sm">Ver estudiantes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-info h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Profesores</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['total_teachers'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-info bg-opacity-25">
                        <a href="{{ route('admin.users', ['role' => 'teacher']) }}" class="btn btn-light btn-sm">Ver profesores</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Cursos</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['total_courses'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-book fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-warning bg-opacity-25">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-dark btn-sm">Ver cursos</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Second row of statistics for Payments and Enrollments -->
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-danger h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Total Pagos</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['total_payments'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-credit-card fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-danger bg-opacity-25">
                        <a href="{{ route('admin.payments') }}" class="btn btn-light btn-sm">Ver pagos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Pagos Completados</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['completed_payments'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-success bg-opacity-25">
                        <a href="{{ route('admin.payments', ['status' => 'completed']) }}" class="btn btn-light btn-sm">Ver completados</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-purple h-100" style="background-color: #6f42c1 !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Inscripciones</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['total_enrollments'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer" style="background-color: rgba(111, 66, 193, 0.25) !important;">
                        <a href="{{ route('admin.enrollments') }}" class="btn btn-light btn-sm">Ver inscripciones</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-dark h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Inscripciones Activas</h5>
                            <p class="card-text display-6 mb-0">{{ $stats['active_enrollments'] ?? 0 }}</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-play-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-dark bg-opacity-25">
                        <a href="{{ route('admin.enrollments', ['status' => 'active']) }}" class="btn btn-light btn-sm">Ver activas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Crear Usuario
                    </a>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-book-plus me-2"></i>Crear Curso
                    </a>
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-outline-danger">
                        <i class="fas fa-credit-card me-2"></i>Crear Pago
                    </a>
                    <a href="{{ route('admin.enrollments.create') }}" class="btn btn-outline-warning">
                        <i class="fas fa-user-graduate me-2"></i>Crear Inscripción
                    </a>
                    <a href="{{ route('admin.levels') }}" class="btn btn-outline-info">
                        <i class="fas fa-layer-group me-2"></i>Gestionar Niveles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($stats['recent_users'] ?? [] as $user)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">Nuevo usuario registrado</small>
                                </div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">No hay actividad reciente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>Cursos Recientes</h5>
            </div>
            <div class="card-body">
                @if(($stats['recent_courses'] ?? collect())->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Nivel</th>
                                    <th>Instructor</th>
                                    <th>Estado</th>
                                    <th>Creado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_courses'] as $course)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($course->cover_image)
                                                    <img src="{{ asset('storage/' . $course->cover_image) }}" 
                                                         class="rounded me-2" width="40" height="30" style="object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $course->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $course->level->name ?? 'Sin nivel' }}</span>
                                        </td>
                                        <td>{{ $course->instructor->name ?? 'Sin instructor' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $course->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.courses.lessons', $course) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <h5>No hay cursos aún</h5>
                        <p>Comienza creando tu primer curso</p>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Crear Curso
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform .3s ease, box-shadow .3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .bg-opacity-25 {
        background-color: rgba(255, 255, 255, 0.25) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard cargado correctamente');
    });
</script>
@endpush