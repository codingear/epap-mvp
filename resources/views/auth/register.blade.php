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
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
            <h1 class="welcome-title">¡Únete a la Aventura! <span class="sparkle">✨</span></h1>
            <p class="welcome-subtitle">Comienza tu viaje de aprendizaje</p>
        </div>

        <div class="tab-switch-container">
            <div class="tab-switch">
                <a href="{{ route('login') }}" class="tab-button">Entrar</a>
                <div class="tab-button active">Registrarse</div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.create') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label for="child_name">¿Cómo se llama el niño/a? <span class="magic">👧👦</span></label>
                <input id="child_name" type="text" class="form-input @error('child_name') is-invalid @enderror" 
                    name="child_name" value="{{ old('child_name') }}" placeholder="Nombre del pequeño genio" 
                    required autocomplete="child_name" autofocus>

                @error('child_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="date_of_birth">¿Cuándo nació? <span class="magic">🎂</span></label>
                <input id="date_of_birth" type="date" class="form-input @error('date_of_birth') is-invalid @enderror" 
                       name="date_of_birth" value="{{ old('date_of_birth') }}" required>

                @error('date_of_birth')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Nombre del padre/madre/tutor <span class="magic">👨‍👩‍👧</span></label>
                <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nombre completo" required>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo del tutor <span class="magic">📧</span></label>
                <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="tu-email@ejemplo.com"  required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Teléfono de contacto <span class="magic">📱</span></label>
                <input id="phone" type="tel" class="form-input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Número de teléfono">

                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Crea una contraseña <span class="lock">🔒</span></label>
                <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password" placeholder="********" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="login-button">
                    Registrar mi cuenta
                </button>
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
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 10px;
    }
    
    .alert-danger {
        background-color: #fff5f5;
        border: 1px solid #ffe3e3;
        color: #e53e3e;
    }
    
    .error-list {
        margin: 0;
        padding-left: 20px;
        text-align: left;
    }
    
    .error-list li {
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .invalid-feedback {
        color: #e53e3e;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    
    .is-invalid {
        border-color: #e53e3e;
        background-color: #fff5f5;
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
