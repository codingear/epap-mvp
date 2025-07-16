@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">
        <i class="fas fa-layer-group me-2"></i>Gestión de Niveles
    </h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLevelModal">
        <i class="fas fa-plus me-2"></i>Crear Nivel
    </button>
</div>

<!-- Levels Cards -->
<div class="row">
    @forelse($levels as $level)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 level-card">
                <!-- Level Image -->
                <div class="position-relative">
                    @if($level->cover_image)
                        <img src="{{ asset('storage/' . $level->cover_image) }}" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" 
                             alt="{{ $level->name }}">
                    @else
                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                             style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-layer-group fa-3x text-white opacity-75"></i>
                        </div>
                    @endif
                    
                    <!-- Order Badge -->
                    <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
                        Nivel {{ $level->order }}
                    </span>
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $level->name }}</h5>
                    <p class="card-text text-muted">{{ $level->description ?: 'Sin descripción' }}</p>

                    <!-- Level Stats -->
                    <div class="mt-auto">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="mb-0 text-primary">{{ $level->courses_count }}</h6>
                                    <small class="text-muted">Cursos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0 text-success">{{ $level->courses->sum('student_count') }}</h6>
                                <small class="text-muted">Estudiantes</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    onclick="editLevel({{ $level->id }}, '{{ $level->name }}', '{{ $level->description }}')">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <a href="{{ route('admin.courses.index', ['level' => $level->id]) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-book"></i> Cursos
                            </a>
                            @if($level->courses_count == 0)
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="deleteLevel({{ $level->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled 
                                        title="No se puede eliminar un nivel con cursos">
                                    <i class="fas fa-lock"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay niveles creados</h5>
                <p class="text-muted">Los niveles organizan los cursos por dificultad o temática</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLevelModal">
                    <i class="fas fa-plus me-2"></i>Crear Primer Nivel
                </button>
            </div>
        </div>
    @endforelse
</div>

<!-- Create Level Modal -->
<div class="modal fade" id="createLevelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.levels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Nivel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Nombre del Nivel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_name" name="name" required 
                               placeholder="Ej: Principiante, Intermedio, Avanzado">
                    </div>

                    <div class="mb-3">
                        <label for="create_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3"
                                  placeholder="Descripción del nivel y lo que incluye..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="create_cover_image" class="form-label">Imagen del Nivel</label>
                        <input type="file" class="form-control" id="create_cover_image" name="cover_image" accept="image/*">
                        <div class="form-text">Formatos: JPG, PNG, GIF. Máximo 2MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Crear Nivel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Level Modal -->
<div class="modal fade" id="editLevelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editLevelForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Editar Nivel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre del Nivel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_cover_image" class="form-label">Nueva Imagen del Nivel</label>
                        <input type="file" class="form-control" id="edit_cover_image" name="cover_image" accept="image/*">
                        <div class="form-text">Deja vacío para mantener la imagen actual</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Actualizar Nivel
                    </button>
                </div>
            </form>
        </div>
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
                <p>¿Estás seguro de que deseas eliminar este nivel?</p>
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

@push('styles')
<style>
.level-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.level-card:hover {
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
function editLevel(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('editLevelForm').action = `{{ route('admin.levels') }}/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('editLevelModal'));
    modal.show();
}

function deleteLevel(levelId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `{{ route('admin.levels') }}/${levelId}`;
    
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

// Clear modal forms when closed
document.getElementById('createLevelModal').addEventListener('hidden.bs.modal', function () {
    this.querySelector('form').reset();
});

document.getElementById('editLevelModal').addEventListener('hidden.bs.modal', function () {
    this.querySelector('form').reset();
});
</script>
@endpush
