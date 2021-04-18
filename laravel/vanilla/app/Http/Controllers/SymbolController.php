<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SymbolController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function exchangeInfo()
  {
    $exchangeInfo = json_decode(Http::get('https://api.binance.com/api/v3/exchangeInfo'));
    $symbols = $exchangeInfo->symbols;
    foreach ($symbols as $key => $value) {
      if (!Symbol::where('symbol', '=', $value->symbol)) {
        Symbol::create([
          'symbol' => $value->symbol,
          'status' => $value->status,
          'baseAsset' => $value->baseAsset,
          'baseAssetPrecision' => $value->baseAssetPrecision,
          'quoteAsset' => $value->quoteAsset,
          'quotePrecision' => $value->quotePrecision,
          'quoteAssetPrecision' => $value->quoteAssetPrecision,
          'icebergAllowed' => $value->icebergAllowed,
          'ocoAllowed' => $value->ocoAllowed,
          'isSpotTradingAllowed' => $value->isSpotTradingAllowed,
          'isMarginTradingAllowed' => $value->isMarginTradingAllowed
        ]);
      }
    }
    return Symbol::where('status', '=', 'TRADING')->get();
  }
  public function getAccountSnapshot()
  {
    $test = false;

    if ($test) {
      $server = 'https://testnet.binance.vision/api';
      $ws = 'wss://testnet.binance.vision/ws';
      $stream = 'wss://testnet.binance.vision/stream';
      $apiKey = env('BINANCE_TEST_API_KEY');
      $apiSecret = env('BINANCE_TEST_API_SECRET');
    } else {
      $server = 'https://api.binance.com/api';
      $ws = 'wss://stream.binance.com:9443/ws';
      $stream = 'wss://stream.binance.com:9443/stream';
      $apiKey = Auth::user()->BINANCE_API_KEY; //env('BINANCE_API_KEY');
      $apiSecret = Auth::user()->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
    }

    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    $timeStamp = 'timestamp=' . $serverTime;
    $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
    $account = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/account', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    $openOrders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/openOrders', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    $balances = [];
    foreach ($account->balances as $key => $crypto) {
      //dd($crypto);
      $total = $crypto->free + $crypto->locked;
      if ($total > 0) {
        $balances[] = $crypto;
      }
    }
    foreach ($openOrders as $key => $order) {
      dd($order);
    }
    return $balances;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Symbol  $symbol
   * @return \Illuminate\Http\Response
   */
  public function show(Symbol $symbol)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Symbol  $symbol
   * @return \Illuminate\Http\Response
   */
  public function edit(Symbol $symbol)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Symbol  $symbol
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Symbol $symbol)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Symbol  $symbol
   * @return \Illuminate\Http\Response
   */
  public function destroy(Symbol $symbol)
  {
    //
  }
}
