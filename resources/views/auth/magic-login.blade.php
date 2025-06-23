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
                    <i class="fas fa-magic"></i>
                </div>
            </div>
            <h1 class="welcome-title">¡Acceso Mágico! <span class="sparkle">✨</span></h1>
            <p class="welcome-subtitle">Ingresa sin contraseña con un enlace mágico</p>
        </div>

        <div class="tab-switch-container">
            <div class="tab-switch">
                <a href="{{ route('login') }}" class="tab-button">Entrar</a>
                <a href="{{ route('magic.login') }}" class="tab-button active">Enlace Mágico</a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('magic.link.send') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label for="email">Tu email mágico <span class="magic">🔮</span></label>
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
                <p class="magic-text">Te enviaremos un enlace mágico a tu correo electrónico para iniciar sesión sin contraseña</p>
            </div>

            <div class="form-group">
                <button type="submit" class="login-button">
                    ¡Enviar Enlace Mágico! <span class="magic-button">✨</span>
                </button>
            </div>
            
            <div class="form-group text-center">
                <a href="{{ route('login') }}" class="back-link">Volver al inicio de sesión tradicional</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    a.tab-button {
        font-size: 14px;
    }
    /* Reusing existing styles from login.blade.php */
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
        max-width: 450px;
        padding: 40px;
        text-align: center;
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
    }
    
    .tab-button.active {
        color: #fff;
        background: linear-gradient(to right, #9042db, #c165dd);
        box-shadow: 0 3px 10px rgba(144, 66, 219, 0.3);
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
        font-weight: 600; /* Make labels bolder */
    }
    
    .form-input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #a0c8e7; /* Light blue border */
        font-size: 14px;
        transition: border 0.3s, box-shadow 0.3s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #5d7bf7;
        box-shadow: 0 0 0 2px rgba(93, 123, 247, 0.15);
    }
    
    .remember-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }
    
    .remember-check {
        display: flex;
        align-items: center;
    }
    
    .remember-check input {
        margin-right: 5px;
    }
    
    .forgot-link {
        color: #9042db;
        text-decoration: none;
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
    
    .magic-text {
        font-size: 13px;
        color: #777;
        text-align: center;
        margin: 15px 0;
    }
    
    .magic-button {
        display: inline-block;
        animation: sparkle 1.5s infinite;
    }
    
    .alert {
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .back-link {
        color: #9042db;
        text-decoration: none;
        font-size: 14px;
    }
    
    @keyframes sparkle {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
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