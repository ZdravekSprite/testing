<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Binance;
use App\Http\Controllers\TestBinance;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PlatnaLista;
use App\Http\Controllers\SymbolController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\KlineController;
use App\Http\Controllers\LottoController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BSystem;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Day;
use App\Models\Settings;

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
  $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
  $month = Auth::user()->month % 12 + 1;
  $year = (Auth::user()->month - $month + 1) / 12;
  return view('dashboard')->with(compact('settings', 'month', 'year'));
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
//Route::resource('days', DayController::class);
Route::get('/days', [DayController::class, 'index'])->name('days.index');
Route::get('/day/create', [DayController::class, 'create'])->name('day.create');
Route::post('/day', [DayController::class, 'store'])->name('day.store');
Route::get('/day/{date}', [DayController::class, 'show'])->name('day.show');
Route::get('/day/edit/{date}', [DayController::class, 'edit'])->name('day.edit');
Route::post('/day/{date}', [DayController::class, 'update'])->name('day.update');
Route::delete('/day/{date}', [DayController::class, 'destroy'])->name('day.destroy');

Route::resource('holidays', HolidayController::class);

Route::get('/month', [DayController::class, 'month'])->name('month');
//Route::get('/platna', [MonthController::class, 'platna_lista'])->name('platna');
Route::resource('months', MonthController::class)->middleware(['auth']);
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

Route::get('/month/{month}', [DayController::class, 'month']);
Route::get('/days/create/{date}', [DayController::class, 'create']);
//Route::get('/lista', PlatnaLista::class)->name('lista');
Route::get('/lista', [MonthController::class, 'platna_lista'])->name('lista');
Route::get('/lista/{month}', [MonthController::class, 'platna_lista']);

//Route::put('/lista', [PlatnaLista::class, 'data']);
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

Route::get('convert', function () {
  /*
  Day::where('sick', 1)
    ->update(['state' => 4]);
  Day::where('go', 1)
    ->update(['state' => 2]);
  Day::where('dopust', 1)
    ->update(['state' => 3]);
  Day::where('state', 2)
    ->update(['end' => '0:00:00']);
    Day::where('state', 3)
    ->update(['end' => '0:00:00']);
    Day::where('state', 4)
    ->update(['end' => '0:00:00']);
  
  Day::where('state', 0)
  ->where('duration', '22:40:00')
  ->update(['state' => 1,'end' => '22:40:00']);
  
  $days = Day::all();
  foreach ($days as $day) {
    //echo $day->name;
    //if ($day->sick) $day->state = 4;
    //if ($day->go) $day->state = 2;
    //if ($day->dopust) $day->state = 3;
    if ($day->duration) $day->end = $day->duration;
    if ($day->duration && !$day->state) $day->state = 1;
    if ($day->night_duration) $day->night = $day->night_duration;
    $day->save;
    dd($day);
  }
  dd($days, Day::all());
  */
  return 'Database days converted.';
})->middleware(['auth'])->name('convert');

Route::get('admin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('admin.impersonate.stop');
Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function () {
  Route::resource('/users', UserController::class, ['except' => ['show', 'create', 'store']]);
  Route::get('/impersonate/{id}', [ImpersonateController::class, 'start'])->name('impersonate.start');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/messages', [ChatController::class, 'fetchAllMessages']);
Route::post('/messages', [ChatController::class, 'sendMessage']);

Route::resource('trades', TradeController::class);
Route::resource('symbols', SymbolController::class);
Route::get('/binance/allMyTrades', [TradeController::class, 'allMyTrades']);
Route::get('/dust', [TradeController::class, 'dustLog']);
Route::get('/binance/prosjek', [TradeController::class, 'prosjek']);
Route::resource('klines', KlineController::class);

Route::get('/binance/portfolio', [Binance::class, 'portfolio']);
//Route::get('/binance/chart', [Binance::class, 'chart']);
Route::get('/binance/chart/{coin?}', [Binance::class, 'chart']);
Route::get('/binance/orders', [Binance::class, 'orders']);
Route::get('/binance/dashboard', [Binance::class, 'dashboard'])->middleware(['auth']);
/*
Route::get('/binance/dashboard', function () {
  return view('/binance/dashboard');
})->middleware(['auth']);
*/
Route::post('/binance/order/test', [Binance::class, 'testNewOrder'])->name('testNewOrder');
Route::get('/binance/test', [TestBinance::class, 'test'])->name('bTest');
Route::get('/binance/crta', [TradeController::class, 'crta']);


Route::get('/binance/', function () {
  return view('binance.welcome');
})->name('bhome');
Route::get('/binance/exchange', [SymbolController::class, 'exchangeInfo'])->name('bExchange');
//Route::get('/binance/exchange/info/{symbol?}', [BSystem::class, 'exchangeInfo'])->name('bExchangeInfo');


Route::get('/lotto/hl', [LottoController::class, 'hl']);
Route::resource('lotto', LottoController::class);
