<?php

namespace App\Http\Controllers;

use App\Events\DbSchoolConnected;
use App\Http\Resources\TeacherResource;
use App\Models\School;
use App\Models\Teacher;
use App\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Exception;

class TeacherController extends Controller
{
    private TeacherRepositoryInterface $teacherRepository;
    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }
    public static function register(Request $request)
    {
        $request->validate([
            //teacher validation
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string', 'max:255'],
            'specialization' => ['required'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Teacher::class],
            'password' => ['required', 'confirmed'],

        ]);
            $teacher = Teacher::create([
                'first_name' =>  $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'about' => $request->about,
                'specialization_id'=> $request->specialization,
                'image' => $request->image,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ]);

            $token = $teacher->createToken('teacher')->plainTextToken;
            $response = [
                'user' => $teacher,
                'role' => 'teacher',
                'token' => $token
            ];
            return response()->json($response, 201);
        }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // //  response()->json(TeacherResource::collection(resource: Teacher::latest()->get()), status: 200);
        // return TeacherResource::collection(resource: Teacher::where('isJoined', true)->latest()->get());

        return  response()->json([
            'data' => $this->teacherRepository->getAllTeachers()
        ]);
    }

    public function test(School $school)
    {
        event(new DbSchoolConnected($school));
        return Teacher::latest()->paginate(2);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return  response()->json([
            'data' => $this->teacherRepository->getTeacherById($id)
        ]);;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->teacherRepository->updateTeacher($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}