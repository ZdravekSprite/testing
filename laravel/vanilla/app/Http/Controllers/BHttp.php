<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class BHttp extends Controller
{
  public function get($url)
  {
    $http_get = json_decode(Http::get($url));
    return $http_get;
  }

}