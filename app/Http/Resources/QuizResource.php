<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            'name' => $this->name,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject->name,
            'teacher_id' => $this->teacher->id,
            'teacher_first_name' => $this->teacher->first_name,
            'teacher_last_name' => $this->teacher->last_name,
            'grade_name' => $this->grade->name,
            'grade_id' => $this->grade->id,
            'classroom_name' => $this->classroom->name,
            'classroom_id' => $this->classroom->id,
            'section_name' => $this->section->name,
            'section_id' => $this->section->id,
        ];
    }
}
