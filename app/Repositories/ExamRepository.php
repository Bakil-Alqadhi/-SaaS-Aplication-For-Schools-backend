<?php

namespace App\Repositories;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Interfaces\ExamRepositoryInterface;
use App\Models\Degree;
use App\Models\Quiz;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

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

        $degrees = Degree::where('quiz_id', $id)->where('student_id', auth()->user()->id)->get();
        if ($degrees->count() > 0 ) {
            return response()->json([
                'message' => "You don't have access to this exam"
            ], 403);
        }

        return response()->json([
            'data' => QuestionResource::collection($quiz->questions)
        ], 201);
    }

    public function storeAnswersExam($request)
    {
        $answers = $request->answers;
        try {
            DB::beginTransaction();
            foreach ($answers as $answer) {
                Degree::create([
                    'quiz_id' => $request->quiz_id,
                    'student_id' => auth()->user()->id,
                    'question_id' => $answer['question_id'],
                    'score' => strcmp(trim($answer['answer']), trim($answer['right_answer'])) === 0 ? $answer['score'] : 0,
                    'date' => date('Y-m-d')
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => 'Answers Are Saved Successfully'
            ], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    // public function storeExam($request, $quiz);
    // public function getQuestionById($quiz, $question);
    // public function updateQuestion($request, $quiz, $question);
    // public function destroyQuestion($quiz, $id);
}