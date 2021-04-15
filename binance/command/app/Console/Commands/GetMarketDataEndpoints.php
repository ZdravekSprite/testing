<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use API;

class GetMarketDataEndpoints extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'binance:get-market-data-endpoints';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get Market Data Endpoints';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $test = false;

    if ($test) {
      $Server = 'https://testnet.binance.vision/api';
      $ws = 'wss://testnet.binance.vision/ws';
      $stream = 'wss://testnet.binance.vision/stream';
      $ApiKey = env('BINANCE_TEST_API_KEY');
      $ApiSecret = env('BINANCE_TEST_API_SECRET');
    } else {
      $Server = 'https://api.binance.com/api';
      $ws = 'wss://stream.binance.com:9443/ws';
      $stream = 'wss://stream.binance.com:9443/stream';
      $ApiKey = env('BINANCE_API_KEY');
      $ApiSecret = env('BINANCE_API_SECRET');
    }

    $this->line('Get Market Data Endpoints:');
    $this->line('Test Connectivity:');
    /*
     *Test Connectivity
     * Response:
     *  {}
     * GET /api/v3/ping
     *
     *Test connectivity to the Rest API.
     * Weight: 1
     * Parameters:
     *  NONE
    */
    $this->line('Check Server Time:');
    /*
     *Check Server Time
     * Response:
     *  {
     *   "serverTime": 1499827319559
     *  }
     * GET /api/v3/time
     *Test connectivity to the Rest API and get the current server time.
     * Weight: 1
     * Parameters:
     *  NONE
    */
    //dd(json_decode(Http::get($Server . '/v3/time')));
    $DecodeTime = json_decode(Http::get($Server . '/v3/time'));
    $serverTime = $DecodeTime->serverTime;
    $this->line('$serverTime: ' . gmdate("Y-m-d H:i:s", $serverTime / 1000));
    $this->line('Exchange Information:');
    /*
     *Exchange Information
     * Response:
     *  {
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
     *  }
     * GET /api/v3/exchangeInfo
     *Current exchange trading rules and symbol information
     * Weight: 1
     * Parameters:
     *  NONE
     */
    //dd(json_decode(Http::get($Server . '/v3/exchangeInfo')));
    $exchangeInfo = json_decode(Http::get($Server . '/v3/exchangeInfo'));
    $timezone = $exchangeInfo->timezone;
    $this->line('$timezone: ' . $timezone);
    $serverTime = $exchangeInfo->serverTime;
    $this->line('$serverTime: ' . gmdate("Y-m-d H:i:s", $serverTime / 1000));
    $rateLimits = $exchangeInfo->rateLimits;
    $this->line('$rateLimits: ' . json_encode($rateLimits));
    foreach ($rateLimits as $key => $value) {
      $this->line($key . ': ' . json_encode($value));
    }
    $exchangeFilters = $exchangeInfo->exchangeFilters;
    $this->line('$exchangeFilters: ' . json_encode($exchangeFilters));
    foreach ($exchangeFilters as $key => $value) {
      $this->line($key . ': ' . json_encode($value));
    }
    $symbols = $exchangeInfo->symbols;
    //$this->line('$symbols: ' . json_encode($symbols));
    $this->line('$symbols:');
    foreach ($symbols as $key => $value) {
      $symbol = $value->symbol;
      if ($value->quoteAsset == 'USDT') {
        $klines = json_decode(Http::get($Server . '/v3/klines?symbol='.$symbol.'&interval=1m&limit=5'));
        //dd($klines[0][1]);
        $tick = 100 * ($klines[0][1] - $klines[4][4]) / $klines[0][1];
        if ($tick < -5 ) $this->line($symbol . ' ' . $tick);
      }
    }
    /* Kline/Candlestick Data */
    //dd(json_decode(Http::get($Server . '/v3/klines?symbol=BNBUSDT&interval=1m&limit=10')));
    $klines = json_decode(Http::get($Server . '/v3/klines?symbol=TRXUSDT&interval=1m&limit=5'));
    foreach ($klines as $key => $value) {
      //dd($value);
      $this->line($key . ': ' . gmdate("Y-m-d H:i:s", $value[0] / 1000) . ' ' . $value[1] . ' -> ' . $value[4] . ' '  . gmdate("Y-m-d H:i:s", $value[6] / 1000));
    }
  }
}
