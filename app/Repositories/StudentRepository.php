<?php

namespace App\Repositories;

use App\Http\Resources\SectionResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Interfaces\SectionRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Models\Grade;
use App\Models\ParentStudent;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentRepository implements StudentRepositoryInterface
{
    // Registration new student
    public static function registerStudent($request)
    {
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
            'student_email' => ['required', 'string', 'email', 'max:255', 'unique:' . Student::class],
            'password' => ['required', 'confirmed'],
            //parent  validation
            'parent_first_name' =>  ['required', 'string', 'max:255'],
            'parent_last_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'string', 'email', 'max:255', 'unique:' . ParentStudent::class],
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
                'parent_id' => $parent->id,
                'first_name' => $request->student_first_name,
                'middle_name' => $request->student_middle_name,
                'last_name' => $request->student_last_name,
                'image' => $request->image,
                'sex'    => $request->sex,
                'address' => $request->student_address,
                'birthday' => $request->birthday,
                'phone' => $request->student_phone,
                'email' => $request->student_email,
                'password' => Hash::make($request->password),
            ]);

            $token = $student->createToken('student')->PlainTextToken;
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        $response = [
            'user' => new StudentResource($student),
            'token' => $token
        ];
        return $response;
    }

    //get all students
    public function getAllStudent($request)
    {
        return StudentResource::collection(Student::where('isJoined', true)->latest()->get());
    }

    //show student
    public function getStudentById($id)
    {
        return  new StudentResource(Student::findOrFail($id));
    }

    //delete student's account
    public function updateStudent($request, $id){

    }

    //delete student's account
    public function destroyStudentAccount($request, $id)
    {

        if( $request->user()->id == $id) {
            $student = Student::findOrFail($id);
        if($student){
            $student->delete();
            return response()->json(
                ['message' => "Student's account is deleted successfully."]
                , 200);
        }
        else {
            return response()->json(
                ['message' => 'The student could not be found.']
                , 404);
        }
        } else {
            return response()->json(['message' => "You are not authorized to delete another user's account."], 403);
        }
    }
}