<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BApi extends Controller
{
  public function time()
  {
    return (new BHttp)->get('https://api.binance.com/api/v3/time');
  }
  public function serverTime()
  {
    $time = (new BHttp)->get('https://api.binance.com/api/v3/time');
    return $time->serverTime;
  }
  public function systemStatus()
  {
    return (new BHttp)->get('https://api.binance.com/sapi/v1/system/status');
  }
  public function getAPIKeyPermission()
  {
    return (new BHttp)->get('https://api.binance.com/sapi/v1/account/apiRestrictions');
  }
  public function allCoinsInformation()
  {
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/capital/config/getall');
  }
  public function myCoinsInformation()
  {
    $allCoinsInformation = (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/capital/config/getall');
    $collection = collect($allCoinsInformation);
    $filtered = $collection->filter(function ($value, $key) {
      return ($value->free + $value->locked + $value->freeze + $value->withdrawing + $value->ipoing + $value->ipoable + $value->storage) > 0;
    });
    return $filtered;
  }
  public function getFlexibleProductPosition()
  {
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/lending/daily/token/position');
  }
  public function getStakingProductPosition()
  {
    $array = array(
      "product" => "STAKING"
    );
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/staking/position', $array);
  }
  public function dailyAccountSnapshot()
  {
    $array = array(
      "type" => "SPOT"
    );
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/accountSnapshot', $array);
    /**
     * if ($accountSnapshot->code !== 200) return $accountSnapshot->msg;
     */
  }
}
