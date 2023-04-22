<?php

namespace App\Repositories;

use App\Http\Resources\SectionResource;
use App\Http\Resources\TeacherResource;
use App\Interfaces\SectionRepositoryInterface;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SectionRepository implements SectionRepositoryInterface
{
    //get all teachers
    public function getSectionsData()
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
        return $gradeData;
    }
    public function storeSection($request){
        DB::setDefaultConnection('tenant');
        $request->validate([
            'name' => ['required', 'string', 'unique:sections'],
            'grade' => ['required', 'exists:grades,id'],
            'classroom' => ['required', 'exists:classrooms,id'],
            'teachers' => ['required', 'array']
        ]);
        DB::setDefaultConnection('mysql');
        $section = Section::create([
            'name' => $request->name,
            'grade_id' => $request->grade,
            'classroom_id' => $request->classroom
        ]);

        //adding the relationship with teachers
        $section->teachers()->attach($request->teachers);
        return response()->json(['message' => 'New Section Created Successfully'], 201);
    }

    public function showSectionById($id){
        $section = Section::where('id', $id)->first();
        // return $section;
        if ($section)
            return response()->json(new SectionResource($section), 200);
        else
            return response()->json(['message' => "The Section is't exist"], 402);
    }

    //update section's data
    public function updateSection($request, $id)
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
        $section->teachers()->sync($request->teachers);
        $section->save();
        DB::setDefaultConnection('mysql');


        return response()->json(['message' => 'The Section Updated Successfully'], 201);
    }
    public function destroySection($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return response()->json(['message' => 'The Section Deleted Successfully'], 200);
    }

}