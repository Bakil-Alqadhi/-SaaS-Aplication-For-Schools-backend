<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'grade_name' => $this->grade->name,
            'grade_id' => $this->grade_id,
            'classroom_name' => $this->classroom->name,
            'classroom_id' => $this->classroom_id,
            'teacher_id' => $this->teacher_id,
            'teacher_first_name' => $this->teacher->first_name,
            'teacher_last_name' => $this->teacher->last_name,
        ];
    }
}
