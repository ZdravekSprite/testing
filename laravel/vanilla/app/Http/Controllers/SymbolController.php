<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SymbolController extends Controller
{
  public function exchangeInfo()
  {
    $exchangeInfo = json_decode(Http::get('https://api.binance.com/api/v3/exchangeInfo'));
    $symbols = $exchangeInfo->symbols;
    //dd('exchangeInfo', $symbols);
    foreach ($symbols as $symbol) {
      if (!Symbol::where('symbol', '=', $symbol->symbol)->first()) {
        //dd('exchangeInfo', $symbol);
        Symbol::create([
          'symbol' => $symbol->symbol,
          'status' => $symbol->status,
          'baseAsset' => $symbol->baseAsset,
          'baseAssetPrecision' => $symbol->baseAssetPrecision,
          'quoteAsset' => $symbol->quoteAsset,
          'quotePrecision' => $symbol->quotePrecision,
          'quoteAssetPrecision' => $symbol->quoteAssetPrecision,
          'icebergAllowed' => $symbol->icebergAllowed,
          'ocoAllowed' => $symbol->ocoAllowed,
          'isSpotTradingAllowed' => $symbol->isSpotTradingAllowed,
          'isMarginTradingAllowed' => $symbol->isMarginTradingAllowed,
          'tickSize' => strpos($symbol->filters[0]->tickSize,"1")-1,
          'stepSize' => strpos($symbol->filters[2]->stepSize,"1")-1
        ]);
      }
    }
    return Symbol::where('status', '=', 'TRADING')->get();
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $symbols = Symbol::all();
    if($symbols->count() == 0) {
      $symbols = $this->exchangeInfo();
      //dd($symbols->count());
    }
    $symbols_usdt = Symbol::where('status', '=', 'TRADING')
      ->where('baseAsset', '=', 'USDT')
      ->orWhere('quoteAsset', '=', 'USDT')
      ->get()
      ->pluck('symbol')
      ->toArray();
    $symbols_busd = Symbol::where('status', '=', 'TRADING')
      ->where('baseAsset', '=', 'BUSD')
      ->orWhere('quoteAsset', '=', 'BUSD')
      ->get()
      ->pluck('symbol')
      ->toArray();
    $symbols_bnbbtc = Symbol::where('status', '=', 'TRADING')
      ->where('baseAsset', '=', 'BNB')
      ->where('quoteAsset', '=', 'BTC')
      ->get()
      ->pluck('symbol')
      ->toArray();
    //dd(count($symbols_usdt),count($symbols_busd));
    $symbols = array_merge($symbols_usdt, $symbols_busd, $symbols_bnbbtc);
    $func = function($n) {
      return strtolower($n).'@kline_1m';
    };
    $link = implode('/', array_map($func, $symbols));
    //$link = implode('/', array_map(fn($n) => strtolower($n).'@kline_1m', $symbols));
    //dd($link,$symbols);
    return view('symbols.index')->with(compact('symbols', 'symbols_usdt', 'symbols_busd', 'link'));
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
