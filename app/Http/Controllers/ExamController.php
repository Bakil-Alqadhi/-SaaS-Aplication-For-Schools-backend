<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\ExamRepositoryInterface;
use Illuminate\Http\Request;

class ExamController extends Controller
{

    private ExamRepositoryInterface $examRepo;
    public function __construct(Request $request, ExamRepositoryInterface $examRepositoryInterface, AuthRepositoryInterface $authRepository)
    {
        $authRepository->switchingMethod($request);
        $this->examRepo = $examRepositoryInterface;
        // $authRepository->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('index', 'show', 'store');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->examRepo->getAllExams();
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
    public function store(Request $request)
    {
        return $this->examRepo->storeAnswersExam($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return  $this->examRepo->getExamQuestionsById($id);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
