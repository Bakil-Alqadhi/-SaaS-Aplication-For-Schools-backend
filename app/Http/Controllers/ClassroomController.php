<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    protected $errors = [];

    public function validateArrayOfObjects($arrayOfObjects)
    {
        foreach ($arrayOfObjects as $index => $object) {
            if (empty($object['name'])) {
                $this->errors[] = "Classroom " . $index + 1 . ": Name is required.";
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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // return json_decode($this->classrooms)[0]->name;
        // return json_decode('$request');
        return response()->json(ClassroomResource::collection(Classroom::all()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        return response()->json(new ClassroomResource(Classroom::findOrFail($id)), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
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

        // if ($this->validateArrayOfObjects($request->all())) {
        //     return response()->json(['message' => 'The Classroom Updated Successfully'], 201);
        // }
        // return response()->json([
        //     'errors' => $validator->errors()
        // ], 422);
        // return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();
        return response()->json(['message' => 'The Classroom Deleted Successfully'], 200);
    }
}