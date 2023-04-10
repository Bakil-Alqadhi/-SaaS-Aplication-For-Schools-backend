<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['sections'])->get();
        $gradeData = [];
        $sectionsData = [];
        foreach ($grades as $grade) {
            // array_diff($sectionsData);
            // reset($sectionsData);

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
        // return GradeResource::collection(Grade::all());
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
}
