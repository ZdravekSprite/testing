<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PlatnaLista;
use Illuminate\Support\Facades\Artisan;

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

Route::get('/', function () {
  return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::resource('days', DayController::class);
Route::resource('holidays', HolidayController::class);
Route::get('/month', [DayController::class, 'month'])->name('month');
Route::get('/month/{month}', [DayController::class, 'month']);
Route::get('/days/create/{date}', [DayController::class, 'create']);
Route::get('/lista', PlatnaLista::class)->name('lista');
Route::put('/lista', [PlatnaLista::class, 'data']);
Route::get('migrate', function () {
  Artisan::call('migrate');
  return 'Database migration success.';
})->middleware(['auth'])->name('migrate');
Route::get('rollback', function () {
  Artisan::call('migrate:rollback');
  return 'Database migrate:rollback success.';
})->middleware(['auth'])->name('rollback');