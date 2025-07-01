@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Course Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="mb-4">
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm">{{ $course->level->name }}</span>
                </div>
                <h1 class="text-4xl font-bold mb-4">{{ $course->title }}</h1>
                <p class="text-blue-100 text-lg mb-6">{{ $course->description }}</p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <div class="text-2xl font-bold">{{ $totalLessons }}</div>
                        <div class="text-blue-200 text-sm">Lecciones</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ $totalStudents }}</div>
                        <div class="text-blue-200 text-sm">Estudiantes</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-blue-200 text-sm">Rating</div>
                    </div>
                    <div>
                        @if($course->duration)
                            <div class="text-2xl font-bold">{{ $course->duration }}min</div>
                            <div class="text-blue-200 text-sm">Duración</div>
                        @else
                            <div class="text-2xl font-bold">∞</div>
                            <div class="text-blue-200 text-sm">A tu ritmo</div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) }}" 
                         alt="{{ $course->instructor->name }}" 
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <div class="font-medium">{{ $course->instructor->name }}</div>
                        <div class="text-blue-200 text-sm">Instructor</div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                @if($course->cover_image)
                    <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            @if($hasPurchased)
                <!-- Progress Bar -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tu Progreso</h2>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">{{ $completedLessons }} de {{ $totalLessons }} lecciones completadas</span>
                        <span class="text-sm font-medium text-blue-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                    @if($progress >= 100)
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-green-800 font-medium">¡Felicidades! Has completado el curso</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Course Description -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Descripción del Curso</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($course->content)) !!}
                </div>
            </div>

            <!-- Curriculum Preview -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Contenido del Curso</h2>
                <div class="space-y-3">
                    @foreach($course->lessons->take(5) as $lesson)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-sm font-medium text-gray-600">{{ $lesson->order }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $lesson->title }}</div>
                                    @if($lesson->duration)
                                        <div class="text-sm text-gray-500">{{ $lesson->duration }} min</div>
                                    @endif
                                </div>
                            </div>
                            @if($hasPurchased)
                                <div class="flex items-center">
                                    @if($lesson->isCompletedByUser(auth()->id()))
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    @endif
                                </div>
                            @else
                                @if($lesson->is_free)
                                    <span class="text-sm text-green-600 font-medium">Gratis</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                @endif
                            @endif
                        </div>
                    @endforeach
                    
                    @if($course->lessons->count() > 5)
                        <div class="text-center py-4">
                            <a href="{{ route('courses.curriculum', $course) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Ver todas las {{ $course->lessons->count() }} lecciones →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Reseñas</h2>
                    <a href="{{ route('courses.reviews', $course) }}" class="text-blue-600 hover:text-blue-800">Ver todas</a>
                </div>
                
                <div class="space-y-4">
                    @foreach($course->reviews->take(3) as $review)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                            <div class="flex items-start">
                                <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" 
                                     alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full mr-3">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <span class="font-medium text-gray-800 mr-2">{{ $review->user->name }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-600">{{ $review->content }}</p>
                                    <div class="text-sm text-gray-500 mt-1">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                @if($hasPurchased)
                    <!-- Course Access -->
                    <div class="text-center mb-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-green-800 font-medium">¡Tienes acceso a este curso!</p>
                        </div>
                        
                        <a href="{{ route('courses.curriculum', $course) }}" 
                           class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors block mb-3">
                            Continuar Aprendiendo
                        </a>
                        
                        @if($course->lessons->count() > 0)
                            @php
                                $nextLesson = $course->getNextLessonForUser(auth()->id()) ?? $course->lessons->first();
                            @endphp
                            <a href="{{ route('lessons.show', ['course' => $course, 'lesson' => $nextLesson]) }}" 
                               class="w-full bg-white text-blue-600 border border-blue-600 py-3 px-4 rounded-lg font-medium hover:bg-blue-50 transition-colors block">
                                {{ $nextLesson->order == 1 ? 'Comenzar Curso' : 'Siguiente Lección' }}
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Purchase Section -->
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-gray-800 mb-2">
                            @if($course->is_free)
                                ¡Gratis!
                            @else
                                ${{ number_format($course->price, 2) }}
                            @endif
                        </div>
                        @if(!$course->is_free)
                            <div class="text-gray-600 text-sm mb-4">{{ $course->currency }}</div>
                        @endif
                        
                        @if($course->is_free)
                            <form action="{{ route('payment.process', $course) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="free">
                                <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors mb-3">
                                    Inscribirse Gratis
                                </button>
                            </form>
                        @else
                            <a href="{{ route('payment.checkout', $course) }}" 
                               class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors block mb-3">
                                Comprar Curso
                            </a>
                        @endif
                        
                        <!-- Free Preview -->
                        @if($course->lessons->where('is_free', true)->count() > 0)
                            @php
                                $freeLesson = $course->lessons->where('is_free', true)->first();
                            @endphp
                            <a href="{{ route('lessons.show', ['course' => $course, 'lesson' => $freeLesson]) }}" 
                               class="w-full bg-white text-blue-600 border border-blue-600 py-3 px-4 rounded-lg font-medium hover:bg-blue-50 transition-colors block">
                                Vista Previa Gratis
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Course Info -->
                <div class="space-y-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Acceso de por vida</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                        <span>Certificado de finalización</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span>Soporte del instructor</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                        </svg>
                        <span>Materiales descargables</span>
                    </div>
                </div>
            </div>

            <!-- Related Courses -->
            @if($relatedCourses->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Cursos Relacionados</h3>
                    <div class="space-y-4">
                        @foreach($relatedCourses as $relatedCourse)
                            <div class="flex">
                                <div class="w-16 h-12 bg-gray-200 rounded flex-shrink-0 mr-3">
                                    @if($relatedCourse->cover_image)
                                        <img src="{{ $relatedCourse->cover_image }}" alt="{{ $relatedCourse->title }}" class="w-full h-full object-cover rounded">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('courses.show', $relatedCourse) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600 line-clamp-2">
                                        {{ $relatedCourse->title }}
                                    </a>
                                    <div class="text-xs text-gray-500">
                                        @if($relatedCourse->is_free)
                                            Gratis
                                        @else
                                            ${{ number_format($relatedCourse->price, 2) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
