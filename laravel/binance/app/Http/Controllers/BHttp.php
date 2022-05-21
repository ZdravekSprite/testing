<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BHttp extends Controller
{
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
}
