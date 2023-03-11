<?php

namespace App\Http\Controllers;

class BSystem extends Controller
{

  /**
   * System Status (System)
   * GET /sapi/v1/system/status
   * Fetch system status.
   * Weight(IP): 1
   * Response
   * { 
   *     "status": 0,              // 0: normal，1：system maintenance
   *     "msg": "normal"           // normal|system maintenance
   * }
   */
  public function status()
  {
    $status = (new BHttp)->get('https://api.binance.com/sapi/v1/system/status');
    return $status;
  }

  /**
   * Test Connectivity
   * GET /api/v3/ping
   * Test connectivity to the Rest API.
   * Weight(IP): 1
   * Parameters:
   * NONE
   * Data Source: Memory
   * Response:
   * {}
   */
  public function ping()
  {
    $ping = (new BHttp)->get('https://api.binance.com/api/v3/ping');
    return $ping;
  }

  /**
   * Check Server Time
   * GET /api/v3/time
   * Test connectivity to the Rest API and get the current server time.
   * Weight(IP): 1
   * Parameters:
   * NONE
   * Data Source: Memory
   * Response:
   * {
   *   "serverTime": 1499827319559
   * }
   */
  public function time()
  {
    $time = (new BHttp)->get('https://api.binance.com/api/v3/time');
    return $time;
  }
  public function serverTime()
  {
    $time = (new BHttp)->get('https://api.binance.com/api/v3/time');
    return $time->serverTime;
  }

  /**
   * Exchange Information
   * GET /api/v3/exchangeInfo
   * Current exchange trading rules and symbol information
   * Weight(IP): 10
   * Parameters:
   * There are 3 possible options:
   * Options	Example
   * No parameter	curl -X GET "https://api.binance.com/api/v3/exchangeInfo"
   * symbol	curl -X GET "https://api.binance.com/api/v3/exchangeInfo?symbol=BNBBTC"
   * symbols	curl -X GET "https://api.binance.com/api/v3/exchangeInfo?symbols=%5B%22BNBBTC%22,%22BTCUSDT%22%5D" or curl -g GET 'https://api.binance.com/api/v3/exchangeInfo?symbols=["BTCUSDT","BNBBTC"]'
   * If any symbol provided in either symbol or symbols do not exist, the endpoint will throw an error.
   * Data Source: Memory
   * Response:
   * {
   *   "timezone": "UTC",
   *   "serverTime": 1565246363776,
   *   "rateLimits": [
   *     {
   *       //These are defined in the `ENUM definitions` section under `Rate Limiters (rateLimitType)`.
   *       //All limits are optional
   *     }
   *   ],
   *   "exchangeFilters": [
   *     //These are the defined filters in the `Filters` section.
   *     //All filters are optional.
   *   ],
   *   "symbols": [
   *     {
   *       "symbol": "ETHBTC",
   *       "status": "TRADING",
   *       "baseAsset": "ETH",
   *       "baseAssetPrecision": 8,
   *       "quoteAsset": "BTC",
   *       "quotePrecision": 8,
   *       "quoteAssetPrecision": 8,
   *       "orderTypes": [
   *         "LIMIT",
   *         "LIMIT_MAKER",
   *         "MARKET",
   *         "STOP_LOSS",
   *         "STOP_LOSS_LIMIT",
   *         "TAKE_PROFIT",
   *         "TAKE_PROFIT_LIMIT"
   *       ],
   *       "icebergAllowed": true,
   *       "ocoAllowed": true,
   *       "isSpotTradingAllowed": true,
   *       "isMarginTradingAllowed": true,
   *       "filters": [
   *         //These are defined in the Filters section.
   *         //All filters are optional
   *       ],
   *       "permissions": [
   *          "SPOT",
   *          "MARGIN"
   *       ]
   *     }
   *   ]
   * }
   */
  public static function exchangeInfo($symbol = null)
  {
    $http = new BHttp();
    if ($symbol == null) {
      $exchangeInfo = $http->get('https://api.binance.com/api/v3/exchangeInfo');
    } else {
      $exchangeInfo = $http->get('https://api.binance.com/api/v3/exchangeInfo?symbol='.$symbol);
    }
    //dd($exchangeInfo);
    return $exchangeInfo;
  }
}