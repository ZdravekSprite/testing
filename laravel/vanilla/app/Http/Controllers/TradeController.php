<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function allMyTrades()
  {
    set_time_limit(0);
    $allTrades = [];
    foreach (Symbol::all() as $key => $symbol) {
      $myTrades = $this->myTrades($symbol->symbol);
      if ($myTrades) {
        //dd($myTrades);
        $allTrades[] = $myTrades;
      }
    }
    return $allTrades;
  }
  public function myTrades($symbol)
  {
    $server = 'https://api.binance.com/api';
    $apiKey = Auth::user()->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = array(
      "symbol" => $symbol,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $getArray = $queryArray + $signatureArray;
    $myTrades = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/myTrades', $getArray));
    foreach ($myTrades as $key => $myTrade) {
      //dd($myTrade);
      //dd(Trade::where('binanceId', '=', $myTrade->id)->count());
      if (Trade::where('binanceId', '=', $myTrade->id)->count() == 0) {
        //dd($myTrade);
        $trade = new Trade;
        $trade->user_id = Auth::user()->id;
        $trade->symbol = $myTrade->symbol;
        $trade->binanceId = $myTrade->id;
        $trade->orderId = $myTrade->orderId;
        $trade->orderListId = $myTrade->orderListId;
        $trade->price = $myTrade->price;
        $trade->qty = $myTrade->qty;
        $trade->quoteQty = $myTrade->quoteQty;
        $trade->commission = $myTrade->commission;
        $trade->commissionAsset = $myTrade->commissionAsset;
        $trade->time = $myTrade->time;
        $trade->isBuyer = $myTrade->isBuyer;
        $trade->isMaker = $myTrade->isMaker;
        $trade->isBestMatch = $myTrade->isBestMatch;
        $trade->save();
      }
    }
    return $myTrades;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $trades = Trade::where('user_id', '=', Auth::user()->id)->orderBy('time', 'asc')->get();;
    return view('trades.index')->with('trades', $trades);
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
   * @param  \App\Models\Trade  $trade
   * @return \Illuminate\Http\Response
   */
  public function show(Trade $trade)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Trade  $trade
   * @return \Illuminate\Http\Response
   */
  public function edit(Trade $trade)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Trade  $trade
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Trade $trade)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Trade  $trade
   * @return \Illuminate\Http\Response
   */
  public function destroy(Trade $trade)
  {
    //
  }
}
