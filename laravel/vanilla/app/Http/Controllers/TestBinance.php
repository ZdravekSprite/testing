<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class TestBinance extends Controller
{
  public function http_get($url)
  {
    $http_get = json_decode(Http::get($url));
    return $http_get;
  }
  public function http_get_withHeaders($url, $array = null)
  {
    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
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
  public function http_delete($url, $array = null)
  {
    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $timestampArray = array(
      "timestamp" => $serverTime
    );
    $queryArray = $array ? $array + $timestampArray : $timestampArray;
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $deleteArray = $queryArray + $signatureArray;
    $http_delete = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->delete($url, $deleteArray));
    return $http_delete;
  }
  public function http_post($url, $array = null)
  {
    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $timestampArray = array(
      "timestamp" => $serverTime
    );
    $queryArray = $array ? $array + $timestampArray : $timestampArray;
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $postArray = $queryArray + $signatureArray;
    //dd($postArray);
    $http_post = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->post($url, $postArray));
    return $http_post;
  }
  /**
   * System Status (System)
   * GET /sapi/v1/system/status
   * Fetch system status.
   * 
   * Response
   * { 
   *     "status": 0,              // 0: normal，1：system maintenance
   *     "msg": "normal"           // normal|system maintenance
   * }
   */
  public function systemStatus()
  {
    $systemStatus = (new $this)->http_get('https://api.binance.com/sapi/v1/system/status');
    return $systemStatus;
  }

  /**
   * All Coins' Information (USER_DATA)
   * GET /sapi/v1/capital/config/getall (HMAC SHA256)
   * Get information of coins (available for deposit and withdraw) for user.
   * Weight: 1
   * Parameters:
   * Name	Type	Mandatory	Description
   * recvWindow	LONG	NO
   * timestamp	LONG	YES
   * Response
   * [ 
   *     {
   *         "coin": "BTC",
   *         "depositAllEnable": true,
   *         "free": "0.08074558",
   *         "freeze": "0.00000000",
   *         "ipoable": "0.00000000",
   *         "ipoing": "0.00000000",
   *         "isLegalMoney": false,
   *         "locked": "0.00000000",
   *         "name": "Bitcoin",
   *         "networkList": [
   *             {
   *             }
   *         ],
   *         "storage": "0.00000000",
   *         "trading": true,
   *         "withdrawAllEnable": true,
   *         "withdrawing": "0.00000000"
   *     }
   * ]
   */
  public function capitalConfigGetall()
  {
    $capitalConfigGetall = (new $this)->http_get_withHeaders('https://api.binance.com/sapi/v1/capital/config/getall');
    return $capitalConfigGetall;
  }

  /**
   * Daily Account Snapshot (USER_DATA)
   * GET /sapi/v1/accountSnapshot (HMAC SHA256)
   * Weight: 1
   * Parameters:
   * Name	Type	Mandatory	Description
   * type	STRING	YES	"SPOT", "MARGIN", "FUTURES"
   * startTime	LONG	NO	
   * endTime	LONG	NO	
   * limit	INT	NO	min 5, max 30, default 5
   * recvWindow	LONG	NO
   * timestamp	LONG	YES
   * Response
   * {
   *    "code":200, // 200 for success; others are error codes
   *    "msg":"", // error message
   *    "snapshotVos":[
   *       {
   *          "data":{
   *             "balances":[
   *                {
   *                   "asset":"BTC",
   *                   "free":"0.09905021",
   *                   "locked":"0.00000000"
   *                },
   *                {
   *                   "asset":"USDT",
   *                   "free":"1.89109409",
   *                   "locked":"0.00000000"
   *                }
   *             ],
   *             "totalAssetOfBtc":"0.09942700"
   *          },
   *          "type":"spot",
   *          "updateTime":1576281599000
   *       }
   *    ]
   * }
   */
  public function accountSnapshot()
  {
    $array = array(
      "type" => "SPOT"
    );
    $accountSnapshot = (new $this)->http_get_withHeaders('https://api.binance.com/sapi/v1/accountSnapshot', $array);
    return $accountSnapshot;
  }

  /**
   * Current Open Orders (USER_DATA)
   * GET /api/v3/openOrders (HMAC SHA256)
   * Get all open orders on a symbol. Careful when accessing this with no symbol.
   * Weight: 3 for a single symbol; 40 when the symbol parameter is omitted
   * Parameters:
   * Name	Type	Mandatory	Description
   * symbol	STRING	NO
   * recvWindow	LONG	NO	The value cannot be greater than 60000
   * timestamp	LONG	YES
   * * If the symbol is not sent, orders for all symbols will be returned in an array.
   * Data Source: Database
   * Response
   * [
   *   {
   *     "symbol": "LTCBTC",
   *     "orderId": 1,
   *     "orderListId": -1, //Unless OCO, the value will always be -1
   *     "clientOrderId": "myOrder1",
   *     "price": "0.1",
   *     "origQty": "1.0",
   *     "executedQty": "0.0",
   *     "cummulativeQuoteQty": "0.0",
   *     "status": "NEW",
   *     "timeInForce": "GTC",
   *     "type": "LIMIT",
   *     "side": "BUY",
   *     "stopPrice": "0.0",
   *     "icebergQty": "0.0",
   *     "time": 1499827319559,
   *     "updateTime": 1499827319559,
   *     "isWorking": true,
   *     "origQuoteOrderQty": "0.000000"
   *   }
   * ]
   */
  public function openOrders()
  {
    $symbol = "ETHBUSD";
    $array = array(
      "symbol" => $symbol
    );
    $openOrders = (new $this)->http_get_withHeaders('https://api.binance.com/api/v3/openOrders', $array);
    return $openOrders;
  }
  /*
   * New Order (TRADE)
   */
  public function order()
  {
    $symbol = "ETHBUSD";
    $side = "SELL";
    $type = "LIMIT_MAKER"; // "LIMIT" "LIMIT_MAKER" "MARKET"
    //$timeInForce = "GTC";
    $quantity = 0.004; // DECIMAL
    //$quoteOrderQty = 10.01528;
    $price = 2503.82;
    $newClientOrderId = "newClientOrderIdTest";
    $array = array(
      "symbol" => $symbol,
      "side" => $side,
      "type" => $type,
      //"timeInForce" => $timeInForce,
      "quantity" => $quantity,
      //"quoteOrderQty" => $quoteOrderQty,
      "price" => $price,
      "newClientOrderId" => $newClientOrderId
    );
    $order = (new $this)->http_post('https://api.binance.com/api/v3/order/test', $array);
    return $order;
  }

  public function curl_post()
  {

    $url = 'https://api.binance.com/api/v3/order';
    $symbol = "ETHBUSD";
    $side = "SELL";
    $type = "LIMIT_MAKER"; // "LIMIT" "LIMIT_MAKER" "MARKET"
    //$timeInForce = "GTC";
    $quantity = 0.004; // DECIMAL
    //$quoteOrderQty = 10.01528;
    $price = 2503.82;
    $newClientOrderId = "newClientOrderIdTest";
    $array = array(
      "symbol" => $symbol,
      "side" => $side,
      "type" => $type,
      //"timeInForce" => $timeInForce,
      "quantity" => $quantity,
      //"quoteOrderQty" => $quoteOrderQty,
      "price" => $price,
      "newClientOrderId" => $newClientOrderId
    );

    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $timestampArray = array(
      "timestamp" => $serverTime
    );
    $queryArray = $array ? $array + $timestampArray : $timestampArray;
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $postArray = $queryArray + $signatureArray;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'X-MBX-APIKEY: ' . $apiKey,
  ));
    curl_setopt($curl, CURLOPT_POST, 1);
    $query = http_build_query($postArray, '', '&');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($curl);
    curl_close ($curl);
    $json = json_decode($server_output, true);
    dd($json);
    return $json;
  }
  public function test()
  {
    $test = (new $this)->curl_post();
    dd($test);
  }
}
