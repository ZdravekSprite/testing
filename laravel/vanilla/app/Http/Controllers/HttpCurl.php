<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HttpCurl extends Controller
{
  public function curl($url = 'https://api.binance.com/sapi/v1/system/status', $method = 'GET', $params = [], $hmac = false)
  {
    $ch = curl_init();

    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
    );

    curl_setopt_array($ch, $defaults);

    $methodArray = [];
    //dd($hmac);
    if ($hmac) {
      $apiKey = Auth::user()->settings->BINANCE_API_KEY;
      $apiSecret = Auth::user()->settings->BINANCE_API_SECRET;
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-MBX-APIKEY: ' . $apiKey,
      ));
      $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
      $serverTime = $time->serverTime;
      $timestampArray = array(
        "timestamp" => $serverTime
      );
      $queryArray = $params ? $params + $timestampArray : $timestampArray;
      dd(http_build_query($queryArray));
      $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
      $signatureArray = array("signature" => $signature);
      $methodArray = $queryArray + $signatureArray;
    }

    // POST Method
    if ($method === "POST") {
      $query = http_build_query($methodArray, '', '&');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
      //dd($params,$queryArray,$methodArray,$query);
    }
    // PUT Method
    if ($method === "PUT") {
      curl_setopt($ch, CURLOPT_PUT, true);
    }
    // Delete Method
    if ($method === "DELETE") {
      $query = http_build_query($methodArray, '', '&');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
      //dd($params, $query);
    }

    $server_output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($server_output, true);
    return $json;
  }

  public function post($url, $params, $hmac)
  {
    $post = (new $this)->curl($url, 'POST', $params, $hmac);
    return $post;
  }
  public function delete($url, $params, $hmac)
  {
    $post = (new $this)->curl($url, 'DELETE', $params, $hmac);
    return $post;
  }
}
