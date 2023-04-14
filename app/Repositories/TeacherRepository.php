<?php

namespace App\Repositories;
use App\Http\Resources\TeacherResource;
use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;

class TeacherRepository implements TeacherRepositoryInterface
{
    //get all teachers
    public function getAllTeachers(){
        return TeacherResource::collection(resource: Teacher::where('isJoined', true)->latest()->get());
    }
}
