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

                    <form id="profileForm" method="POST" action="{{ route('student.profile.update') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="type_update" value="welcome">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">País</label>
                                    <select id="country" name="country" class="form-control required-field" required>
                                        <option value="">Selecciona un país</option>
                                        <option value="142">México</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">Estado/Provincia</label>
                                    <select id="state" name="state" class="form-control required-field" required disabled>
                                        <option value="">Selecciona un estado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">Ciudad</label>
                                    <select id="city" name="city" class="form-control required-field" required disabled>
                                        <option value="">Selecciona una ciudad</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">Zona Horaria</label>
                                    <select id="timezone" name="timezone" class="form-control required-field" required disabled>
                                        <option value="">Selecciona una zona horaria</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg px-5" disabled>
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
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    
    select.form-control {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 10px 15px;
    }
    
    select.form-control:focus {
        border-color: #a265ff;
        box-shadow: 0 0 0 0.2rem rgba(162, 101, 255, 0.25);
    }
    
    label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }
    
    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        border-radius: 10px;
        border: 1px solid #ddd;
        height: 45px;
        padding: 8px 5px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px;
    }
    
    .select2-container--default .select2-selection--single:focus {
        border-color: #a265ff;
        box-shadow: 0 0 0 0.2rem rgba(162, 101, 255, 0.25);
    }
    
    .select2-dropdown {
        border-radius: 10px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #ff7043;
    }
    
    .floating-stars {
        position: absolute;
        z-index: 0;
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#country, #state, #city, #timezone').select2({
            placeholder: "Selecciona una opción",
            width: '100%'
        });
        
        // Load states when country is selected
        $('#country').change(function() {
            const countryId = $(this).val();
            if (countryId) {
                $('#state').prop('disabled', false);
                $('#city').prop('disabled', true).html('<option value="">Selecciona una ciudad</option>').trigger('change');
                $('#timezone').prop('disabled', true).html('<option value="">Selecciona una zona horaria</option>').trigger('change');
                
                // Get states for selected country
                $.ajax({
                    url: `/api/states/${countryId}`,
                    type: 'GET',
                    success: function(data) {
                        const stateSelect = $('#state');
                        stateSelect.html('<option value="">Selecciona un estado</option>');
                        $.each(data, function(index, state) {
                            stateSelect.append($('<option>').val(state.id).text(state.name));
                        });
                        stateSelect.trigger('change'); // Update Select2
                    }
                });
                
                // Get timezones for selected country
                $.ajax({
                    url: `/api/timezone/${countryId}`,
                    type: 'GET',
                    success: function(data) {
                        const timezoneSelect = $('#timezone');
                        timezoneSelect.html('<option value="">Selecciona una zona horaria</option>');
                        timezoneSelect.prop('disabled', false);
                        $.each(data, function(index, tz) {
                            timezoneSelect.append($('<option>').val(tz.id).text(tz.name));
                        });
                        timezoneSelect.trigger('change'); // Update Select2
                    }
                });
            } else {
                $('#state').prop('disabled', true).html('<option value="">Selecciona un estado</option>').trigger('change');
                $('#city').prop('disabled', true).html('<option value="">Selecciona una ciudad</option>').trigger('change');
                $('#timezone').prop('disabled', true).html('<option value="">Selecciona una zona horaria</option>').trigger('change');
            }
            validateForm();
        });
        
        // Load cities when state is selected
        $('#state').change(function() {
            const stateId = $(this).val();
            if (stateId) {
                $('#city').prop('disabled', false);
                
                // Get cities for selected state
                $.ajax({
                    url: `/api/cities/${stateId}`,
                    type: 'GET',
                    success: function(data) {
                        const citySelect = $('#city');
                        citySelect.html('<option value="">Selecciona una ciudad</option>');
                        $.each(data, function(index, city) {
                            citySelect.append($('<option>').val(city.id).text(city.name));
                        });
                        citySelect.trigger('change'); // Update Select2
                    }
                });
            } else {
                $('#city').prop('disabled', true).html('<option value="">Selecciona una ciudad</option>').trigger('change');
            }
            validateForm();
        });
        
        // Validate when city or timezone changes
        $('#city, #timezone').change(function() {
            validateForm();
        });
        
        // Validate form to enable/disable submit button
        function validateForm() {
            const country = $('#country').val();
            const state = $('#state').val();
            const city = $('#city').val();
            const timezone = $('#timezone').val();
            
            if (country && state && city && timezone) {
                $('#submitBtn').prop('disabled', false);
            } else {
                $('#submitBtn').prop('disabled', true);
            }
        }
    });
</script>
@endpush