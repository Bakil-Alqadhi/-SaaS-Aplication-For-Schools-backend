<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolController;
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

Route::get('/user', [AuthController::class, 'user']);
Route::delete('/user/logout', [AuthController::class, 'destroy']);


Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group( ['middleware' => 'auth:sanctum'],function(){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth:sanctum')->get('/get', function (Request $request) {
    return 'hi bakil';
});
Route::get('/me', function(Request $request){
//     $user = User::tokens()->
$token = $request->header('Authorization');

// The `$token` variable now contains the value of the `Authorization` header
// You can extract the token value from the header by removing the "Bearer " prefix:
$tokenValue = str_replace('Bearer ', '', $token);

// $tokenId = DB::table('personal_access_tokens')
//             ->where('token',  hash('sha256', $tokenValue))->value('id');
// $user = User::where($tokenId)->first();

$tokenModel = PersonalAccessToken::where('token',$tokenValue)->first();

return response()->json($tokenModel->name);
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

    Route::prefix('/schools')->group(function(){
        Route::get('', [SchoolController::class, 'index'])->name('schools');
        Route::prefix("/{school}")->group(function(){
            Route::get('', [SchoolController::class, 'show'])->name('show');

            //student routes
            Route::prefix('/students')->group(function(){
                //get all students
                Route::get('/', [StudentController::class, 'index'])->name('students');
                Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            });

            //test pagination
            Route::get('/pagination', [TeacherController::class, 'test'])->name('test');

            //teachers routes
            Route::prefix('/teachers')->group(function(){
                Route::get('', [TeacherController::class, 'index'])->name('teachers');
                Route::get('/{id}', [TeacherController::class, 'show'])->name('show');
            });
});
    });