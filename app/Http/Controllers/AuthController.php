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
use App\Interfaces\AuthRepositoryInterface;
use App\Models\Teacher;
use App\Repositories\AuthRepository;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;
    protected $school_id;
    protected $school_name;

    protected $authUser;
    protected $guard = 'web';

    protected $token;
    public  function __construct(Request $request, AuthRepositoryInterface $authRepository)
    {
        $authRepository->switchingMethod($request);
        $this->authRepository = $authRepository;

        // if ($request->header('X-Sanctum-Guard') != 'director') {
        //     $this->school_id = $request->header('X-School');
        //     $this->school_name = School::where('id', $this->school_id)->first()->school_name;
        //     $this->guard = $request->header('X-Sanctum-Guard');
        //     event(new DbSchoolConnected(School::findOrFail($this->school_id)));
        //     DB::setDefaultConnection('tenant');
        // } else {
        //     DB::setDefaultConnection('mysql');
        // }
        // $this->token = $request->bearerToken();
        $this->middleware('auth:sanctum')->only('user', 'destroy');
    }
    public function user(Request $request)
    {
        return $this->authRepository->user($request);
    }
    public function login(Request $request)
    {
        return $this->authRepository->login($request);
    }
    public function register(Request $request)
    {
        // if ($request->userType == 'director') {
        //     $request->validate([
        //         'userType' => ['required', 'string', 'max:255'],
        //         'name' => ['required', 'string', 'max:255'],
        //         'school_name' => ['required', 'string', 'max:255'],
        //         'school_image' => ['required'],
        //         'director_image' => ['required'],
        //         'address' => ['required', 'string', 'max:255'],
        //         'about_school' => ['required', 'string', 'max:255'],
        //         'about_director' => ['required', 'string', 'max:255'],
        //         'phone' => ['required', 'string', 'max:255'],
        //         'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
        //         'password' => ['required', 'confirmed'],
        //     ]);
        //     DB::beginTransaction();
        //     try {


        //         $school = School::create([
        //             'school_name' => $request->school_name,
        //             'domain' => Str::slug($request->school_name) . '.localhost',
        //             'database_options' => '',
        //             'address' => $request->address,
        //             'phone' => $request->phone,
        //             'school_image' => $request->school_image,
        //             'director_image' => $request->director_image,
        //             'about_school' => $request->about_school,
        //             'about_director' => $request->about_director
        //         ]);
        //         $user = User::create([
        //             'school_id' => $school->id,
        //             'name' => $request->name,
        //             'email' => $request->email,
        //             'password' => Hash::make($request->password),
        //         ]);

        //         $token = $user->createToken('director')->plainTextToken;
        //         DB::commit();
        //         event(new SchoolCreated($school));
        //         $response = [
        //             'user' => $user,
        //             'school' => $school,
        //             'token' => $token
        //         ];
        //         return response($response, 201);
        //     } catch (Exception $e) {
        //         DB::rollBack();
        //         throw $e;
        //     }
        // } else if ($this->guard == 'student') {
        //     return StudentController::register($request);
        // } else if ($this->guard == 'teacher') {
        //     return TeacherController::register($request);
        // }


        return $this->authRepository->register($request);

    }

    //logout method
    public function destroy(Request $request)
    {
        return $this->authRepository->logout($request);

   }
}
