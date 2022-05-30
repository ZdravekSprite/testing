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
  public function symbolPriceTicker($symbol = null)
  {
    $params = '';
    if ($symbol) {
      if (is_countable($symbol) && count($symbol) > 1) {
        $params = '?symbols=["' . implode('","', $symbol) . '"]';
      } else {
        $params = '?symbol=' . $symbol;
      }
    }
    return (new BHttp)->get('https://api.binance.com/api/v3/ticker/price' . $params);
  }

  public function getAPIKeyPermission()
  {
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/account/apiRestrictions');
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
  public function getFlexibleProductList()
  {
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/lending/daily/product/list');
  }
  public function redeemFlexibleProduct($productId, $amount, $type = "NORMAL")
  {
    $array = array(
      "productId" => $productId, // STRING
      "amount" => $amount, // DECIMAL
      "type" => $type, // ENUM "FAST", "NORMAL"
    );
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/lending/daily/redeem', $array);
  }
  public function getFlexibleProductPosition()
  {
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/lending/daily/token/position');
  }
  public function lendingAccount()
  {
    return (new BHttp)->get_withHeaders('https://api.binance.com/sapi/v1/lending/union/account');
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
