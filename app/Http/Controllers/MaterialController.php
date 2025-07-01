<?php

namespace App\Http\Controllers;

use App\Models\LessonMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    public function download(LessonMaterial $material)
    {
        $user = Auth::user();
        $lesson = $material->lesson;
        
        // Check if user has access to this lesson
        if (!$lesson->canBeAccessedByUser($user->id)) {
            abort(403, 'No tienes acceso a este material.');
        }

        // Check if material is downloadable
        if (!$material->downloadable) {
            abort(403, 'Este material no está disponible para descarga.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($material->file_path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('private')->download(
            $material->file_path,
            $material->file_name
        );
    }

    public function upload(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,mp3|max:10240', // 10MB max
            'downloadable' => 'boolean'
        ]);

        $user = Auth::user();
        
        // Check if user is instructor of the course
        $lesson = \App\Models\Lesson::find($request->lesson_id);
        if ($lesson->course->instructor_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para subir materiales a esta lección.');
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('lesson_materials', $fileName, 'private');

        $material = LessonMaterial::create([
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'type' => $this->determineFileType($file->getClientMimeType()),
            'downloadable' => $request->boolean('downloadable', true)
        ]);

        return response()->json([
            'success' => true,
            'material' => $material,
            'message' => 'Material subido exitosamente'
        ]);
    }

    private function determineFileType($mimeType)
    {
        $typeMap = [
            'application/pdf' => 'pdf',
            'application/msword' => 'document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
            'application/vnd.ms-powerpoint' => 'document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'document',
            'application/vnd.ms-excel' => 'document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'document',
            'image/jpeg' => 'image',
            'image/jpg' => 'image',
            'image/png' => 'image',
            'image/gif' => 'image',
            'video/mp4' => 'video',
            'audio/mpeg' => 'audio',
            'audio/mp3' => 'audio'
        ];

        return $typeMap[$mimeType] ?? 'other';
    }

    public function destroy(LessonMaterial $material)
    {
        $user = Auth::user();
        $lesson = $material->lesson;
        
        // Check if user is instructor of the course
        if ($lesson->course->instructor_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar este material.');
        }

        // Delete file from storage
        Storage::disk('private')->delete($material->file_path);
        
        // Delete record
        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material eliminado exitosamente'
        ]);
    }
}
