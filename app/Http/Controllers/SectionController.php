<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use App\Http\Resources\SectionResource;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['sections'])->get();
        $gradeData = [];
        $sectionsData = [];
        foreach ($grades as $grade) {
            foreach ($grade->sections as $section) {
                $sectionsData[] = [
                    'classroom_name' => $section->classroom->name,
                    'classroom_id' => $section->classroom->id,
                    'section_name' => $section->name,
                    'section_id' => $section->id,
                ];
            }
            $gradeData[] = [
                'grade_id' => $grade->id,
                'grade_name' => $grade->name,
                'sectionsData' => $sectionsData
            ];
            $sectionsData = array();
        }
        return response()->json($gradeData, 200);
    }
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

    public function show($id)
    {
        $section = Section::where('id', $id)->first();
        // return $section;
        if ($section)
            return response()->json(new SectionResource($section), 200);
        else
            return response()->json(['message' => "The Section is't exist"], 402);
    }

    public function update(Request $request, $id)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', Rule::unique('sections', 'name')->ignore($id)],
            'grade' => ['required', 'exists:grades,id'],
            'classroom' => ['required', 'exists:classrooms,id']
        ]);

        $section = Section::findOrFail($id);
        $section->name = $request->input('name');
        $section->grade_id = $request->input('grade');
        $section->classroom_id = $request->input('classroom');
        $section->save();
        DB::setDefaultConnection('mysql');


        return response()->json(['message' => 'The Section Updated Successfully'], 201);
    }
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return response()->json(['message' => 'The Section Deleted Successfully'], 200);
    }
}
