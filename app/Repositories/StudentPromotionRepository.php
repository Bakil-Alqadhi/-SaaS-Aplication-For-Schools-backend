<?php

namespace App\Repositories;

use App\Http\Resources\PromotionResource;
use App\Interfaces\StudentPromotionRepositoryInterface;
use App\Models\Promotion;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentPromotionRepository implements StudentPromotionRepositoryInterface
{
    public function getAllPromotions()
    {

        // PromotionResource::collection(Promotion::all())
        return response()->json([
            'data' => PromotionResource::collection(Promotion::all())
        ], 200);
    }
    public function store($request)
    {
        DB::setDefaultConnection('tenant');
        $request->validate([
            'grade_id' => ['required', 'exists:grades,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'academic_year' => ['required', 'string'],
            'grade_id_new' => ['required', 'exists:grades,id'],
            'classroom_id_new' => ['required', 'exists:classrooms,id'],
            'section_id_new' => ['required', 'exists:sections,id'],
            'academic_year_new' => ['required', 'string'],


        ]);
        $students = Student::where('grade_id', $request->grade_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('section_id', $request->section_id)
            ->where('academic_year', $request->academic_year)
            ->get();
        DB::beginTransaction();

        try {

            $students = Student::where('grade_id', $request->grade_id)
                ->where('classroom_id', $request->classroom_id)
                ->where('section_id', $request->section_id)
                ->where('academic_year', $request->academic_year)
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
                $student->academic_year = $request->academic_year_new;
                $student->save();

                //insert into promotions table
                Promotion::updateOrCreate([
                    'student_id' => $student->id,
                    //from
                    'from_grade' => $request->grade_id,
                    'from_classroom' => $request->classroom_id,
                    'from_section' => $request->section_id,
                    'from_academic_year' => $request->academic_year,
                    //to
                    'to_grade' => $request->grade_id_new,
                    'to_classroom' => $request->classroom_id_new,
                    'to_section' => $request->section_id_new,
                    'to_academic_year' => $request->academic_year_new,

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
    public function destroyAllPromotions($request)
    {
        // return response(Promotion::all());
        DB::beginTransaction();
        try {
            if ($request->header('Type') == 'all') {
                $promotions = Promotion::all();
                foreach ($promotions as $promotion) {
                    $student = $promotion->student;
                    $student->grade_id = $promotion->from_grade;
                    $student->classroom_id = $promotion->from_classroom;
                    $student->section_id = $promotion->from_section;
                    $student->academic_year = $promotion->from_academic_year;
                    $student->save();
                }

                //delete all promotions
                Promotion::truncate();
            } else {
                $promotion = Promotion::findOrFail($request->header('id'));
                $student = $promotion->student;
                $student->grade_id = $promotion->from_grade;
                $student->classroom_id = $promotion->from_classroom;
                $student->section_id = $promotion->from_section;
                $student->academic_year = $promotion->from_academic_year;
                $student->save();
                $promotion->delete();
            }
            DB::commit();
            return response()->json(
                ['message' => "Data is deleted successfully."],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
