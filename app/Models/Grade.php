<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Section;
use App\Models\Student;

class Grade extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $fillable = ['name', 'number'];

    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }
    public function sections(){
        return $this->hasMany(Section::class);
    }

    //section has many students
    public function students(){
        return $this->hasMany(Student::class);
    }
}