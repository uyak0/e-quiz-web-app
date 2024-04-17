<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\TestRunner\TestResult\Collector;
use Illuminate\Support\Facades\DB;

class ClassroomsController extends Controller
{
    private function findId(string $classroomCode): int
    {
        $classroom = Classroom::where('code', $classroomCode)->first();
        if ($classroom == null) {
            return 0;
        }
        else {
            return $classroom->id;
        }
    }

    public function updateName(Request $request): JsonResponse
    {
        if (auth()->user()->roles->first()->name) {
            $classroom = Classroom::find($request->classroom_id);
            $classroom->name = $request->name;
            $classroom->save();

            return response() -> json([
                'status' => 'success',
                'message' => 'Classroom name updated successfully!'
            ]);
        }
        else {
            return response() -> json([
                'status' => 'error',
                'message' => 'You are not authorized to update the classroom name!'
            ]);
        }
    }

    public function updateDescription(Request $request): JsonResponse
    {
        $classroom = Classroom::find($request->classroom_id);
        $classroom->description = $request->description;
        $classroom->save();

        return response() -> json([
            'status' => 'success',
            'message' => 'Classroom description updated successfully!'
        ]);
    }

    public function deleteClassroom(int $id)
    {
        Classroom::find($id)->users()->detach();
        Classroom::destroy($id);
        return response() -> json([
            'status' => 'success',
            'message' => 'Classroom deleted successfully!'
        ]);
    }

    public function createClassroom(Request $request): JsonResponse
    {
        $role = auth()->user()->roles()->first()->name;
        if (Auth::check()) {
            if ($role != 'teacher') {
                return response() -> json([
                    'status' => 'error',
                    'message' => 'You are not authorized to create a classroom!'
                ]);
            }

            $code = hash('crc32', $request->classroom_name);
            $classroom = Classroom::create([
                'name' => $request->classroom_name,
                'description' => $request->classroom_desc,
                'code' => $code,
                'type' => $request->type,
                'max_members' => $request->maxMembers

            ]);

            $this->joinClassroom(auth()->user()->id, $classroom->code);

            return response() -> json([
                'classroom_id' => $classroom->id,
                'status' => 'success',
                'message' => 'Classroom created successfully!',
            ], 201);
        }
    }

    public function joinClassroom(int $userId, string $classroomCode): JsonResponse
    {
        $classroomId = $this->findId($classroomCode);
        $classroom = Classroom::find($classroomId);

        if (!$classroom) {
            return response()->json([
                'status' => 'classroom not found',
                'message' => 'Classroom not found! Check the code and try again'
            ]);
        }

        $currentMemberCount = $classroom->users()->count();
        if ($currentMemberCount >= $classroom->max_members) {
            return response()->json([
                'status' => 'classroom full',
                'message' => 'This classroom is full and cannot accept more members.'
            ]);
        }

        $classroomJoined = User::find($userId)->classrooms()->where('classroom_id', $classroomId)->exists();
        if ($classroomJoined) {
            return response()->json([
                'status' => 'already joined',
                'message' => 'You already joined this classroom!'
            ]);
        }

        $user = User::find($userId);
        $user->classrooms()->attach($classroom);

        return response()->json([
            'status' => 'success',
            'classroom_id' => $classroomId,
            'message' => 'Classroom joined successfully'
        ]);
    }

    public function userClassrooms(int $userId): JsonResponse
    {
        $user = User::find($userId);
        $classrooms = $user->classrooms;
        return response() -> json($classrooms);
    }

    public function index(int $id): JsonResponse
    {
        $classroom=Classroom::find($id);
        return response() -> json($classroom);
    }

    public function classroomQuizzes(int $classroomId): JsonResponse
    {
        $classroom = Classroom::find($classroomId);
        $quizzes = $classroom->quizzes;
        return response() -> json($quizzes);
    }

    public function classroomAssignments(int $classroomId): JsonResponse
    {
        $classroom = Classroom::find($classroomId);
        $assignments = $classroom->assignments;
        return response() -> json($assignments);
    }

    public function topStudents(int $id): JsonResponse
    {
        $classroom = Classroom::find($id);
        $users = $classroom->users;

        $students = $users->filter(function ($user) {
            return $user->student !== null;
        })->map(function ($user) {
            $student = $user->student;
            $studentPoints = $student->points;

            return [
                'name' => $student->user->name,
                'points' => $studentPoints,
            ];
        });

        // Sort the students by points in descending order
        $sortedStudents = $students->sortByDesc('points')->take(5);

        return response()->json($sortedStudents->values()->all());
    }

    public function getClassroomData($classroomId)
    {
        $classroom = Classroom::find($classroomId);

        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], 404);
        }

        // Count the number of users in the classroom
        $memberCount = DB::table('user_classrooms')
                        ->where('classroom_id', $classroomId)
                        ->count();

        // Prepare and return only the necessary data, including the classroom type
        $data = [
            'maxMembers' => $classroom->max_members,
            'memberCount' => $memberCount,
            'type' => $classroom->type, // Include the type of the classroom
        ];

        return response()->json($data);
    }

    public function getClassroomUsers($classroomId)
    {
        $classroom = Classroom::with('users')->find($classroomId);

        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], 404);
        }

        $users = $classroom->users->map(function ($user) {
            return ['id' => $user->id, 'name' => $user->name];
        });

        return response()->json($users);
    }

    public function addStudentToClassroom(Request $request): JsonResponse
    {
        $classroom = Classroom::find($request->classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], 404);
        }

        // Check if the classroom is full
        $currentMemberCount = $classroom->users()->count();
        if ($currentMemberCount >= $classroom->max_members) {
            return response()->json(['message' => 'Classroom is full'], 409);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if the user is already a member of the classroom
        $isMember = $classroom->users()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return response()->json(['message' => 'User is already a member of this classroom'], 409);
        }

        // Add the user to the classroom
        $classroom->users()->attach($user->id);

        return response()->json(['message' => 'Student added successfully to the classroom']);
    }

    public function removeStudent(Request $request): JsonResponse
    {
        $classroomId = $request->classroomId;
        $userId = $request->userId;

        // Find the classroom by ID
        $classroom = Classroom::find($classroomId);

        if (!$classroom) {
            return response()->json(['status' => 'error', 'message' => 'Classroom not found'], 404);
        }

        // Check if the user is part of the classroom
        $isMember = $classroom->users()->where('user_id', $userId)->exists();
        if (!$isMember) {
            return response()->json(['status' => 'error', 'message' => 'User not found in this classroom'], 404);
        }

        // Remove the user from the classroom
        $classroom->users()->detach($userId);

        return response()->json(['status' => 'success', 'message' => 'Student removed successfully']);
    }


}
