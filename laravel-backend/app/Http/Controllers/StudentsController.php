<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentBadge;

class StudentsController extends Controller
{
    public function index(): JsonResponse
    {
        $students = Student::all();
        return response() -> json($students);
    }

    public function getPoints(): JsonResponse
    {
        $student = Student::where('user_id', auth()->user()->id)->first();
        return response() -> json($student->points);
    }

    public function getBadges(): JsonResponse
    {
        $student = Student::where('user_id', auth()->user()->id)->first();
        return response() -> json($student->badges);
    }
}
