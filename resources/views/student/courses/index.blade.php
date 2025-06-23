@extends('layouts.student')

@section('title', 'Niveles de Curso')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Niveles de Curso</h1>
    
    <div class="row">
        @foreach($levels as $level)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <img src="{{ $level->cover_image ?? asset('images/default-level.jpg') }}" class="card-img-top" alt="{{ $level->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $level->name }}</h5>
                    <p class="card-text">{{ Str::limit($level->description, 100) }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('student.courses.level', $level->id) }}" class="btn btn-primary">Ver Detalles</a>
                    
                    @if(!auth()->user()->hasLevel($level->id))
                    <button class="btn btn-success purchase-btn" data-level-id="{{ $level->id }}" data-level-price="{{ $level->price }}">
                        Comprar (${{ $level->price }})
                    </button>
                    @else
                    <span class="badge bg-info p-2 align-self-center">Ya adquirido</span>
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
            
            // Implement purchase functionality or redirect to payment page
            if(confirm(`¿Estás seguro que deseas adquirir este nivel por $${levelPrice}?`)) {
                window.location.href = `/student/purchase/level/${levelId}`;
            }
        });
    });
</script>
@endpush
