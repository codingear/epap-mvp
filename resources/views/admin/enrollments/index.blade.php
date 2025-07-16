@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">
        <i class="fas fa-graduation-cap me-2"></i>Gestión de Inscripciones
    </h2>
    <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Crear Inscripción
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.enrollments') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Usuario o curso...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos los estados</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="course_id" class="form-label">Curso</label>
                <select class="form-select" id="course_id" name="course_id">
                    <option value="">Todos los cursos</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.enrollments') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Enrollments Table -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Lista de Inscripciones
            <span class="badge bg-white text-primary ms-2">{{ $enrollments->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($enrollments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Curso</th>
                            <th>Nivel</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <strong>#{{ $enrollment->id }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($enrollment->user->name) }}&background=9042db&color=fff&size=32" 
                                             class="rounded-circle me-2" width="32" height="32" alt="{{ $enrollment->user->name }}">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->user->name }}</h6>
                                            <small class="text-muted">{{ $enrollment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $enrollment->course->title }}</strong>
                                    @if($enrollment->course->level)
                                        <br><small class="text-muted">{{ $enrollment->course->level->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->level)
                                        <span class="badge bg-info">{{ $enrollment->level->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->payment)
                                        <a href="{{ route('admin.payments.edit', $enrollment->payment) }}" class="text-decoration-none">
                                            <span class="badge bg-{{ $enrollment->payment->isCompleted() ? 'success' : 'warning' }}">
                                                #{{ $enrollment->payment->id }}
                                            </span>
                                        </a>
                                        <br><small class="text-muted">${{ number_format($enrollment->payment->amount, 2) }}</small>
                                    @else
                                        <span class="text-muted">Sin pago</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'completed' ? 'primary' : ($enrollment->status === 'cancelled' ? 'danger' : 'warning')) }} dropdown-toggle" 
                                              data-bs-toggle="dropdown" style="cursor: pointer;">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                        <ul class="dropdown-menu">
                                            @foreach($statuses as $status)
                                                @if($status !== $enrollment->status)
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="{{ $status }}">
                                                            <button type="submit" class="dropdown-item">
                                                                {{ ucfirst($status) }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $enrollment->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Eliminar" onclick="deleteEnrollment({{ $enrollment->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($enrollments->hasPages())
                <div class="card-footer">
                    {{ $enrollments->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No se encontraron inscripciones</h5>
                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Primera Inscripción
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta inscripción?</p>
                <p class="text-danger">
                    <i class="fas fa-warning me-1"></i>
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteEnrollment(enrollmentId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `{{ route('admin.enrollments') }}/${enrollmentId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Show success/error messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ $errors->first() }}',
        timer: 5000,
        showConfirmButton: false
    });
@endif
</script>
@endpush
