<?php

namespace App\Http\Controllers;

use App\Events\DbSchoolConnected;
use App\Http\Resources\StudentResource;
use App\Models\ParentStudent;
use App\Models\School;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Exception;
use Laravel\Sanctum\HasApiTokens;

class StudentController extends Controller
{
    //get all students
    public function index(Request $request)
    {
        event(new DbSchoolConnected(School::findOrFail($request->user()->school_id)));
        return response()->json(StudentResource::collection(Student::where('isJoined', true)->latest()->get()));
    }
    public static function register($request)
    {
        event(new DbSchoolConnected(School::findOrFail($request->school_id)));
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
        return response($response, 201);
    }

    //show one student
    public function show(Request $request , $id)
    {
        event(new DbSchoolConnected(School::findOrFail($request->user()->school_id)));
        return response()->json(new StudentResource(Student::findOrFail($id)));
    }
}