@extends('layouts.student')

@section('title', $level->name)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('student.courses.index') }}">Niveles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $level->name }}</li>
                </ol>
            </nav>
            <h1>{{ $level->name }}</h1>
            <p class="lead">{{ $level->description }}</p>
        </div>
        <div class="col-md-4 text-end">
            @if(!auth()->user()->hasLevel($level->id))
            <button class="btn btn-lg btn-success purchase-btn" data-level-id="{{ $level->id }}" data-level-price="{{ $level->price }}">
                Comprar Nivel Completo (${{ $level->price }})
            </button>
            @else
            <span class="badge bg-info p-3 fs-5">Nivel adquirido</span>
            @endif
        </div>
    </div>
    
    @if($level->instructor)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ $level->instructor->profile_photo ?? asset('images/default-avatar.jpg') }}" class="rounded-circle me-3" width="80" alt="{{ $level->instructor->name }}">
                        <div>
                            <h3 class="card-title">Instructor: {{ $level->instructor->name }}</h3>
                            <p class="card-text">{{ $level->instructor->bio }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Contenido del nivel ({{ $sublevels->count() }} cursos)</h2>
        </div>
    </div>
    
    <div class="row">
        @foreach($sublevels as $sublevel)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="{{ $sublevel->cover_image ?? asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $sublevel->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $sublevel->name }}</h5>
                    <p class="card-text">{{ Str::limit($sublevel->description, 100) }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('student.courses.course', $sublevel->id) }}" class="btn btn-primary">Ver Curso</a>
                    
                    @if(!auth()->user()->hasLevel($level->id) && !auth()->user()->hasLevel($sublevel->id))
                    <button class="btn btn-success btn-sm purchase-btn" data-level-id="{{ $sublevel->id }}" data-level-price="{{ $sublevel->price }}">
                        Comprar (${{ $sublevel->price }})
                    </button>
                    @else
                    <span class="badge bg-info p-2 align-self-center">Disponible</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.purchase-btn').forEach(button => {
        button.addEventListener('click', function() {
            const levelId = this.getAttribute('data-level-id');
            const levelPrice = this.getAttribute('data-level-price');
            
            // Implement purchase functionality
            if(confirm(`¿Estás seguro que deseas adquirir este curso por $${levelPrice}?`)) {
                window.location.href = `/student/purchase/level/${levelId}`;
            }
        });
    });
</script>
@endpush
