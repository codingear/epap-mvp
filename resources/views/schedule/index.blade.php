@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Horarios</h1>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
                        <i class="bi bi-plus-circle me-2"></i>Crear Horario
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                        <i class="bi bi-calendar-plus me-2"></i>Crear en Lote
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="total-slots">-</h4>
                                    <p class="mb-0">Total Horarios</p>
                                </div>
                                <i class="bi bi-calendar-event fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="booked-slots">-</h4>
                                    <p class="mb-0">Horarios Ocupados</p>
                                </div>
                                <i class="bi bi-calendar-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="available-slots">-</h4>
                                    <p class="mb-0">Horarios Disponibles</p>
                                </div>
                                <i class="bi bi-calendar-plus fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="booking-rate">-%</h4>
                                    <p class="mb-0">Tasa de Reserva</p>
                                </div>
                                <i class="bi bi-graph-up fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="filter-start-date" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="filter-end-date" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Profesor</label>
                            <select class="form-select" id="filter-teacher">
                                <option value="">Todos los profesores</option>
                                @foreach($teachers ?? [] as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="filter-status">
                                <option value="">Todos</option>
                                <option value="available">Disponible</option>
                                <option value="booked">Ocupado</option>
                                <option value="unavailable">No Disponible</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-outline-primary" onclick="loadSchedules()">
                                <i class="bi bi-search me-2"></i>Filtrar
                            </button>
                            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedules Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Horarios Programados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="schedules-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Profesor</th>
                                    <th>Disponibilidad</th>
                                    <th>Citas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="schedules-tbody">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Schedule Modal -->
<div class="modal fade" id="createScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nuevo Horario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createScheduleForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Profesor</label>
                        <select class="form-select" name="teacher_id">
                            <option value="">Sin asignar</option>
                            @foreach($teachers ?? [] as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Hora Fin</label>
                                <input type="time" class="form-control" name="end_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zona Horaria</label>
                        <select class="form-select" name="timezone">
                            <option value="America/Mexico_City">Ciudad de México</option>
                            <option value="America/Cancun">Cancún</option>
                            <option value="America/Tijuana">Tijuana</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Máximo de Citas</label>
                        <input type="number" class="form-control" name="max_slots" value="1" min="1" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas (Opcional)</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Horario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Create Modal -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Horarios en Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkCreateForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Días de la Semana</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="1" id="monday">
                                    <label class="form-check-label" for="monday">Lunes</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="2" id="tuesday">
                                    <label class="form-check-label" for="tuesday">Martes</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="3" id="wednesday">
                                    <label class="form-check-label" for="wednesday">Miércoles</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="4" id="thursday">
                                    <label class="form-check-label" for="thursday">Jueves</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="5" id="friday">
                                    <label class="form-check-label" for="friday">Viernes</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Horarios</label>
                        <div id="time-slots-container">
                            <div class="row time-slot-row mb-2">
                                <div class="col-5">
                                    <input type="time" class="form-control" name="times[0][start_time]" placeholder="Hora inicio">
                                </div>
                                <div class="col-5">
                                    <input type="time" class="form-control" name="times[0][end_time]" placeholder="Hora fin">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeTimeSlot(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addTimeSlot()">
                            <i class="bi bi-plus"></i> Agregar Horario
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Profesor</label>
                                <select class="form-select" name="teacher_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($teachers ?? [] as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Máximo de Citas por Horario</label>
                                <input type="number" class="form-control" name="max_slots" value="1" min="1" max="10" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Zona Horaria</label>
                        <select class="form-select" name="timezone">
                            <option value="America/Mexico_City">Ciudad de México</option>
                            <option value="America/Cancun">Cancún</option>
                            <option value="America/Tijuana">Tijuana</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear Horarios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadSchedules();

    // Initialize date inputs
    document.querySelector('[name="date"]').value = new Date().toISOString().split('T')[0];
    document.querySelector('[name="start_date"]').value = new Date().toISOString().split('T')[0];
    document.querySelector('[name="end_date"]').value = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
});

// Load statistics
function loadStatistics() {
    const startDate = document.getElementById('filter-start-date').value;
    const endDate = document.getElementById('filter-end-date').value;
    
    fetch(`/schedule/statistics?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.statistics;
                document.getElementById('total-slots').textContent = stats.total_slots;
                document.getElementById('booked-slots').textContent = stats.booked_slots;
                document.getElementById('available-slots').textContent = stats.available_slots;
                document.getElementById('booking-rate').textContent = stats.booking_rate + '%';
            }
        })
        .catch(error => console.error('Error loading statistics:', error));
}

// Load schedules
function loadSchedules() {
    const tbody = document.getElementById('schedules-tbody');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>';
    
    // You would implement the actual loading logic here
    // For now, show a placeholder
    setTimeout(() => {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay horarios para mostrar con los filtros seleccionados</td></tr>';
    }, 1000);
}

// Reset filters
function resetFilters() {
    document.getElementById('filter-start-date').value = new Date().toISOString().split('T')[0];
    document.getElementById('filter-end-date').value = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    document.getElementById('filter-teacher').value = '';
    document.getElementById('filter-status').value = '';
    loadSchedules();
    loadStatistics();
}

// Add time slot for bulk create
function addTimeSlot() {
    const container = document.getElementById('time-slots-container');
    const index = container.children.length;
    const html = `
        <div class="row time-slot-row mb-2">
            <div class="col-5">
                <input type="time" class="form-control" name="times[${index}][start_time]" placeholder="Hora inicio">
            </div>
            <div class="col-5">
                <input type="time" class="form-control" name="times[${index}][end_time]" placeholder="Hora fin">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeTimeSlot(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

// Remove time slot
function removeTimeSlot(button) {
    const container = document.getElementById('time-slots-container');
    if (container.children.length > 1) {
        button.closest('.time-slot-row').remove();
    }
}

// Handle create schedule form
document.getElementById('createScheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/schedule', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('createScheduleModal')).hide();
            loadSchedules();
            loadStatistics();
            this.reset();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error al crear el horario', 'error');
    });
});

// Handle bulk create form
document.getElementById('bulkCreateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        if (key.includes('[') && key.includes(']')) {
            // Handle array/object fields
            const match = key.match(/^(.+?)\[(.+?)\](?:\[(.+?)\])?$/);
            if (match) {
                const [, mainKey, index, subKey] = match;
                if (!data[mainKey]) data[mainKey] = {};
                if (subKey) {
                    if (!data[mainKey][index]) data[mainKey][index] = {};
                    data[mainKey][index][subKey] = value;
                } else {
                    if (!data[mainKey]) data[mainKey] = [];
                    data[mainKey].push(value);
                }
            }
        } else {
            data[key] = value;
        }
    });
    
    fetch('/schedule/bulk-create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('bulkCreateModal')).hide();
            loadSchedules();
            loadStatistics();
            this.reset();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error al crear los horarios', 'error');
    });
});
</script>
@endpush
