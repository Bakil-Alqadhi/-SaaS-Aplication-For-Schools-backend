<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_first_name' => $this->student->first_name,
            'student_last_name' => $this->student->last_name,
            'section_name' => $this->section->name,
            'section_id' => $this->section->id,
            'grade_name' => $this->grade->name,
            'grade_id' => $this->grade->id,
            'classroom_id' => $this->classroom->id,
            'classroom_name' => $this->classroom->name,
            'attendance_date' => $this->attendance_date,
            'attendance_status' => $this->attendance_status
        ];
    }
}