<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DegreeResource extends JsonResource
{
    private $date = null;
    private $totalScore = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        foreach($this['degrees'] as $degree){
            $this->totalScore += $degree->score;
            $this->date = $degree->date;
        }
        // $this->date =
        return [
            'id' => $this->id,
            'student_first_name' => $this->first_name,
            'student_middle_name' => $this->middle_name,
            'student_last_name' => $this->last_name,
            'date' => $this->date,
            'totalScore' => $this->totalScore
        ];
    }
}