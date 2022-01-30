<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HttpCurl;

class TestBinance extends Controller
{
  public function http_delete($url, $array = null)
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
    $deleteArray = $queryArray + $signatureArray;
    $http_delete = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->delete($url, $deleteArray));
    return $http_delete;
  }
  public function http_post($url, $array = null)
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
    $postArray = $queryArray + $signatureArray;
    //dd($postArray);
    $http_post = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->post($url, $postArray));
    return $http_post;
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
    $http = new BHttp();
    $capitalConfigGetall = $http->get_withHeaders('https://api.binance.com/sapi/v1/capital/config/getall');
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
    $http = new BHttp();
    $accountSnapshot = $http->get_withHeaders('https://api.binance.com/sapi/v1/accountSnapshot', $array);
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
  public function openOrders($symbol = "ETHBUSD")
  {
    $array = array(
      "symbol" => $symbol
    );
    $http = new BHttp();
    $openOrders = $http->get_withHeaders('https://api.binance.com/api/v3/openOrders', $array);
    return $openOrders;
  }
  /*
   * New buy Order (TRADE)
   */
  public function buy($symbol, $quantity, $price, $newClientOrderId)
  {
    $side = "BUY";
    $type = "LIMIT_MAKER";
    $buy = (new $this)->order($symbol, $side, $type, $quantity, $price, $newClientOrderId);
    //dd($buy);
    return $buy;
  }
  /*
   * New sell Order (TRADE)
   */
  public function sell($symbol, $quantity, $price, $newClientOrderId)
  {
    $side = "SELL";
    $type = "LIMIT_MAKER";
    $sell = (new $this)->order($symbol, $side, $type, $quantity, $price, $newClientOrderId);
    //dd($sell);
    return $sell;
  }
  /*
   * New Order (TRADE)
    $type = "LIMIT" "LIMIT_MAKER" "MARKET"
    $side = "SELL" "BUY"
   */
  public function order($symbol, $side, $type, $quantity, $price, $newClientOrderId)
  {
    $url = 'https://api.binance.com/api/v3/order';
    //$timeInForce = "GTC";
    //$quoteOrderQty = 10.01528;
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
    //dd($array);
    //$order = (new $this)->http_post('https://api.binance.com/api/v3/order/test', $array);
    $curl = new HttpCurl();
    $order = $curl->post($url, $array, true);
    if (isset($order["code"]) && $order["code"] == -2010) {
      $del_array = array(
        "symbol" => $symbol,
        //"orderId" => $orderId,
        "origClientOrderId" => $newClientOrderId,
        "newClientOrderId" => $newClientOrderId . '_del'
      );
      $order = $curl->delete($url, $del_array, true);
      $order = $curl->post($url, $array, true);
    };
    //dd($order);
    return $order;
  }

  /*
   * New sell Orders (TRADE)
   */
  public function sell_targets()
  {
    $sells = [];
    //$sells[] = (new $this)->sell("BTCBUSD", 0.00021, 48894.81, "sell_btc01");
    //$sells[] = (new $this)->sell("BTCBUSD", 0.00169, 49603.56, "sell_btc02");
    //$sells[] = (new $this)->sell("ETHBUSD", 0.0068, 4241.99, "sell_eth02");
    //$sells[] = (new $this)->sell("ETHBUSD", 0.0024, 4171.1, "sell_eth01");
    //$sells[] = (new $this)->sell("BNBBUSD", 0.015, 666.7, "sell_bnb00");
    //$sells[] = (new $this)->sell("BNBBUSD", 0.017, 588.3, "sell_bnb01");
    //$sells[] = (new $this)->sell("BNBBUSD", 0.019, 545.3, "sell_bnb02");
    //$sells[] = (new $this)->sell("ADABUSD", 4.0, 2.5, "sell_ada00");
    //$sells[] = (new $this)->sell("ADABUSD", 4.1, 2.44, "sell_ada01");
    //$sells[] = (new $this)->sell("ADABUSD", 4.2, 2.381, "sell_ada02");
    //$sells[] = (new $this)->sell("ADABUSD", 22.5, 1.995, "sell_ada_quick");
    //$sells[] = (new $this)->sell("MATICBUSD", 2.4, 4.3, "sell_matic00");
    //$sells[] = (new $this)->sell("MATICBUSD", 3.2, 3.2, "sell_matic01");
    //$sells[] = (new $this)->sell("MATICBUSD", 4.8, 2.1, "sell_matic02");
    //$sells[] = (new $this)->sell("SOLBUSD", 0.03, 333.34, "sell_sol00");
    //$sells[] = (new $this)->sell("SOLBUSD", 0.04, 250.00, "sell_sol01");
    //$sells[] = (new $this)->sell("SOLBUSD", 0.05, 240.00, "sell_sol02");
    //$sells[] = (new $this)->sell("LUNABUSD", 0.13, 76.93, "sell_luna00");
    //$sells[] = (new $this)->sell("LUNABUSD", 0.15, 66.67, "sell_luna01");
    //$sells[] = (new $this)->sell("LUNABUSD", 0.17, 58.83, "sell_luna02");
    //$sells[] = (new $this)->sell("FTTBUSD", 0.74, 88,52, "sell_ftt02");
    //$sells[] = (new $this)->sell("FTTBUSD", 0.14, 71.94, "sell_ftt01");
    //$sells[] = (new $this)->sell("EURBUSD", 8.6, 1.163, "sell_eur01");
    //$sells[] = (new $this)->sell("EURBUSD", 8.5, 1.177, "sell_eur02");
    //$sells[] = (new $this)->sell("EURBUSD", 8.4, 1.191, "sell_eur02");
    //$sells[] = (new $this)->sell("BUSDDAI", 10.0, 1.0005, "buy_dai00");
    //$sells[] = (new $this)->sell("BUSDDAI", 10.0, 1.0010, "buy_dai01");
    //$sells[] = (new $this)->sell("BUSDDAI", 10.0, 1.0020, "buy_dai02");
    //$sells[] = (new $this)->sell("BUSDDAI", 10.0, 1.0030, "buy_dai03");
    //$sells[] = (new $this)->sell("BUSDDAI", 10.0, 1.0040, "buy_dai04");
    //$sells[] = (new $this)->sell("BUSDDAI", 10.0, 1.0050, "buy_dai05");
    //$sells[] = (new $this)->sell("BUSDDAI", 60.0, 0.9992, "buy_dai_quick");
    //dd($sells);
    return $sells;
  }
  /*
   * New buy Orders (TRADE)
   */
  public function buy_targets()
  {
    $buys = [];
    //$buys[] = (new $this)->buy("BTCBUSD", 0.000329, 30454.74, "buy_btc01");
    //$buys[] = (new $this)->buy("BTCBUSD", 0.000303, 33024.03, "buy_btc02");
    //$buys[] = (new $this)->buy("ETHBUSD", 0.00577, 1734.58, "buy_eth01");
    //$buys[] = (new $this)->buy("ETHBUSD", 0.00491, 2038.18, "buy_eth02");
    //$buys[] = (new $this)->buy("BNBBUSD", 0.0374, 267.96, "buy_bnb01");
    //$buys[] = (new $this)->buy("BNBBUSD", 0.0359, 279.25, "buy_bnb02");
    //$buys[] = (new $this)->buy("ADABUSD", 8.28, 1.2078, "buy_ada01");
    //$buys[] = (new $this)->buy("ADABUSD", 7.71, 1.2979, "buy_ada02");
    //$buys[] = (new $this)->buy("ADABUSD", 7.19, 1.3926, "buy_ada_quick");
    //$buys[] = (new $this)->buy("MATICBUSD", 9.9, 1.01583, "buy_matic01");
    //$buys[] = (new $this)->buy("MATICBUSD", 9.9, 1.0108, "buy_matic02");
    //$buys[] = (new $this)->buy("SOLBUSD", 0.15, 70.28, "buy_sol01");
    //$buys[] = (new $this)->buy("SOLBUSD", 0.316, 31.687, "buy_sol02");
    //$buys[] = (new $this)->buy("LUNABUSD", 0.19, 52.64, "buy_luna01");
    //$buys[] = (new $this)->buy("LUNABUSD", 0.3, 50.00, "buy_luna02");
    //$buys[] = (new $this)->buy("LUNABUSD", 0.41, 48.79, "buy_luna03");
    //$buys[] = (new $this)->buy("DOTBUSD", 0.19, 52.64, "buy_dot01");
    //$buys[] = (new $this)->buy("DOTBUSD", 0.19, 53.00, "buy_dot02");
    //$buys[] = (new $this)->buy("EURBUSD", 8.9, 1.124, "buy_eur01");
    //$buys[] = (new $this)->buy("EURBUSD", 9.0, 1.112, "buy_eur02");
    //$buys[] = (new $this)->buy("EURBUSD", 9.1, 1.099, "buy_eur03");
    //$buys[] = (new $this)->buy("BUSDDAI", 10.0, 1.0000, "sell_dai01");
    //dd($buys);
    return $buys;
  }
  public function openOrders_list()
  {
    $openOrders = [];
    $openOrders[] = (new $this)->openOrders("BTCBUSD");
    $openOrders[] = (new $this)->openOrders("ETHBUSD");
    $openOrders[] = (new $this)->openOrders("BNBBUSD");
    $openOrders[] = (new $this)->openOrders("ADABUSD");
    $openOrders[] = (new $this)->openOrders("MATICBUSD");
    $openOrders[] = (new $this)->openOrders("SOLBUSD");
    $openOrders[] = (new $this)->openOrders("LUNABUSD");
    //dd($openOrders);
    return $openOrders;
  }
  public function test()
  {
    $test = [];
    $symbol = 'ADABUSD';
    $interval = '1w';
    $limit = 1;
    $klines = json_decode(Http::get('https://api.binance.com/api/v3/klines', [
      'symbol' => $symbol,
      'interval' => $interval,
      'limit' => $limit
    ]));
    $test[] = $klines;
    $test[] = (new $this)->sell_targets();
    $test[] = (new $this)->buy_targets();
    $test[] = (new $this)->openOrders_list();
    //$test = new HttpCurl();
    dd($test);
    //dd($test->curl());
  }
}
