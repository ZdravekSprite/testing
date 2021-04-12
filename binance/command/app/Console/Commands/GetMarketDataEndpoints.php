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
    echo gmdate("Y-m-d H:i:s", $serverTime / 1000);
  }
}
