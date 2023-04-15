<?php

namespace App\Providers;

use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\SectionRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use App\Repositories\GradeRepository;
use App\Repositories\SectionRepository;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
