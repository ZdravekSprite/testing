<?php

namespace App\Http\Controllers;

use App\Models\Hnb;
use App\Models\Trade;
use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Http\Controllers\HnbController;
use App\Models\Kline;

class TradeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function allMyTrades()
  {
    set_time_limit(0);
    $symbols = Symbol::where('status', '=', 'TRADING')->pluck('symbol');
    /* kada treba provjeriti sve symbole */
    $trades = Trade::where('user_id', '=', Auth::user()->id)->orderBy('time', 'asc')->get();
    $symbols = $trades->pluck('symbol')->unique();
    /**/
    //dd(http_build_query(json_decode($symbols)));
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
  public function crta()
  {
    $symbols = [];
    $symbols['SOLBUSD'] = 0;
    $symbols['BTCBUSD'] = 0;
    $symbols['ETHBUSD'] = 0;
    $symbols['BNBBUSD'] = 0;
    $symbols['MATICBUSD'] = 0;
    $symbols['FTTBUSD'] = 0;
    $symbols['LUNABUSD'] = 0;
    $symbols['ADABUSD'] = 0;
    foreach ($symbols as $symbol => $value) {
      $myTrades = $this->myTrades($symbol);
      $qty = 0;
      $quoteQty = 0;
      $commission = 0;
      foreach ($myTrades as $trade) {
        if ($trade->isBuyer) {
          $qty += $trade->qty * 1;
          $quoteQty -= $trade->quoteQty * 1;
        } else {
          $qty -= $trade->qty * 1;
          $quoteQty += $trade->quoteQty * 1;
        }
        $commission -= $trade->commission * 1;
      }
      $res1 = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=' . $symbol);
      $price = $res1->json()['price'] * 1;
      $res2 = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BNBBUSD');
      $bnb = $res2->json()['price'] * 1;
      $symbols[$symbol] = $qty * $price + $quoteQty + $commission * $bnb;
      //dd($value, $qty, $quoteQty, $commission, $price, $bnb, $myTrades);
    }
    dd($symbols);
    return $symbols;
  }
  public function myTrades($symbol)
  {
    $server = 'https://api.binance.com/api';
    $apiKey = Auth::user()->settings->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->settings->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
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
    //dd($myTrades);
    foreach ($myTrades as $key => $myTrade) {
      //dd($myTrade);
      //dd(Trade::where('binanceId', '=', $myTrade->id)->count());
      if (!is_object($myTrade)) return null;
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
    $apiKey = Auth::user()->settings->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->settings->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
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
    $apiKey = Auth::user()->settings->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->settings->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
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
        //dd(Symbol::where('quoteAsset', '=', 'KEY')->orWhere('baseAsset', '=', 'KEY')->get());
        $symbol = Symbol::where('quoteAsset', '=', 'BNB')->where('baseAsset', '=', $myDust->fromAsset)->first();
        $myDust->isBuyer = !$symbol;
        if (!$symbol) $symbol = Symbol::where('baseAsset', '=', 'BNB')->where('quoteAsset', '=', $myDust->fromAsset)->first();
        if (!$symbol) {
          $symbol = Symbol::create([
            'symbol' => $myDust->fromAsset . 'BNB',
            'status' => 'DUST',
            'baseAsset' => $myDust->fromAsset,
            'baseAssetPrecision' => 8,
            'quoteAsset' => 'BNB',
            'quotePrecision' => 8,
            'quoteAssetPrecision' => 8,
            'icebergAllowed' => false,
            'ocoAllowed' => false,
            'isSpotTradingAllowed' => false,
            'isMarginTradingAllowed' => false
          ]);
          //dd($symbol);
        }
        if (Trade::where('binanceId', '=', $myDust->transId)->where('symbol', '=', $myDust->fromAsset . 'BNB')->orWhere('symbol', '=',  'BNB' . $myDust->fromAsset)->count() == 0) {
          //dd($symbol, $myDust);
          $trade = new Trade;
          $trade->user_id = Auth::user()->id;
          $trade->symbol = $symbol->symbol;
          $trade->binanceId = $myDust->transId;
          $trade->orderId = $myDust->transId;
          $trade->orderListId = -2;
          $trade->commission = number_format($myDust->serviceChargeAmount, 8);
          $trade->commissionAsset = 'BNB';

          $qty = $myDust->isBuyer ? $myDust->transferedAmount + $myDust->serviceChargeAmount : $myDust->amount;
          $quoteQty = $myDust->isBuyer ? $myDust->amount : $myDust->transferedAmount + $myDust->serviceChargeAmount;
          $price = $quoteQty / $qty;
          //dd($myDust,$symbol->symbol,$price,$qty,$quoteQty);
          $trade->price = number_format($price, 8, '.', '');
          $trade->qty = number_format($qty, 8, '.', '');
          $trade->quoteQty = number_format($quoteQty, 8, '.', '');

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
    $server = 'https://api.binance.com';
    $apiKey = Auth::user()->settings->BINANCE_API_KEY; //env('BINANCE_API_KEY');
    $apiSecret = Auth::user()->settings->BINANCE_API_SECRET; //env('BINANCE_API_SECRET');
    $time = json_decode(Http::get($server . '/api/v3/time'));
    $serverTime = $time->serverTime;
    $timeStamp = 'timestamp=' . $serverTime;
    $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
    $getall = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/sapi/v1/capital/config/getall', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    //dd($getall);

    $all_assets = [];
    $balance = [];
    $my_assets = [];
    foreach ($getall as $coin) {
      //dd($coin->coin);
      $all_assets = Arr::add($all_assets, $coin->coin, (object) array('name' => $coin->name));
      $total = $coin->free + $coin->locked + $coin->freeze + $coin->withdrawing + $coin->ipoing + $coin->ipoable + $coin->storage;
      if ($total > 0) {
        $balance = Arr::add($balance, $coin->coin, $total);
        $my_assets = Arr::add($my_assets, $coin->coin, $all_assets[$coin->coin]);
      }
      $all_assets[$coin->coin]->total = number_format($total, 8, '.', '');
    }
    //dd($balance,$all_assets);

    //$trades = Trade::where('user_id', '=', Auth::user()->id)->orderBy('time', 'desc')->get();
    $trades = Trade::where('user_id', '=', Auth::user()->id)->where('time', '>', ($serverTime - 14 * 24 * 60 * 60 * 1000))->orderBy('time', 'desc')->get();
    $symbols = $trades->pluck('symbol')->unique();
    //dd($balance,$all_assets,$trades,$symbols);

    foreach ($symbols as $key => $value) {
      $symbol = Symbol::where('symbol', '=', $value)->first();
      $my_assets = Arr::add($my_assets, $symbol->baseAsset, $all_assets[$symbol->baseAsset]);
      $my_assets = Arr::add($my_assets, $symbol->quoteAsset, $all_assets[$symbol->quoteAsset]);
    }
    //dd($balance,$my_assets,$trades,$symbols);

    foreach ($my_assets as $key => $coin) {
      $symbol = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', $key)->where('quoteAsset', '=', 'EUR')->first();
      if (!$symbol) $symbol = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', 'EUR')->where('quoteAsset', '=', $key)->first();
      if (!$symbol) $symbol = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', $key)->where('quoteAsset', '=', 'BUSD')->first();
      if (!$symbol) $symbol = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', 'BUSD')->where('quoteAsset', '=', $key)->first();
      if (!$symbol) $symbol = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', $key)->where('quoteAsset', '=', 'USDT')->first();
      if (!$symbol) $symbol = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', 'USDT')->where('quoteAsset', '=', $key)->first();
      //dd($symbol);
      $coin->symbol = $symbol->symbol;
      $coin->isBase = $symbol->baseAsset == $key;
      $coin->fiat = $coin->isBase ? $symbol->quoteAsset : $symbol->baseAsset;
    }
    //dd($my_assets);

    //$assets = $my_assets;
    //$zero = 100000000;
    /*
    $assets['BTC'] = number_format((0.000341 + 0.000333), 8);
    $assets['USDT'] = number_format((59.36301 + 58.847475 + 58.563814), 8); //10.29,319.15004872
    $assets['BNB'] = number_format((0), 8); //0.21,0.00001586,0.04978458
    $assets['XRP'] = number_format((62.7), 8);
    */
    //$symbols = $assets;
    set_time_limit(0);
    foreach ($trades as $trade_key => $trade) {
      $date = gmdate("Y-m-d", $trade->time / 1000);
      $hnb_eur_kn = Hnb::where('datum_primjene', '=', $date)->where('valuta', '=', 'EUR')->first();
      //dd($trade->eur_kn);
      if (!$hnb_eur_kn) {
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
        $hnb_eur_kn = Hnb::where('datum_primjene', '=', $date)->where('valuta', '=', 'EUR')->first();
      }
      $trade->eur_kn = str_replace(',', '.', $hnb_eur_kn->kupovni_tecaj);
      //dd($trade->eur_kn);

      //$klines = Kline::where('start_time', '=', floor($trade->time / 60000) * 60000)->get();
      //
      $time = floor($trade->time / 60000) * 60000;
      $eur_kn = $trade->eur_kn * 1;
      $total_kn = -0.8 * $eur_kn;

      $k_eur_usdt = KlineController::kline('EURUSDT', $time);
      $eur_usdt = 2 / ($k_eur_usdt->o + $k_eur_usdt->c);
      $k_eur_busd = KlineController::kline('EURBUSD', $time);
      $eur_busd = 2 / ($k_eur_busd->o + $k_eur_busd->c);

      $trade->kline = KlineController::kline($trade->symbol, $time);
      $trade->assets = [];
      foreach ($my_assets as $key => $coin) {
        $kline = KlineController::kline($coin->symbol, $time);
        $fiat_price = $coin->isBase ? ($kline->o + $kline->c) / 2 : 2 / ($kline->o + $kline->c);
        $trade->assets = Arr::add($trade->assets, $key, (object) array('total' => $my_assets[$key]->total, 'name' => $my_assets[$key]->name));
        switch ($coin->fiat) {
          case 'EUR':
            $coin->price = $fiat_price * $eur_kn;
            break;
          case 'BUSD':
            $coin->price = $fiat_price * $eur_busd * $eur_kn;
            break;
          case 'USDT':
            $coin->price = (1 - 0.0075) * $fiat_price * $eur_usdt * $eur_kn;
            break;
        }
        $total_kn = $total_kn + $coin->price * $coin->total;
      }
      $trade->total_kn = $total_kn * (1 - 0.00075);
      //dd($eur_kn,$trade,$my_assets, $eur_usdt, $eur_busd);

      $symbol = Symbol::where('symbol', '=', $trade->symbol)->first();
      $trade->symbolFull = $symbol;
      //dd($trade, $assets, number_format($assets[$symbol->baseAsset] + ($trade->isBuyer ? 1 : -1) * $trade->qty, 8));

      //$old_assets = $my_assets;
      //$trade->assets = $old_assets;
      $my_assets[$symbol->baseAsset]->total = number_format((float)$my_assets[$symbol->baseAsset]->total - (float)$trade->qty * ($trade->isBuyer ? 1 : -1), 8, '.', '');
      $my_assets[$symbol->quoteAsset]->total = number_format((float)$my_assets[$symbol->quoteAsset]->total - (float)$trade->quoteQty * ($trade->isBuyer ? -1 : 1), 8, '.', '');
      $my_assets[$trade->commissionAsset]->total = number_format((float)$my_assets[$trade->commissionAsset]->total + (float)$trade->commission, 8, '.', '');
      //dd($trade, $my_assets);
      //$trade->assets = $assets;
      //dd($trade->hnb->where('valuta', '=', 'USD')->first()->prodajni_tecaj);
      //dd($trade_key);
      //if ($trade_key > 246) break;
    }

    $trades = $trades->sortByDesc('time');

    $trades->values()->all();
    //dd($trades[0]->assets,$trades[1]->assets,$symbols,$balance);
    //return view('trades.index')->with('trades', $trades);
    return view('trades.index')->with(compact('trades', 'symbols', 'balance'));
  }

  public function prosjek()
  {
    $the_asset = 'ADA';
    $symbol_base = Symbol::where('status', '=', 'TRADING')->where('baseAsset', '=', $the_asset)->get();
    $symbol_quote = Symbol::where('status', '=', 'TRADING')->where('quoteAsset', '=', $the_asset)->get();
    $trades = [];
    foreach ($symbol_base as $skey => $svalue) {
      $symbol_trades = Trade::where('symbol', '=', $svalue->symbol)->get();
      if (!$symbol_trades->isEmpty()) {
        foreach ($symbol_trades as $tkey => $tvalue) {
          if (!isset($trades[$svalue->baseAsset])) $trades[$svalue->baseAsset] = 0;
          if (!isset($trades[$svalue->quoteAsset])) $trades[$svalue->quoteAsset] = 0;
          if (!isset($trades[$tvalue->commissionAsset])) $trades[$tvalue->commissionAsset] = 0;
          $trades[$svalue->baseAsset] = $trades[$svalue->baseAsset] + ($tvalue->isBuyer ? 1 : -1) * $tvalue->qty;
          $trades[$svalue->quoteAsset] = $trades[$svalue->quoteAsset] + ($tvalue->isBuyer ? -1 : 1) * $tvalue->quoteQty;
          $trades[$tvalue->commissionAsset] = $trades[$tvalue->commissionAsset] - 1 * $tvalue->commission;
        }
        //dd($symbol_trades);
      }
    }
    foreach ($symbol_quote as $skey => $svalue) {
      $symbol_trades = Trade::where('symbol', '=', $svalue->symbol)->get();
      if (!$symbol_trades->isEmpty()) {
        foreach ($symbol_trades as $tkey => $tvalue) {
          if (!isset($trades[$svalue->baseAsset])) $trades[$svalue->baseAsset] = 0;
          if (!isset($trades[$svalue->quoteAsset])) $trades[$svalue->quoteAsset] = 0;
          if (!isset($trades[$tvalue->commissionAsset])) $trades[$tvalue->commissionAsset] = 0;
          $trades[$svalue->baseAsset] = $trades[$svalue->baseAsset] + ($tvalue->isBuyer ? -1 : 1) * $tvalue->qty;
          $trades[$svalue->quoteAsset] = $trades[$svalue->quoteAsset] + ($tvalue->isBuyer ? 1 : -1) * $tvalue->quoteQty;
          $trades[$tvalue->commissionAsset] = $trades[$tvalue->commissionAsset] - 1 * $tvalue->commission;
        }
        //dd($symbol_trades);
      }
    }
    //dd($trades);
    $asset_qty = 0;
    $busd_qty = 0;
    foreach ($trades as $coin => $qty) {
      if ($coin == $the_asset) {
        $asset_qty += $qty;
      } else {
        if ($coin == 'BUSD') {
          $busd_qty += $qty;
        }
        if ($coin == 'USDT') {
          $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BUSDUSDT');
          $data = $res->json();
          //dd($data);
          $busd_qty += $qty / $data['price'];
        }
        if ($coin == 'BNB') {
          $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BNBBUSD');
          $data = $res->json();
          //dd($data);
          $busd_qty += $qty * $data['price'];
        }
        if ($coin == 'XRP') {
          $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=XRPBUSD');
          $data = $res->json();
          //dd($data);
          $busd_qty += $qty * $data['price'];
        }
        if ($coin == 'ADA') {
          $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=ADABUSD');
          $data = $res->json();
          //dd($data);
          $busd_qty += $qty * $data['price'];
        }
        if ($coin == 'ETH') {
          $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=ETHBUSD');
          $data = $res->json();
          //dd($data);
          $busd_qty += $qty * $data['price'];
        }
      }
    }
    dd($trades, $the_asset . ': ' . $asset_qty, 'BUSD: ' . $busd_qty, 'AVRG: ' . (-$busd_qty / $asset_qty));
    //$res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=' . $coin->coin . 'USDT');
    //dd($asset,$symbol_base,$symbol_quote);
    /*
    $coin = 0;
    $busd = 0;
    $trades = $this->myTrades('ETHBUSD');
    foreach ($trades as $trade_key => $trade) {
      if ($trade->isBuyer) {
        $coin += $trade->qty;
        $busd += $trade->quoteQty;
      } else {
        $coin -= $trade->qty;
        $busd -= $trade->quoteQty;
      }
      //dd($trade->price);
      //dd($coin,$busd,$trade);
    }
    dd($busd / $coin, $trades);
    */
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
