<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Grade;

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
}