<?php

use App\Models\User;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::prefix('api')->apiResource('holidays', HolidayController::class);
//Route::middleware('auth:api')->get('holidays', function () {
// Only authenticated users may enter...
//})->middleware('auth');

Route::get('/holidays', [HolidayController::class, 'index']);
Route::get('/holidays/{holiday}', [HolidayController::class, 'show']);

Route::middleware(['auth.admin', 'auth:sanctum'])->group(function () {
  Route::post('/holidays', [HolidayController::class, 'store']);
  Route::put('/holidays/{holiday}', [HolidayController::class, 'update']);
  Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy']);
});

Route::get('/routes', [RouteController::class, 'index']);
Route::get('/routes/{route}', [RouteController::class, 'show']);
Route::post('/routes', [RouteController::class, 'store']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('/sanctum/token', function (Request $request) {
  $request->validate([
    'email' => 'required|email',
    'password' => 'required',
    'device_name' => 'required',
  ]);

  $user = User::where('email', $request->email)->first();

  if (!$user || !Hash::check($request->password, $user->password)) {
    throw ValidationException::withMessages([
      'email' => ['The provided credentials are incorrect.'],
    ]);
  }

  return $user->createToken($request->device_name)->plainTextToken;
});
