<?php

namespace App\Repositories;

use App\Http\Resources\AttendanceReportResource;
use App\Http\Resources\AttendanceResource;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AttendanceRepository implements AttendanceRepositoryInterface
{

    public function getStudentsBySectionId($id)
    {
        $students = Student::with('attendances')->where('section_id', $id)->get();
        return response()->json([
            'data' => AttendanceResource::collection($students)
        ], 200);
    }

    public function storeAttendance($request, $id)
    {
        try {
            $attendanceDate = date('Y-m-d');
            $section = Section::findOrFail($id);
            foreach ($request->attendance as $student_id => $attendance) {
                if ($attendance === 1) {
                    $attendance_status = true;
                } else if ($attendance === 0) {
                    $attendance_status = false;
                }
                if ($section->id == $request->section_id && $attendance !== null) {
                    $exist_attendance = Attendance::where('student_id', $student_id)->where('attendance_date', $attendanceDate)->first();
                    // ->where('grade_id', $section->grade->id)
                    // ->where('section_id', $id)
                    // ->where('classroom_id', $section->classroom->id)

                    if ($exist_attendance) {
                        if ($exist_attendance->attendance_status !== $attendance_status) {
                            $exist_attendance->attendance_status = $attendance_status;
                            $exist_attendance->save();
                        }
                    } else {
                        Attendance::updateOrCreate(
                            [
                                'student_id' => $student_id,
                                'attendance_date' => $attendanceDate
                            ],
                            [
                                'student_id' => $student_id,
                                'grade_id' => $section->grade->id,
                                'classroom_id' => $section->classroom->id,
                                'section_id' => $id,
                                'teacher_id' => auth()->user()->id,
                                'attendance_date' => $attendanceDate,
                                'attendance_status' => $attendance_status
                            ]
                        );
                    }
                }
            }
            return response()->json([
                'message' => 'Data Saved Successfully'
            ], 200);
        } catch (\Exception $e) {
            throw $e;
        }
        // return response(['dd' => $request->attendance]);
    }

    //getting students attendance report
    public function getAttendanceReport($request)
    {

        try {
            DB::setDefaultConnection('tenant');

            $request->validate([
                'section_id' => ['required', 'exists:sections,id'],
                'from' => ['required', 'date', 'date_format:Y-m-d'],
                'to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:from'],
            ], [
                'section_id.required' => 'The selected section is required.',
                'section_id.exists' => 'Please select section.'
            ]);
            $ids = DB::table('teacher_section')->where('teacher_id', auth()->user()->id)->pluck('section_id');
            $students  = Student::whereIn('section_id', $ids)->get();

            if ($ids->contains($request->section_id)) {
                if ($request->student_id == -1) {
                    if (isset($request->from) && isset($request->to)) {
                        $attendances = Attendance::where('section_id', $request->section_id)
                            ->whereBetween('attendance_date', [$request->from, $request->to])
                            ->get();
                    } else {
                        $attendances = Attendance::where('section_id', $request->section_id)->get();
                    }
                } else {
                    if (isset($request->from) && isset($request->to)) {
                        $attendances = Attendance::where('section_id', $request->section_id)
                            ->where('student_id', $request->student_id)
                            ->whereBetween('attendance_date', [$request->from, $request->to])
                            ->get();
                    } else {
                        $attendances = Attendance::where('section_id', $request->section_id)
                            ->where('student_id', $request->student_id)->get();
                    }
                }
                return response()->json(['data' => AttendanceReportResource::collection($attendances)]);
            } else {
                return response()->json(['message' => "You don't have access to this section"], 500);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
