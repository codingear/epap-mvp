@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">
        <i class="fas fa-edit me-2"></i>Editar Inscripción #{{ $enrollment->id }}
    </h2>
    <a href="{{ route('admin.enrollments') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Volver a Inscripciones
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-graduation-cap me-2"></i>Información de la Inscripción
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">Seleccionar usuario...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (old('user_id') ?? $enrollment->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Curso <span class="text-danger">*</span></label>
                        <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                            <option value="">Seleccionar curso...</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (old('course_id') ?? $enrollment->course_id) == $course->id ? 'selected' : '' }}
                                        data-level="{{ $course->level_id }}" data-price="{{ $course->price }}">
                                    {{ $course->title }}
                                    @if($course->level)
                                        ({{ $course->level->name }})
                                    @endif
                                    - ${{ number_format($course->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="level_id" class="form-label">Nivel</label>
                        <select class="form-select @error('level_id') is-invalid @enderror" id="level_id" name="level_id">
                            <option value="">Seleccionar nivel...</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ (old('level_id') ?? $enrollment->level_id) == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('level_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">El nivel se puede autocompletar según el curso seleccionado</div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="payment_id" class="form-label">Pago Asociado</label>
                        <select class="form-select @error('payment_id') is-invalid @enderror" id="payment_id" name="payment_id">
                            <option value="">Sin pago asociado...</option>
                            @foreach($payments as $payment)
                                <option value="{{ $payment->id }}" {{ (old('payment_id') ?? $enrollment->payment_id) == $payment->id ? 'selected' : '' }}>
                                    #{{ $payment->id }} - {{ $payment->user->name }} - {{ $payment->course->title }} 
                                    (${{ number_format($payment->amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('payment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ (old('status') ?? $enrollment->status) == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="completed" {{ (old('status') ?? $enrollment->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelled" {{ (old('status') ?? $enrollment->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            <option value="suspended" {{ (old('status') ?? $enrollment->status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Monto</label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" value="{{ old('amount') ?? $enrollment->amount }}">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Monto personalizado (opcional, se puede tomar del curso)</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Fecha de Creación</label>
                        <input type="text" class="form-control" value="{{ $enrollment->created_at->format('d/m/Y H:i') }}" readonly>
                    </div>
                </div>
            </div>

            @if($enrollment->payment)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Pago Asociado:</strong> Esta inscripción está vinculada al pago 
                    <a href="{{ route('admin.payments.edit', $enrollment->payment) }}" class="alert-link">
                        #{{ $enrollment->payment->id }}
                    </a>
                    por ${{ number_format($enrollment->payment->amount, 2) }} {{ strtoupper($enrollment->payment->currency) }}
                    (Estado: {{ ucfirst($enrollment->payment->status) }})
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.enrollments') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Actualizar Inscripción
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-fill level and amount when course is selected (only if fields are empty)
document.getElementById('course_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        const levelId = selectedOption.getAttribute('data-level');
        const price = selectedOption.getAttribute('data-price');
        
        // Set level if available and current level field is empty
        const levelField = document.getElementById('level_id');
        if (levelId && !levelField.value) {
            levelField.value = levelId;
        }
        
        // Set amount if not already set
        const amountField = document.getElementById('amount');
        if (!amountField.value && price) {
            amountField.value = price;
        }
        
        // Filter payments for selected course and user
        filterPayments();
    }
});

// Filter payments based on selected user and course
function filterPayments() {
    const userId = document.getElementById('user_id').value;
    const courseId = document.getElementById('course_id').value;
    const paymentSelect = document.getElementById('payment_id');
    
    // Show/hide payment options based on user and course selection
    Array.from(paymentSelect.options).forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        // Here you would implement the filtering logic
        // For now, we'll show all options
        option.style.display = 'block';
    });
}

// Filter payments when user changes
document.getElementById('user_id').addEventListener('change', filterPayments);
</script>
@endpush
