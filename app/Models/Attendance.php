<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Attendance extends Model
{
    use HasFactory;
    protected $connection = 'tenant';


    protected $fillable = ['student_id', 'grade_id', 'classroom_id', 'section_id', 'teacher_id', 'attendance_date', 'attendance_status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
