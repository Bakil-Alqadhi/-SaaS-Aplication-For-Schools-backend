<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'student_first_name' => $this->first_name,
            'student_last_name' => $this->last_name,
            'gender' => $this->sex,
            // 'teacher_id' => $this->teacher_id,
            'section_name' => $this->section->name,
            'section_id' => $this->section->id,
            'grade_name' => $this->grade->name,
            'grade_id' => $this->grade->id,
            'classroom_id' => $this->classroom->id,
            'classroom_name' => $this->classroom->name,
            'attendances' => $this->attendances
        ];
    }
}
