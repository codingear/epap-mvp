@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-1">
            <i class="fas fa-plus-circle me-2"></i>Crear Lección
        </h2>
        <p class="text-muted mb-0">Curso: {{ $course->title }}</p>
    </div>
    <a href="{{ route('admin.courses.lessons', $course) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver a Lecciones
    </a>
</div>

<form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
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
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Breve descripción de lo que aprenderán en esta lección</div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Contenido de la Lección <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" required>{{ old('content') }}</textarea>
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
                               id="video_url" name="video_url" value="{{ old('video_url') }}" 
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

            <!-- Materials Section -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-paperclip me-2"></i>Materiales de la Lección
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="materials" class="form-label">Archivos de Material</label>
                        <input type="file" class="form-control @error('materials.*') is-invalid @enderror" 
                               id="materials" name="materials[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                        @error('materials.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Puedes subir múltiples archivos. Formatos permitidos: PDF, DOC, PPT, XLS, TXT. Máximo 10MB por archivo.
                        </div>
                    </div>

                    <!-- File List Preview -->
                    <div id="file-list" class="mt-3" style="display: none;">
                        <h6 class="text-muted">Archivos seleccionados:</h6>
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
                               id="duration" name="duration" value="{{ old('duration') }}" min="1">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Duración estimada de la lección</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free" 
                                   value="1" {{ old('is_free') ? 'checked' : '' }}>
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

            <!-- Course Information -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Información del Curso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($course->cover_image)
                            <img src="{{ asset('storage/' . $course->cover_image) }}" 
                                 class="rounded me-3" width="60" height="45" style="object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $course->title }}</h6>
                            <small class="text-muted">{{ $course->level->name ?? 'Sin nivel' }}</small>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted d-block">Lecciones</small>
                            <strong>{{ $course->lessons()->count() }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Estudiantes</small>
                            <strong>{{ $course->student_count }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Estado</small>
                            <span class="badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'danger') }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">Esta será la lección #{{ $course->lessons()->count() + 1 }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.courses.lessons', $course) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Crear Lección
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
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

// Auto-generate duration from video URL (for YouTube)
document.getElementById('video_url').addEventListener('blur', function() {
    const url = this.value;
    if (url.includes('youtube.com') || url.includes('youtu.be')) {
        // You could implement YouTube API integration here to get video duration
        console.log('YouTube video detected:', url);
    }
});

// Rich text editor for content (you can integrate TinyMCE, CKEditor, etc.)
// For now, we'll add some basic formatting help
document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    
    // Add formatting toolbar
    const toolbar = document.createElement('div');
    toolbar.className = 'mb-2';
    toolbar.innerHTML = `
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Formato básico: <code>&lt;h3&gt;Título&lt;/h3&gt;</code>, <code>&lt;p&gt;Párrafo&lt;/p&gt;</code>, 
            <code>&lt;strong&gt;Negrita&lt;/strong&gt;</code>, <code>&lt;em&gt;Cursiva&lt;/em&gt;</code>
        </small>
    `;
    contentTextarea.parentNode.insertBefore(toolbar, contentTextarea);
});
</script>
@endpush
