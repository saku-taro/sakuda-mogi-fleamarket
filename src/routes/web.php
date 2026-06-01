<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


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

// --- メール認証関連ルート ---
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証リンククリック時の処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// メール再送信処理
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['check.profile'])->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('item.index');
    Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware(['check.profile'])->group(function () {
        Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
        Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

        Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');

        Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');

        Route::post('/item/{item_id}/like', [FavoriteController::class, 'store'])->name('like.store');
        Route::delete('/item/{item_id}/like', [FavoriteController::class, 'destroy'])->name('like.destroy');

        Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
        Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

        Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('purchase.address.edit');
        Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'update'])->name('purchase.address.update');
    });
});
