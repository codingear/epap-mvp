@extends('student.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h1 class="welcome-title">¡Bienvenido, Pequeño Explorador! 🚀</h1>
                        <p class="welcome-subtitle">
                            Estás a punto de comenzar una increíble aventura de aprendizaje.<br>
                            Primero, vamos a conocer a tu tutor personal.
                        </p>
                    </div>

                    <div class="text-center mb-4">
                        <div class="tutor-icon-container">
                            <i class="fas fa-video tutor-icon"></i>
                        </div>
                        <h2 class="mt-3">¡Conoce a tu Tutor Personal! 👨‍🏫</h2>
                        <p class="tutor-description">
                            Para comenzar tu increíble viaje de aprendizaje, primero tendrás una
                            videollamada de bienvenida con un tutor humano. Después, Aranza IA
                            será tu compañera de aprendizaje en todas las lecciones, con el apoyo
                            de profesores cuando lo necesites.
                        </p>
                    </div>

                    <form id="profileForm" method="GET" action="{{ route('student.calendar') }}" class="mt-4">
                        <div class="text-center mt-4">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-calendar-alt me-2"></i> Agendar mi Primera Videollamada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #4e54c8, #8f94fb, #a265ff);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
    
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    
    .welcome-title {
        color: #4e54c8;
        font-weight: 700;
        font-size: 2.2rem;
    }
    
    .welcome-subtitle {
        color: #666;
        font-size: 1.1rem;
    }
    
    .tutor-icon-container {
        width: 80px;
        height: 80px;
        background-color: #ff7043;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .tutor-icon {
        color: white;
        font-size: 2rem;
    }
    
    .tutor-description {
        color: #555;
        max-width: 85%;
        margin: 0 auto;
    }
    
    .btn-primary {
        background: #ff7043;
        border: none;
        border-radius: 30px;
        padding: 10px 25px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(255, 112, 67, 0.3);
        transition: all 0.3s;
    }
    
    .btn-primary:hover:not(:disabled) {
        background: #f4511e;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 112, 67, 0.4);
    }
    
    .btn-primary:disabled {
        background: #ffa088;
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    .floating-stars {
        position: absolute;
        z-index: 0;
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Form is now ready to submit without validation
        $('#submitBtn').prop('disabled', false);
    });
</script>
@endpush