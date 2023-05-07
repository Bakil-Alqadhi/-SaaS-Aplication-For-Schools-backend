<?php

namespace App\Repositories;

use App\Http\Resources\ExamResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\SubjectResource;
use App\Interfaces\QuizRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;


class QuizRepository implements QuizRepositoryInterface
{
    public function getAllQuizzes()
    {
        //ExamResource::collection(Exam::all())
        return response()->json([
            'data' => QuizResource::collection(Quiz::all())
        ], 200);
    }
    public function storeQuiz($request)
    {
        try {
            DB::setDefaultConnection('tenant');
            $request->validate([
                'name' => ['required', 'string'],
                'subject' => ['required', 'exists:subjects,id'],
                'grade' => ['required', 'exists:grades,id'],
                'classroom' => ['required', 'exists:classrooms,id'],
                'section' => ['required', 'exists:sections,id'],
                'teacher' => ['required', 'exists:teachers,id']
            ]);
            DB::setDefaultConnection('mysql');
            Quiz::create([
                'name' => $request->name,
                'subject_id' => $request->subject,
                'teacher_id' => $request->teacher,
                'grade_id' => $request->grade,
                'classroom_id' => $request->classroom,
                'section_id' => $request->section,

            ]);
            return response()->json(['message' => 'New Quiz added Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function getQuizById($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            return response()->json([
                'data' => new QuizResource($quiz)
            ], 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function updateQuiz($request, $id)
    {
        try {
            DB::setDefaultConnection('tenant');
            $request->validate([
                'name' => ['required', 'string'],
                'subject' => ['required', 'exists:subjects,id'],
                'grade' => ['required', 'exists:grades,id'],
                'classroom' => ['required', 'exists:classrooms,id'],
                'section' => ['required', 'exists:sections,id'],
                'teacher' => ['required', 'exists:teachers,id']
            ]);
            DB::setDefaultConnection('mysql');
            $quiz = Quiz::findOrFail($id);
            $quiz->update([
                'name' => $request->name,
                'subject_id' => $request->subject,
                'teacher_id' => $request->teacher,
                'grade_id' => $request->grade,
                'classroom_id' => $request->classroom,
                'section_id' => $request->section,
            ]);
            return response()->json(['message' => 'Quiz updated Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function destroyQuiz($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $quiz->delete();
            return response()->json(['message' => 'Quiz deleted Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
