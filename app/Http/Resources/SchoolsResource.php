<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'userType' => 'director',
            'director_name' => User::where('school_id', $this->id)->first()->name,
            'school_name' => $this->school_name,
            'address' => $this->address,
            'email' => User::where('school_id', $this->id)->first()->email,
            'phone' => $this->phone,
            'school_image'=> $this->school_image,
            'director_image' => $this->director_image,
            'about_school'=> $this->about_school,
            'about_director' => $this->about_director
        ];
    }
}