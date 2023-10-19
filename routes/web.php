<?php

use App\Http\Controllers\LoginController;
use App\Livewire\CartDetail;
use App\Livewire\CategoriesCreate;
use App\Livewire\CategoriesIndex;
use App\Livewire\GroupsCreate;
use App\Livewire\GroupsIndex;
use App\Livewire\HomeComponent;
use App\Livewire\PackagesCreate;
use App\Livewire\PackagesIndex;
use App\Livewire\ProductsCreate;
use App\Livewire\ProductsIndex;
use App\Livewire\RolesIndex;
use App\Livewire\RolesCreate;
use App\Livewire\SchedulesCreate;
use App\Livewire\SchedulesEdit;
use App\Livewire\SchedulesIndex;
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

    Route::get('packages', PackagesIndex::class)->name('packages.index');
    Route::get('packages/create', PackagesCreate::class)->name('packages.create');

    Route::get('groups', GroupsIndex::class)->name('groups.index');
    Route::get('groups/create', GroupsCreate::class)->name('groups.create');

    Route::get('categories', CategoriesIndex::class)->name('categories.index');
    Route::get('categories/create', CategoriesCreate::class)->name('categories.create');

    Route::get('products', ProductsIndex::class)->name('products.index');
    Route::get('products/create', ProductsCreate::class)->name('products.create');

    Route::get('schedules', SchedulesIndex::class)->name('schedules.index');
    Route::get('schedules/create', SchedulesCreate::class)->name('schedules.create');
    Route::get('schedules/edit/{id}', SchedulesEdit::class)->name('schedules.edit');

    Route::get('cart', CartDetail::class)->name('cart.detail');
});
