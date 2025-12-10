<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\MailsController;
use Illuminate\Support\Facades\Route;

Route::middleware('log.request')->group(function () {

    Route::get('/', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');

    Route::get('/send-welcome-mail/{todo}', [MailsController::class, 'sendWelcomeMail'])->name('send-welcome-mail');

    Route::get('/todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
    Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::get('/todos/{todo}/toggle-status', [TodoController::class, 'toggleStatus'])->name('todos.toggle-status');

    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');

    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
});
