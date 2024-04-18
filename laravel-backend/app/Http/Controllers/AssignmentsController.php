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
        $urls = [];

        $assignment = new Assignment();
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->due_date = $request->due_date;
        $assignment->classroom_id = $request->classroom_id;

        $assignment->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalFileName = $file->getClientOriginalName(); // Get the original file name
                $path = $file->store(
                    'classroom-' . $request->classroom_id . '/assignment-' . $assignment->id,
                    'public' // Specify the disk name here, make it public so client-side can access to it lol
                );
                $url = Storage::disk('public')->url($path);
                $urls[] = [
                    'url' => str_replace('http://localhost', 'http://localhost:8000', $url), //replace it with domain name since localhost:8000 handle this for filesystem
                    'original_name' => $originalFileName // Store the original file name
                ];
            }

            $assignment->files = json_encode($urls); // Store URLs and original file names as JSON
            $assignment->save(); 
        }

        return response()->json([
            'message' => 'Assignment created successfully',
            'assignment' => $assignment
        ]);
    }

    public function show($id)
    {
        $assignment = Assignment::findOrFail($id);

        // Assuming the 'files' attribute is stored as a JSON encoded array in the database
        $assignment->files = json_decode($assignment->files);

        return response()->json($assignment);
    }
}
