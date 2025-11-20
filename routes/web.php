<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- AÑADIDO
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ActividadController; // <-- AÑADIDO



Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('posts', PostController::class);
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('comments.store'); // <-- Ahora no tiene restricción
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');


Route::put('/comments/{comment}', [CommentController::class, 'update'])
    ->name('comments.update')
    ->middleware('auth');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy')
    ->middleware('auth');

Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
Route::resource('notas', NotaController::class);



Route::get('/notas/{nota}/actividades', [ActividadController::class, 'index'])
    ->name('actividades.index');
Route::post('/notas/{nota}/actividades', [ActividadController::class, 'store'])
    ->name('actividades.store');
Route::get('/actividades/{actividad}/edit', [ActividadController::class, 'edit'])
    ->name('actividades.edit');
Route::put('/actividades/{actividad}', [ActividadController::class, 'update'])
    ->name('actividades.update');
Route::delete('/actividades/{actividad}', [ActividadController::class, 'destroy'])
    ->name('actividades.destroy');
