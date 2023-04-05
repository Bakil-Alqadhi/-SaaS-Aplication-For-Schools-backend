<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassroomController extends Controller
{


    protected $errors = [];

    public function validateArrayOfObjects($arrayOfObjects)
    {
        foreach ($arrayOfObjects as $index => $object) {
            if (empty($object['name'])) {
                $this->errors[] = "Classroom " . $index + 1 . ": Name is required";
            }
            if (empty($object['grade'])) {
                $this->errors[] = "Classroom " . $index + 1 . ": Grade is required and must be an integer";
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
    public function update($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
    }
}
