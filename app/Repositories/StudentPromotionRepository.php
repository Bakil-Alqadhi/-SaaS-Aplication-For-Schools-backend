<?php

namespace App\Repositories;

use App\Interfaces\StudentPromotionRepositoryInterface;
use App\Models\Promotion;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentPromotionRepository implements StudentPromotionRepositoryInterface
{
    public function store($request)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'grade_id' => ['required', 'exists:grades,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'grade_id_new' => ['required', 'exists:grades,id'],
            'classroom_id_new' => ['required', 'exists:classrooms,id'],
            'section_id_new' => ['required', 'exists:sections,id'],

        ]);
        DB::beginTransaction();

        try {

            $students = Student::where('grade_id', $request->grade_id)
                ->where('classroom_id', $request->classroom_id)
                ->where('section_id', $request->section_id)
                ->get();

            if ($students->count() < 1) {
                return response()->json([
                    'message' => "There is no data in student's table"
                ], 204);
            }

            //update in table students
            foreach ($students as $student) {
                $student->grade_id = $request->grade_id_new;
                $student->classroom_id = $request->classroom_id_new;
                $student->section_id = $request->section_id_new;

                $student->save();

                //insert into promotions table
                Promotion::updateOrCreate([
                    'student_id' => $student->id,
                    //from
                    'from_grade' => $request->grade_id,
                    'from_classroom' => $request->classroom_id,
                    'from_section' => $request->section_id,
                    //to
                    'to_grade' => $request->grade_id_new,
                    'to_classroom' => $request->classroom_id_new,
                    'to_section' => $request->section_id_new,
                ]);
            }
            DB::commit();
            DB::setDefaultConnection('mysql');
            return response()->json([
                'message' => "New promotion added successfully"
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
