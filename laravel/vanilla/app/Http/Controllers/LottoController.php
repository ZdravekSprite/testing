<?php

namespace App\Http\Controllers;

use App\Models\Lotto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LottoController extends Controller
{
  public function kombinacija($lotto)
  {
    $kombinacija = [];
    $numbers = range(1, $lotto->draws_info[1]);
    shuffle($numbers);
    $draw = [];
    foreach ($numbers as $key => $value) {
      if ($key < $lotto->draws_info[0]) $draw[] = $value;
    }
    $kombinacija['draw'] = $draw;
    $bonuses = range(1, $lotto->bonus_info[1]);
    shuffle($bonuses);
    $bonus = [];
    foreach ($bonuses as $key => $value) {
      if ($key < $lotto->bonus_info[0]) $bonus[] = $value;
    }
    $kombinacija['bonus'] = $bonus;
    return $kombinacija;
  }

  public function ponavljanje($kombinacija, $lotto)
  {
    foreach ($lotto->draws as $draw) {
      if (count(array_diff($kombinacija['draw'], $draw->draw)) < $lotto->draws_info[0] - 3) return $draw->draw;
    }
    return false;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $http_get = Http::get('http://localhost:3001/euro_jackpot');
    $lotto = json_decode($http_get);
    $kombinacija = (new $this)->kombinacija($lotto);
    $ponavljanje = (new $this)->ponavljanje($kombinacija, $lotto);
    while($ponavljanje) {
      $kombinacija = (new $this)->kombinacija($lotto);
      $ponavljanje = (new $this)->ponavljanje($kombinacija, $lotto);
    }
    dd($kombinacija,$ponavljanje,$lotto);
    return $http_get;
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
   * @param  \App\Models\Lotto  $lotto
   * @return \Illuminate\Http\Response
   */
  public function show(Lotto $lotto)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Lotto  $lotto
   * @return \Illuminate\Http\Response
   */
  public function edit(Lotto $lotto)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Lotto  $lotto
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Lotto $lotto)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Lotto  $lotto
   * @return \Illuminate\Http\Response
   */
  public function destroy(Lotto $lotto)
  {
    //
  }
}
