<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class AssignmentsController extends Controller
{
    public function index()
    {
        $assignments = Assignment::classroom()->classroom_id;
        return Assignment::all();
    }

    public function store(Request $request): JsonResponse
    {
        $url = [];

        $assignment = new Assignment();
        $assignment->title = request('title');
        $assignment->description = request('description');
        $assignment->due_date = request('due_date');
        $assignment->classroom_id = request('classroom_id');

        $assignment->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                Storage::put(
                    'classroom-' . $request->classroom_id . '/assignment-' . $assignment->id,
                    $file
                );
                $url[] = Storage::url($file);
            }

            $assignment->update([
                'files' => $url
            ]);
        }

        return response()->json([
            'message' => 'Assignment created successfully',
            'assignment' => $assignment
        ]);
    }
}
