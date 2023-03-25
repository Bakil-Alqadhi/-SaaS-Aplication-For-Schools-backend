<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\PersonalAccessToken;;

use App\Events\DbSchoolConnected;
use App\Events\SchoolCreated;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Resources\SchoolsResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    protected $school_id;

    protected $authUser;
    protected $guard = 'web';

    protected $token;
    public  function __construct(Request $request)
    {

        if ($request->header('X-Sanctum-Guard') != 'director') {
            $this->school_id = $request->header('X-School');
            $this->guard = $request->header('X-Sanctum-Guard');
        }
        $this->token = $request->bearerToken();
        if ($this->guard == 'teacher' || $this->guard == 'student') {
            event(new DbSchoolConnected(School::findOrFail($this->school_id)));
            DB::setDefaultConnection('tenant');
        } else {
            DB::setDefaultConnection('mysql');
        }
        //    Config::set('database.connections', 'tenant');
        $this->middleware('auth:sanctum')->only('user', 'destroy');
    }
    public function user(Request $request)
    {
        $user = $request->user();
        $tokens = $user->tokens;
        if ($this->guard == 'web') {
            $this->authUser = new SchoolsResource(School::where('id', $this->school_id)->first());
        } else if ($this->guard == 'teacher') {
            $this->authUser = new TeacherResource($user);
        } else if ($this->guard == 'student') {
            $this->authUser = new StudentResource($user);
        }
        //$firstTokenName = $tokens->isEmpty() ? null : $tokens->first()->name;
        return response()->json([
            'user' => $this->authUser,
            'school' => $this->school_id,
            'role' => $user->currentAccessToken()->name,
            'token' => $this->token
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            // 'userType' => ['required', 'string']
        ]);
        // $credentials = $request->only('email', 'password');
        if ($this->guard == 'web') {
            //check email
            $user = User::where('email', $request->email)->first();

            //check password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 401);
            }
            // if(Auth::guard('director')->attempt($credentials)){
            // $user = Auth::guard('director')->user();
            $token = $user->createToken('director')->plainTextToken;
            $response = [
                'user' => new SchoolsResource(School::where('id', $user->school_id)->first()),
                'token' => $token,
                // 'school' => $this->school_id
            ];
            return response($response, 201);
            // }
        } else if ($this->guard == 'teacher') {
            // event(new DbSchoolConnected(School::findOrFail($this->school_id)));
            // check email
            $teacher = Teacher::where('email', $request->email)->first();
            //check password
            if (!$teacher || !Hash::check($request->password, $teacher->password)) {
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 401);
            }
            $token = $teacher->createToken('teacher')->plainTextToken;
            $response = [
                'user' => new TeacherResource($teacher),
                'token' => $token,
                'school' => $this->school_id,
            ];
            return response($response, 201);
            // }

        } else if ($request->userType == 'student') {
            // event(new DbSchoolConnected(School::findOrFail($this->school_id)));
            //check email
            $student = Student::where('email', $request->email)->first();

            //check password
            if (!$student || !Hash::check($request->password, $student->password)) {
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 401);
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
        ], 401);
    }
    public function register(Request $request)
    {
        if ($request->userType == 'director') {
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
                return response($response, 201);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else if ($request->userType == 'student') {
            return StudentController::register($request);
        } else if ($request->userType == 'teacher') {
            return TeacherController::register($request);
            //    return $this->testReg();

            //   return  response($request);
        }
    }

    //logout method
    public function destroy(Request $request)
    {
        // if($this->guard == 'director') {
        //     $this->guard ='web';
        // }
        $user = Auth::guard($this->guard)->user();

        //logout from single device
        // $token = $user->tokens()->findOrFail($token_id)->delete();

        //logout from current device
        //    $user->currentAccessToken()->delete();

        //logout from all devices
           $user->tokens->delete();

        return response()->json(['message' => 'You logged out from all the devices!']);
    }
}
