@extends('layouts.app')

@section('title', 'Curriculum - ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">{{ $course->title }}</h1>
            </div>
            
            @if($hasAccess)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-blue-800 font-medium">Tienes acceso completo a este curso</span>
                        </div>
                        @php
                            $completedCount = $lessons->where('user_completed', true)->count();
                            $totalCount = $lessons->count();
                            $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                        @endphp
                        <div class="text-blue-800">
                            <span class="font-bold">{{ $completedCount }}/{{ $totalCount }}</span> completadas ({{ $progressPercent }}%)
                        </div>
                    </div>
                    @if($totalCount > 0)
                        <div class="w-full bg-blue-200 rounded-full h-2 mt-3">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Lessons List -->
        <div class="space-y-4">
            @foreach($lessons as $index => $lesson)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <!-- Lesson Number -->
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0
                                    @if($hasAccess && $lesson->user_completed)
                                        bg-green-100 text-green-800
                                    @elseif($hasAccess)
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-gray-100 text-gray-600
                                    @endif">
                                    @if($hasAccess && $lesson->user_completed)
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <span class="font-bold text-lg">{{ $lesson->order }}</span>
                                    @endif
                                </div>

                                <!-- Lesson Info -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $lesson->title }}</h3>
                                    @if($lesson->description)
                                        <p class="text-gray-600 mb-3">{{ $lesson->description }}</p>
                                    @endif
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        @if($lesson->duration)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $lesson->duration }} min
                                            </div>
                                        @endif
                                        
                                        @if($lesson->video_url)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                </svg>
                                                Video
                                            </div>
                                        @endif
                                        
                                        @if($lesson->materials && $lesson->materials->count() > 0)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $lesson->materials->count() }} archivo(s)
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Progress Bar for current lesson -->
                                    @if($hasAccess && $lesson->user_progress > 0 && !$lesson->user_completed)
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                                <span>Progreso</span>
                                                <span>{{ $lesson->user_progress }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $lesson->user_progress }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-3 ml-4">
                                @if($hasAccess || $lesson->is_free)
                                    <a href="{{ route('lessons.show', ['course' => $course, 'lesson' => $lesson]) }}" 
                                       class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                        @if($hasAccess && $lesson->user_completed)
                                            Repasar
                                        @elseif($hasAccess && $lesson->user_progress > 0)
                                            Continuar
                                        @else
                                            @if($lesson->is_free)
                                                Vista Previa
                                            @else
                                                Comenzar
                                            @endif
                                        @endif
                                    </a>
                                @else
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Bloqueado
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($lessons->count() === 0)
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Sin lecciones disponibles</h3>
                    <p class="text-gray-500">Este curso aún no tiene lecciones publicadas.</p>
                </div>
            @endif
        </div>

        <!-- Course Actions -->
        @if(!$hasAccess)
            <div class="bg-white rounded-lg shadow-md p-6 mt-8 text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">¿Listo para comenzar?</h3>
                <p class="text-gray-600 mb-6">Compra este curso para acceder a todas las lecciones y materiales.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if($course->is_free)
                        <form action="{{ route('payment.process', $course) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="free">
                            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                Inscribirse Gratis
                            </button>
                        </form>
                    @else
                        <a href="{{ route('payment.checkout', $course) }}" 
                           class="bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Comprar por ${{ number_format($course->price, 2) }}
                        </a>
                    @endif
                    
                    <a href="{{ route('courses.show', $course) }}" 
                       class="bg-white text-blue-600 border border-blue-600 px-8 py-3 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                        Ver Detalles del Curso
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
