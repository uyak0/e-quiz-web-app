<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;

class StudentsController extends Controller
{
    public function index()
    {
        $students = Students::all();
        return response() -> json($students);
    }
}
