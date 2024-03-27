<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TchiroTestController;
use App\Http\Controllers\User\ItemController;
use App\Http\Controllers\User\CartController;


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

Route::get('/', function () {
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
Route::prefix('items')->middleware('auth:users')->group(function () {
  Route::get('index', [ItemController::class, 'index'])->name('items.index');
  Route::post('index', [ItemController::class, 'index'])->name('items.index');
  Route::get('{item}', [ItemController::class, 'show'])->name('items.show');
});

//cart
Route::prefix('cart')->middleware('auth:users')->group(function () {
  Route::get('index', [CartController::class, 'index'])->name('cart.index');
  Route::post('add', [CartController::class, 'add'])->name('cart.add');
  Route::post('delete', [CartController::class, 'delete'])->name('cart.delete');
  Route::post('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
  Route::get('success', [CartController::class, 'success'])->name('cart.success');
  Route::get('cancel', [CartController::class, 'cancel'])->name('cart.cancel');
});
require __DIR__ . '/auth.php';
