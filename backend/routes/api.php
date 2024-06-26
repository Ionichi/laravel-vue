<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TutorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function() {
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth:api')->group(function(){
    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index'])->name('get.siswa');
        Route::post('/create', [SiswaController::class, 'create'])->name('create.siswa');
        Route::get('/edit/{id}', [SiswaController::class, 'edit'])->name('edit.siswa');
        Route::post('/update', [SiswaController::class, 'update'])->name('update.siswa');
        Route::post('/delete/{id}', [SiswaController::class, 'destroy'])->name('delete.siswa');
    });

    Route::prefix('tutor')->group(function () {
        Route::get('/', [TutorController::class, 'index'])->name('get.tutor');
        Route::post('/create', [TutorController::class, 'create'])->name('create.tutor');
        Route::get('/edit/{id}', [TutorController::class, 'edit'])->name('edit.tutor');
        Route::post('/update', [TutorController::class, 'update'])->name('update.tutor'); 
        Route::post('/delete/{id}', [TutorController::class, 'destroy'])->name('delete.tutor');
    });

    Route::prefix('course')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('get.course');
        Route::get('/get-tutor', [CourseController::class, 'get_tutor'])->name('get.course.tutor');
        Route::post('/create', [CourseController::class, 'create'])->name('create.course');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit.course');
        Route::post('/update', [CourseController::class, 'update'])->name('update.course');
        Route::post('/delete/{id}', [CourseController::class, 'destroy'])->name('delete.course');
    });
    
});

Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });