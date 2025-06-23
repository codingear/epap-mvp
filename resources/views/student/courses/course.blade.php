@extends('layouts.student')

@section('title', $course->name)

@section('content')
<div class="container-fluid p-0">
    <div class="course-header position-relative">
        <img src="{{ $course->cover_image ?? asset('images/default-course-cover.jpg') }}" class="w-100" style="height: 300px; object-fit: cover;" alt="{{ $course->name }}">
        <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: rgba(0, 0, 0, 0.7);">
            <div class="container">
                <h1 class="text-white">{{ $course->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('student.courses.index') }}" class="text-white">Niveles</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.courses.level', $course->level_id) }}" class="text-white">{{ $course->level->name }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $course->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2>Acerca de este curso</h2>
                    <p class="lead">{{ $course->description }}</p>
                    
                    <h3 class="mt-4">Lo que aprenderás</h3>
                    <ul class="list-group list-group-flush">
                        @foreach(explode("\n", $course->learning_objectives) as $objective)
                            @if(!empty(trim($objective)))
                                <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> {{ trim($objective) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    
                    <h3 class="mt-4">Requisitos</h3>
                    <ul class="list-group list-group-flush">
                        @foreach(explode("\n", $course->prerequisites) as $prerequisite)
                            @if(!empty(trim($prerequisite)))
                                <li class="list-group-item"><i class="fas fa-arrow-right text-primary me-2"></i> {{ trim($prerequisite) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h2>Contenido del curso</h2>
                    <div class="accordion" id="courseContent">
                        @foreach($course->sections as $index => $section)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#section{{ $section->id }}">
                                    {{ $section->title }} ({{ $section->lessons->count() }} lecciones)
                                </button>
                            </h2>
                            <div id="section{{ $section->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($section->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas {{ $lesson->type === 'video' ? 'fa-play-circle' : 'fa-file-alt' }} me-2"></i>
                                                {{ $lesson->title }}
                                            </div>
                                            <span class="text-muted">{{ $lesson->duration }} min</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Detalles del curso</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Duración total:</span>
                            <strong>{{ $course->total_duration }} horas</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Lecciones:</span>
                            <strong>{{ $course->total_lessons }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Nivel:</span>
                            <strong>{{ $course->difficulty }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Estudiantes inscritos:</span>
                            <strong>{{ $course->enrolled_count }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Instructor</h3>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $course->instructor->profile_photo ?? asset('images/default-avatar.jpg') }}" class="rounded-circle me-3" width="60" alt="{{ $course->instructor->name }}">
                        <div>
                            <h5 class="mb-0">{{ $course->instructor->name }}</h5>
                            <p class="text-muted mb-0">{{ $course->instructor->title }}</p>
                        </div>
                    </div>
                    <p>{{ Str::limit($course->instructor->bio, 150) }}</p>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-star text-warning me-1"></i>
                                <span>{{ $course->instructor->rating }} Calificación</span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-graduate me-1"></i>
                                <span>{{ $course->instructor->students_count }} Estudiantes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(!auth()->user()->hasAccess($course->id))
            <div class="card">
                <div class="card-body">
                    <h3>Inscríbete en este curso</h3>
                    <p class="lead text-center mb-4">
                        <strong class="text-primary">${{ $course->price }}</strong>
                    </p>
                    <button class="btn btn-lg btn-success w-100 purchase-btn" data-course-id="{{ $course->id }}" data-course-price="{{ $course->price }}">
                        Comprar este curso
                    </button>
                    <hr>
                    <p class="small text-muted text-center">Garantía de devolución de 30 días</p>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center">
                    <h3>Ya tienes acceso a este curso</h3>
                    <a href="{{ route('student.learning.course', $course->id) }}" class="btn btn-lg btn-primary w-100">
                        Continuar Aprendiendo
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.purchase-btn').forEach(button => {
        button.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const coursePrice = this.getAttribute('data-course-price');
            
            // Implement purchase functionality
            if(confirm(`¿Estás seguro que deseas adquirir este curso por $${coursePrice}?`)) {
                window.location.href = `/student/purchase/course/${courseId}`;
            }
        });
    });
</script>
@endpush
