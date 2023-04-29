<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Student;

class Section extends Model
{
    use HasFactory;
    protected $connection= 'tenant';
    protected $fillable = ['name', 'status', 'grade_id', 'classroom_id'];
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    //relationship between teacher and section (many to many)
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_section');
    }

    public function students(){
        return $this->hasMany(Student::class);
    }
}