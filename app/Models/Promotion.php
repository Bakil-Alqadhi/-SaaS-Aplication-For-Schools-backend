<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = ['student_id', 'from_grade', 'from_classroom', 'from_section', 'to_grade', 'to_classroom', 'to_section'];
}