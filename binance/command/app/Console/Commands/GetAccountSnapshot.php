<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
/*
 * Get Balance Binance Exchange
 * Documentation https://github.com/binance-exchange/binance-official-api-docs/blob/master/rest-api.md
 */
use API;

class GetAccountSnapshot extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'binance:get-account-snapshot';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get Daily Account Snapshot (USER_DATA) from binance';

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

    $response = Http::get('https://api.hnb.hr/tecajn/v2?valuta=EUR');
    $data = $response->json();
    $euro = str_replace(',', '.', $data[0]['kupovni_tecaj']) * 1;
    $this->line('Euro (kupovni tečaj u HNB):' . $euro);

    $response = Http::get($Server . '/v3/exchangeInfo');
    $exchangeInfo = $response->json();
    //dd($exchangeInfo['serverTime']);

    /**
     * Get server time
     * the server time must be obtained to sign the requests curl
     * Time is the variable used for requests
     */
    $ServerTimeUrl = $Server . '/v3/time';
    $ClassServerTime = new APIREST($ServerTimeUrl);
    $CallServerTime = $ClassServerTime->call(array());
    $DecodeCallTime = json_decode($CallServerTime);
    $Time = $DecodeCallTime->serverTime;
    /*
    $Time = $exchangeInfo['serverTime'];
     */
    //dd($exchangeInfo['serverTime'],$Time);
    $Timestamp = 'timestamp=' . $Time; // build timestamp type url get
    $Signature = hash_hmac('SHA256', $Timestamp, $ApiSecret); // build firm with sha256
    /**
     * Get balance
     * @var BalanceUrl is the url of the request
     * @var ClassBalance initializes the APIREST class
     * @var CallBalance request balance sheets, X-MBX-APIKEY is required by binance api
     */
    $BalanceUrl = $Server . '/v3/account?timestamp=' . $Time . '&signature=' . $Signature;
    $ClassBalance = new APIREST($BalanceUrl);
    $CallBalance = $ClassBalance->call(
      array('X-MBX-APIKEY:' . $ApiKey)
    );
    //dd(json_decode($CallBalance)->balances);
    /**
     * Get balance
     * @var OpenOrdersUrl is the url of the request
     * @var ClassOpenOrders initializes the APIREST class
     * @var CallOpenOrders request balance sheets, X-MBX-APIKEY is required by binance api
     */
    $OpenOrdersUrl = $Server . '/v3/openOrders?timestamp=' . $Time . '&signature=' . $Signature;
    $ClassOpenOrders = new APIREST($OpenOrdersUrl);
    $CallOpenOrders = $ClassOpenOrders->call(
      array('X-MBX-APIKEY:' . $ApiKey)
    );
    //dd($CallOpenOrders);

    $kn = -0.8 * $euro;
    $this->line('Balance:');
    foreach (json_decode($CallBalance)->balances as $key => $crypto) {
      //dd($crypto);
      $total = $crypto->free * 1 + $crypto->locked * 1;
      //dd($total);
      if ($total > 0) {
        if ($test) {
          $this->line($crypto->asset . ': ' . $total);
        } else {
          $price = 1;
          if ($crypto->asset == 'EUR') {
            $total_kn = $total * $euro;
          } else {
            if ($crypto->asset != 'USDT') {
              $res = Http::get($Server . '/v3/ticker/price?symbol=' . $crypto->asset . 'EUR');
            } else {
              $res = Http::get($Server . '/v3/ticker/price?symbol=EURUSDT');
            }
            $data = $res->json();
            //dd($data);
            if ($crypto->asset != 'USDT') {
              $price = $data['price'] * 1;
            } else {
              $price = 1 / $data['price'];
            }
            $total_kn = $total * $price * $euro * (1 - 0.00075);
          }
          $kn += $total_kn;
          $this->line($crypto->asset . ': < ' . $total_kn . ' kn(HNB) [' . $total . ' ' . $price . ' euro]');
        }
      }
    }
    if (!$test) $this->line($kn . ' kn(HNB)');
    /*
    $cryptos = $this->withProgressBar(json_decode($CallBalance)->balances, function ($crypto) use ($euro) {
      dd($crypto->asset);
    });
    */

    //dd($exchangeInfo['symbols'][0]);
    $this->newLine();
    $this->line('Orders:');
    foreach (json_decode($CallOpenOrders) as $key => $order) {
      $res = Http::get($Server . '/v3/ticker/price?symbol=' . $order->symbol);
      $data = $res->json();
      //dd($data);
      foreach ($exchangeInfo['symbols'] as $key => $symbol) {
        if ($symbol['symbol'] == $order->symbol) {
          $order->baseAsset = $symbol['baseAsset'];
          $order->quoteAsset = $symbol['quoteAsset'];
        }
      }
      $this->line(
        'date: ' . gmdate("Y-m-d H:i:s", $order->time / 1000)
          . ' pair: ' . $order->symbol
          . ' type: ' . $order->type
          . ' side: ' . $order->side
          . ' price: ' . $order->price * 1 . ' ( ' . (round(100 * $data['price'] / $order->price, 2) - 100) . '%)'
          . ' amount: ' . $order->origQty * 1 . ' ' . $order->baseAsset . ' (' . 1 * $order->origQty * $order->price . ' ' . $order->quoteAsset . ')'
        /*. ' flled: '
          . ' total: '
          . ' trigger condition: '*/
      );
    }
  }
}
