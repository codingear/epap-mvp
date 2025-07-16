@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">
        <i class="fas fa-book-plus me-2"></i>Crear Curso
    </h2>
    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Main Course Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Curso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título del Curso <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción Corta <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Breve descripción que aparecerá en las tarjetas de curso</div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Contenido Detallado</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="8">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Descripción completa del curso que se mostrará en la página de detalles</div>
                    </div>

                    <div class="mb-3">
                        <label for="video_url" class="form-label">URL del Video de Presentación</label>
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" name="video_url" value="{{ old('video_url') }}" 
                               placeholder="https://youtube.com/watch?v=...">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Video de introducción o trailer del curso</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Settings -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Configuración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="level_id" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select @error('level_id') is-invalid @enderror" 
                                id="level_id" name="level_id" required>
                            <option value="">Seleccionar nivel...</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('level_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="instructor_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                        <select class="form-select @error('instructor_id') is-invalid @enderror" 
                                id="instructor_id" name="instructor_id" required>
                            <option value="">Seleccionar instructor...</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('instructor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration') }}" min="1">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Duración estimada total del curso</div>
                    </div>

                    <!-- Pricing -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free" 
                                   value="1" {{ old('is_free') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_free">
                                <strong>Curso Gratuito</strong>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="price-section">
                        <label for="price" class="form-label">Precio (USD)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', 0) }}" min="0" step="0.01">
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Course Image -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-image me-2"></i>Imagen del Curso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Imagen de Portada</label>
                        <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                               id="cover_image" name="cover_image" accept="image/*">
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Formatos: JPG, PNG, GIF. Máximo 2MB</div>
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="text-center" style="display: none;">
                        <img id="preview-img" class="img-fluid rounded" style="max-height: 200px;">
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="removeImage()">
                                <i class="fas fa-times me-1"></i>Remover
                            </button>
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
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Crear Curso
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Toggle price field based on free course checkbox
document.getElementById('is_free').addEventListener('change', function() {
    const priceSection = document.getElementById('price-section');
    const priceInput = document.getElementById('price');
    
    if (this.checked) {
        priceSection.style.display = 'none';
        priceInput.value = 0;
    } else {
        priceSection.style.display = 'block';
    }
});

// Image preview
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('cover_image').value = '';
    document.getElementById('image-preview').style.display = 'none';
}

// Set initial state
document.addEventListener('DOMContentLoaded', function() {
    const isFree = document.getElementById('is_free').checked;
    if (isFree) {
        document.getElementById('price-section').style.display = 'none';
    }
});

// Rich text editor for content (optional)
// You can integrate TinyMCE, CKEditor, or similar here
</script>
@endpush
