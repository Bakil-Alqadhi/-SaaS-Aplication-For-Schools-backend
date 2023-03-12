<?php

namespace App\Http\Controllers;

use App\Events\DbSchoolConnected;
use App\Http\Resources\TeacherResource;
use App\Models\School;
use App\Models\Teacher;
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
    public static function register(Request $request)
    {
        event(new DbSchoolConnected(School::findOrFail($request->school_id)));
        $request->validate([
            //teacher validation
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Teacher::class],
            'password' => ['required', 'confirmed'],

        ]);
        try {

            // $image = null;
            // if($request->hasFile('school_image') && $request->file('school_image')->isValid()
            //     && $request->hasFile('director_image') && $request->file('director_image')->isValid()
            // ){
            //     $image = $request->file('school_image')->store('schools', 'school_images');
            //     $director_image = $request->file('director_image')->store('directors', 'school_images');
            // }

            $teacher = Teacher::create([
                'first_name' =>  $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'about' => $request->about,
                'image' => $request->image,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ]);

            $token = $teacher->createToken('teacher')->plainTextToken ;
            $response = [
                'user' => $teacher,
                'userType'=> 'teacher',
                'token' =>$token
            ];
            return response($response, 201);

        } catch(Exception $e){
            throw $e;
        }
        return response('Bad Request ):');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(School $school)
    {
        event(new DbSchoolConnected($school));
        // return response()->json(TeacherResource::collection(resource: Teacher::latest()->paginate(5)), status: 200);
        return response()->json(TeacherResource::collection(resource: Teacher::where('isJoined', true)->latest()->get()));

        // return Teacher::latest()->paginate(5);
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
    public function show(school $school, $id)
    {
        event(new DbSchoolConnected($school));

        return response()->json(new TeacherResource(Teacher::findOrFail($id)), status:200);
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