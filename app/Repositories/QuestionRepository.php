<?php

namespace App\Repositories;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Interfaces\QuestionRepositoryInterface;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function getAllQuestions($quiz)
    {
        //ExamResource::collection(Exam::all())
        if ($quiz->teacher_id == auth()->user()->id) {
            return response()->json([
                'data' => QuestionResource::collection(Question::where('quiz_id', $quiz->id)->get())
            ], 200);
        } else {
            return response()->json(['message' => "You don't have access to this quiz"], 500);
        }
    }
    public function storeQuestion($request, $quiz)
    {
        if ($quiz->teacher_id == auth()->user()->id) {
            $data = json_decode($request->getContent(), true);
            DB::setDefaultConnection('tenant');
            // $request->validate([
            //     'quiz' => ['required', 'exists:quizzes,id'],
            // ]);
            $errors = array();
            DB::beginTransaction();
            try {
                foreach ($data as $index => $question) {

                    $validator = Validator::make(
                        $question,
                        [
                            'title' => ['required', 'string'],
                            'answers' => ['required', 'string'],
                            'right_answer' => ['required', 'string'],
                            'score' => ['required', 'min:1'],
                        ],
                        [
                            'title.required' => 'The title field is required in question ' . $index + 1 . '.',
                            'answers.required' => 'The answers field is required in question ' . $index + 1 . '.',
                            'right_answer.required' => 'The right answer field is required in question ' . $index + 1 . '.',
                            'score.required' => 'The score field is required in question ' . $index + 1 . '.',
                            'score.min' => "The score should't be less than 1 in question " . $index + 1 . '.'
                        ]
                    );
                    if ($validator->fails()) {
                        $errors = array_merge($errors, $validator->errors()->all());
                    } else {
                        Question::create([
                            'title' => $question['title'],
                            'answers' => $question['answers'],
                            'right_answer' => $question['right_answer'],
                            'score' => $question['score'],
                            'quiz_id' => $quiz->id
                        ]);
                    }
                }
                DB::commit();
                DB::setDefaultConnection('mysql');
                if (count($errors)) {
                    return response()->json(['errors' => $errors], 422);
                } else {
                    return response()->json(['message' => 'The Data saved Successfully'], 201);
                }
            } catch (\Exception $e) {
                throw $e;
            }
        } else {
            return response()->json(['message' => "You don't have access to this quiz"], 500);
        }
        // return response($quiz);

    }
    public function getQuestionById($quiz, $question)
    {
        try {
            if ($quiz->teacher_id == auth()->user()->id || $question->quiz_id == $quiz->id) {
                // $question = Question::findOrFail($id);
                return response()->json([
                    'data' => new QuestionResource($question)
                ], 200);
            } else {
                return response()->json(['message' => "You don't have access to this quiz"], 500);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function updateQuestion($request, $quiz, $question)
    {
        try {
            if ($quiz->teacher_id == auth()->user()->id || $question->quiz_id == $quiz->id) {
                DB::setDefaultConnection('tenant');
                $request->validate([
                    'title' => ['required', 'string'],
                    'answers' => ['required', 'string'],
                    'right_answer' => ['required', 'string'],
                    'score' => ['required', 'min:1'],
                ]);
                DB::setDefaultConnection('mysql');
                $question->update([
                    'title' => $request->title,
                    'answers' => $request->answers,
                    'right_answer' => $request->right_answer,
                    'score' => $request->score,
                    'quiz_id' => $quiz->id
                ]);
                return response()->json(['message' => 'Question updated Successfully'], 201);
            } else {
                return response()->json(['message' => "You don't have access to this quiz"], 500);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function destroyQuestion($quiz, $id)
    {
        try {
            if ($quiz->teacher_id == auth()->user()->id) {
                $question = Question::findOrFail($id);
                $question->delete();
            } else {
                return response()->json(['message' => "You don't have access to this quiz"], 500);
            }
            return response()->json(['message' => 'Question deleted Successfully'], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
