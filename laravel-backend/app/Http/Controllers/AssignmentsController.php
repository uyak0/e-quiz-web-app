<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Comment;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewAssignmentAssigned;

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

        try {
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

            $classroom = Classroom::find($request->classroom_id);
            $classroomUsers = $classroom->users;
            $classroomStudents = [];
            $classroomStudents = $classroomUsers->filter(function ($user) {
                return $user->roles->contains('name', 'student');
            });

            Notification::send($classroomStudents, new NewAssignmentAssigned($assignment));

            return response()->json([
                'message' => 'Assignment created successfully',
                'assignment' => $assignment,
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $assignment = Assignment::findOrFail($id);

        // Assuming the 'files' attribute is stored as a JSON encoded array in the database
        $assignment->files = json_decode($assignment->files);

        return response()->json($assignment);
    }

    public function getComments($assignmentId)
    {
        $comments = Comment::where('assignment_id', $assignmentId)
                            ->with('user') // Assuming you have a User model and a relationship set up
                            ->get();

        return response()->json($comments);
    }

    public function postComment(Request $request, $assignmentId)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id(); // Assuming users are authenticated
        $comment->assignment_id = $assignmentId;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'message' => 'Comment posted successfully',
            'comment' => $comment
        ]);
    }

    public function submitAssignment(Request $request, $assignmentId)
    {
        $request->validate([
            'files.*' => 'required|file',
        ]);

        $files = $request->file('files');
        $filePaths = [];

        foreach ($files as $file) {
            $originalFileName = $file->getClientOriginalName();
            $path = $file->store(
                'submissions/' . $assignmentId . '/' . Auth::id(),
                'public'
            );
            $url = Storage::disk('public')->url($path);
            $filePaths[] = [
                'url' => str_replace('http://localhost', 'http://localhost:8000', $url), //replace it with domain name since localhost:8000 handle this for filesystem
                'original_name' => $originalFileName // Store the original file name
            ];
        }

        $submission = new Submission();
        $submission->assignment_id = $assignmentId;
        $submission->user_id = Auth::id();
        $submission->file_path = json_encode($filePaths); // Store all file paths as a JSON array
        $submission->submitted_at = now();
        $submission->save();

        return response()->json([
            'message' => 'Submission successful',
            'submission' => $submission
        ]);
    }

    public function getSubmissionDetails($assignmentId)
    {
        $submissions = Submission::where('assignment_id', $assignmentId)
                                ->with('user') // Ensure you have a 'user' relationship defined in your Submission model
                                ->get(['id', 'user_id', 'file_path', 'submitted_at', 'grade', 'feedback'])
                                ->map(function ($submission) {
                                    // Assuming 'file_path' contains an array of files
                                    $submission->file_path = json_decode($submission->file_path, true);
                                    return $submission;
                                });
        return response()->json($submissions);
    }

    // public function getComments($assignmentId)
    // {
    //     $comments = Comment::where('assignment_id', $assignmentId)
    //                         ->with('user') // Assuming you have a User model and a relationship set up
    //                         ->get();

    //     return response()->json($comments);
    // }

    public function gradeAssignment(Request $request, $submissionId)
    {
        $request->validate([
            'grade' => 'required|numeric',
            'feedback' => 'nullable|string',
        ]);

        $submission = Submission::findOrFail($submissionId);
        $submission->grade = $request->grade;
        $submission->feedback = $request->feedback;
        $submission->save();

        return response()->json([
            'message' => 'Assignment graded successfully',
            'submission' => $submission
        ]);
    }

}
