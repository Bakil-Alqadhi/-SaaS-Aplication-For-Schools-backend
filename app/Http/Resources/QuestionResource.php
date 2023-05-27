<?php

namespace App\Http\Resources;

use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $answers = explode("\n", $this->answers);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'answers' => $answers,
            'right_answer' => $this->right_answer,
            'score' => $this->score,
            // 'quiz_name' => $this->quiz->name,
            'quiz_id' => $this->quiz_id,
            // 'teacher_id'=> $this->teacher_id,
            // 'teacher_first_name' => $this->grateacher->first_name,
            // 'teacher_last_name' => $this->teacher->last_name,
        ];
    }
}