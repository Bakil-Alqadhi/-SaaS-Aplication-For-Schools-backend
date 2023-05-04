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
        $this->middleware('auth:sanctum')->only('studentsClassroom', 'store','update','destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->classroomRepository->getAllClassrooms();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->classroomRepository->storeClassroom($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        // return response()->json(new ClassroomResource(Classroom::findOrFail($id)), 200);
        return $this->classroomRepository->getClassroomById($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->classroomRepository->updateClassroom($request, $id);
    }

    public function studentsClassroom($id)
    {
        return $this->classroomRepository->getStudentsBySectionId($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->classroomRepository->destroyClassroom($id);
    }
}