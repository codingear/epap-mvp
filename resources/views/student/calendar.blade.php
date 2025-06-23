@extends('student.layouts.app')

@section('content')
<!-- Navbar - Moved outside container to be full width -->
<div class="card shadow-sm sticky-top w-100 mb-4 rounded-0">
    <div class="card-body p-2">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Back Button -->
                <div>
                    <a href="{{route('student.index')}}" class="btn btn-outline-primary rounded-pill">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
                
                <!-- Right Side: Timezone, User Info and Logout -->
                <div class="d-flex align-items-center">
                    <!-- Timezone Display (No longer a dropdown) -->
                    <div class="me-3">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-globe me-1"></i> {{auth()->user()->timezoneuser}}
                        </button>
                    </div>
                    
                    <!-- User Avatar and Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=9042db&color=fff" class="rounded-circle me-2" width="30" height="30" alt="Avatar">
                            <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <!-- Background Stars -->
    <div class="background-stars">
        <div class="star top-left">✦</div>
        <div class="star bottom-left">✧</div>
        <div class="star bottom-right">📚</div>
    </div>

    <!-- Header Section -->
    <div class="card mb-4 border-0 rounded-4 bg-gradient" style="background: linear-gradient(135deg, #9042db, #c165dd);">
        <div class="card-body text-center text-white py-4">
            <h2 class="fw-bold mb-2">¡Agenda tu Videollamada! <span class="ms-2 sparkle">✨</span></h2>
            <p class="mb-0">Selecciona el día y hora para tu videollamada de bienvenida</p>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="text-primary">
                <i class="bi bi-calendar-event"></i>
                Selecciona el día <span class="magic">🔮</span>
            </h5>
            <p class="text-muted small mb-4">Elige el día que mejor te convenga para tu videollamada</p>

            <div class="row g-3 justify-content-center">
                @php
                    $currentDay = now();
                    $weekdays = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                @endphp

                @for ($i = 0; $i < 5; $i++)
                    @php
                        $day = $currentDay->addDay($i == 0 ? 0 : 1);
                        $dayNumber = $day->day;
                        $dayName = $weekdays[$i];
                    @endphp
                    <div class="col">
                        <div class="card day-card text-center" data-day="{{ $dayNumber }}" data-day-name="{{ $dayName }}">
                            <div class="card-body py-3">
                                <h5 class="card-title mb-1">{{ $dayNumber }}</h5>
                                <p class="card-text small mb-0">{{ $dayName }}</p>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Time Selection -->
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="text-primary">
                <i class="bi bi-clock"></i>
                Elige la hora <span class="lock">🔒</span>
            </h5>
            <p class="text-muted small mb-4">Selecciona el horario que mejor se adapte a tu rutina</p>

            <div class="row g-3">
                <!-- Time slots with timezone info -->
                <div class="col-md-4">
                    <div class="card time-slot" data-time="09:00 AM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">09:00 AM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card time-slot" data-time="10:00 AM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">10:00 AM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card time-slot" data-time="11:00 AM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">11:00 AM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>

                <!-- Midday Time Slots -->
                <div class="col-md-4">
                    <div class="card time-slot" data-time="12:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">12:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card time-slot" data-time="01:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">01:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card time-slot" data-time="02:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">02:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>

                <!-- Afternoon Time Slots -->
                <div class="col-md-4">
                    <div class="card time-slot" data-time="03:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">03:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card time-slot" data-time="04:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">04:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card time-slot" data-time="05:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">05:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                
                <!-- Evening Time Slots -->
                <div class="col-md-4">
                    <div class="card time-slot" data-time="06:00 PM">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">06:00 PM</h5>
                            <small class="text-muted timezone-note">{{ auth()->user()->timezoneuser ? '(' . str_replace('_', ' ', auth()->user()->timezoneuser) . ')' : '' }}</small>
                        </div>
                    </div>
                </div>
                
                <!-- Current Date Time Display -->
                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <small class="text-muted d-block mb-1">Tu hora local:</small>
                            <p id="current-datetime" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Summary -->
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="text-primary">
                <i class="bi bi-clipboard-check"></i>
                Resumen de tu Cita <span class="ms-2 sparkle">📝</span>
            </h5>
            <p class="text-muted small mb-4">Completa todos los campos para confirmar tu cita</p>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Día:</label>
                        <p id="selected-day" class="mb-0 text-primary">No seleccionado</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hora:</label>
                        <p id="selected-time" class="mb-0 text-primary">No seleccionado</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Zona Horaria:</label>
                        <p id="selected-timezone" class="mb-0 text-primary">{{ auth()->user()->timezoneuser ? str_replace('_', ' ', auth()->user()->timezoneuser) : 'No configurada' }}</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button id="confirm-button" class="btn btn-lg px-5 py-2 rounded-pill" style="background: linear-gradient(to right, #9042db, #c165dd); color: white;" disabled>
                    <i class="bi bi-camera-video me-2"></i>
                    ¡Confirmar mi Videollamada! <span class="rocket-button">🚀</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedDay = null;
        let selectedDayName = null;
        let selectedTime = null;
        let userTimezone = "{{ auth()->user()->timezoneuser ?? 'America/Mexico_City' }}";
        
        // Update current date time display
        function updateCurrentDateTime() {
            const currentDatetimeElement = document.getElementById('current-datetime');
            
            if (currentDatetimeElement) {
                const options = {
                    timeZone: userTimezone,
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                
                const formatter = new Intl.DateTimeFormat('es-ES', options);
                currentDatetimeElement.textContent = formatter.format(new Date());
            }
            
            // Update timezone notes below time slots
            document.querySelectorAll('.timezone-note').forEach(note => {
                note.textContent = `(${userTimezone.replace('_', ' ')})`;
            });
        }
        
        // Initialize with current datetime
        updateCurrentDateTime();
        // Update every second
        setInterval(updateCurrentDateTime, 1000);
        
        // Day Selection
        document.querySelectorAll('.day-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove active class from all day cards
                document.querySelectorAll('.day-card').forEach(c => {
                    c.classList.remove('border-primary', 'border-2');
                });
                
                // Add active class to selected card
                this.classList.add('border-primary', 'border-2');
                
                // Update selected day
                selectedDay = this.getAttribute('data-day');
                selectedDayName = this.getAttribute('data-day-name');
                document.getElementById('selected-day').textContent = `${selectedDay} (${selectedDayName})`;
                
                updateConfirmButton();
            });
        });
        
        // Time Selection
        document.querySelectorAll('.time-slot').forEach(card => {
            card.addEventListener('click', function() {
                // Remove active class from all time cards
                document.querySelectorAll('.time-slot').forEach(c => {
                    c.classList.remove('border-primary', 'border-2');
                });
                
                // Add active class to selected card
                this.classList.add('border-primary', 'border-2');
                
                // Update selected time
                selectedTime = this.getAttribute('data-time');
                document.getElementById('selected-time').textContent = selectedTime;
                
                updateConfirmButton();
            });
        });
        
        // Update confirm button state
        function updateConfirmButton() {
            const button = document.getElementById('confirm-button');
            
            if (selectedDay && selectedTime) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        }
        
        // Confirm button click handler
        document.getElementById('confirm-button').addEventListener('click', function() {
            if (selectedDay && selectedTime) {
                // Use SweetAlert2 for confirmation
                Swal.fire({
                    title: 'Confirmando tu cita',
                    html: `
                        <p style="margin-bottom:5px;">Día: <b>${selectedDay} (${selectedDayName})</b></p>
                        <p style="margin-bottom:5px;">Hora: <b>${selectedTime}</b></p>
                        <p style="margin-bottom:5px;">Zona Horaria: <b>${userTimezone.replace('_', ' ')}</b></p>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#9042db',
                    cancelButtonColor: '#6c757d',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Guardando tu cita...',
                            text: 'Espera un momento por favor',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // AJAX request to save the appointment with the correct route
                        fetch('{{ route("student.calendar.create") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                day: selectedDay,
                                time: selectedTime,
                                timezone: userTimezone
                            })
                        })
                        .then(response => {
                            // Store the response status for later use
                            const isSuccessful = response.ok;
                            
                            // Try to parse JSON response if available, otherwise return empty object
                            return response.text().then(text => {
                                try {
                                    return text ? JSON.parse(text) : {};
                                } catch (e) {
                                    console.log('Response is not JSON:', text);
                                    return { message: text || 'Success' };
                                }
                            }).then(data => {
                                return { isSuccessful, data };
                            });
                        })
                        .then(({ isSuccessful, data }) => {
                            if (isSuccessful) {
                                // Success modal
                                Swal.fire({
                                    title: '¡Cita confirmada!',
                                    text: 'Tu videollamada ha sido agendada correctamente',
                                    icon: 'success',
                                    confirmButtonColor: '#9042db'
                                }).then(() => {
                                    // Redirect to welcome page or the provided redirect URL
                                    window.location.href = '{{ route("student.index") }}';
                                });
                            } else {
                                // Error message from server
                                throw new Error(data.message || 'Error en la respuesta del servidor');
                            }
                        })
                        .catch(error => {
                            console.error('Error saving appointment:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema al guardar tu cita. Por favor, intenta de nuevo.',
                                icon: 'error',
                                confirmButtonColor: '#9042db'
                            });
                        });
                    }
                });
            }
        });
    });
</script>
@endpush 

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #b887ef 0%, #f979a4 100%);
        padding-top: 0; /* Remove any default padding that might interfere with the sticky navbar */
    }
    
    /* Sticky navbar styling */
    .sticky-top {
        z-index: 1030;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Current time badge styling */
    #current-time-display {
        font-size: 0.9rem;
        font-weight: 500;
        min-width: 80px;
        background-color: #9042db !important;
    }
    
    .background-stars {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: -1;
    }
    
    .background-stars .star {
        position: absolute;
        font-size: 2rem;
        color: rgba(144, 66, 219, 0.1);
        z-index: -1;
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
    
    .text-primary {
        color: #9042db !important;
    }
    
    .tutor-card, .day-card, .time-slot {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #a0c8e7;
        border-radius: 10px;
    }
    
    .tutor-card:hover, .day-card:hover, .time-slot:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .border-primary {
        border-color: #9042db !important;
    }
    
    /* Active states for selection cards */
    .tutor-card.border-primary, .day-card.border-primary, .time-slot.border-primary {
        background-color: #f0f3ff;
        box-shadow: 0 3px 10px rgba(144, 66, 219, 0.2);
    }
    
    /* Tutor avatars with soft shadow */
    .tutor-avatar img {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 3px solid white;
    }
    
    /* Button hover effect */
    #confirm-button:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(144, 66, 219, 0.4);
    }
    
    /* Icons and decorative elements */
    .sparkle {
        color: #ff6b9d;
        margin-left: 5px;
    }
    
    .magic, .lock, .rocket-button {
        margin-left: 5px;
    }
    
    /* Clock icon styling */
    .clock {
        color: #6b8dff;
    }
    
    /* Current time card styling */
    #current-datetime {
        color: #9042db;
    }
    
    /* Navbar styling */
    #userDropdown {
        border: none;
        background: transparent;
    }
</style>
@endpush