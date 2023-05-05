<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\ExamRepositoryInterface;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private $examRepository;
    public function __construct(Request $request, ExamRepositoryInterface $examRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->examRepository = $examRepository;
        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('index', 'show', 'store','update', 'destroy');
    }
    public function index()
    {
         return $this->examRepository->getAllExams();
    }
    public function store(Request $request)
    {
        return $this->examRepository->storeExam($request);
    }
    public function show($id)
    {
        return $this->examRepository->getExamById($id);
    }
    public function update(Request $request, $id)
    {
        return $this->examRepository->updateExam($request, $id);
    }
    public function destroy($id){
        return $this->examRepository->destroyExam($id);
    }

}
