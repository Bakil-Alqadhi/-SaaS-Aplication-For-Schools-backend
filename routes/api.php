<?php

use App\Events\DbSchoolConnected;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GraduatedController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Resources\StudentResource;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\School;
use App\Models\Student;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::get('/grades/data', [GradeController::class, 'gradeData'])->name('gradeData');

// Route::middleware(['cors'])->group(function(){
Route::get('/user', [AuthController::class, 'user']);

Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::middleware(['SetConnection'])->get('/teachers/who', function (Request $request) {
Route::get('/teachers/who', [TeacherController::class, 'who']);

//needed it in frontend reg. form and in the dashboard
Route::get('/grades/index', [GradeController::class, 'index'])->name('indexGrades');
//needed for sections and reg. student
Route::get('grades/data', [GradeController::class, 'gradeData'])->name('gradeData');


//Start Specializations
// Route::get('/specializations', [SpecializationController::class, 'index'])->name('getSpecializations');
//End Specializations


//routes for the director requests
// Route::middleware(['SetConnection'])->group(function () {

//Start directors routes
Route::middleware(['is-director'])->group(function () {

    Route::get('/waiting', [SchoolController::class, 'getWaiting'])->name('getWaiting');
    Route::post('/acceptNewMember/{id}', [SchoolController::class, 'newMember'])->name('newMember');

    //promotions
    Route::prefix('promotions')->group(function () {
        Route::get('', [PromotionController::class, 'index']);
        Route::post('', [PromotionController::class, 'store']);
        Route::delete('', [PromotionController::class, 'destroy']);
    });
    //graduate
    Route::prefix('graduate')->group(function () {
        Route::get('', [GraduatedController::class, 'index']);
        Route::post('', [GraduatedController::class, 'store']);
        Route::post('/{id}', [GraduatedController::class, 'storeStudent']);
        Route::put('/{id}', [GraduatedController::class, 'update']);
        Route::delete('', [GraduatedController::class, 'destroy']);
    });

    //Start Grades
    Route::prefix('grades')->group(function () {
        // Route::get('/index', [GradeController::class, 'index'])->name('getGrades');
        Route::post('/', [GradeController::class, 'store'])->name('storeGrade');
        Route::get('/{id}', [GradeController::class, 'show'])->name('showGrade');
        Route::put('/{grade}', [GradeController::class, 'update'])->name('updateGrade');
        Route::delete('/{id}', [GradeController::class, 'destroy'])->name('deleteGrade');
    });
    //End Grades

    //Start Classroom
    Route::prefix('classrooms')->group(function () {
        Route::get('/', [ClassroomController::class, 'index'])->name('indexClassrooms');
        Route::post('/', [ClassroomController::class, 'store'])->name('createClassroom');
        Route::get('/{id}', [ClassroomController::class, 'show'])->name('showClassroom');
        Route::put('/{id}', [ClassroomController::class, 'update'])->name('updateClassroom');
        Route::delete('/{id}', [ClassroomController::class, 'destroy'])->name('deleteGrade');

        //getting only students of a specific classroom
        Route::get('/{id}/students', [ClassroomController::class, 'studentsClassroom']);
    });
    //End Classroom

    //Start Sections
    Route::prefix('sections')->group(function () {
        Route::get('/', [SectionController::class, 'index'])->name('indexSections');
        Route::get('/{id}', [SectionController::class, 'show']);
        Route::post('/create', [SectionController::class, 'store'])->name('storeSection');
        Route::put('/{id}', [SectionController::class, 'update'])->name('updateSection');
        Route::delete('/{id}', [SectionController::class, 'destroy'])->name('deleteSection');

        //adding students to the section
        Route::post('add/students/to/{id}', [SectionController::class, 'addStudents']);

        //getting section's students
        Route::get('/{id}/students', [AttendanceController::class, 'show']);
    });
    //End Sections
    //Start Subject
    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index']);
        Route::get('/{id}', [SubjectController::class, 'show']);
        Route::post('/', [SubjectController::class, 'store']);
        Route::put('/{id}', [SubjectController::class, 'update']);
        Route::delete('/{id}', [SubjectController::class, 'destroy']);
    });
    //End Subject
    //Start Exam
    // Route::prefix('subjects')->group(function () {
    Route::resource('quizzes', QuizController::class);
    // });
    //End Exam
});

//End directors routes
/////////////////////////////////////////////////////////////
//teachers routes
Route::middleware(['is-teacher'])->group(function () {


    Route::resource('quizzes', QuestionController::class);


    Route::prefix('sections')->group(function () {
        //getting section's students
        // Route::get('sections/{id}/students', [SectionController::class, 'getSectionStudents']);
        Route::get('/{id}/students', [AttendanceController::class, 'show']);
        Route::post('/{id}/students/attendance', [AttendanceController::class, 'store']);
    });



    Route::get('/teacher/sections', [TeacherController::class, 'teacherSections']);
    Route::prefix('/teachers')->group(function () {
        // Route::get('', [TeacherController::class, 'index'])->name('indexTeachers');
        // Route::get('/{id}', [TeacherController::class, 'show'])->name('showTeacher');
        Route::put('/{id}', [TeacherController::class, 'update'])->name('updateTeacher');
    });

    // Route::prefix('/teachers')->group(function () {
    //     Route::put('/{id}', [TeacherController::class, 'update'])->name('updateTeacher');
    // });
    // Route::get('/teachers/who', function (Request $request) {
    //     return 'this is who method';
    // });
});


//End teachers routes
/////////////////////////////////////////////////////////////////
//Start Student routes
Route::middleware(['is-student'])->group(function () {
    Route::prefix('/students')->group(function () {
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroyStudent');
        Route::put('/{id}', [StudentController::class, 'update'])->name('updateStudent');
    });
});
//End Student routes

Route::prefix('/teachers')->group(function () {
    Route::get('', [TeacherController::class, 'index'])->name('teachers');
    Route::get('/{id}', [TeacherController::class, 'show'])->name('show');
});
/////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
//student routes
Route::prefix('/students')->group(function () {
    //get all students
    Route::get('/', [StudentController::class, 'index'])->name('students');
    Route::get('/{student}', [StudentController::class, 'show'])->name('show');
});
// });
// });



// Route::middleware(['auth:sanctum,student-api'])->get('/student', function (Request $request) {
//     return $request->user();
// });

// Route::middleware(['auth:sanctum,teacher-api'])->get('/teacher', function (Request $request) {
//     return $request->user();
// });



// Route::middleware(['auth', 'isDirector'])->group(function(){
//     Route::get('/waiting/{school}', [SchoolController::class, 'getWaiting'])->name('wait');
//     Route::post('/acceptNewMember', [SchoolController::class, 'newMember'])->name('newMember');
// });

Route::prefix('/schools')->group(function () {
    Route::get('', [SchoolController::class, 'index'])->name('schools');
    Route::prefix("/{school}")->group(function () {
        Route::get('', [SchoolController::class, 'show'])->name('show');

        //test pagination
        Route::get('/pagination', [TeacherController::class, 'test'])->name('test');

        //teachers routes
        // Route::prefix('/teachers')->group(function () {
        //     Route::get('', [TeacherController::class, 'index'])->name('teachers');
        //     Route::get('/{id}', [TeacherController::class, 'show'])->name('show');
        // });
    });
});


// Route::get('/teachers', [TeacherController::class, 'index'])->middleware(['auth:sanctum'])->name('teachers');