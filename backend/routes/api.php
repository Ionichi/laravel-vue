<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use Illuminate\Http\Request;
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
    Route::post('logout', 'logout')->name('logout');
});

Route::prefix('siswa')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('get.siswa');
    Route::get('/edit/{id}', [SiswaController::class, 'edit'])->name('edit.siswa');
})->middleware('auth:api');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
