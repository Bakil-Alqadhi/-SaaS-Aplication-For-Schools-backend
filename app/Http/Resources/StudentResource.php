<?php

namespace App\Http\Resources;

use App\Models\ParentStudent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parent = ParentStudent::findOrFail($this->parent_id);
        return [
            'id' => $this->id,
            'userType' => 'student',
            'student_first_name' => $this->first_name,
            'student_middle_name' => $this->middle_name,
            'student_last_name' => $this->last_name,
            'image' => $this->image,
            'sex' => $this->sex,
            'birthday' => $this->birthday,
            'address'=> $this->address,
            'grade_id' => $this->grade_id,
            'classroom_id' => $this->classroom_id,
            'section_id' => $this->section_id,
            'academic_year' => $this->academic_year,
            'student_email' => $this->email,
            'student_phone' => $this->phone,
            'isJoined' => $this->isJoined,
            'parent_first_name' => $parent->first_name,
            'parent_last_name' => $parent->last_name,
            'parent_email' => $parent->email,
            'parent_phone' => $parent->phone
        ];
    }
}