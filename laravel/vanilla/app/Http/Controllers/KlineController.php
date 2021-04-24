<?php

namespace App\Http\Controllers;

use App\Models\Kline;
use App\Models\Symbol;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KlineController extends Controller
{
  public function add_kline($symbol, $interval, $kline)
  {
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
    $symbols = Symbol::where('status', '=', 'TRADING')->where('quoteAsset', '=', 'USDT')->get();
    $klines = $this->klines('BTCUSDT');
    $klines = $this->klines('ETHUSDT');
    $klines = $this->klines('BNBUSDT');
    return view('klines.index')->with(compact('symbols', 'klines'));
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
