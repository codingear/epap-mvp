@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">
        <i class="fas fa-book me-2"></i>Gestión de Cursos
    </h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Crear Curso
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Título del curso...">
            </div>
            <div class="col-md-3">
                <label for="level" class="form-label">Nivel</label>
                <select class="form-select" id="level" name="level">
                    <option value="">Todos los niveles</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos los estados</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Courses Cards -->
<div class="row">
    @forelse($courses as $course)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 course-card">
                <!-- Course Image -->
                <div class="position-relative">
                    @if($course->cover_image)
                        <img src="{{ asset('storage/' . $course->cover_image) }}" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" 
                             alt="{{ $course->title }}">
                    @else
                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                             style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-book fa-3x text-white opacity-75"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'danger') }}">
                        {{ ucfirst($course->status) }}
                    </span>

                    <!-- Free Badge -->
                    @if($course->is_free)
                        <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
                            Gratis
                        </span>
                    @endif
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $course->level->name ?? 'Sin nivel' }}</span>
                        @if($course->duration)
                            <span class="badge bg-info ms-1">{{ $course->duration }} min</span>
                        @endif
                    </div>

                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>

                    <!-- Course Info -->
                    <div class="mt-auto">
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="mb-0 text-primary">{{ $course->lessons()->count() }}</h6>
                                    <small class="text-muted">Lecciones</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="mb-0 text-success">{{ $course->student_count }}</h6>
                                    <small class="text-muted">Estudiantes</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-0 text-warning">
                                    @if($course->is_free)
                                        Gratis
                                    @else
                                        ${{ number_format($course->price, 0) }}
                                    @endif
                                </h6>
                                <small class="text-muted">Precio</small>
                            </div>
                        </div>

                        <!-- Instructor -->
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'Sin instructor') }}&background=9042db&color=fff&size=30" 
                                 class="rounded-circle me-2" width="30" height="30">
                            <small class="text-muted">{{ $course->instructor->name ?? 'Sin instructor' }}</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('admin.courses.edit', $course) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.courses.lessons', $course) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-list"></i> Lecciones
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    onclick="deleteCourse({{ $course->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No se encontraron cursos</h5>
                <p class="text-muted">Intenta ajustar los filtros de búsqueda o crea un nuevo curso</p>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Primer Curso
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($courses->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $courses->appends(request()->query())->links() }}
    </div>
@endif

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
                <p>¿Estás seguro de que deseas eliminar este curso?</p>
                <p class="text-danger">
                    <i class="fas fa-warning me-1"></i>
                    Esta acción eliminará también todas las lecciones y materiales asociados.
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

@push('styles')
<style>
.course-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteCourse(courseId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `{{ route('admin.courses.index') }}/${courseId}`;
    
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
</script>
@endpush
