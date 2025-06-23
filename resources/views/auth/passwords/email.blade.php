@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="background-stars">
        <div class="star top-left">✦</div>
        <div class="star bottom-left">✧</div>
        <div class="star bottom-right">📚</div>
    </div>
    
    <div class="login-card">
        <div class="login-header">
            <div class="icon-container">
                <div class="book-icon">
                    <i class="fas fa-key"></i>
                </div>
            </div>
            <h1 class="welcome-title">Recuperar contraseña <span class="sparkle">✨</span></h1>
            <p class="welcome-subtitle">Te enviaremos un enlace mágico para restablecer tu contraseña</p>
        </div>

        @if (session('status'))
            <div class="alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label for="email">Tu email<span class="magic">🔮</span></label>
                <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" placeholder="tu-email@ejemplo.com" 
                    required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="login-button">
                    {{ __('Enviar enlace de recuperación') }} <span class="rocket-button">🚀</span>
                </button>
            </div>

            <div class="form-group text-center">
                <a href="{{ route('login') }}">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #b887ef 0%, #f979a4 100%);
        position: relative;
        overflow: hidden;
        padding: 20px;
    }
    
    .background-stars .star {
        position: absolute;
        font-size: 2rem;
        color: rgba(255, 255, 255, 0.7);
    }
    
    .star.top-left {
        top: 80px;
        left: 80px;
        font-size: 2.5rem;
    }
    
    .star.bottom-left {
        bottom: 150px;
        left: 150px;
        font-size: 3rem;
    }
    
    .star.bottom-right {
        bottom: 100px;
        right: 100px;
        font-size: 3rem;
    }
    
    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        padding: 40px;
        text-align: center;
        margin: 0 auto; /* Centrado horizontal */
    }
    
    .login-header {
        margin-bottom: 30px;
    }
    
    .icon-container {
        margin-bottom: 15px;
    }
    
    .book-icon {
        width: 70px;
        height: 70px;
        background-color: #5d7bf7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        font-size: 30px;
    }
    
    .welcome-title {
        color: #9042db;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .welcome-subtitle {
        color: #777;
        font-size: 16px;
        margin-bottom: 25px;
    }
    
    .login-form {
        text-align: left;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-size: 14px;
        font-weight: 600;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #a0c8e7;
        font-size: 14px;
        transition: border 0.3s, box-shadow 0.3s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #5d7bf7;
        box-shadow: 0 0 0 2px rgba(93, 123, 247, 0.15);
    }
    
    .login-button {
        width: 100%;
        background: linear-gradient(to right, #9042db, #c165dd);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 15px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s;
    }
    
    .login-button:hover {
        transform: translateY(-2px);
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
        text-align: center;
        font-weight: 500;
    }
    
    .rocket, .star-icon, .magic, .lock, .rocket-button {
        margin-left: 5px;
    }
    
    .sparkle {
        color: #ff6b9d;
    }
    
    .is-invalid {
        border-color: #f44336;
    }
    
    .invalid-feedback {
        color: #f44336;
        font-size: 12px;
        margin-top: 5px;
    }
    
    .magic-link-button {
        display: block;
        width: 100%;
        background: transparent;
        color: #9042db;
        border: 2px dashed #c165dd;
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
        margin-top: 10px;
    }
    
    .magic-link-button:hover {
        background-color: #f0f3ff;
        transform: translateY(-2px);
    }
    
    @media (max-width: 576px) {
        .login-card {
            padding: 25px;
        }
        
        .welcome-title {
            font-size: 24px;
        }
    }
</style>
@endpush
