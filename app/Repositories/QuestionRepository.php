<?php

namespace App\Repositories;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Interfaces\QuestionRepositoryInterface;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;


class QuestionRepository implements QuestionRepositoryInterface
{
    public function getAllQuestions()
    {
        //ExamResource::collection(Exam::all())
        return response()->json([
            'data' => QuestionResource::collection(Question::where('teacher_id', auth()->user()->id)->get())
        ], 200);
    }
    public function storeQuestion($request)
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
    public function getQuestionById($id)
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
    public function updateQuestion($request, $id)
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
    public function destroyQuestion($id)
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
