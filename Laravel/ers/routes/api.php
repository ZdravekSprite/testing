<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DayController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/days', [DayController::class, 'index']);
Route::prefix('/day')->group(function () {
  Route::post('/store', [DayController::class, 'store']);
  Route::put('/{id}', [DayController::class, 'update']);
  Route::delete('/{id}', [DayController::class, 'destroy']);
});
