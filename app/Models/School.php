<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = ['school_name','domain', 'database_options', 'school_image', 'director_image','address', 'phone', 'about_school', 'about_director'];

    protected $casts = [
        'database_options' => 'array',
    ];
}
