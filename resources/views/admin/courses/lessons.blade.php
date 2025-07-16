@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-1">
            <i class="fas fa-list me-2"></i>Lecciones del Curso
        </h2>
        <p class="text-muted mb-0">{{ $course->title }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline-info">
            <i class="fas fa-edit me-2"></i>Editar Curso
        </a>
        <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Crear Lección
        </a>
    </div>
</div>

<!-- Course Summary -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-2">
                @if($course->cover_image)
                    <img src="{{ asset('storage/' . $course->cover_image) }}" 
                         class="img-fluid rounded" alt="{{ $course->title }}">
                @else
                    <div class="bg-gradient rounded d-flex align-items-center justify-content-center" 
                         style="height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-book text-white fa-2x"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <h5 class="mb-1">{{ $course->title }}</h5>
                <p class="text-muted mb-2">{{ $course->description }}</p>
                <div>
                    <span class="badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'danger') }}">
                        {{ ucfirst($course->status) }}
                    </span>
                    <span class="badge bg-secondary ms-1">{{ $course->level->name ?? 'Sin nivel' }}</span>
                    @if($course->is_free)
                        <span class="badge bg-primary ms-1">Gratis</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="mb-0 text-primary">{{ $lessons->count() }}</h6>
                        <small class="text-muted">Lecciones</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-success">{{ $course->student_count }}</h6>
                        <small class="text-muted">Estudiantes</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0 text-warning">
                            {{ $course->duration ? $course->duration . ' min' : '-' }}
                        </h6>
                        <small class="text-muted">Duración</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lessons List -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-play-circle me-2"></i>Lista de Lecciones
            <span class="badge bg-white text-primary ms-2">{{ $lessons->count() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($lessons->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($lessons as $index => $lesson)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <div class="lesson-number">
                                    <span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; line-height: 16px;">
                                        {{ $lesson->order }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $lesson->title }}</h6>
                                <p class="text-muted mb-1">{{ Str::limit($lesson->description, 100) }}</p>
                                <div class="d-flex align-items-center">
                                    @if($lesson->duration)
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="fas fa-clock me-1"></i>{{ $lesson->duration }} min
                                        </span>
                                    @endif
                                    @if($lesson->is_free)
                                        <span class="badge bg-success me-2">Gratis</span>
                                    @endif
                                    @if($lesson->video_url)
                                        <span class="badge bg-info me-2">
                                            <i class="fas fa-video me-1"></i>Video
                                        </span>
                                    @endif
                                    @if($lesson->materials->count() > 0)
                                        <span class="badge bg-warning me-2">
                                            <i class="fas fa-paperclip me-1"></i>{{ $lesson->materials->count() }} archivos
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted">
                                    {{ $lesson->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="btn-group btn-group-sm">
                                    @if($lesson->video_url)
                                        <a href="{{ $lesson->video_url }}" target="_blank" 
                                           class="btn btn-outline-info" title="Ver video">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" 
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            title="Eliminar" onclick="deleteLesson({{ $lesson->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Move Up/Down buttons -->
                                    @if($index > 0)
                                        <button type="button" class="btn btn-outline-secondary" 
                                                title="Mover arriba" onclick="moveLesson({{ $lesson->id }}, 'up')">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                    @endif
                                    @if($index < $lessons->count() - 1)
                                        <button type="button" class="btn btn-outline-secondary" 
                                                title="Mover abajo" onclick="moveLesson({{ $lesson->id }}, 'down')">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Materials -->
                        @if($lesson->materials->count() > 0)
                            <div class="mt-3 ps-5">
                                <h6 class="text-muted">Materiales:</h6>
                                <div class="row">
                                    @foreach($lesson->materials as $material)
                                        <div class="col-md-4 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt text-muted me-2"></i>
                                                <div class="flex-grow-1">
                                                    <small class="d-block">{{ $material->name }}</small>
                                                    <small class="text-muted">{{ number_format($material->file_size / 1024, 1) }} KB</small>
                                                </div>
                                                <a href="{{ asset('storage/' . $material->file_path) }}" 
                                                   target="_blank" class="btn btn-outline-primary btn-sm ms-2">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-play-circle fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay lecciones creadas</h5>
                <p class="text-muted">Comienza agregando la primera lección a este curso</p>
                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Primera Lección
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
                <p>¿Estás seguro de que deseas eliminar esta lección?</p>
                <p class="text-danger">
                    <i class="fas fa-warning me-1"></i>
                    Esta acción eliminará también todos los materiales asociados.
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
function deleteLesson(lessonId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `{{ url('/dashboard/admin/lessons') }}/${lessonId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function moveLesson(lessonId, direction) {
    // This function would handle lesson reordering
    // You might want to implement an AJAX call to update lesson order
    Swal.fire({
        title: 'Funcionalidad en desarrollo',
        text: 'La reordenación de lecciones estará disponible pronto',
        icon: 'info'
    });
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
