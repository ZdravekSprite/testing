<?php

use App\Http\Controllers\BinanceController;
use Illuminate\Support\Facades\Route;

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
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

//Route::resource('binance', BinanceController::class)->middleware(['auth']);
Route::get('/binance', [BinanceController::class, 'index'])->name('binance.index');
Route::get('/binance/create', [BinanceController::class, 'create'])->name('binance.create');
Route::post('/binance', [BinanceController::class, 'store'])->name('binance.store');
Route::get('/binance/show', [BinanceController::class, 'show'])->name('binance.show');
Route::get('/binance/edit', [BinanceController::class, 'edit'])->name('binance.edit');
Route::put('/binance', [BinanceController::class, 'update'])->name('binance.update');
Route::get('/binance/test', [BinanceController::class, 'test'])->name('binance.test');
