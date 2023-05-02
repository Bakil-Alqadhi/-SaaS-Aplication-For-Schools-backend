<?php

namespace App\Repositories;

use App\Http\Resources\StudentResource;
use App\Interfaces\GraduatedRepositoryInterface;
use App\Models\Student;

class GraduatedRepository implements GraduatedRepositoryInterface
{

    public function index()
    {
        $students = Student::onlyTrashed()->get();
        return response()->json([
            'data' => StudentResource::collection($students)
        ], 200);
    }
    //soft delete for all the section's students
    public function softDelete($request)
    {
        $students = Student::where('grade_id', $request->grade_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('section_id', $request->section_id)
            ->get();
        if ($students->count() < 1) {
            return response()->json([
                'message' => "There is no data in student's table"
            ], 204);
        }
        foreach ($students as $student) {
            //softDelete
            $student->delete();
        }
        return response()->json(
            ['message' => "Data is deleted successfully."],
            200
        );
    }

    public function softDeleteByStudentId($id)
    {
        $student = Student::findOrFail($id);
        if (!$student) {
            return response()->json([
                'message' => "There is no data in student's table"
            ], 204);
        }
            //softDelete
            $student->delete();
        return response()->json(
            ['message' => "Student graduated successfully."],
            200
        );
    }
    public function restoreGraduatedByStudentId($id)
    {

        Student::onlyTrashed()->findOrFail($id)->restore();

        return response()->json(
            ['message' => "Data is updated successfully."],
            200
        );
    }

    public function destroyGraduatedByStudentId($id)
    {
        Student::onlyTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['message' => 'Student Deleted Successfully'], 200);

    }
}