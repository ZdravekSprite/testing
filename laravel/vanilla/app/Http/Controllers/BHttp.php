<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class BHttp extends Controller
{
  public function get($url)
  {
    $http_get = json_decode(Http::get($url));
    return $http_get;
  }

  public function get_withHeaders($url, $array = null)
  {
    $apiKey = Auth::user()->settings->BINANCE_API_KEY;
    $apiSecret = Auth::user()->settings->BINANCE_API_SECRET;
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
}