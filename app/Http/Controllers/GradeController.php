<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use function PHPSTORM_META\type;

class GradeController extends Controller
{
    public function index()
    {
        return response()->json(Grade::all());
    }

    public function store(Request $request)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', 'unique:grades'],
            'number' => ['required', 'unique:grades']
        ]);

        DB::setDefaultConnection('mysql');
        Grade::create([
            'name' => $request->name,
            'number' => $request->number
        ]);
        return response()->json(['message' => 'New Grade Created Successfully'], 201);
    }
    public function show($id)
    {
        $grade = Grade::findOrFail($id);
        return response()->json($grade);
    }

    public function update(Request $request, $id)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', Rule::unique('grades', 'name')->ignore($id)],
            'number' => ['required', Rule::unique('grades', 'number')->ignore($id)]
        ]);
        $grade = Grade::findOrFail($id);
        $grade->name = $request->input('name');
        $grade->number = $request->input('number');
        $grade->save();
        DB::setDefaultConnection('mysql');
        return response()->json(['message' => 'The Grade Updated Successfully'], 201);
    }
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();
        return response()->json(['message' => 'Grade Deleted Successfully'], 200);
    }
}