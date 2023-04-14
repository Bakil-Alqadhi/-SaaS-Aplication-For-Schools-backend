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
        //event(new DbSchoolConnected(School::findOrFail($request->school_id)));
        $request->validate([
            //teacher validation
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Teacher::class],
            'password' => ['required', 'confirmed'],

        ]);
        try {
            $teacher = Teacher::create([
                'first_name' =>  $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'about' => $request->about,
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
            return response($response, 201);
        } catch (Exception $e) {
            throw $e;
        }
        return response('Bad Request ):');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // event(new DbSchoolConnected(School::findOrFail($request->header('X-School'))));

        return new TeacherResource(Teacher::findOrFail($id));
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