<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassroomResource;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\ClassroomRepositoryInterface;
use App\Models\Classroom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    private ClassroomRepositoryInterface $classroomRepository;

    public function __construct(Request $request, ClassroomRepositoryInterface $classroomRepository, AuthRepositoryInterface $authRepository)
    {
        $authRepository->switchingMethod($request);
        $this->classroomRepository = $classroomRepository;
        // $authRepository->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('index','studentsClassroom', 'store','update','destroy');
    }

    public function index()
    {
        return $this->classroomRepository->getAllClassrooms();
    }

    public function store(Request $request)
    {
        return $this->classroomRepository->storeClassroom($request);
    }

    public function show($id)
    {

        // return response()->json(new ClassroomResource(Classroom::findOrFail($id)), 200);
        return $this->classroomRepository->getClassroomById($id);
    }

    public function update(Request $request, $id)
    {
        return $this->classroomRepository->updateClassroom($request, $id);
    }

    public function studentsClassroom($id)
    {
        return $this->classroomRepository->getStudentsBySectionId($id);
    }

    public function destroy($id)
    {
        return $this->classroomRepository->destroyClassroom($id);
    }
}