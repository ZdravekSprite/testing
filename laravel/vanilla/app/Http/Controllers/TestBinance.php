<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HttpCurl;

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
  public function openOrders($symbol = "ETHBUSD")
  {
    $array = array(
      "symbol" => $symbol
    );
    $openOrders = (new $this)->http_get_withHeaders('https://api.binance.com/api/v3/openOrders', $array);
    return $openOrders;
  }
  /*
   * New buy Order (TRADE)
   */
  public function buy($symbol, $quantity, $price, $newClientOrderId)
  {
    $side = "BUY";
    $type = "LIMIT_MAKER";
/*
    $symbol = "BTCBUSD";
    $quantity = 0.000325; // DECIMAL
    $price = 30845;
    $newClientOrderId = "buy_btc";
/*
    $symbol = "ETHBUSD";
    $quantity = 0.0055; // DECIMAL
    $price = 1819;
    $newClientOrderId = "buy_eth";
/*
    $symbol = "BNBBUSD";
    $quantity = 0.0379; // DECIMAL
    $price = 264;
    $newClientOrderId = "buy_bnb";
/*
    $symbol = "ADABUSD";
    $quantity = 8.63; // DECIMAL
    $price = 1.16;
    $newClientOrderId = "buy_ada";
/*
    $symbol = "MATICBUSD";
    $quantity = 9.1; // DECIMAL
    $price = 1.1;
    $newClientOrderId = "buy_matic1";
/*
    $symbol = "MATICBUSD";
    $quantity = 9.8; // DECIMAL
    $price = 1.025;
    $newClientOrderId = "buy_matic2";
/*
    $symbol = "SOLBUSD";
    $quantity = 0.359; // DECIMAL
    $price = 27.9;
    $newClientOrderId = "buy_sol1";
/*
    $symbol = "SOLBUSD";
    $quantity = 0.371; // DECIMAL
    $price = 27;
    $newClientOrderId = "buy_sol2";
*/
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
/*
    $symbol = "BTCBUSD";
    $quantity = 0.000292; // DECIMAL
    $price = 34272;
    $newClientOrderId = "sell_btc";
/*
    $symbol = "ETHBUSD";
    $quantity = 0.00495; // DECIMAL
    $price = 2021;
    $newClientOrderId = "sell_eth1";
/*
    $symbol = "ETHBUSD";
    $quantity = 0.00493; // DECIMAL
    $price = 2029;
    $newClientOrderId = "sell_eth2";
/*
    $symbol = "BNBBUSD";
    $quantity = 0.0342; // DECIMAL
    $price = 293;
    $newClientOrderId = "sell_bnb";
/*
    $symbol = "ADABUSD";
    $quantity = 7.77; // DECIMAL
    $price = 1.288;
    $newClientOrderId = "sell_ada1";
/*
    $symbol = "ADABUSD";
    $quantity = 7.45; // DECIMAL
    $price = 1.344;
    $newClientOrderId = "sell_ada2";
/*
    $symbol = "MATICBUSD";
    $quantity = 8.4; // DECIMAL
    $price = 1.2;
    $newClientOrderId = "sell_matic1";
/*
    $symbol = "MATICBUSD";
    $quantity = 8.2; // DECIMAL
    $price = 1.225;
    $newClientOrderId = "sell_matic2";
/*
    $symbol = "SOLBUSD";
    $quantity = 0.323; // DECIMAL
    $price = 31;
    $newClientOrderId = "sell_sol1";
/*
    $symbol = "SOLBUSD";
    $quantity = 0.313; // DECIMAL
    $price = 32;
    $newClientOrderId = "sell_sol2";
*/
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
    $order = (new $this)->http_post('https://api.binance.com/api/v3/order/test', $array);
    $curl = new HttpCurl();
    $order = $curl->post($url, $array, true);
    //dd($order);
    return $order;
  }

  /*
   * New sell Orders (TRADE)
   */
  public function sell_targets()
  {
    $sells = [];
    //$sells[] = (new $this)->sell("BTCBUSD", 0.000289, 34630.38, "sell_btc01");
    //$sells[] = (new $this)->sell("BTCBUSD", 0.000238, 42114.12, "sell_btc02");
    //$sells[] = (new $this)->sell("ETHBUSD", 0.00506, 2050, "sell_eth0");
    //$sells[] = (new $this)->sell("ETHBUSD", 0.00506, 1944.33, "sell_eth01");
    //$sells[] = (new $this)->sell("ETHBUSD", 0.00559, 2684.92, "sell_eth02");
    //$sells[] = (new $this)->sell("BNBBUSD", 0.0346, 289.16, "sell_bnb01");
    //$sells[] = (new $this)->sell("BNBBUSD", 0.0319, 313.93, "sell_bnb02");
    //$sells[] = (new $this)->sell("ADABUSD", 7.49, 1.35, "sell_ada0");
    //$sells[] = (new $this)->sell("ADABUSD", 7.49, 1.3365, "sell_ada01");
    //$sells[] = (new $this)->sell("ADABUSD", 7.15, 1.3996, "sell_ada02");
    //$sells[] = (new $this)->sell("ADABUSD", 7.15, 1.3996, "sell_ada_quick");
    //$sells[] = (new $this)->sell("MATICBUSD", 9.1, 1.10781, "sell_matic01");
    //$sells[] = (new $this)->sell("MATICBUSD", 8.1, 1.24375, "sell_matic02");
    //$sells[] = (new $this)->sell("SOLBUSD", 0.317, 31.64, "sell_sol01");
    //$sells[] = (new $this)->sell("SOLBUSD", 0.28, 35.81, "sell_sol02");
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
    //$buys[] = (new $this)->buy("SOLBUSD", 0.37, 27.07, "buy_sol01");
    //$buys[] = (new $this)->buy("SOLBUSD", 0.316, 31.687, "buy_sol02");
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
    //$test[] = (new $this)->openOrders_list();
    $test[] = (new $this)->sell_targets();
    //$test[] = (new $this)->buy_targets();
    //$test = new HttpCurl();
    dd($test);
    //dd($test->curl());
  }
}
