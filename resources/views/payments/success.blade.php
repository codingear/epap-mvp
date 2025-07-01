@extends('layouts.app')

@section('title', 'Pago Exitoso')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">¡Pago Exitoso!</h1>
        <p class="text-lg text-gray-600 mb-8">Tu pago ha sido procesado correctamente. Ya tienes acceso completo al curso.</p>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 text-left">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detalles del Pago</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-600">Curso:</span>
                    <p class="font-medium">{{ $payment->course->title }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Monto Pagado:</span>
                    <p class="font-medium text-green-600">${{ number_format($payment->amount, 2) }} {{ $payment->currency }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Fecha de Pago:</span>
                    <p class="font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-600">ID de Transacción:</span>
                    <p class="font-medium text-sm">{{ $payment->dlocal_transaction_id ?? $payment->id }}</p>
                </div>
            </div>
        </div>

        <!-- Course Access -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-blue-800 mb-2">¿Qué sigue?</h3>
            <p class="text-blue-700 mb-4">Ya puedes acceder a todas las lecciones del curso. ¡Comienza tu aprendizaje ahora!</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('courses.show', $payment->course) }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Ver Curso
                </a>
                <a href="{{ route('courses.curriculum', $payment->course) }}" 
                   class="bg-white text-blue-600 border border-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                    Ver Lecciones
                </a>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="text-sm text-gray-500">
            <p>📧 Recibirás un correo de confirmación con los detalles de tu compra.</p>
            <p>💬 Si tienes alguna pregunta, contacta a nuestro soporte.</p>
        </div>
    </div>
</div>
@endsection
