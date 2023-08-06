<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\MonthController;

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

  Route::get('/days', [DayController::class, 'index'])
    ->name('days.index');
  Route::get('/days/create/{date}', [DayController::class, 'create']);

  Route::get('/day/create', [DayController::class, 'create'])->name('day.create');
  Route::post('/day', [DayController::class, 'store'])->name('day.store');
  Route::get('/day/{date}', [DayController::class, 'show'])->name('day.show');
  Route::get('/day/edit/{date}', [DayController::class, 'edit'])->name('day.edit');
  Route::post('/day/{date}', [DayController::class, 'update'])->name('day.update');
  Route::delete('/day/{date}', [DayController::class, 'destroy'])->name('day.destroy');

  Route::put('/sick/{date}', [DayController::class, 'sick'])->name('sick');

  Route::get('/month', [DayController::class, 'month'])->name('month');

  Route::get('/month/{month}', [DayController::class, 'month']);
  Route::get('/month/print/{month}', [DayController::class, 'print'])->name('print');

  Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

  Route::resource('months', MonthController::class);
});

Route::resource('holidays', HolidayController::class);

Route::get('admin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('admin.impersonate.stop');

Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function () {
  Route::get('/impersonate/{id}', [ImpersonateController::class, 'start'])->name('impersonate.start');
  Route::resource('/users', UserController::class, ['except' => ['show', 'create', 'store']]);
  Route::resource('/roles', RoleController::class);

  Route::get('/export/days', [ExportController::class, 'days'])->name('export.days');
  Route::get('/export/draws', [ExportController::class, 'draws'])->name('export.draws');
  Route::get('/export/holidays', [ExportController::class, 'holidays'])->name('export.holidays');
  Route::get('/export/months', [ExportController::class, 'months'])->name('export.months');
});
require __DIR__ . '/auth.php';
