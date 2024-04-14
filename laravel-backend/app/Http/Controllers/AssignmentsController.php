<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Storage;

class AssignmentsController extends Controller
{
    public function store()
    {
        Storage::disk('public')->put('assignments', request('files'));

        $path =
        $assignment = new Assignment();
        $assignment->title = request('title');
        $assignment->description = request('description');
        $assignment->due_date = request('due_date');
        $assignment->files = request('files');

        $assignment->save()->classroom();

        return response()->json([
            'message' => 'Assignment created successfully',
            'assignment' => $assignment
        ]);
    }
}
