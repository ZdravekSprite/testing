<?php

namespace App\Http\Controllers;

use App\Models\Hnb;
use App\Models\Trade;
use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HnbController;

class TradeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function allMyTrades()
  {
    set_time_limit(0);
    //$symbols = Symbol::where('status', '=', 'TRADING');
    $trades = Trade::where('user_id', '=', Auth::user()->id)->orderBy('time', 'asc')->get();
    $symbols = $trades->pluck('symbol')->unique();
    //dd($symbols);
    $allTrades = [];
    foreach ($symbols as $key => $symbol) {
      //$myTrades = $this->myTrades($symbol->symbol);
      $myTrades = $this->myTrades($symbol);
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
  public function depositHistory()
  {
    $server = 'https://api.binance.com';
    $apiKey = Auth::user()->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
    $time = json_decode(Http::get($server . '/api/v3/time'));
    $serverTime = $time->serverTime;
    $timeStamp = 'timestamp=' . $serverTime;
    $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
    $depositHistory = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/wapi/v3/depositHistory.html', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    dd($depositHistory);
    return $depositHistory;
  }
  public function dustLog()
  {
    $server = 'https://api.binance.com';
    $apiKey = Auth::user()->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
    $time = json_decode(Http::get($server . '/api/v3/time'));
    $serverTime = $time->serverTime;
    $timeStamp = 'timestamp=' . $serverTime;
    $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
    $dustLog = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/sapi/v1/asset/dribblet', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    //dd($dustLog->userAssetDribblets);
    foreach ($dustLog->userAssetDribblets as $key => $myDusts) {
      //dd($myDusts);
      foreach ($myDusts->userAssetDribbletDetails as $key => $myDust) {
        //dd(Trade::where('binanceId', '=', $myTrade->id)->count());
        $symbol = Symbol::where('quoteAsset', '=', 'BNB')->where('baseAsset', '=', $myDust->fromAsset)->first();
        $myDust->isBuyer = !$symbol;
        if (!$symbol) $symbol = Symbol::where('baseAsset', '=', 'BNB')->where('quoteAsset', '=', $myDust->fromAsset)->first();
        //dd($symbol);
        if (Trade::where('binanceId', '=', $myDust->transId)->where('symbol', '=', $symbol->symbol)->count() == 0) {
          //dd($symbol, $myDust);
          $trade = new Trade;
          $trade->user_id = Auth::user()->id;
          $trade->symbol = $symbol->symbol;
          $trade->binanceId = $myDust->transId;
          $trade->orderId = $myDust->transId;
          $trade->orderListId = -2;
          $trade->price = number_format($myDust->amount / $myDust->transferedAmount, 8);
          $trade->qty = number_format($myDust->amount, 8);
          $trade->quoteQty = number_format($myDust->transferedAmount, 8);
          $trade->commission = number_format($myDust->serviceChargeAmount, 8);
          $trade->commissionAsset = 'BNB';
          $trade->time = $myDust->operateTime;
          $trade->isBuyer = $myDust->isBuyer;
          $trade->isMaker = true;
          $trade->isBestMatch = true;
          $trade->save();
        }
      }
    }
    dd($dustLog);
    return $dustLog;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $trades = Trade::where('user_id', '=', Auth::user()->id)->orderBy('time', 'asc')->get();
    $symbols = $trades->pluck('symbol')->unique();
    $assets = [];
    foreach ($symbols as $key => $value) {
      $symbol = Symbol::where('symbol', '=', $value)->first();
      //dd($symbol);
      $assets[$symbol->baseAsset] = 0;
      $assets[$symbol->quoteAsset] = 0;
    }
    $zero = 100000000;
    $assets['BTC'] = number_format((0.000341 + 0.000333), 8);
    $assets['USDT'] = number_format((59.36301 + 58.847475 + 58.563814), 8);
    $assets['XRP'] = number_format((62.7), 8);
    $symbols = $assets;
    //$symbols = collect($assets)->unique();

    foreach ($trades as $key => $trade) {
      $symbol = Symbol::where('symbol', '=', $trade->symbol)->first();
      //dd($trade, $assets, number_format($assets[$symbol->baseAsset] + ($trade->isBuyer ? 1 : -1) * $trade->qty, 8));
      $assets[$symbol->baseAsset] = number_format((float)$assets[$symbol->baseAsset] + (float)$trade->qty * ($trade->isBuyer ? 1 : -1), 8, '.', '');
      $assets[$symbol->quoteAsset] = number_format((float)$assets[$symbol->quoteAsset] + (float)$trade->quoteQty * ($trade->isBuyer ? -1 : 1), 8, '.', '');
      $assets[$trade->commissionAsset] = number_format((float)$assets[$trade->commissionAsset] - (float)$trade->commission, 8, '.', '');
      $trade->assets = $assets;
      $date = gmdate("Y-m-d", $trade->time / 1000);
      $trade->hnb = Hnb::where('datum_primjene', '=', $date)->get();
      //dd($trade->hnb);
      if ($trade->hnb->count() == 0) {
        $response = Http::get('https://api.hnb.hr/tecajn/v2?datum-primjene=' . $date);
        $day = $response->json();
        //dd($day[0]['datum_primjene']);
        foreach ($day as $key => $valuta) {
          $hnb = new Hnb;
          $hnb->broj_tecajnice = $valuta['broj_tecajnice'];
          $hnb->datum_primjene = $valuta['datum_primjene'];
          $hnb->drzava = $valuta['drzava'];
          $hnb->drzava_iso = $valuta['drzava_iso'];
          $hnb->sifra_valute = $valuta['sifra_valute'];
          $hnb->valuta = $valuta['valuta'];
          $hnb->jedinica = $valuta['jedinica'];
          $hnb->kupovni_tecaj = $valuta['kupovni_tecaj'];
          $hnb->srednji_tecaj = $valuta['srednji_tecaj'];
          $hnb->prodajni_tecaj = $valuta['prodajni_tecaj'];
          $hnb->save();
        }
        $trade->hnb = Hnb::where('datum_primjene', '=', $date)->get();
      }
      //dd($trade->hnb->where('valuta', '=', 'USD')->first()->prodajni_tecaj);
    }

    $trades = $trades->sortByDesc('time');

    $trades->values()->all();
    //return view('trades.index')->with('trades', $trades);
    return view('trades.index')->with(compact('trades', 'symbols'));
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
