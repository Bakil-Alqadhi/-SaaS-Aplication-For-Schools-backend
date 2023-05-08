<?php

namespace App\Repositories;

use App\Http\Resources\AttendanceResource;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;

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
}
