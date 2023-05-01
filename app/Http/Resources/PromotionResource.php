<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
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
            'student_id' => $this->student_id,
            'student_name' => $this->student->last_name . ' ' . $this->student->first_name,
            'from_grade' => $this->f_grade->name,
            'from_classroom' => $this->f_classroom->name,
            'from_academic_year' => $this->from_academic_year,
            'from_section' => $this->f_section->name,
            'to_grade' => $this->t_grade->name,
            'to_classroom' => $this->t_classroom->name,
            'to_section' => $this->t_section->name,
            'to_academic_year' => $this->to_academic_year,

        ];
    }
}