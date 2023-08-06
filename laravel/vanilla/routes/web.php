<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DrawController;
use App\Http\Controllers\LottoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Holiday;
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
Route::get('/home', function () {
  return redirect(route('home'));
});
Route::view('/policy', 'policy')->name('policy');

Route::get('/dashboard', function () {
  $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
  //dd($settings);
  return view('dashboard')->with(compact('settings'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('login/{provider}', function ($provider) {
  //dd($provider);
  return Socialite::driver($provider)->redirect();
})->name('{provider}Login');
Route::get('login/{provider}/callback', function ($provider) {
  //dd($provider);
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
    if ($provider == 'facebook') {
      $url = $social_user->getAvatar() . '&access_token=' . $social_user->token;
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

      $res = curl_exec($ch);
      $redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
      //$avatar = ltrim($redirectedUrl,"https://");
      $avatar = $redirectedUrl;
    } else {
      $avatar = $social_user->getAvatar();
    }
    if (!$user->avatar) {
      $user->avatar = $avatar;
    }
    if (!$user[$provider . "_avatar"]) {
      $user[$provider . "_avatar"] = $avatar;
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
  return redirect(route('dashboard'))->with('success', 'reset success');
})->middleware(['auth'])->name('reset');
Route::get('phpinfo', function () {
  return phpinfo();
})->middleware(['auth'])->name('phpinfo');

require __DIR__ . '/ers.php';

Route::get('admin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('admin.impersonate.stop');
Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function () {
  Route::resource('/users', UserController::class, ['except' => ['show', 'create', 'store']]);
  Route::get('/impersonate/{id}', [ImpersonateController::class, 'start'])->name('impersonate.start');
  Route::resource('/roles', RoleController::class);

  Route::get('/export/days', [ExportController::class, 'days'])->name('export.days');
  Route::get('/export/draws', [ExportController::class, 'draws'])->name('export.draws');
  Route::get('/export/holidays', [ExportController::class, 'holidays'])->name('export.holidays');
  Route::get('/export/months', [ExportController::class, 'months'])->name('export.months');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/messages', [ChatController::class, 'fetchAllMessages']);
Route::post('/messages', [ChatController::class, 'sendMessage']);

require __DIR__ . '/binance.php';

Route::get('/lotto/hl', [LottoController::class, 'hl']);
Route::get('/lotto/eurojackpot', [LottoController::class, 'eurojackpot'])->name('eurojackpot');
Route::resource('draws', DrawController::class);

Route::get('/help', function () {
  return view('help.index');
})->name('help');
Route::get('/help/bruto', function () {
  return view('help.bruto');
})->name('bruto');
Route::get('/help/fond', function () {
  $holidays = Holiday::orderBy('date', 'desc')->get();
  return view('help.fond')->with('blagdani', $holidays);
})->name('fond');

Route::resource('articles', ArticleController::class);
Route::resource('routes', RouteController::class);
Route::resource('signs', SignController::class);
Route::get('/gif/{sign}', [SignController::class, 'gif']);
Route::get('/svg/{sign}', [SignController::class, 'svg']);
