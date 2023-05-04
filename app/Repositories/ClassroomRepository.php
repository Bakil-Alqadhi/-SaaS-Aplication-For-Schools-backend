<?php

namespace App\Repositories;

use App\Http\Resources\ClassroomResource;
use App\Http\Resources\StudentResource;
use App\Interfaces\ClassroomRepositoryInterface;
use App\Models\Classroom;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassroomRepository implements ClassroomRepositoryInterface
{

    protected $errors = [];
    public function getAllClassrooms()
    {
        return response()->json([
            'data' => ClassroomResource::collection(Classroom::all())
        ], 200);
    }
    public function validateArrayOfObjects($arrayOfObjects)
    {
        foreach ($arrayOfObjects as $index => $object) {
            if (empty($object['name'])) {
                $this->errors[] = "Classroom " . $index + 1 . ": Name is required.";
            } else {
                DB::setDefaultConnection('tenant');
                $validator = Validator::make($object, [
                    $object['name'] => [Rule::unique('classrooms', 'name')]
                ]);
                if (!$validator) {
                    $this->errors[] = "Classroom " . $index + 1 . ": This name is taken";
                }
                DB::setDefaultConnection('mysql');
            }
            if (empty($object['grade'])) {
                $this->errors[] = "Classroom " . $index + 1 . ": Grade is required.";
            } else {
                $validator = Validator::make($object, [
                    // $object['name'] => ['required', 'string'],
                    $object['grade'] => ['exists:grades,id']
                ]);
                if ($validator->fails()) {
                    $this->errors[] = "Classroom " . $index + 1 . ": Grade is't in our records";
                }
            }
        };
        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }
    public function storeClassroom($request)
    {
        $data = json_decode($request->getContent(), true);
        if ($this->validateArrayOfObjects($data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $classroom) {
                    Classroom::create([
                        'name' => $classroom['name'],
                        'grade_id' => $classroom['grade']
                    ]);
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
            DB::commit();
            if (count($data) > 1) {
                return response()->json([
                    'message' => 'Classrooms Created Successfylly'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Classroom Created Successfylly'
                ], 201);
            }
        } else {
            return response()->json([
                'errors' => $this->errors
            ], 422);
        }
    }
    public function getClassroomById($id)
    {
        // return response()->json(new ClassroomResource(Classroom::findOrFail($id)), 200);
        return response()->json([
            'data' => new ClassroomResource(Classroom::findOrFail($id))
        ], 200);
    }
    public function updateClassroom($request, $id)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string'],
            'grade' => ['required', 'exists:grades,id']
        ]);
        DB::setDefaultConnection('mysql');

        $classroom = Classroom::findOrFail($id);
        $classroom->name = $request->input('name');
        $classroom->grade_id = $request->input('grade');
        $classroom->save();

        return response()->json(['message' => 'The Classroom Updated Successfully'], 201);
    }
    public function getStudentsBySectionId($id)
    {
        $section = Section::findOrFail($id);
        $classroom_id = $section->classroom->id;
        if ($section) {
            $students = Student::whereNull('section_id')->where('classroom_id', $classroom_id)->where('isJoined', '1')->get();
            // $section->classroom->students->where('section_id', null)->where('isJointed', '1')
            if ($students) {
                return response()->json([
                    'data' =>  StudentResource::collection($students)
                ], 200);
            } else
                return response()->json(['message' => "The Section is't exist"], 402);
        } else
            return response()->json(['message' => "The Section is't exist"], 402);
    }
    public function destroyClassroom($id)
    {
        $classroom = Classroom::findOrFail($id);
        $students = $classroom->students;
        foreach ($students as $student) {
            $student->classroom_id = null;
            $student->save();
        }
        $classroom->delete();
        return response()->json(['message' => 'The Classroom Deleted Successfully'], 200);
    }
}
