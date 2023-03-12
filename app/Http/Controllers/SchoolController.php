<?php

namespace App\Http\Controllers;

use App\Events\DbSchoolConnected;
use App\Http\Resources\SchoolsResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;

use Illuminate\Http\Request;


class SchoolController extends Controller
{
    public function index()
    {
        // $schools = SchoolsResource::collection(School::all());
        // array_push($schools, )
        // School::all()
        return response()->json(SchoolsResource::collection(School::all()));
    }

    public function show(School $school)
    {

        // $new_db = $school->database_options['dbname'];
        event( new DbSchoolConnected($school));
        $response= array_merge(['school' =>  new SchoolsResource($school)], ['teachers'=>TeacherResource::collection(Teacher::all())]);
        return response()->json($response);
    }

    //getting all the waiting requests
    public function getWaiting($school)
    {
        event(new DbSchoolConnected(School::findOrFail($school)));
        $teachers = Teacher::where('isJoined', false)->latest()->get();
        $students = Student::where('isJoined', false)->latest()->get();
        $response = array_merge(['students'=> StudentResource::collection($students) ], ['teachers' => TeacherResource::collection($teachers)]);
        return response()->json($response);
        // return auth()->user();
        // return $teachers;
    }
    //accept new member
    public function newMember(Request $request)
    {
        event(new DbSchoolConnected(School::findOrFail($request->school_id)));
        if($request->typeMember == 'student'){
            $student = Student::findOrFail($request->member_id);
            if($student){
                $student->isJoined = true ;
                $student->save();
                return response('The new student has been added to the member of your create school!');
            } else {
                return response('Some with went wrong with adding new student');
            }
        }else if($request->typeMember == 'teacher') {
            $teacher = Teacher::findOrFail($request->member_id);
            if($teacher){
                $teacher->isJoined = true ;
                $teacher->save();
                return response('The new teacher has been added to the member of your create school!');
            } else {
                return response('Some with went wrong with adding new teacher');
            }
        }
    }

}