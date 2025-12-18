<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PublicPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPostController::class, 'index'])->name('home');
Route::get('/posts/{slug}', [PublicPostController::class, 'show'])->name('posts.show');
Route::get('/categories/{slug}', [PublicPostController::class, 'category'])->name('categories.show');
Route::post('/posts/{slug}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

Route::get('dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
