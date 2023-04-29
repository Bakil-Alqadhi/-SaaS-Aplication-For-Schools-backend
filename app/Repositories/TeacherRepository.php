<?php

namespace App\Repositories;

use App\Http\Resources\TeacherResource;
use App\Interfaces\TeacherRepositoryInterface;
use App\Models\School;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherRepository implements TeacherRepositoryInterface
{
    //register new teacher
    public function registerTeacher($request)
    {
        $request->validate([
            //teacher validation
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
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
                'specialization' => $request->specialization,
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
        } catch (Exception $e) {
            throw $e;
        }
    }
    //get all teachers
    public function getAllTeachers()
    {
        return TeacherResource::collection(resource: Teacher::where('isJoined', true)->latest()->paginate(5));
    }

    //show teacher
    public function getTeacherById($id)
    {
        return new TeacherResource(Teacher::findOrFail($id));
    }

    //edit teacher
    public function updateTeacher($request, $id)
    {
        // DB::setDefaultConnection('tenant');
        $request->validate([
            //teacher validation
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($id)],
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->first_name = $request->first_name;
        $teacher->last_name = $request->last_name;
        $teacher->image = $request->image;
        $teacher->about = $request->about;
        $teacher->phone = $request->phone;
        $teacher->email = $request->email;
        $teacher->save();

        // DB::setDefaultConnection('mysql');

        return response()->json(['message' => 'The Teacher Updated Successfully'], 201);
    }
}
