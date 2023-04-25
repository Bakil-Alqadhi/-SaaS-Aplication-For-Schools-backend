<?php

namespace App\Http\Controllers;

use App\Events\DbSchoolConnected;
use App\Http\Resources\SchoolsResource;
use App\Http\Resources\TeacherResource;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\SchoolRepositoryInterface;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;


class SchoolController extends Controller
{
    private SchoolRepositoryInterface $schoolRepository;
    public function __construct(Request $request, SchoolRepositoryInterface $schoolRepository, AuthRepositoryInterface $authRepository)
    {
        $authRepository->switchingMethod($request);
        $this->schoolRepository = $schoolRepository;
    }
    public function index()
    {
        // $schools = SchoolsResource::collection(School::all());
        // array_push($schools, )
        // School::all()
        return response()->json(SchoolsResource::collection(School::all()), 200);
    }

    public function show(School $school)
    {

        // $new_db = $school->database_options['dbname'];
        event(new DbSchoolConnected($school));
        $response = array_merge(['school' =>  new SchoolsResource($school)], ['teachers' => TeacherResource::collection(Teacher::take(4)->get())]);
        return response()->json($response);
    }

    //getting all the waiting requests
    public function getWaiting()
    {
        // event(new DbSchoolConnected(School::findOrFail($request->user()->school_id)));
        return response()->json([
            'data' => $this->schoolRepository->getWaiting()
        ],200);
        // return auth()->user();
        // return $teachers;
    }
    //accept new member
    public function newMember(Request $request, $id)
    {
        // $request->validate([
        //     'userType' => ['required', 'string', 'max:255']
        // ]);
        // event(new DbSchoolConnected(School::findOrFail($request->user()->school_id)));
        return response()->json([
            'message' => $this->schoolRepository->newMember($request, $id)
        ]);
    }
}
