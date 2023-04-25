<?php

namespace App\Repositories;

use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Interfaces\SchoolRepositoryInterface;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SchoolRepository implements SchoolRepositoryInterface
{
    //register new teacher
    public function getWaiting()
    {
        $teachers = Teacher::where('isJoined', false)->orderBy('first_name', 'desc')->get();
        $students = Student::where('isJoined', false)->orderBy('first_name', 'desc')->get();
        $response = array_merge(['students' => StudentResource::collection($students)], ['teachers' => TeacherResource::collection($teachers)]);
        return $response;
    }
    //get all teachers
    public function newMember($request, $id)
    {
        if ($request->userType == 'student') {
            $student = Student::findOrFail($id);
            if ($student) {
                $student->isJoined = true;
                $student->save();
                return 'The new student has been added to the member of your create school!';
            } else {
                return 'Some with went wrong with adding new student';
            }
        } else if ($request->userType == 'teacher') {
            $teacher = Teacher::findOrFail($id);
            if ($teacher) {
                $teacher->isJoined = true;
                $teacher->save();
                return  'The new teacher has been added to the member of your create school!';
            } else {
                return  'Some thing went wrong with adding new teacher';
            }
        }
    }

    //show teacher
    public function getTeacherById($id)
    {
    }

    //edit teacher
    public function updateTeacher($request, $id)
    {



    }
}