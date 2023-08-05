<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\RoleController;

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

Route::get('/', function () {
  return view('welcome');
})->name('home');
Route::get('/home', function () {
  return redirect(route('home'));
});
Route::view('/policy', 'policy')->name('policy');

Route::get('/dashboard', function () {
  $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
  return view('dashboard')->with(compact('settings'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('migrate', function () {
  Artisan::call('migrate');
  return redirect(route('dashboard'))->with('success', 'Database migration success.');
})->middleware(['auth'])->name('migrate');
Route::get('rollback', function () {
  Artisan::call('migrate:rollback');
  return redirect(route('dashboard'))->with('success', 'Database migrate:rollback success.');
})->middleware(['auth'])->name('rollback');
Route::get('seed', function () {
  Artisan::call('db:seed --class=RoleSeeder');
  return redirect(route('dashboard'))->with('success', 'php artisan db:seed --class=RoleSeeder success.');
})->middleware(['auth'])->name('seed');
Route::get('reset', function () {
  Artisan::call('route:cache');
  return redirect(route('dashboard'))->with('success', 'php artisan route:cache success');
})->middleware(['auth'])->name('reset');
Route::get('phpinfo', function () {
  return phpinfo();
})->middleware(['auth'])->name('phpinfo');


Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
  Route::resource('/roles', RoleController::class);
});
require __DIR__ . '/auth.php';
