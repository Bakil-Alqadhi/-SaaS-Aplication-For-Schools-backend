<?php

namespace App\Repositories;

use App\Http\Resources\GradeResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\TeacherResource;
use App\Interfaces\GradeRepositoryInterface;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GradeRepository implements GradeRepositoryInterface
{
    //get all grades
    public function getAllGrades()
    {
        return response()->json(Grade::all());
    }
    public function getGradeData()
    {
        return response()->json(GradeResource::collection(Grade::latest()->get()));
    }
    public function storeGrade($request)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', 'unique:grades'],
            'number' => ['required', 'unique:grades']
        ]);

        DB::setDefaultConnection('mysql');
        Grade::create([
            'name' => $request->name,
            'number' => $request->number
        ]);
        return response()->json(['message' => 'New Grade Created Successfully'], 201);
    }
    public function showGrade($id)
    {
        $grade = Grade::findOrFail($id);
        return response()->json($grade);
    }
    public function updateGrade($request, $id)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', Rule::unique('grades', 'name')->ignore($id)],
            'number' => ['required', Rule::unique('grades', 'number')->ignore($id)]
        ]);
        $grade = Grade::findOrFail($id);
        $grade->name = $request->input('name');
        $grade->number = $request->input('number');
        $grade->save();
        DB::setDefaultConnection('mysql');
        return response()->json(['message' => 'The Grade Updated Successfully'], 201);
    }
    public function destroyGrade($id)
    {
        $grade = Grade::findOrFail($id);
        $students = $grade->students;
        foreach ($students as $student) {
            $student->grade_id = null;
            $student->save();
        }
        $grade->delete();
        return response()->json(['message' => 'Grade Deleted Successfully'], 200);
    }
}
