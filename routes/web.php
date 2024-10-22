<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\UserImportController;


Route::get('/', function () {
    return view('welcome');
});

// Update this route to call the index method of UserController
Route::get('/TMS', [UserController::class, 'index'])->name('tms.index');

Route::post('/import-users', [UserImportController::class, 'import'])->name('import.users');
Route::post('/users/add', [UserController::class, 'store'])->name('add.user'); 
Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');

Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');


Route::get('/get-time-logged/{userId}', [UserController::class, 'getTimeLogged'])->name('get.time.logged');



