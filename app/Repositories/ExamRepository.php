<?php

namespace App\Repositories;

use App\Http\Resources\ExamResource;
use App\Http\Resources\SubjectResource;
use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;


class ExamRepository implements ExamRepositoryInterface
{
    public function getAllExams()
    {
        //ExamResource::collection(Exam::all())
        return response()->json([
            'data' => Exam::all()
        ], 200);
    }
    public function storeExam($request)
    {
        try {
            DB::setDefaultConnection('tenant');
            $request->validate([
                'name' => ['required', 'string'],
                'term' => ['required'],
                'academic_year' => ['required'],
            ]);
            DB::setDefaultConnection('mysql');
            Exam::create([
                'name' => $request->name,
                'term' => $request->term,
                'academic_year' => $request->academic_year,
            ]);
            return response()->json(['message' => 'New Exam added Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function getExamById($id)
    {
        try{
            $exam = Exam::findOrFail($id);
            return response()->json([
                'data' => $exam
            ], 200);
        }catch (\Exception $e) {
            throw $e;
        }
    }
    public function updateExam($request, $id)
    {
        try {
            DB::setDefaultConnection('tenant');
            $request->validate([
                'name' => ['required', 'string'],
                'term' => ['required'],
                'academic_year' => ['required'],
            ]);
            DB::setDefaultConnection('mysql');
            $exam = Exam::findOrFail($id);
            $exam->update([
                'name' => $request->name,
                'term' => $request->term,
                'academic_year' => $request->academic_year,
            ]);
            return response()->json(['message' => 'Exam updated Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function destroyExam($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            $exam->delete();
            return response()->json(['message' => 'Exam deleted Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}