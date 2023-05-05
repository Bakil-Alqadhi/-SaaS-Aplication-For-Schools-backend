<?php

namespace App\Repositories;

use App\Http\Resources\SubjectResource;
use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;


class SubjectRepository implements SubjectRepositoryInterface
{
    public function getAllSubjects()
    {
        return response()->json([
            'data' => SubjectResource::collection(Subject::all())
        ], 200);
    }
    public function getSubjectById($id)
    {
        $subject = Subject::findOrFail($id);
        return response()->json([
            'data' => new SubjectResource($subject)
        ], 200);
    }

    public function storeSubject($request)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string'],
            'grade' => ['required', 'exists:grades,id'],
            'classroom' => ['required', 'exists:classrooms,id'],
            'teacher' => ['required', 'exists:teachers,id']
        ]);
        DB::setDefaultConnection('mysql');
        Subject::create([
            'name' => $request->name,
            'grade_id' => $request->grade,
            'classroom_id' => $request->classroom,
            'teacher_id' => $request->teacher
        ]);
        return response()->json(['message' => 'New Subject added Successfully'], 201);
    }

    public function updateSubject($request, $id)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string'],
            'grade' => ['required', 'exists:grades,id'],
            'classroom' => ['required', 'exists:classrooms,id'],
            'teacher' => ['required', 'exists:teachers,id']
        ]);
        DB::setDefaultConnection('mysql');
        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'grade_id' => $request->grade,
            'classroom_id' => $request->classroom,
            'teacher_id' => $request->teacher
        ]);
        return response()->json(['message' => 'Subject updated Successfully'], 201);
    }

    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response()->json(['message' => 'Subject Deleted Successfully'], 200);
    }
}
