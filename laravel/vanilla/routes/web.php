<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PlatnaLista;
use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

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

Route::get('login/{provider}', function ($provider) {
  return Socialite::driver($provider)->redirect();
})->name('{provider}Login');
Route::get('login/{provider}/callback', function ($provider) {
  $social_user = Socialite::driver($provider)->user();
  // $user->token
  $user = User::firstOrCreate([
    'email' => $social_user->getEmail(),
  ]);
  if (!$user->name) {
    $user->name = $social_user->getName();
  }
  if (!$user[$provider . "_id"]) {
    $user[$provider . "_id"] = $social_user->getId();
  }
  if ($social_user->getAvatar()) {
    if (!$user->avatar) {
      $user->avatar = $social_user->getAvatar();
    }
    if (!$user[$provider . "_avatar"]) {
      $user[$provider . "_avatar"] = $social_user->getAvatar();
    }
  }
  if (!$user->roles->pluck('name')->contains('socialuser')) {
    $socialUserRole = Role::where('name', 'socialuser')->first();
    $user->roles()->attach($socialUserRole);
  }
  $user->save();
  Auth::Login($user, true);
  return redirect(route('home'));
})->name('{provider}Callback');
/*
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}Login');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('{provider}Callback');
*/
Route::resource('days', DayController::class);
Route::resource('holidays', HolidayController::class);
Route::get('/month', [DayController::class, 'month'])->name('month');
Route::get('/month/{month}', [DayController::class, 'month']);
Route::get('/days/create/{date}', [DayController::class, 'create']);
Route::get('/lista', PlatnaLista::class)->name('lista');
Route::put('/lista', [PlatnaLista::class, 'data']);
Route::put('/sick/{date}', [DayController::class, 'sick'])->name('sick');
Route::get('migrate', function () {
  Artisan::call('migrate');
  return 'Database migration success.';
})->middleware(['auth'])->name('migrate');
Route::get('rollback', function () {
  Artisan::call('migrate:rollback');
  return 'Database migrate:rollback success.';
})->middleware(['auth'])->name('rollback');
Route::get('seed', function () {
  Artisan::call('db:seed --class=RoleSeeder');
  return 'php artisan db:seed --class=RoleSeeder success.';
})->middleware(['auth'])->name('seed');

Route::get('lista', function () {
  /*
  https://api.hnb.hr/tecajn/v1
    ?datum=2014-03-02
    ?valuta=EUR
  https://api.hnb.hr/tecajn/v2
    ?datum-primjene=2019-03-02
    ?valuta=EUR
  */
  $response = Http::get('https://api.hnb.hr/tecajn/v2?valuta=EUR');
  $data = $response->json();
  dd($data);
  return 'Database migrate:rollback success.';
})->middleware(['auth'])->name('binance');
Route::get('binance', function () {
  //https://api.binance.com/api/v3/exchangeInfo
  $response = Http::get('https://api.binance.com/api/v3/trades?symbol=ETHUSDT&limit=10');
  $data = $response->json();
  dd($data);
  return 'Database migrate:rollback success.';
})->middleware(['auth'])->name('binance');

Route::get('admin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('admin.impersonate.stop');
Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function () {
  Route::resource('/users', UserController::class, ['except' => ['show', 'create', 'store']]);
  Route::get('/impersonate/{id}', [ImpersonateController::class, 'start'])->name('impersonate.start');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/messages', [ChatController::class, 'fetchAllMessages']);
Route::post('/messages', [ChatController::class, 'sendMessage']);