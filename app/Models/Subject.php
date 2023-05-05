<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $fillable = ['name', 'grade_id', 'classroom_id', 'teacher_id'];
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}