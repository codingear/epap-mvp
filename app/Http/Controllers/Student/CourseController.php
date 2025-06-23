<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display the main levels overview page
     */
    public function index()
    {
        $levels = Level::where('parent_id', null)->get();
        return view('student.courses.index', compact('levels'));
    }

    /**
     * Display the details of a specific level
     */
    public function showLevel($id)
    {
        $level = Level::findOrFail($id);
        $sublevels = Level::where('parent_id', $id)->get();
        return view('student.courses.level', compact('level', 'sublevels'));
    }

    /**
     * Display the details of a specific course
     */
    public function showCourse($id)
    {
        $course = Course::with('instructor')->findOrFail($id);
        return view('student.courses.course', compact('course'));
    }
}
