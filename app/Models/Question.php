<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = ['title', 'title', 'answers', 'right_answer', 'score', 'quiz_id'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
