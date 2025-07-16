@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-1">
            <i class="fas fa-edit me-2"></i>Editar Lección
        </h2>
        <p class="text-muted mb-0">Curso: {{ $lesson->course->title }}</p>
    </div>
    <a href="{{ route('admin.courses.lessons', $lesson->course) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver a Lecciones
    </a>
</div>

<form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Main Lesson Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información de la Lección
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título de la Lección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $lesson->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $lesson->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Breve descripción de lo que aprenderán en esta lección</div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Contenido de la Lección <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" required>{{ old('content', $lesson->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Contenido completo de la lección. Puedes usar HTML básico para formato.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="video_url" class="form-label">URL del Video</label>
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" 
                               placeholder="https://youtube.com/watch?v=... o https://vimeo.com/...">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Soporta YouTube, Vimeo y otros servicios de video
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Materials -->
            @if($lesson->materials->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-paperclip me-2"></i>Materiales Actuales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($lesson->materials as $material)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $material->name }}</h6>
                                                    <small class="text-muted">
                                                        {{ number_format($material->file_size / 1024, 1) }} KB • 
                                                        {{ $material->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ asset('storage/' . $material->file_path) }}" 
                                                       target="_blank" class="btn btn-outline-primary" title="Descargar">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            title="Eliminar" onclick="deleteMaterial({{ $material->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- New Materials Section -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Agregar Nuevos Materiales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="materials" class="form-label">Nuevos Archivos de Material</label>
                        <input type="file" class="form-control @error('materials.*') is-invalid @enderror" 
                               id="materials" name="materials[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                        @error('materials.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Los nuevos archivos se agregarán a los materiales existentes. Máximo 10MB por archivo.
                        </div>
                    </div>

                    <!-- File List Preview -->
                    <div id="file-list" class="mt-3" style="display: none;">
                        <h6 class="text-muted">Nuevos archivos seleccionados:</h6>
                        <div id="file-preview" class="list-group"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Settings -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Configuración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration', $lesson->duration) }}" min="1">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Duración estimada de la lección</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free" 
                                   value="1" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_free">
                                <strong>Lección Gratuita</strong>
                            </label>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Las lecciones gratuitas pueden ser vistas sin comprar el curso
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lesson Statistics -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="mb-0 text-primary">{{ $lesson->order }}</h6>
                            <small class="text-muted">Orden</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0 text-success">{{ $lesson->materials->count() }}</h6>
                            <small class="text-muted">Materiales</small>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <strong>Creado:</strong> {{ $lesson->created_at->format('d/m/Y H:i') }}<br>
                        <strong>Actualizado:</strong> {{ $lesson->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            <!-- Course Information -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Información del Curso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($lesson->course->cover_image)
                            <img src="{{ asset('storage/' . $lesson->course->cover_image) }}" 
                                 class="rounded me-3" width="60" height="45" style="object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $lesson->course->title }}</h6>
                            <small class="text-muted">{{ $lesson->course->level->name ?? 'Sin nivel' }}</small>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted d-block">Lecciones</small>
                            <strong>{{ $lesson->course->lessons()->count() }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Estudiantes</small>
                            <strong>{{ $lesson->course->student_count }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Estado</small>
                            <span class="badge bg-{{ $lesson->course->status === 'active' ? 'success' : ($lesson->course->status === 'draft' ? 'warning' : 'danger') }}">
                                {{ ucfirst($lesson->course->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.courses.lessons', $lesson->course) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Actualizar Lección
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// File preview functionality
document.getElementById('materials').addEventListener('change', function(e) {
    const files = e.target.files;
    const fileList = document.getElementById('file-list');
    const filePreview = document.getElementById('file-preview');
    
    if (files.length > 0) {
        fileList.style.display = 'block';
        filePreview.innerHTML = '';
        
        Array.from(files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            fileItem.innerHTML = `
                <div>
                    <i class="fas fa-file-alt text-muted me-2"></i>
                    <strong>${file.name}</strong>
                    <small class="text-muted d-block">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                </div>
                <span class="badge bg-primary rounded-pill">${getFileType(file.name)}</span>
            `;
            filePreview.appendChild(fileItem);
        });
    } else {
        fileList.style.display = 'none';
    }
});

function getFileType(filename) {
    const extension = filename.split('.').pop().toLowerCase();
    const types = {
        'pdf': 'PDF',
        'doc': 'Word',
        'docx': 'Word',
        'ppt': 'PowerPoint',
        'pptx': 'PowerPoint',
        'xls': 'Excel',
        'xlsx': 'Excel',
        'txt': 'Texto'
    };
    return types[extension] || 'Archivo';
}

function deleteMaterial(materialId) {
    Swal.fire({
        title: '¿Eliminar material?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form to delete the material
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('/dashboard/admin/materials') }}/${materialId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
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
</script>
@endpush
