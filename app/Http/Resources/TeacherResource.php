<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'userType' => 'teacher',
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'image' => $this->image,
            'about'=> $this->about,
            'isLeader' => $this->isLeader,
            'isJoined' => $this->isJoined,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}