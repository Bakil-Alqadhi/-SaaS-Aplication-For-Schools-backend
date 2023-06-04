<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = ['quiz_id', 'student_id', 'question_id', 'score', 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}