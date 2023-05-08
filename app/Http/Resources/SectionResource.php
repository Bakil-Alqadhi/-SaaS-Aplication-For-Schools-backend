<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $teachers = array();
        foreach($this->teachers as $teacher) {
            $teachers[] = ['id' =>  $teacher->id,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name];
        }
        $students = Student::where('isJoined', '!=', '0')->where('section_id', $this->id)->get();
        return [
            'id' => $this->id,
            'section_name' => $this->name,
            'grade_id' => $this->grade->id,
            'grade_name' => $this->grade->name,
            'classroom_id' => $this->classroom->id,
            'classroom_name' => $this->classroom->name,
            'teachers' => $teachers,
            // 'teachers' => $this->teachers,
            'students' => $students
        ];
    }
}
