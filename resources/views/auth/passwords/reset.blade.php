@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="background-stars">
        <div class="star top-left">✦</div>
        <div class="star bottom-left">✧</div>
        <div class="star bottom-right">🔑</div>
    </div>
    
    <div class="login-card">
        <div class="login-header">
            <div class="icon-container">
                <div class="book-icon">
                    <i class="fas fa-key"></i>
                </div>
            </div>
            <h1 class="welcome-title">Restablecer contraseña <span class="sparkle">✨</span></h1>
            <p class="welcome-subtitle">Crea una nueva contraseña segura</p>
        </div>

        <div class="tab-switch-container">
            <div class="tab-switch">
                <div class="tab-button active">Restablecer Contraseña</div>
            </div>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="login-form">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Tu email<span class="magic">🔮</span></label>
                <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" 
                    name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nueva contraseña<span class="lock">🔒</span></label>
                <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" 
                    name="password" placeholder="********" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirmar contraseña<span class="lock">🔒</span></label>
                <input id="password-confirm" type="password" class="form-input" 
                    name="password_confirmation" placeholder="********" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <button type="submit" class="login-button">
                    Restablecer Contraseña <span class="rocket-button">🚀</span>
                </button>
            </div>

            <div class="form-group text-center">
                <a href="{{ route('login') }}" class="magic-link-button">
                    Volver a Iniciar Sesión <span class="magic">↩️</span>
                </a>
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
    
    .text-center {
        text-align: center;
    }
    
    .tab-switch-container {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }
    
    .tab-switch {
        display: flex;
        background-color: #f0f3ff;
        border-radius: 50px;
        padding: 4px;
        width: 100%;
        position: relative;
    }
    
    .tab-button {
        flex: 1;
        padding: 10px 15px;
        text-align: center;
        border-radius: 50px;
        cursor: pointer;
        color: #777;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        text-decoration: none;
        font-size: 14px;
    }
    
    .tab-button.active {
        color: #fff;
        background: linear-gradient(to right, #9042db, #c165dd);
        box-shadow: 0 3px 10px rgba(144, 66, 219, 0.3);
    }
    
    @media (max-width: 576px) {
        .login-card {
            padding: 25px 20px; /* Padding horizontal y vertical consistente */
            max-width: 95%; /* Mayor porcentaje en dispositivos pequeños */
        }
        
        .welcome-title {
            font-size: 24px;
        }
    }
</style>
@endpush
