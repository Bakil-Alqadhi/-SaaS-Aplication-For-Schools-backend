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
        $totalScore = null;
        if(auth()->user()->userType === 'student'){
            if( $this->degrees->count() > 0 ) {
                foreach($this->degrees as $index => $degree){
                    if(auth()->user()->id == $degree->student_id){
                        // if($index == 0){
                        //     $totalScore = 0;
                        //     $totalScore += $degree->score;
                        // } else {
                            $totalScore += $degree->score;
                        // }
                    }
                }
            }
            // if($totalScore != -1){
            //     return [
            //         'id' => $this->id,
            //         'name' => $this->name,
            //         'subject_id' => $this->subject_id,
            //         'subject_name' => $this->subject->name,
            //         'totalScore' =>  auth()->user()->userType != 'student' ? null: $totalScore,
            //         'questions' => $this->questions->count() > 0? true: false
            //     ];
            // }
        }

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
            'totalScore' =>  auth()->user()->userType != 'student' ? null: $totalScore,
            'questions' => $this->questions->count() > 0? true: false
        ];
    }
}