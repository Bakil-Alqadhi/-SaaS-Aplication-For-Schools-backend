<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\QuizRepositoryInterface;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    private $quizRepository;
    public function __construct(Request $request, QuizRepositoryInterface $quizRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->quizRepository = $quizRepository;
        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('index', 'show', 'store','update', 'destroy');
    }
    public function index()
    {
         return $this->quizRepository->getAllQuizzes();
    }
    public function store(Request $request)
    {
        return $this->quizRepository->storeQuiz($request);
    }
    public function show($id)
    {
        return $this->quizRepository->getQuizById($id);
    }

    public function update(Request $request, $id)
    {
        return $this->quizRepository->updateQuiz($request, $id);
    }
    public function destroy($id){
        return $this->quizRepository->destroyQuiz($id);
    }

}