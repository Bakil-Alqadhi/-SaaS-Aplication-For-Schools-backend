<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', 'unique:sections'],
            'grade' => ['required', 'exists:grades,id'],
            'classroom' => ['required', 'exists:classrooms,id']
        ]);
        DB::setDefaultConnection('mysql');
        Section::create([
            'name' => $request->name,
            'grade_id' => $request->grade,
            'classroom_id' => $request->classroom
        ]);
        return response()->json(['message' => 'New Section Created Successfully'], 201);

    }
}
