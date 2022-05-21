<?php

namespace App\Http\Controllers;

use App\Models\Binance;
use App\Http\Requests\StoreBinanceRequest;
use App\Http\Requests\UpdateBinanceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BinanceController extends Controller
{
  /**
   * Instantiate a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('binance.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $binance = Binance::where('user_id', '=', Auth::user()->id)->first();
    if ($binance) return redirect(route('binance.edit', $binance));
    $binance = new Binance();
    //dd($binance);
    return view('binance.create')->with(compact('binance'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreBinanceRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreBinanceRequest $request)
  {
    $binance = Binance::where('user_id', '=', Auth::user()->id)->first();
    if (!$binance) {
      $binance = new Binance();
      $binance->user_id = Auth::user()->id;
    }
    $binance->BINANCE_API_KEY = $request->input('bkey') ?? $binance->BINANCE_API_KEY;
    $binance->BINANCE_API_SECRET = $request->input('bsecret') ?? $binance->BINANCE_API_SECRET;
    //dd($binance);
    $binance->save();
    return redirect(route('dashboard'))->with('success', 'Binance Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  //public function show(Binance $binance)
  public function show()
  {
    $binance = Binance::where('user_id', '=', Auth::user()->id)->first();
    if (!$binance) return redirect(route('binance.create'));
    return view('binance.show')->with(compact('binance'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  //public function edit(Binance $binance)
  public function edit()
  {
    $binance = Binance::where('user_id', '=', Auth::user()->id)->first();
    if (!$binance) return redirect(route('binance.create'));
    //dd($binance);
    return view('binance.edit')->with(compact('binance'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateBinanceRequest  $request
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  //public function update(UpdateBinanceRequest $request, Binance $binance)
  public function update(UpdateBinanceRequest $request)
  {
    $binance = Binance::where('user_id', '=', Auth::user()->id)->first();
    if (!$binance) return redirect(route('binance.create'));
    $binance->BINANCE_API_KEY = $request->input('bkey') ?? $binance->BINANCE_API_KEY;
    $binance->BINANCE_API_SECRET = $request->input('bsecret') ?? $binance->BINANCE_API_SECRET;
    //dd($binance);
    $binance->save();
    return redirect(route('dashboard'))->with('success', 'Binance Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  public function destroy(Binance $binance)
  {
    //
  }

  public function get($url)
  {
    $http_get = json_decode(Http::get($url));
    return $http_get;
  }

  public function get_withHeaders($url, $array = null)
  {
    $apiKey = Auth::user()->binance->apiKey();
    $apiSecret = Auth::user()->binance->apiSecret();
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));

    $serverTime = $time->serverTime;
    $timestampArray = array(
      "timestamp" => $serverTime
    );
    $queryArray = $array ? $array + $timestampArray : $timestampArray;
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $getArray = $queryArray + $signatureArray;
    $http_get_withHeaders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($url, $getArray));
    return $http_get_withHeaders;
  }

  public function post_withHeaders($url, $array = null)
  {
    $apiKey = Auth::user()->binance->apiKey();
    $apiSecret = Auth::user()->binance->apiSecret();
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));

    $serverTime = $time->serverTime;
    $timestampArray = array(
      "timestamp" => $serverTime
    );
    $queryArray = $array ? $array + $timestampArray : $timestampArray;
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $postArray = $queryArray + $signatureArray;
    $http_post_withHeaders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->post($url, $postArray));
    return $http_post_withHeaders;
  }

  /**
   */
  public function test()
  {
    $url = 'https://api.binance.com/sapi/v1/system/status';
    $systemStatus = $this->get($url);
    if ($systemStatus->status) return $systemStatus->msg;

    $url = 'https://api.binance.com/sapi/v1/account/apiRestrictions';
    $getAPIKeyPermission = $this->get_withHeaders($url);

    $coins = [];

    $url = 'https://api.binance.com/sapi/v1/capital/config/getall';
    $allCoinsInformation = $this->get_withHeaders($url);
    foreach ($allCoinsInformation as $key => $value) {
      if ($value->free > 0) $coins[$value->coin] = ['free' => $value->free];
      if ($value->locked > 0) $coins[$value->coin] = isset($coins[$value->coin]) ? [...$coins[$value->coin], 'locked' => $value->locked] : ['locked' => $value->locked];
    }
    /*
    $collection = collect($allCoinsInformation);
    $filtered = $collection->filter(function ($value, $key) {
      return ($value->free + $value->locked) > 0;
    });
    */
    $url = 'https://api.binance.com/sapi/v1/lending/daily/token/position';
    $getFlexibleProductPosition = $this->get_withHeaders($url);
    /*
    $collection = collect($getFlexibleProductPosition);
    $filtered = $collection->filter(function ($value, $key) {
      return $value->totalAmount > 0;
    });
    */

    foreach ($getFlexibleProductPosition as $key => $value) {
      if ($value->totalAmount > 0) $coins[$value->asset] = isset($coins[$value->asset]) ? [...$coins[$value->asset], 'save' => $value->totalAmount] : ['save' => $value->totalAmount];
    }

    /*
    $url = 'https://api.binance.com/sapi/v1/accountSnapshot';
    $array = array(
      "type" => "SPOT"
    );
    $accountSnapshot = $this->get_withHeaders($url, $array);
    if ($accountSnapshot->code !== 200) return $accountSnapshot->msg;
    */

    $url = 'https://api.binance.com/sapi/v1/staking/position';
    $array = array(
      "product" => "STAKING"
    );
    $getStakingProductPosition = $this->get_withHeaders($url, $array);
    foreach ($getStakingProductPosition as $key => $value) {
      $coins[$value->asset] = isset($coins[$value->asset]) ? [...$coins[$value->asset], 'stake' => (isset($coins[$value->asset]['stake']) ? $coins[$value->asset]['stake'] + $value->amount : $value->amount)] : ['stake' => $value->amount];
    }


    $url = 'https://api.binance.com/api/v3/ticker/price';
    $symbolPriceTicker = $this->get($url);
    $collection = collect($symbolPriceTicker);
    $busdTotal = 0;
    foreach ($coins as $coin => $amount) {
      $total = array_sum($amount);
      $busd = 0;
      $coinBusd = $collection->firstWhere('symbol', $coin . 'BUSD');
      $busdCoin = $collection->firstWhere('symbol', 'BUSD' . $coin);
      $coins[$coin] = [...$coins[$coin], 'total' => $total, 'coinBusd' => $coinBusd, 'busdCoin' => $busdCoin];
      if ($coin === 'BUSD') {
        $busd = $total;
      } elseif ($coinBusd) {
        $busd = $total * $coinBusd->price;
      } else {
        $busd = $total / $busdCoin->price;
      }
      $coins[$coin] = [...$coins[$coin], 'busd' => $busd];
      $busdTotal += $busd;
    }
/*
    $url = 'https://api.binance.com/sapi/v1/lending/daily/redeem';
    $array = array(
      "productId" => "DAI",
      "amount" => 1.95240903,
      "type" => "FAST",
    );
    $redeemFlexibleProduct = $this->post_withHeaders($url, $array);
*/
    dd($getAPIKeyPermission, $getStakingProductPosition, $coins, $busdTotal);
  }
}
