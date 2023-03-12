<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable= ['first_name', 'last_name', 'phone', 'email'];
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
