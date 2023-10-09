<?php

use App\Http\Controllers\LoginController;
use App\Livewire\HomeComponent;
use App\Livewire\RolesIndex;
use App\Livewire\RolesCreate;
use App\Livewire\UsersIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('handleLogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


//Employee route
Route::group(['middleware'=>'auth:web'], function() {
    Route::get('/', HomeComponent::class)->name('home');

    Route::get('roles', RolesIndex::class)->name('roles.index');
    Route::get('roles/create', RolesCreate::class)->name('roles.create');

    Route::get('users', UsersIndex::class)->name('users.index');
});
