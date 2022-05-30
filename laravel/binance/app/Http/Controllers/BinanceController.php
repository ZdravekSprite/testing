<?php

namespace App\Http\Controllers;

use App\Models\Binance;
use App\Http\Requests\StoreBinanceRequest;
use App\Http\Requests\UpdateBinanceRequest;
use Illuminate\Support\Facades\Auth;

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

  /**
   */
  public function test()
  {
    $systemStatus = (new BApi)->systemStatus();
    //dd($systemStatus);
    if ($systemStatus->status) return $systemStatus->msg;
    $getAPIKeyPermission = (new BApi)->getAPIKeyPermission();
    //dd($getAPIKeyPermission);

    $coins = [];

    $allCoinsInformation = (new BApi)->myCoinsInformation();
    //dd($allCoinsInformation);
    foreach ($allCoinsInformation as $key => $value) {
      if ($value->free > 0) $coins[$value->coin] = ['free' => $value->free];
      if ($value->locked > 0) $coins[$value->coin] = isset($coins[$value->coin]) ? [...$coins[$value->coin], 'locked' => $value->locked] : ['locked' => $value->locked];
    }
    $getFlexibleProductPosition = (new BApi)->getFlexibleProductPosition();
    //dd($getFlexibleProductPosition);
    /*
    foreach ($getFlexibleProductPosition as $key => $value) {
      $coins[$value->asset] = isset($coins[$value->asset]) ? [...$coins[$value->asset], 'save' => $value->totalAmount] : ['save' => $value->totalAmount];
    }
    */
    $lendingAccount = (new BApi)->lendingAccount();
    //dd($getFlexibleProductPosition, $lendingAccount);
    foreach ($lendingAccount->positionAmountVos as $key => $value) {
      $coins[$value->asset] = isset($coins[$value->asset]) ? [...$coins[$value->asset], 'save' => $value->amount] : ['save' => $value->amount];
    }

    $getStakingProductPosition = (new BApi)->getStakingProductPosition();
    //dd($getStakingProductPosition);
    foreach ($getStakingProductPosition as $key => $value) {
      $coins[$value->asset] = isset($coins[$value->asset]) ? [...$coins[$value->asset], 'stake' => (isset($coins[$value->asset]['stake']) ? $coins[$value->asset]['stake'] + $value->amount : $value->amount)] : ['stake' => $value->amount];
    }

    $symbolPriceTicker = (new BApi)->symbolPriceTicker();
    //$symbolPriceTicker = (new BApi)->symbolPriceTicker(['EURBUSD','BUSDUSDT']);
    //dd($symbolPriceTicker);
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

    //$redeemFlexibleProduct = (new BApi)->redeemFlexibleProduct("DAI",1.95240903,"FAST");

    dd($getAPIKeyPermission, $getStakingProductPosition, $coins, $busdTotal);
  }
}
