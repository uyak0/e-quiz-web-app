<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class ClassroomsController extends Controller
{
    public function index()
    {
        $classrooms=Classroom::all();
        return response() -> json($classrooms);
    }
}
