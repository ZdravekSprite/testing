<?php

use App\Http\Controllers\Binance;
use App\Http\Controllers\TestBinance;
use App\Http\Controllers\KlineController;
use App\Http\Controllers\SymbolController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\BSystem;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
  Route::get('/binance', function () {
    return view('binance.welcome');
  })->name('bHome');

  Route::resource('symbols', SymbolController::class);
  Route::get('/binance/exchange', [SymbolController::class, 'exchangeInfo'])
    ->name('bExchange');

  Route::resource('trades', TradeController::class);
  Route::get('/binance/allMyTrades', [TradeController::class, 'allMyTrades'])
    ->name('allMyTrades');
  Route::get('/binance/dust', [TradeController::class, 'dustLog'])
    ->name('dustLog');
  Route::get('/binance/prosjek', [TradeController::class, 'prosjek']);
  Route::get('/binance/crta', [TradeController::class, 'crta']);

  Route::resource('klines', KlineController::class);

  Route::get('/binance/portfolio', [Binance::class, 'portfolio'])
    ->name('bPortfolio');
  Route::get('/binance/chart/{coin?}', [Binance::class, 'chart']);
  Route::get('/binance/orders', [Binance::class, 'orders']);
  Route::get('/binance/dashboard', [Binance::class, 'dashboard']);
  Route::post('/binance/order/test', [Binance::class, 'testNewOrder'])
    ->name('testNewOrder');
  Route::post('/binance/dustTransfer', [Binance::class, 'dustTransfer'])
    ->name('dustTransfer');

  Route::get('/binance/test', [TestBinance::class, 'test'])
    ->name('bTest');

  Route::get('/binance/getInterestHistory', [Binance::class, 'getInterestHistory'])
    ->name('bInterestHistory');
  Route::get('/binance/getPurchaseRecord', [Binance::class, 'getPurchaseRecord'])
    ->name('bPurchaseRecord');
  Route::get('/binance/getFixedAndActivityProjectList', [Binance::class, 'getFixedAndActivityProjectList'])
    ->name('bFixedAndActivityProjectList');
    Route::get('/binance/getProjectPosition', [Binance::class, 'getProjectPosition'])
    ->name('bProjectPosition');
  //Route::get('/binance/exchange/info/{symbol?}', [BSystem::class, 'exchangeInfo'])->name('bExchangeInfo');
});
