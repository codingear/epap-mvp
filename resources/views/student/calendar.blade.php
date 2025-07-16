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
                    <!-- Timezone Dropdown -->
                    <div class="me-3">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="timezoneDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-globe me-1"></i> 
                                <span id="current-timezone-display">{{auth()->user()->timezone ?? 'Seleccionar zona horaria'}}</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="timezoneDropdown" style="max-height: 300px; overflow-y: auto;">
                                <li><h6 class="dropdown-header">Zonas Horarias Disponibles</h6></li>
                                <!-- Common timezones for Mexico and Latin America -->
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Mexico_City">México Central (UTC-6)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Tijuana">México Pacífico (UTC-8)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Cancun">México Este (UTC-5)</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/New_York">Nueva York (UTC-5)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Los_Angeles">Los Ángeles (UTC-8)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Chicago">Chicago (UTC-6)</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Bogota">Bogotá (UTC-5)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Lima">Lima (UTC-5)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Argentina/Buenos_Aires">Buenos Aires (UTC-3)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Santiago">Santiago (UTC-3)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="America/Caracas">Caracas (UTC-4)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="Europe/Madrid">Madrid (UTC+1)</a></li>
                                <li><a class="dropdown-item timezone-option" href="#" data-timezone="Europe/London">Londres (UTC+0)</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- User Avatar and Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=9042db&color=fff" class="rounded-circle me-2" width="30" height="30" alt="Avatar">
                            <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('student.profile.edit') }}"><i class="bi bi-person me-2"></i>Editar Perfil</a></li>
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

    <!-- Current Date Time Display Mejorado -->
    <div class="row justify-content-center mt-3 mb-4">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded-4" style="background: linear-gradient(90deg, #9042db 60%, #c165dd 100%);">
                <div class="card-body text-center py-3">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <span class="text-white fw-bold mb-2" style="font-size: 1rem;">
                            <i class="bi bi-clock-history me-2"></i> Tu hora local actual
                        </span>
                        <span id="current-datetime" class="fw-bold text-white" style="font-size: 1.5rem; letter-spacing: 1px; text-shadow: 0 2px 8px #9042db;">
                            <!-- La hora se mostrará aquí -->
                        </span>
                    </div>
                </div>
            </div>
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
                    $weekdaysInSpanish = [
                        'Monday' => 'Lunes',
                        'Tuesday' => 'Martes', 
                        'Wednesday' => 'Miércoles',
                        'Thursday' => 'Jueves',
                        'Friday' => 'Viernes',
                        'Saturday' => 'Sábado',
                        'Sunday' => 'Domingo'
                    ];
                @endphp

                @for ($i = 0; $i < 5; $i++)
                    @php
                        $day = now()->addDays($i);
                        $dayNumber = $day->day;
                        $englishDayName = $day->format('l'); // Monday, Tuesday, etc.
                        $dayName = $weekdaysInSpanish[$englishDayName];
                        $dateString = $day->format('Y-m-d');
                    @endphp
                    <div class="col">
                        <div class="card day-card text-center" 
                             data-day="{{ $dayNumber }}" 
                             data-day-name="{{ $dayName }}"
                             data-date="{{ $dateString }}">
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
                <!-- Time slots will be loaded dynamically -->
                <div id="time-slots-container" class="row">
                    <div class="col-12 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando horarios...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando horarios disponibles...</p>
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
                        <p id="selected-timezone" class="mb-0 text-primary">{{ auth()->user()->timezone ? str_replace('_', ' ', auth()->user()->timezone) : 'No configurada' }}</p>
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
        let selectedDate = null;
        let selectedTime = null;
        let userTimezone = "{{ auth()->user()->timezone ?? 'America/Mexico_City' }}";
        let availableSlots = [];
        
        // Validate and sanitize timezone
        if (!userTimezone || userTimezone.trim() === '' || userTimezone === 'null' || userTimezone === 'undefined') {
            userTimezone = 'America/Mexico_City';
        }

        // Timezone change handler
        document.querySelectorAll('.timezone-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const newTimezone = this.getAttribute('data-timezone');
                const timezoneText = this.textContent;
                
                // Show loading state
                Swal.fire({
                    title: 'Actualizando zona horaria...',
                    text: 'Espera un momento',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Update timezone in database
                fetch('{{ route("student.update.timezone") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        timezone: newTimezone
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update local timezone
                        userTimezone = newTimezone;
                        
                        // Update display
                        document.getElementById('current-timezone-display').textContent = timezoneText;
                        document.getElementById('selected-timezone').textContent = timezoneText;
                        
                        // Reload time slots if date is selected
                        if (selectedDate) {
                            loadAvailableSlots(selectedDate);
                        }
                        
                        // Update current time display
                        updateCurrentDateTime();
                        
                        Swal.close();
                        
                        // Show success message
                        Swal.fire({
                            title: '¡Zona horaria actualizada!',
                            text: 'Los horarios se han actualizado según tu nueva zona horaria',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Error al actualizar zona horaria');
                    }
                })
                .catch(error => {
                    console.error('Error updating timezone:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo actualizar la zona horaria. Intenta de nuevo.',
                        icon: 'error',
                        confirmButtonColor: '#9042db'
                    });
                });
            });
        });
        
        // Update current date time display
        function updateCurrentDateTime() {
            const currentDatetimeElement = document.getElementById('current-datetime');
            
            if (currentDatetimeElement) {
                // Validate timezone before using it
                let validTimezone = userTimezone;
                if (!validTimezone || validTimezone.trim() === '') {
                    validTimezone = 'America/Mexico_City'; // Default fallback
                }
                
                try {
                    const options = {
                        timeZone: validTimezone,
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
                } catch (error) {
                    console.error('Invalid timezone:', validTimezone, error);
                    // Fallback to default timezone
                    userTimezone = 'America/Mexico_City';
                    const options = {
                        timeZone: 'America/Mexico_City',
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
            }
        }

        // Load available time slots for selected date
        function loadAvailableSlots(date) {
            const container = document.getElementById('time-slots-container');
            
            // Show loading state
            container.innerHTML = `
                <div class="col-12 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando horarios...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando horarios disponibles para tu zona horaria...</p>
                    <small class="text-muted">${userTimezone.replace('_', ' ')}</small>
                </div>
            `;

            // Fetch available slots
            fetch(`/api/student/available-slots?date=${date}&timezone=${encodeURIComponent(userTimezone)}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer {{ auth()->user()->createToken("api")->plainTextToken ?? "" }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Available slots response:', data);
                
                if (data.success) {
                    availableSlots = data.slots || data.data || [];
                    renderTimeSlots();
                } else {
                    throw new Error(data.message || 'Error al cargar horarios disponibles');
                }
            })
            .catch(error => {
                console.error('Error loading slots:', error);
                showError('Error de conexión al cargar horarios. Verifica tu conexión e intenta de nuevo.');
            });
        }

        // Render time slots in the UI
        function renderTimeSlots() {
            const container = document.getElementById('time-slots-container');
            
            if (!availableSlots || availableSlots.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No hay horarios disponibles para esta fecha
                            <br><small class="text-muted">Zona horaria: ${userTimezone.replace('_', ' ')}</small>
                        </div>
                        <button class="btn btn-outline-primary btn-sm mt-2" id="reload-slots-btn">
                            <i class="bi bi-arrow-clockwise me-1"></i> Recargar horarios
                        </button>
                    </div>
                `;
                return;
            }

            let slotsHtml = '';
            availableSlots.forEach((slot, index) => {
                const timeFormatted = formatTime(slot.time || slot.start_time || slot.hour);
                const isAvailable = slot.available !== false && slot.status !== 'occupied';
                const disabledClass = !isAvailable ? 'disabled opacity-50' : '';
                const availableText = isAvailable ? 'Disponible' : 'Ocupado';
                
                slotsHtml += `
                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                        <div class="card time-slot ${disabledClass}" data-time="${slot.time || slot.start_time || slot.hour}" ${isAvailable ? '' : 'style="pointer-events: none;"'}>
                            <div class="card-body text-center py-3">
                                <h5 class="mb-1">${timeFormatted}</h5>
                                <small class="text-muted timezone-note d-block">${userTimezone.replace('_', ' ')}</small>
                                <small class="badge ${isAvailable ? 'bg-success' : 'bg-secondary'} mt-2">${availableText}</small>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Add refresh button at the end
            slotsHtml += `
                <div class="col-12 text-center mt-3">
                    <button class="btn btn-outline-primary btn-sm" id="refresh-slots-btn">
                        <i class="bi bi-arrow-clockwise me-1"></i> Actualizar horarios
                    </button>
                </div>
            `;

            container.innerHTML = slotsHtml;
            
            // Re-attach event listeners for time selection
            attachTimeSlotListeners();
            
            // Add event listeners for reload buttons
            const reloadBtn = document.getElementById('reload-slots-btn');
            if (reloadBtn) {
                reloadBtn.addEventListener('click', function() {
                    loadAvailableSlots(selectedDate);
                });
            }
            
            const refreshBtn = document.getElementById('refresh-slots-btn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    loadAvailableSlots(selectedDate);
                });
            }
        }

        // Format time from 24h to 12h format
        function formatTime(time24) {
            if (!time24) return 'Hora no disponible';
            
            // Handle different time formats
            let timeStr = time24;
            if (time24.includes('T')) {
                // ISO format
                timeStr = new Date(time24).toLocaleTimeString('en-US', { 
                    hour12: false, 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
            }
            
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes || '00'} ${ampm}`;
        }

        // Show error message
        function showError(message) {
            const container = document.getElementById('time-slots-container');
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message}
                    </div>
                    <div class="text-center">
                        <button class="btn btn-outline-primary btn-sm" id="retry-slots-btn">
                            <i class="bi bi-arrow-clockwise me-1"></i> Intentar de nuevo
                        </button>
                    </div>
                </div>
            `;
            
            // Add event listener for retry button
            const retryBtn = document.getElementById('retry-slots-btn');
            if (retryBtn) {
                retryBtn.addEventListener('click', function() {
                    loadAvailableSlots(selectedDate || new Date().toISOString().split('T')[0]);
                });
            }
        }

        // Attach event listeners to time slots
        function attachTimeSlotListeners() {
            document.querySelectorAll('.time-slot:not(.disabled)').forEach(card => {
                card.addEventListener('click', function() {
                    // Remove active class from all time cards
                    document.querySelectorAll('.time-slot').forEach(c => {
                        c.classList.remove('border-primary', 'border-2');
                    });
                    
                    // Add active class to selected card
                    this.classList.add('border-primary', 'border-2');
                    
                    // Update selected time
                    selectedTime = this.getAttribute('data-time');
                    document.getElementById('selected-time').textContent = formatTime(selectedTime);
                    
                    updateConfirmButton();
                });
            });
        }
        
        // Initialize with current datetime
        updateCurrentDateTime();
        // Update every second
        setInterval(updateCurrentDateTime, 1000);
        
        // Load default time slots for today
        const today = new Date().toISOString().split('T')[0];
        selectedDate = today;
        
        // Auto-select today
        const todayCard = document.querySelector(`[data-date="${today}"]`);
        if (todayCard) {
            todayCard.classList.add('border-primary', 'border-2');
            selectedDay = todayCard.getAttribute('data-day');
            selectedDayName = todayCard.getAttribute('data-day-name');
            document.getElementById('selected-day').textContent = `${selectedDay} (${selectedDayName})`;
        }
        
        loadAvailableSlots(today);
        
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
                selectedDate = this.getAttribute('data-date') || calculateDateFromDay(selectedDay);
                
                document.getElementById('selected-day').textContent = `${selectedDay} (${selectedDayName})`;
                
                // Clear selected time
                selectedTime = null;
                document.getElementById('selected-time').textContent = 'No seleccionado';
                document.querySelectorAll('.time-slot').forEach(c => {
                    c.classList.remove('border-primary', 'border-2');
                });
                
                // Load available slots for selected date
                loadAvailableSlots(selectedDate);
                
                updateConfirmButton();
            });
        });

        // Helper function to calculate date from day number
        function calculateDateFromDay(dayNumber) {
            const today = new Date();
            const currentDay = today.getDate();
            const targetDay = parseInt(dayNumber);
            
            if (targetDay >= currentDay) {
                today.setDate(targetDay);
            } else {
                today.setMonth(today.getMonth() + 1);
                today.setDate(targetDay);
            }
            
            return today.toISOString().split('T')[0];
        }
        
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
                                timezone: userTimezone,
                                date: selectedDate
                            })
                        })
                        .then(response => {
                            return response.json().then(data => {
                                return { isSuccessful: response.ok, data };
                            });
                        })
                        .then(({ isSuccessful, data }) => {
                            if (isSuccessful && data.success) {
                                // Success modal
                                Swal.fire({
                                    title: '¡Cita confirmada!',
                                    html: `
                                        <p>Tu videollamada ha sido agendada correctamente</p>
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <strong>Enlace de Google Meet:</strong><br>
                                            <a href="${data.meet_link}" target="_blank" class="btn btn-primary btn-sm mt-2">
                                                <i class="bi bi-camera-video me-1"></i>
                                                Abrir Google Meet
                                            </a>
                                        </div>
                                    `,
                                    icon: 'success',
                                    confirmButtonColor: '#9042db',
                                    confirmButtonText: 'Ir al Dashboard'
                                }).then(() => {
                                    // Redirect to student dashboard
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
                                text: error.message || 'Hubo un problema al guardar tu cita. Por favor, intenta de nuevo.',
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
    
    /* Timezone dropdown styling */
    .dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: none;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #9042db;
    }
    
    .dropdown-header {
        color: #9042db;
        font-weight: 600;
    }
    
    /* Loading states */
    .spinner-border {
        animation-duration: 0.8s;
    }
    
    /* Time slot improvements */
    .time-slot .timezone-note {
        font-size: 0.7rem;
        opacity: 0.8;
    }
    
    .time-slot.border-primary .timezone-note {
        color: #9042db;
        font-weight: 500;
    }
</style>
@endpush