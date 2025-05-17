<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil dibuat!',
            'data' => $course,
        ], 201);
    }

    public function update(Request $request, Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this course',
            ], 403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil diperbarui!',
            'data' => $course,
        ]);
    }

    public function destroy(Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this course',
            ], 403);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil dihapus!',
        ]);
    }

    public function show(Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $course,
        ]);
    }
}
