<?php

namespace App\Http\Controllers;

use App\Models\Kline;
use App\Models\Symbol;
use Illuminate\Http\Request;

class KlineController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $symbols = Symbol::all();
    return view('klines.index')->with(compact('symbols'));
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
