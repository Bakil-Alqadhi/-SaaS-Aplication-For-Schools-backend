<?php

namespace App\Providers;

use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\ClassroomRepositoryInterface;
use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\GraduatedRepositoryInterface;
use App\Interfaces\SchoolRepositoryInterface;
use App\Interfaces\SectionRepositoryInterface;
use App\Interfaces\StudentPromotionRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use App\Repositories\AttendanceRepository;
use App\Repositories\AuthRepository;
use App\Repositories\ClassroomRepository;
use App\Repositories\ExamRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GraduatedRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SectionRepository;
use App\Repositories\StudentPromotionRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(SectionRepositoryInterface::class, SectionRepository::class);
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(SchoolRepositoryInterface::class, SchoolRepository::class);
        $this->app->bind(ClassroomRepositoryInterface::class, ClassroomRepository::class);
        $this->app->bind(StudentPromotionRepositoryInterface::class, StudentPromotionRepository::class);
        $this->app->bind(GraduatedRepositoryInterface::class, GraduatedRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
