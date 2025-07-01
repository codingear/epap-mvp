@extends('layouts.app')

@section('title', 'Cursos Disponibles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Cursos de Inglés</h1>
        <p class="text-lg text-gray-600">Mejora tus habilidades en inglés con nuestros cursos especializados</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('courses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar curso</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Título del curso..."
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Level Filter -->
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Nivel</label>
                <select name="level" 
                        id="level"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los niveles</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Filter -->
            <div>
                <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                <select name="price_range" 
                        id="price_range"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los precios</option>
                    <option value="free" {{ request('price_range') == 'free' ? 'selected' : '' }}>Gratis</option>
                    <option value="paid" {{ request('price_range') == 'paid' ? 'selected' : '' }}>De pago</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md font-medium hover:bg-blue-700 transition-colors">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <!-- Course Image -->
                <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 relative">
                    @if($course->cover_image)
                        <img src="{{ $course->cover_image }}" 
                             alt="{{ $course->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-16 h-16 text-white opacity-75" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Level Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="bg-white bg-opacity-90 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                            {{ $course->level->name }}
                        </span>
                    </div>

                    <!-- Price Badge -->
                    <div class="absolute top-3 right-3">
                        @if($course->is_free)
                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                Gratis
                            </span>
                        @else
                            <span class="bg-white bg-opacity-90 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                                ${{ number_format($course->price, 0) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Course Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->description }}</p>

                    <!-- Course Stats -->
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $course->lessons->count() }} lecciones
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($course->average_rating, 1) }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            {{ $course->student_count }} estudiantes
                        </div>
                    </div>

                    <!-- Instructor -->
                    <div class="flex items-center mb-4">
                        <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) }}" 
                             alt="{{ $course->instructor->name }}" 
                             class="w-8 h-8 rounded-full mr-2">
                        <span class="text-sm text-gray-600">{{ $course->instructor->name }}</span>
                    </div>

                    <!-- User Status -->
                    @auth
                        @if(auth()->user()->hasPurchasedCourse($course->id))
                            <div class="mb-4">
                                @php
                                    $progress = auth()->user()->getCourseProgress($course->id);
                                @endphp
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                    <span>Tu progreso</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endauth

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <a href="{{ route('courses.show', $course) }}" 
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-md font-medium hover:bg-blue-700 transition-colors text-center block">
                            Ver Detalles
                        </a>
                        
                        @auth
                            @if(auth()->user()->hasPurchasedCourse($course->id))
                                <a href="{{ route('courses.curriculum', $course) }}" 
                                   class="w-full bg-green-600 text-white py-2 px-4 rounded-md font-medium hover:bg-green-700 transition-colors text-center block">
                                    Continuar Aprendiendo
                                </a>
                            @else
                                @if($course->is_free)
                                    <form action="{{ route('payment.process', $course) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="free">
                                        <button type="submit" 
                                                class="w-full bg-green-600 text-white py-2 px-4 rounded-md font-medium hover:bg-green-700 transition-colors">
                                            Inscribirse Gratis
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('payment.checkout', $course) }}" 
                                       class="w-full bg-green-600 text-white py-2 px-4 rounded-md font-medium hover:bg-green-700 transition-colors text-center block">
                                        Comprar - ${{ number_format($course->price, 0) }}
                                    </a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="w-full bg-gray-600 text-white py-2 px-4 rounded-md font-medium hover:bg-gray-700 transition-colors text-center block">
                               Iniciar Sesión para Comprar
                            </a>
                        @endauth

                        <!-- Preview for free lessons -->
                        @if($course->lessons->where('is_free', true)->count() > 0)
                            @php
                                $freeLesson = $course->lessons->where('is_free', true)->first();
                            @endphp
                            <a href="{{ route('lessons.show', ['course' => $course, 'lesson' => $freeLesson]) }}" 
                               class="w-full bg-white text-blue-600 border border-blue-600 py-2 px-4 rounded-md font-medium hover:bg-blue-50 transition-colors text-center block">
                                Vista Previa Gratis
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($courses->count() === 0)
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No se encontraron cursos</h3>
            <p class="text-gray-500 mb-6">Intenta ajustar tus filtros de búsqueda.</p>
            <a href="{{ route('courses.index') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Ver Todos los Cursos
            </a>
        </div>
    @endif

    <!-- Pagination -->
    @if($courses->hasPages())
        <div class="mt-8">
            {{ $courses->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
