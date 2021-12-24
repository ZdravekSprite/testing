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
    while ($ponavljanje) {
      $kombinacija = (new $this)->kombinacija($lotto);
      $ponavljanje = (new $this)->ponavljanje($kombinacija, $lotto);
    }
    dd($kombinacija, $ponavljanje, $lotto);
    return $http_get;
  }

  // https://www.sazka.cz/api/draw-info/past-draws/eurojackpot
  // https://www.sazka.cz/api/draw-info/draws/universal/eurojackpot/[DRAW_ID]


  /**
   * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
   * array containing the HTTP server response header fields and content.
   */
  function get_web_page($url)
  {
    $options = array(
      CURLOPT_RETURNTRANSFER => true,     // return web page
      CURLOPT_HEADER         => false,    // don't return headers
      CURLOPT_FOLLOWLOCATION => true,     // follow redirects
      CURLOPT_ENCODING       => "",       // handle all encodings
      CURLOPT_USERAGENT      => "spider", // who am i
      CURLOPT_AUTOREFERER    => true,     // set referer on redirect
      CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
      CURLOPT_TIMEOUT        => 120,      // timeout on response
      CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
      CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err     = curl_errno($ch);
    $errmsg  = curl_error($ch);
    $header  = curl_getinfo($ch);
    curl_close($ch);

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
  }

  public function hl(Request $request)
  {
    dd($request);
    $kolo = $request->input('kolo') ?? 1;
    return $kolo;
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
