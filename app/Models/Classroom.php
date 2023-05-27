<?php

namespace App\Models;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function students(){
        return $this->hasMany(Student::class);
    }
}