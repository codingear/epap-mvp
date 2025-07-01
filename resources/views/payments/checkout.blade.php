@extends('layouts.app')

@section('title', 'Comprar Curso - ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/3">
                    @if($course->cover_image)
                        <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-lg">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500">Sin imagen</span>
                        </div>
                    @endif
                </div>
                <div class="md:w-2/3">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>
                    <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                    
                    <div class="flex items-center mb-4">
                        <div class="flex items-center mr-6">
                            <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-gray-700">{{ number_format($course->average_rating, 1) }}</span>
                        </div>
                        <div class="flex items-center mr-6">
                            <svg class="w-5 h-5 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            <span class="text-gray-700">{{ $course->student_count }} estudiantes</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">{{ $course->lessons->count() }} lecciones</span>
                        </div>
                    </div>
                    
                    <div class="text-3xl font-bold text-green-600">
                        @if($course->is_free)
                            ¡Gratis!
                        @else
                            ${{ number_format($course->price, 2) }} {{ $course->currency }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Payment Methods -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Método de Pago</h2>
                    
                    <form action="{{ route('payment.process', $course) }}" method="POST" id="payment-form">
                        @csrf
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona un método de pago</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" checked class="sr-only payment-method">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors payment-option">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM2 10v4a2 2 0 002 2h12a2 2 0 002-2v-4H2z"/>
                                            </svg>
                                            <span class="font-medium">Tarjeta de Crédito/Débito</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="oxxo" class="sr-only payment-method">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors payment-option">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                            </svg>
                                            <span class="font-medium">OXXO</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div id="card-details" class="space-y-4">
                            <div>
                                <label for="card_number" class="block text-sm font-medium text-gray-700">Número de Tarjeta</label>
                                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                            
                            <div>
                                <label for="card_holder" class="block text-sm font-medium text-gray-700">Nombre del Titular</label>
                                <input type="text" id="card_holder" name="card_holder" placeholder="Juan Pérez"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label for="expiry_month" class="block text-sm font-medium text-gray-700">Mes</label>
                                    <select id="expiry_month" name="expiry_month" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">MM</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label for="expiry_year" class="block text-sm font-medium text-gray-700">Año</label>
                                    <select id="expiry_year" name="expiry_year"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">AA</option>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           maxlength="4" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8">
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Comprar Curso - ${{ number_format($course->price, 2) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Resumen de Compra</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Curso:</span>
                            <span class="font-medium">{{ $course->title }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Precio:</span>
                            <span class="font-medium">${{ number_format($course->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Descuento:</span>
                            <span class="font-medium text-green-600">$0.00</span>
                        </div>
                        <hr class="my-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">${{ number_format($course->price, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-sm text-gray-500">
                        <p>✅ Acceso de por vida al curso</p>
                        <p>✅ {{ $course->lessons->count() }} lecciones</p>
                        <p>✅ Materiales descargables</p>
                        <p>✅ Soporte del instructor</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method');
    const cardDetails = document.getElementById('card-details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Update visual selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            this.closest('.payment-option').classList.add('border-blue-500', 'bg-blue-50');
            
            // Show/hide card details
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
                cardDetails.querySelectorAll('input, select').forEach(input => input.required = true);
            } else {
                cardDetails.style.display = 'none';
                cardDetails.querySelectorAll('input, select').forEach(input => input.required = false);
            }
        });
    });
    
    // Format card number
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
        let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
        e.target.value = formattedValue;
    });
    
    // CVV validation
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
});
</script>
@endsection
