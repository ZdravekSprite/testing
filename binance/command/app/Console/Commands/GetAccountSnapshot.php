<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
      $server = 'https://testnet.binance.vision/api';
      $ws = 'wss://testnet.binance.vision/ws';
      $stream = 'wss://testnet.binance.vision/stream';
      $apiKey = env('BINANCE_TEST_API_KEY');
      $apiSecret = env('BINANCE_TEST_API_SECRET');
    } else {
      $server = 'https://api.binance.com/api';
      $ws = 'wss://stream.binance.com:9443/ws';
      $stream = 'wss://stream.binance.com:9443/stream';
      $apiKey = env('BINANCE_API_KEY');
      $apiSecret = env('BINANCE_API_SECRET');
    }

    $response = Http::get('https://api.hnb.hr/tecajn/v2?valuta=EUR');
    $data = $response->json();
    $euro = str_replace(',', '.', $data[0]['kupovni_tecaj']) * 1;
    $this->line('Euro (kupovni tečaj u HNB):' . $euro);

    $response = Http::get($server . '/v3/exchangeInfo');
    $exchangeInfo = $response->json();
    //dd($exchangeInfo['serverTime']);

    /**
     * Get server time
     * the server time must be obtained to sign the requests curl
     * Time is the variable used for requests
     */
    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    /*
    $Time = $exchangeInfo['serverTime'];
     */
    //dd($exchangeInfo['serverTime'],$Time);
    $timeStamp = 'timestamp=' . $serverTime; // build timestamp type url get
    $signature = hash_hmac('SHA256', $timeStamp, $apiSecret); // build firm with sha256

    $account = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/account', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    //dd(json_decode($CallBalance)->balances);

    $openOrders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/openOrders', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    //dd($CallOpenOrders);

    $kn = -0.8 * $euro;
    $this->line('Balance:');
    foreach ($account->balances as $key => $crypto) {
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
              $res = Http::get($server . '/v3/ticker/price?symbol=' . $crypto->asset . 'EUR');
            } else {
              $res = Http::get($server . '/v3/ticker/price?symbol=EURUSDT');
            }
            $data = $res->json();
            if (isset($data['code'])) {
              $res = Http::get($server . '/v3/ticker/price?symbol=' . $crypto->asset . 'USDT');
              $data = $res->json();
              $usdt = $data['price'] * 1;
              $res = Http::get($server . '/v3/ticker/price?symbol=EURUSDT');
              $data = $res->json();
              $price = (1 - 0.0075) * $usdt / $data['price'];
            } else {
              if ($crypto->asset != 'USDT') {
                $price = $data['price'] * 1;
              } else {
                $price = 1 / $data['price'];
              }
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
    foreach ($openOrders as $key => $order) {
      $res = Http::get($server . '/v3/ticker/price?symbol=' . $order->symbol);
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
