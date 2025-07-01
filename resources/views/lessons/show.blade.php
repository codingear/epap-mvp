@extends('layouts.app')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center">
                    <a href="{{ route('courses.curriculum', $course) }}" 
                       class="text-gray-600 hover:text-gray-800 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ $lesson->title }}</h1>
                        <p class="text-sm text-gray-600">{{ $course->title }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Progress -->
                    <div class="hidden md:flex items-center">
                        <span class="text-sm text-gray-600 mr-2">Progreso:</span>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $progress }}%" id="lesson-progress-bar"></div>
                        </div>
                        <span class="text-sm text-gray-600 ml-2" id="lesson-progress-text">{{ $progress }}%</span>
                    </div>
                    
                    <!-- Complete Button -->
                    @if(!$isCompleted)
                        <button onclick="markAsCompleted()" 
                                class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors"
                                id="complete-btn">
                            Marcar como Completada
                        </button>
                    @else
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Completada
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Video Player -->
                @if($lesson->video_url)
                    <div class="bg-black rounded-lg overflow-hidden mb-8">
                        <div class="aspect-w-16 aspect-h-9">
                            @if(strpos($lesson->video_url, 'youtube.com') !== false || strpos($lesson->video_url, 'youtu.be') !== false)
                                @php
                                    $videoId = '';
                                    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $lesson->video_url, $id)) {
                                        $videoId = $id[1];
                                    } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $lesson->video_url, $id)) {
                                        $videoId = $id[1];
                                    }
                                @endphp
                                @if($videoId)
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen
                                            class="w-full h-full"
                                            id="youtube-player">
                                    </iframe>
                                @endif
                            @elseif(strpos($lesson->video_url, 'vimeo.com') !== false)
                                @php
                                    preg_match('/vimeo\.com\/(\d+)/', $lesson->video_url, $matches);
                                    $videoId = $matches[1] ?? '';
                                @endphp
                                @if($videoId)
                                    <iframe src="https://player.vimeo.com/video/{{ $videoId }}" 
                                            frameborder="0" 
                                            allow="autoplay; fullscreen; picture-in-picture" 
                                            allowfullscreen
                                            class="w-full h-full">
                                    </iframe>
                                @endif
                            @else
                                <video controls class="w-full h-full" id="video-player">
                                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Lesson Content -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <div class="prose max-w-none">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $lesson->title }}</h2>
                        @if($lesson->description)
                            <p class="text-lg text-gray-600 mb-6">{{ $lesson->description }}</p>
                        @endif
                        
                        @if($lesson->content)
                            <div class="lesson-content">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Materials Section -->
                @if($materials->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Materiales de la Lección</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($materials as $material)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1">
                                            <!-- File Type Icon -->
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3
                                                @if($material->type === 'pdf') bg-red-100 text-red-600
                                                @elseif($material->type === 'document') bg-blue-100 text-blue-600
                                                @elseif($material->type === 'image') bg-green-100 text-green-600
                                                @elseif($material->type === 'video') bg-purple-100 text-purple-600
                                                @else bg-gray-100 text-gray-600
                                                @endif">
                                                @switch($material->type)
                                                    @case('pdf')
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                        </svg>
                                                        @break
                                                    @case('image')
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        @break
                                                    @case('video')
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                        </svg>
                                                @endswitch
                                            </div>
                                            
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-800">{{ $material->title }}</h4>
                                                @if($material->description)
                                                    <p class="text-sm text-gray-600">{{ $material->description }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500">{{ $material->formatted_file_size }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($material->downloadable)
                                            <a href="{{ route('materials.download', $material) }}" 
                                               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors ml-4">
                                                Descargar
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Comentarios</h3>
                    
                    <!-- Add Comment Form -->
                    <form action="{{ route('lessons.comment', ['course' => $course, 'lesson' => $lesson]) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Agregar un comentario</label>
                            <textarea id="content" name="content" rows="3" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Comparte tus dudas o comentarios sobre esta lección..."
                                      required></textarea>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Publicar Comentario
                        </button>
                    </form>
                    
                    <!-- Comments List -->
                    <div class="space-y-6">
                        @foreach($comments as $comment)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                <div class="flex items-start">
                                    <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                         alt="{{ $comment->user->name }}" 
                                         class="w-10 h-10 rounded-full mr-3">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="font-medium text-gray-800 mr-2">{{ $comment->user->name }}</span>
                                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                        
                                        @if($comment->user_id === auth()->id())
                                            <div class="mt-2">
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 text-sm"
                                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este comentario?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($comments->count() === 0)
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="text-gray-500">No hay comentarios aún. ¡Sé el primero en comentar!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <!-- Navigation Buttons -->
                    <div class="space-y-3 mb-6">
                        @if($previousLesson)
                            <a href="{{ route('lessons.show', ['course' => $course, 'lesson' => $previousLesson]) }}" 
                               class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Lección Anterior
                            </a>
                        @endif
                        
                        @if($nextLesson)
                            <a href="{{ route('lessons.show', ['course' => $course, 'lesson' => $nextLesson]) }}" 
                               class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                                Siguiente Lección
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <div class="w-full bg-green-100 text-green-800 py-3 px-4 rounded-lg font-medium text-center">
                                ¡Has completado el curso!
                            </div>
                        @endif
                    </div>

                    <!-- Course Progress -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-800 mb-3">Progreso del Curso</h4>
                        @php
                            $totalCourseLessons = $course->lessons()->count();
                            $completedCourseLessons = auth()->user()->getCompletedLessonsCount($course->id);
                            $courseProgress = $totalCourseLessons > 0 ? round(($completedCourseLessons / $totalCourseLessons) * 100) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>{{ $completedCourseLessons }}/{{ $totalCourseLessons }} lecciones</span>
                            <span>{{ $courseProgress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $courseProgress }}%"></div>
                        </div>
                    </div>

                    <!-- Course Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('courses.curriculum', $course) }}" 
                           class="w-full bg-white text-blue-600 border border-blue-600 py-2 px-4 rounded-lg font-medium hover:bg-blue-50 transition-colors text-center block">
                            Ver Todas las Lecciones
                        </a>
                        <a href="{{ route('courses.show', $course) }}" 
                           class="w-full bg-white text-gray-600 border border-gray-300 py-2 px-4 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center block">
                            Detalles del Curso
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let progressInterval;

// Mark lesson as completed
function markAsCompleted() {
    fetch(`{{ route('lessons.complete', ['course' => $course, 'lesson' => $lesson]) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateProgress(100);
            document.getElementById('complete-btn').style.display = 'none';
            
            // Show completion message
            showNotification('¡Lección completada!', 'success');
            
            // Redirect to next lesson if available
            if (data.next_lesson) {
                setTimeout(() => {
                    if (confirm('¡Lección completada! ¿Quieres continuar con la siguiente lección?')) {
                        window.location.href = data.next_lesson.url;
                    }
                }, 1500);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al marcar la lección como completada', 'error');
    });
}

// Update progress
function updateProgress(progress) {
    fetch(`{{ route('lessons.progress', ['course' => $course, 'lesson' => $lesson]) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ progress: progress })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('lesson-progress-bar').style.width = data.progress + '%';
            document.getElementById('lesson-progress-text').textContent = data.progress + '%';
        }
    })
    .catch(error => console.error('Error updating progress:', error));
}

// Auto-track video progress
document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('video-player');
    if (videoPlayer) {
        videoPlayer.addEventListener('timeupdate', function() {
            const progress = Math.round((videoPlayer.currentTime / videoPlayer.duration) * 100);
            if (progress % 10 === 0) { // Update every 10%
                updateProgress(progress);
            }
        });
    }
    
    // Start progress tracking
    startProgressTracking();
});

// Track time spent on lesson
function startProgressTracking() {
    let timeSpent = 0;
    progressInterval = setInterval(() => {
        timeSpent += 5; // 5 seconds
        
        // Auto-update progress based on time spent (basic implementation)
        if (!{{ $isCompleted ? 'true' : 'false' }}) {
            const estimatedProgress = Math.min(Math.round(timeSpent / 300 * 100), 90); // 5 minutes = 90%
            if (estimatedProgress > {{ $progress }}) {
                updateProgress(estimatedProgress);
            }
        }
    }, 5000);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (progressInterval) {
        clearInterval(progressInterval);
    }
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
