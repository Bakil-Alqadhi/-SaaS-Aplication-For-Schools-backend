<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

// Route::middleware('auth:sanctum, teacher')->get('/user', function (Request $request) {
//     //$guard = $request->header('guard', 'web');
//     // return Auth::guard($guard)->user();
//     return Auth::user();
//     // return ;

// });

Route::middleware(['IsDirector', 'auth:sanctum'])->get( 'gettt',function(){
    return 'dddd';
});

Route::get('/user', [AuthController::class, 'user']);

Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//routes for the director requests
Route::middleware('auth:sanctum')->group(function(){

    //teachers routes
    Route::prefix('/teachers')->group(function () {
        Route::get('', [TeacherController::class, 'index'])->name('teachers');
        Route::get('/{id}', [TeacherController::class, 'show'])->name('show');
    });

    //student routes
    Route::prefix('/students')->group(function () {
        //get all students
        Route::get('/', [StudentController::class, 'index'])->name('students');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
    });

    Route::get('/waiting', [SchoolController::class, 'getWaiting'])->name('wait');
    Route::post('/acceptNewMember/{id}', [SchoolController::class, 'newMember'])->name('newMember');
});




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
