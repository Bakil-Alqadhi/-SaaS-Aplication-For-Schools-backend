<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\QuestionRepositoryInterface;
use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private $questionRepository;
    public function __construct(Request $request, QuestionRepositoryInterface $questionRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->questionRepository = $questionRepository;
        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('index', 'show', 'store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz)
    {
        return $this->questionRepository->getAllQuestions($quiz);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz)
    {
        return $this->questionRepository->storeQuestion($request, $quiz);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Question $question)
    {
        return $this->questionRepository->getQuestionById($quiz, $question);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,Quiz $quiz, Question $question)
    {
        return $this->questionRepository->updateQuestion($request, $quiz, $question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, string $id)
    {
        return $this->questionRepository->destroyQuestion($quiz, $id);
    }
}
