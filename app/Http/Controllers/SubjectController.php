<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $subjectRepository;
    public function __construct(Request $request, SubjectRepositoryInterface $subjectRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->subjectRepository = $subjectRepository;

        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('index', 'show', 'store','update', 'destroy');
    }
    public function index()
    {
         return $this->subjectRepository->getAllSubjects();
    }
    public function show($id)
    {
        return $this->subjectRepository->getSubjectById($id);
    }
    public function store(Request $request)
    {
        return $this->subjectRepository->storeSubject($request);
    }
    public function update(Request $request, $id)
    {
        return $this->subjectRepository->updateSubject($request, $id);
    }
    public function destroy($id){
        return $this->subjectRepository->destroySubject($id);
    }

}
