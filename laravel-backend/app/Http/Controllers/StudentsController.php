<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentsController extends Controller
{
    public function index(): JsonResponse
    {
        $students = Student::all();
        return response() -> json($students);
    }
}
