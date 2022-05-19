<?php

namespace App\Http\Controllers;

use App\Models\Binance;
use App\Http\Requests\StoreBinanceRequest;
use App\Http\Requests\UpdateBinanceRequest;

class BinanceController extends Controller
{
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
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreBinanceRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreBinanceRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  public function show(Binance $binance)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  public function edit(Binance $binance)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateBinanceRequest  $request
   * @param  \App\Models\Binance  $binance
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateBinanceRequest $request, Binance $binance)
  {
    //
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
}
