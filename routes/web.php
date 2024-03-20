<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TchiroTestController;
use App\Http\Controllers\User\ItemController;


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
//練習
Route::prefix('/tchiro-test')->as('tchirotest.')->controller(TchiroTestController::class)->group(function () {
  Route::get('/', 'index')->name('index');
  Route::get('/show/{id}', 'show')->name('show');
  Route::get('/create', 'create')->name('create');
  Route::post('/', 'store')->name('store');
  Route::delete('/{id}', 'delete')->name('delete');
});
//Controllerなしのルート。viewファイルをただリターンする。
// Route::get('/tchiro-test/test', function () {
//     return Inertia::render('TchiroTest/InertiaTest');
// });

Route::get('/', function () {
  return Inertia::render('User/Welcome', [
    'canLogin' => Route::has('user.login'),
    'canRegister' => Route::has('user.register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::get('/dashboard', function () {
  return Inertia::render('User/Dashboard');
})->middleware(['auth:users', 'verified'])->name('dashboard');

Route::get('/tchiro-dashboard', function () {
  return Inertia::render('User/TchiroDashboard', [
    'canLogin' => Route::has('user.login'),
    'canRegister' => Route::has('user.register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});


Route::middleware('auth:users')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//shop関連
Route::middleware('auth:users')->group(function () {
  Route::get('/', [ItemController::class, 'index'])->name('items.index');
  Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
});

require __DIR__ . '/auth.php';
