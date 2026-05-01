<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileEditController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProfileEditController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile', [ProfileEditController::class, 'edit']);
    Route::post('/mypage/update', [ProfileEditController::class, 'update']);
});
