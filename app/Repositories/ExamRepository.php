<?php

namespace App\Repositories;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Interfaces\ExamRepositoryInterface;
use App\Models\Quiz;

class ExamRepository implements ExamRepositoryInterface
{
    public function getAllExams()
    {
        $quizzes = Quiz::where('grade_id', auth()->user()->grade_id)
            ->where('classroom_id', auth()->user()->classroom_id)
            ->where('section_id', auth()->user()->section_id)
            ->get();
        return response()->json([
            'data' => QuizResource::collection($quizzes)
        ], 201);
    }
    public function getExamQuestionsById($id)
    {
        $quiz = Quiz::findOrFail($id);
        return response()->json([
            'data' => QuestionResource::collection($quiz->questions)
        ], 201);
    }
    // public function storeExam($request, $quiz);
    // public function getQuestionById($quiz, $question);
    // public function updateQuestion($request, $quiz, $question);
    // public function destroyQuestion($quiz, $id);
}
