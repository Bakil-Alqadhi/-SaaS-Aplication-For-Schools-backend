<?php

namespace App\Http\Resources;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $students = Student::where('isJoined', '!=', '0')->where('classroom_id', $this->id)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'grade' => $this->grade->name,
            'grade_id' => $this->grade->id,
            'sections' => $this->sections,
            'students' => $students
            //or
            //'grade' => $this->Grade::findOrFail($this->grade_id)->name
        ];
    }
}
