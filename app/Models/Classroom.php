<?php

namespace App\Models;

use App\Models\Grade;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{

    protected $connection = 'tenant';

    protected $fillable = ['name', 'grade_id'];
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}