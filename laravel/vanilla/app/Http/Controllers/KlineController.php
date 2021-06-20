<?php

namespace App\Http\Controllers;

use App\Models\Kline;
use App\Models\Symbol;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class KlineController extends Controller
{
  public static function add_kline($symbol, $interval, $kline)
  {
    //dd('add',$kline);
    $old_kline = Kline::where('symbol', '=', $symbol)->where('interval', '=', $interval)->where('start_time', '=', $kline[0])->first();
    if ($old_kline) return $old_kline;
    $new_kline = new Kline;
    $new_kline->symbol = $symbol;
    $new_kline->interval = $interval;
    $new_kline->start_time = $kline[0];
    $new_kline->o = $kline[1];
    $new_kline->h = $kline[2];
    $new_kline->l = $kline[3];
    $new_kline->c = $kline[4];
    $new_kline->v = $kline[5];
    $new_kline->close_time = $kline[6];
    $new_kline->q = $kline[7];
    $new_kline->n = $kline[8];
    $new_kline->base_volume = $kline[9];
    $new_kline->quote_volume = $kline[10];
    $new_kline->save();
    return $new_kline;
  }
  public static function kline($symbol, $time)
  {
    if(Symbol::where('symbol', '=', $symbol)->where('status', '=', 'DUST')->first()) return null;
    $kline = Kline::where('symbol', '=', $symbol)->where('start_time', '=', $time)->first();
    if (!$kline) {
      $decode_kline = json_decode(Http::get('https://api.binance.com/api/v3/klines?symbol=' . $symbol . '&interval=1m&limit=1&startTime=' . $time));
      //dd($symbol);
      if(!isset($decode_kline[0])) return null;
      $kline = KlineController::add_kline($symbol,'1m',$decode_kline[0]);
    }
    //dd($kline);
    return $kline;
  }
  public function klines($symbol)
  {
    $server = 'https://api.binance.com/api';
    $klines = Kline::where('symbol', '=', $symbol)->get();
    //dd($klines->last()->close_time);
    $time = json_decode(Http::get($server . '/v3/time'));
    //dd($time->serverTime);
    $end_time = $time->serverTime;
    if ($klines->count() == 0) {
      $trade_1st = Trade::where('symbol', '=', $symbol)->orderBy('time', 'asc')->first()->time;
      //dd(gmdate("Y-m-d H:i:s", $trade_1st / 1000));
      //$kline_1st = json_decode(Http::get($server . '/v3/klines?symbol=' . $symbol . '&interval=1m&limit=1&startTime=0'));
      //dd($kline_1st[0][0]);
      //$start_time = $kline_1st[0][0];
      $start_time = $trade_1st;
    } else {
      $start_time = $klines->last()->close_time;
    }
    set_time_limit(0);
    while ($start_time <= $end_time) {
      //echo "Start time is: $start_time <br>";
      $klines = json_decode(Http::get('https://api.binance.com/api/v3/klines?symbol=' . $symbol . '&interval=1m&limit=1000&startTime=' . $start_time));
      foreach ($klines as $key => $kline) {
        //dd($kline);
        $kline = $this->add_kline($symbol, '1m', $kline);
        //dd($kline->close_time + 1);
        $start_time = $kline->close_time + 1;
      }
      //sleep(1);
    }
    //dd($klines);
    return $klines;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //ini_set("memory_limit","1024M");
    //$symbols = Symbol::where('status', '=', 'TRADING')->where('quoteAsset', '=', 'USDT')->get();
    
    $trades = Trade::where('user_id', '=', Auth::user()->id)->get();
    $symbols = $trades->pluck('symbol')->unique();
    $times = $trades->pluck('time')->map(function ($time) {
      return number_format(floor($time / 60000) * 60000, 0, '.', '');
    })->unique();
    $assets = [];
    $klines = [];
    $klines_symbols = [];
    foreach ($symbols as $key => $value) {
      $symbol = Symbol::where('symbol', '=', $value)->first();
      //dd($symbol);
      $assets[] = $symbol->baseAsset;
      $assets[] = $symbol->quoteAsset;
    }
    //dd(array_unique($assets));
    foreach (array_unique($assets) as $key => $value) {
      if (Symbol::where('symbol', '=', $value . 'EUR')->first()) $klines_symbols[] = Symbol::where('symbol', '=', $value . 'EUR')->first()->symbol;
      elseif (Symbol::where('symbol', '=', 'EUR' . $value)->first()) $klines_symbols[] = Symbol::where('symbol', '=', 'EUR' . $value)->first()->symbol;
      elseif (Symbol::where('symbol', '=', $value . 'BUSD')->first()) $klines_symbols[] = Symbol::where('symbol', '=', $value . 'BUSD')->first()->symbol;
      elseif (Symbol::where('symbol', '=', 'BUSD' . $value)->first()) $klines_symbols[] = Symbol::where('symbol', '=', 'BUSD' . $value)->first()->symbol;
      elseif (Symbol::where('symbol', '=', $value . 'USDT')->first()) $klines_symbols[] = Symbol::where('symbol', '=', $value . 'USDT')->first()->symbol;
      elseif (Symbol::where('symbol', '=', 'USDT' . $value)->first()) $klines_symbols[] = Symbol::where('symbol', '=', 'USDT' . $value)->first()->symbol;
      //dd($klines_symbols);
    }
    //dd($times,$klines_symbols);
/*    set_time_limit(0);
    
    foreach ($times as $key => $time) {
      //dd($time);
      foreach ($klines_symbols as $key => $symbol) {
        $old_kline = Kline::where('symbol', '=', $symbol)->where('interval', '=', '1m')->where('start_time', '=', $time)->first();
        if (!$old_kline) {
          $kline = json_decode(Http::get('https://api.binance.com/api/v3/klines?symbol=' . $symbol . '&interval=1m&limit=1&startTime=' . $time));
          if (isset($kline[0])) $kline = $this->add_kline($symbol, '1m', $kline[0]);
        }
      }
      //dd($klines_symbols);
    }
*/
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
      //$apiKey = env('BINANCE_API_KEY');
      //$apiSecret = env('BINANCE_API_SECRET');
      $apiKey = Auth::user()->BINANCE_API_KEY;
      $apiSecret = Auth::user()->BINANCE_API_SECRET;
    }

    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    $timeStamp = 'timestamp=' . $serverTime; // build timestamp type url get
    $signature = hash_hmac('SHA256', $timeStamp, $apiSecret); // build firm with sha256
    $openOrders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/openOrders', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    $trades = Trade::where('user_id', '=', Auth::user()->id)->where('time', '>', ($serverTime - 1000*600000))->get();
    //dd($trades);
    $symbols = [['BTCBUSD',[],[],[]], ['ETHBUSD',[],[],[]], ['BNBBUSD',[],[],[]], ['ADABUSD',[],[],[]], ['MATICBUSD',[],[],[]], ['SOLBUSD',[],[],[]]];//, ['LPTBUSD',[],[],[]], ['KSMBUSD',[],[],[]]];
    //dd($openOrders,$symbols);
    foreach ($symbols as $key => $symbol) {
      foreach ($openOrders as $order) {
        if($order->symbol == $symbol[0]) {
          if($order->side == 'BUY') {
            $symbols[$key][1][$order->orderId] = $order->price;
          }
          if($order->side == 'SELL') {
            $symbols[$key][2][$order->orderId] = $order->price;
          }
        }
      }
      foreach ($trades as $trade) {
        if($trade->symbol == $symbol[0]) {
          $marker = (object)[];
          $marker->time = $trade->time/1000;//gmdate("Y-m-d h:i:s",$trade->time);//
          $marker->position = $trade->isBuyer ? 'belowBar' : 'aboveBar';
          $marker->color = $trade->isBuyer ? 'red' : 'green';
          $marker->shape = $trade->isBuyer ? 'arrowUp' : 'arrowDown';
          $marker->id = $trade->orderId;
          $marker->text = $trade->price*1;
          $marker->size = 1;
          $symbols[$key][3][] = $marker;
        }
      }
    }
    //dd($symbols);
    //$symbols = [['BTCUSDT',53600,53200], ['ETHBTC'], ['ETHUSDT',2510,2480], ['BNBBTC'], ['BNBUSDT',540,532], ['BNBETH']];
    //$link = implode('/', array_map(fn($n) => strtolower($n[0]).'@kline_1m', $symbols));
    $func = function($n) {
      return strtolower($n[0]).'@kline_1m';
    };
    $link = implode('/', array_map($func, $symbols));
    //dd($link);
    return view('klines.index')->with(compact('symbols', 'link'));
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
   * @param  \App\Models\Kline  $kline
   * @return \Illuminate\Http\Response
   */
  public function show(Kline $kline)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Kline  $kline
   * @return \Illuminate\Http\Response
   */
  public function edit(Kline $kline)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Kline  $kline
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Kline $kline)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Kline  $kline
   * @return \Illuminate\Http\Response
   */
  public function destroy(Kline $kline)
  {
    //
  }
}
