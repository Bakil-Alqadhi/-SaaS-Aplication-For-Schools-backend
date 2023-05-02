<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ParentStudent;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Classroom;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $connection = 'tenant';
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'sex', 'birthday', 'image', 'isJoined', 'address', 'phone', 'email', 'academic_year', 'parent_id', 'grade_id', 'password'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //student and parent
    public function parent()
    {
        return $this->belongsTo(ParentStudent::class);
    }

    //student and grade
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    //student and classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    //student and section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    //Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}