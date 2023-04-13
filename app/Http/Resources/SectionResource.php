<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'section_name' => $this->name,
            'grade_id' => $this->grade->id,
            'grade_name' => $this->grade->name,
            'classroom_id' => $this->classroom->id,
            'classroom_name' => $this->classroom->name,
        ];
    }
}