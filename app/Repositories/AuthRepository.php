<?php

namespace App\Repositories;

use App\Events\DbSchoolConnected;
use App\Events\SchoolCreated;
use App\Http\Resources\SchoolsResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Interfaces\AuthRepositoryInterface;
use App\Models\ParentStudent;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface
{
    protected $school_id;
    protected $school_name;

    protected $authUser;
    protected $guard = 'web';

    protected $token;
    public function switchingMethod($request)
    {
        if ($request->header('X-Sanctum-Guard') != 'director') {
            $this->school_id = $request->header('X-School');
            $this->school_name = School::where('id', $this->school_id)->first()->school_name;
            $this->guard = $request->header('X-Sanctum-Guard');
            /////
            event(new DbSchoolConnected(School::findOrFail($request->header('X-School'))));
            DB::setDefaultConnection('tenant');
        } else {
            DB::setDefaultConnection('mysql');
        }
        $this->token = $request->bearerToken();
    }
    //registration method
    public function register($request){
        if ($request->userType == 'director') {

            $this->directorRegistration($request);
        } else if ($this->guard == 'student') {
            $this->studentRegistration($request);
        } else if ($this->guard == 'teacher') {
            $this->teacherRegistration($request);
        }
    }
    //Director Registration method
    public function directorRegistration($request){
        $request->validate([
            'userType' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'school_name' => ['required', 'string', 'max:255'],
            'school_image' => ['required'],
            'director_image' => ['required'],
            'address' => ['required', 'string', 'max:255'],
            'about_school' => ['required', 'string', 'max:255'],
            'about_director' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed'],
        ]);
        DB::beginTransaction();
        try {
            $school = School::create([
                'school_name' => $request->school_name,
                'domain' => Str::slug($request->school_name) . '.localhost',
                'database_options' => '',
                'address' => $request->address,
                'phone' => $request->phone,
                'school_image' => $request->school_image,
                'director_image' => $request->director_image,
                'about_school' => $request->about_school,
                'about_director' => $request->about_director
            ]);
            $user = User::create([
                'school_id' => $school->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('director')->plainTextToken;
            DB::commit();
            event(new SchoolCreated($school));
            $response = [
                'user' => $user,
                'school' => $school,
                'token' => $token
            ];
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    //Student registration method
    public function studentRegistration($request){
        $request->validate([
            //student validation
            'student_first_name' => ['required', 'string', 'max:255'],
            'student_middle_name' => ['required', 'string', 'max:255'],
            'student_last_name' => ['required', 'string', 'max:255'],
            'image' => ['required'],
            'sex'    => ['require', 'string', 'max:6'],
            'student_address' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date', 'before:today', 'after:' . date('Y-m-d', strtotime('-100 years'))],
            'student_phone' => ['required', 'string', 'max:255'],
            'student_email' => ['required', 'string', 'email', 'max:255', 'unique:'.Student::class],
            'password' => ['required', 'confirmed'],
            //parent  validation
            'parent_first_name' =>  ['required', 'string', 'max:255'],
            'parent_last_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'string', 'email', 'max:255', 'unique:'.ParentStudent::class],
        ]);
        DB::beginTransaction();
        try {
            $parent = ParentStudent::create([
                'first_name' =>  $request->parent_first_name,
                'last_name' => $request->parent_last_name,
                'phone' => $request->parent_phone,
                'email' => $request->parent_email,
            ]);
            $student = Student::create([
                'parent_id'=> $parent->id,
                'first_name' => $request->student_first_name,
                'middle_name' => $request->student_middle_name,
                'last_name' => $request->student_last_name,
                'image' =>$request->image,
                'sex'    => $request->sex,
                'address' => $request->student_address,
                'birthday' => $request->birthday,
                'phone' => $request->student_phone,
                'email' => $request->student_email,
                'password' => Hash::make($request->password),
            ]);

            $token = $student->createToken('student')->PlainTextToken;
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
        $response = [
            'user' => new StudentResource($student),
            'token' =>$token
        ];
        return $response;
    }
    //Teacher Registration method
    public function teacherRegistration($request){
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
            'specialization_id' => $request->specialization,
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
        return $response;
    }

    //login method
    public function login($request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if ($this->guard == 'web') {
            //check email
            $user = User::where('email', $request->email)->first();

            //check password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 422);
            }

            $token = $user->createToken('director')->plainTextToken;
            $response = [
                'user' => new SchoolsResource(School::where('id', $user->school_id)->first()),
                'token' => $token,
            ];
            return response($response, 201);
            // }
        } else if ($this->guard == 'teacher') {
            // check email
            $teacher = Teacher::where('email', $request->email)->first();
            //check password
            if (!$teacher || !Hash::check($request->password, $teacher->password)) {
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 422);
            }
            $token = $teacher->createToken('teacher')->plainTextToken;
            $response = [
                'user' => new TeacherResource($teacher),
                'token' => $token,
                'school' => $this->school_id,
            ];
            return response($response, 201);
            // }

        } else if ($this->guard == 'student') {
            //check email
            $student = Student::where('email', $request->email)->first();

            //check password
            if (!$student || !Hash::check($request->password, $student->password)) {
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 422);
            }
            $token = $student->createToken('student')->plainTextToken;
            $response = [
                'user' => new StudentResource($student),
                'token' => $token,
                'school' => $this->school_id
            ];
            return response($response, 201);
        }
        return response()->json([
            'message' => 'Invalid email or password',
        ], 422);
    }

    public function user($request)
    {
        // $this->switchingMethod($request);
        $user = $request->user();
        $tokens = $user->tokens;
        if ($this->guard == 'web') {
            $this->authUser = new SchoolsResource(School::where('id', $user->school_id)->first());
        } else if ($this->guard == 'teacher') {
            $this->authUser = new TeacherResource($user);
        } else if ($this->guard == 'student') {
            $this->authUser = new StudentResource($user);
        }
        return response()->json([
            'user' => $this->authUser,
            'school' => $this->school_id,
            'school_name' => $this->school_name,
            'role' => $user->currentAccessToken()->name,
            'token' => $this->token
        ]);
    }
    public function logout($request)
    {
        // $this->switchingMethod($request);
        $user = $request->user();

        //logout from single device
        // $token = $user->tokens()->findOrFail($token_id)->delete();

        //logout from current device
        //    $user->currentAccessToken()->delete();

        //logout from all devices
        $user->tokens()->delete();

        return response()->json(['message' => 'You logged out from all the devices!']);
    }
}
