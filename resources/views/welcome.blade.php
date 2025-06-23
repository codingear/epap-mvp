@extends('layouts.app')

@section('content')
<div class="container my-5">
    @if (session('message'))
    <div class="alert alert-success alert-kids mb-4" role="alert">
        <i class="alert-icon fas fa-check-circle"></i>
        {{ session('message') }}
    </div>
    @endif
    
    <div class="row mb-5">
        <div class="col-xl-12 text-center">
            <div class="section-heading">
                <h1 class="display-4 fw-bold text-primary kids-title">¡Bienvenido a {{ config('app.name') }}!</h1>
                <div class="heading-decoration">
                    <img src="https://html.vecurosoft.com/kiddino/demo/images/toy-car.svg" alt="Decoración" width="80">
                <p class="lead">Una aplicación divertida para niños</p>
            </div>
        </div>
    </div>
    
    <!-- Componentes básicos -->
    <div class="row g-4">
        <!-- Sección de botones -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Botones</h3>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary btn-kids">Botón Primario</button>
                    <button class="btn btn-success btn-kids">Botón Éxito</button>
                    <button class="btn btn-warning btn-kids">Botón Alerta</button>
                    <button class="btn btn-danger btn-kids">Botón Peligro</button>
                    <button class="btn btn-info btn-kids">Botón Info</button>
                    <button class="btn btn-outline-primary btn-kids-outline">Contorno</button>
                </div>
            </div>
        </div>
        
        <!-- Sección de inputs -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Campos de Texto</h3>
                <div class="mb-3">
                    <label for="inputText" class="form-label">Campo de texto</label>
                    <input type="text" class="form-control input-kids" id="inputText" placeholder="Escribe aquí...">
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Contraseña</label>
                    <input type="password" class="form-control input-kids" id="inputPassword" placeholder="Tu contraseña secreta">
                </div>
            </div>
        </div>
        
        <!-- Área de texto -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Área de Texto</h3>
                <div class="mb-3">
                    <label for="textareaInput" class="form-label">Cuéntanos una historia</label>
                    <textarea class="form-control textarea-kids" id="textareaInput" rows="3" placeholder="Había una vez..."></textarea>
                </div>
            </div>
        </div>
        
        <!-- Checkboxes y Radios -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Opciones</h3>
                <div class="mb-3">
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input checkbox-kids" type="checkbox" id="check1">
                        <label class="form-check-label" for="check1">Me gustan los animales</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input checkbox-kids" type="checkbox" id="check2" checked>
                        <label class="form-check-label" for="check2">Me gustan los dibujos</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check custom-radio">
                        <input class="form-check-input radio-kids" type="radio" name="colorOptions" id="radio1" checked>
                        <label class="form-check-label" for="radio1">Rojo</label>
                    </div>
                    <div class="form-check custom-radio">
                        <input class="form-check-input radio-kids" type="radio" name="colorOptions" id="radio2">
                        <label class="form-check-label" for="radio2">Azul</label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Select -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Selector</h3>
                <div class="mb-3 position-relative">
                    <label for="selectInput" class="form-label">¿Cuál es tu animal favorito?</label>
                    <select class="form-select select-kids" id="selectInput">
                        <option selected>Elige uno...</option>
                        <option value="1">Perro</option>
                        <option value="2">Gato</option>
                        <option value="3">Delfín</option>
                        <option value="4">Dinosaurio</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Tarjetas -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Tarjetas</h3>
                <div class="card card-kids mb-3">
                    <div class="card-header">¡Sorpresa!</div>
                    <div class="card-body">
                        <h5 class="card-title">Tarjeta divertida</h5>
                        <p class="card-text">Esta es una tarjeta con información interesante.</p>
                        <a href="#" class="btn btn-warning btn-sm btn-kids">¡Explorar!</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alerta -->
        <div class="col-md-6 mb-4">
            <div class="component-card">
                <h3 class="component-title">Alertas</h3>
                <div class="alert alert-primary alert-kids" role="alert">
                    <i class="alert-icon fas fa-info-circle"></i>
                    ¡Una información muy interesante para ti!
                </div>
                <div class="alert alert-success alert-kids" role="alert">
                    <i class="alert-icon fas fa-check-circle"></i>
                    ¡Muy bien hecho! Has completado la actividad.
                </div>
            </div>
        </div>
        
        <!-- Formulario completo -->
        <div class="col-md-12 mb-4">
            <div class="component-card">
                <h3 class="component-title">Formulario Completo</h3>
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control input-kids" id="name" placeholder="Tu nombre">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Edad</label>
                            <input type="number" class="form-control input-kids" id="age" placeholder="Tu edad">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="favorite" class="form-label">¿Qué te gusta hacer?</label>
                        <textarea class="form-control textarea-kids" id="favorite" rows="2" placeholder="Me gusta..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Colores favoritos:</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input checkbox-kids" type="checkbox" id="favoriteRed">
                                <label class="form-check-label text-danger fw-bold" for="favoriteRed">Rojo</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input checkbox-kids" type="checkbox" id="favoriteBlue">
                                <label class="form-check-label text-primary fw-bold" for="favoriteBlue">Azul</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input checkbox-kids" type="checkbox" id="favoriteGreen">
                                <label class="form-check-label text-success fw-bold" for="favoriteGreen">Verde</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input checkbox-kids" type="checkbox" id="favoriteYellow">
                                <label class="form-check-label text-warning fw-bold" for="favoriteYellow">Amarillo</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-kids btn-lg">¡Enviar!</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Estilos generales para componentes infantiles */
@import url('https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Nunito:wght@400;700&display=swap');

:root {
    --primary-color: #FF6F00;
    --secondary-color: #7E57C2;
    --success-color: #66BB6A;
    --warning-color: #FFCA28;
    --danger-color: #EF5350;
    --info-color: #29B6F6;
    --light-bg: #F5F9FD;
}

body {
    background-color: var(--light-bg);
    font-family: 'Nunito', sans-serif;
    background-image: url('https://html.vecurosoft.com/kiddino/demo/images/bg/geometry.png');
}

.kids-title {
    font-family: 'Bubblegum Sans', cursive;
    color: var(--primary-color);
    text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.section-heading {
    position: relative;
    margin-bottom: 40px;
}

.heading-decoration {
    margin: 15px auto;
}

.heading-decoration img {
    animation: float 3s ease-in-out infinite;
    filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
}

.component-card {
    background-color: white;
    border-radius: 30px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    border: 4px solid #FFD54F;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
    overflow: hidden;
}

.component-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 10px;
    background: linear-gradient(45deg, #FF6F00, #FFD54F);
    border-radius: 5px 5px 0 0;
    z-index: -1;
}

.component-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(255, 111, 0, 0.1);
}

.component-title {
    color: var(--primary-color);
    font-family: 'Bubblegum Sans', cursive;
    font-size: 1.8rem;
    position: relative;
    display: inline-block;
    margin-bottom: 25px;
}

.component-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(to right, var(--primary-color), var(--warning-color));
    border-radius: 5px;
}

/* Estilos para botones */
.btn-kids {
    border-radius: 50px;
    font-family: 'Bubblegum Sans', cursive;
    font-weight: normal;
    font-size: 1rem;
    padding: 10px 25px;
    border: none;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    z-index: 1;
    text-transform: initial;
}

.btn-kids::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background-color: rgba(0, 0, 0, 0.15);
    z-index: -1;
}

.btn-kids:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.btn-primary.btn-kids {
    background-color: var(--primary-color);
}

.btn-success.btn-kids {
    background-color: var(--success-color);
}

.btn-warning.btn-kids {
    background-color: var(--warning-color);
    color: #333;
}

.btn-danger.btn-kids {
    background-color: var(--danger-color);
}

.btn-info.btn-kids {
    background-color: var(--info-color);
    color: white;
}

.btn-kids-outline {
    border-radius: 50px;
    font-family: 'Bubblegum Sans', cursive;
    font-size: 1rem;
    border-width: 3px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

/* Estilos para inputs */
.input-kids, .textarea-kids, .select-kids {
    border-radius: 20px;
    border: 3px solid #FFD54F;
    padding: 12px 20px;
    transition: all 0.3s;
    background-color: #FFFDE7;
    font-size: 1rem;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
}

.input-kids:focus, .textarea-kids:focus, .select-kids:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(255, 111, 0, 0.25);
    background-color: white;
}

.form-label {
    font-weight: 700;
    color: #555;
    margin-bottom: 8px;
    position: relative;
    display: inline-block;
}

.form-label::after {
    content: '✏️';
    font-size: 14px;
    margin-left: 5px;
}

.textarea-kids {
    border-radius: 25px;
}

/* Estilos para checkboxes y radios */
.custom-checkbox, .custom-radio {
    padding-left: 35px;
    margin-bottom: 10px;
    position: relative;
    cursor: pointer;
}

.checkbox-kids, .radio-kids {
    width: 25px;
    height: 25px;
    position: absolute;
    left: 0;
    top: 0;
    cursor: pointer;
    border: 3px solid #FFD54F;
    transition: all 0.2s;
    opacity: 0;
}

.custom-checkbox .form-check-label::before {
    content: '';
    position: absolute;
    left: -35px;
    top: 0;
    width: 25px;
    height: 25px;
    border-radius: 8px;
    border: 3px solid #FFD54F;
    background-color: #FFFDE7;
    transition: all 0.2s;
}

.custom-checkbox .checkbox-kids:checked ~ .form-check-label::before {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.custom-checkbox .checkbox-kids:checked ~ .form-check-label::after {
    content: '✓';
    position: absolute;
    left: -29px;
    top: -3px;
    font-size: 20px;
    color: white;
    font-weight: bold;
}

.custom-radio .form-check-label::before {
    content: '';
    position: absolute;
    left: -35px;
    top: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    border: 3px solid #FFD54F;
    background-color: #FFFDE7;
    transition: all 0.2s;
}

.custom-radio .radio-kids:checked ~ .form-check-label::before {
    background-color: #FFFDE7;
    border-color: var(--primary-color);
}

.custom-radio .radio-kids:checked ~ .form-check-label::after {
    content: '';
    position: absolute;
    left: -27px;
    top: 8px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background-color: var(--primary-color);
}

/* Estilos para tarjetas */
.card-kids {
    border-radius: 30px;
    border: 4px solid #FFD54F;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    background-color: white;
}

.card-kids:hover {
    transform: translateY(-8px) rotate(1deg);
    box-shadow: 0 15px 35px rgba(255, 111, 0, 0.15);
}

.card-kids .card-header {
    background: var(--primary-color);
    font-weight: bold;
    color: white;
    font-family: 'Bubblegum Sans', cursive;
    font-size: 1.2rem;
    padding: 15px 20px;
    border-bottom: 3px dashed #FFD54F;
}

.card-kids .card-body {
    padding: 20px;
}

.card-kids .card-title {
    color: var(--primary-color);
    font-weight: bold;
    font-size: 1.3rem;
}

/* Estilos para alertas */
.alert-kids {
    border-radius: 20px;
    border: none;
    font-weight: bold;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    position: relative;
    padding-left: 55px;
    padding-top: 15px;
    padding-bottom: 15px;
}

.alert-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
}

.alert-primary.alert-kids {
    background-color: #E3F2FD;
    color: #0D47A1;
    border-left: 5px solid var(--info-color);
}

.alert-success.alert-kids {
    background-color: #E8F5E9;
    color: #1B5E20;
    border-left: 5px solid var(--success-color);
}

/* Animaciones */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.btn-primary.btn-kids:hover {
    animation: pulse 0.8s ease-in-out infinite;
}

/* Fix para Select */
.select-kids {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23FF6F00' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px 12px;
    padding-right: 45px;
}
</style>
@endpush