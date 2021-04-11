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
    $response = Http::get('https://api.hnb.hr/tecajn/v2?valuta=EUR');
    $data = $response->json();
    $euro = str_replace(',', '.', $data[0]['kupovni_tecaj']) * 1;
    $this->line('Euro (kupovni tečaj u HNB):' . $euro);

    /*
     * Get Balance Binance Exchange
     * Documentation https://github.com/binance-exchange/binance-official-api-docs/blob/master/rest-api.md
     */
    //include('API.php');
    /**
     * Get server time
     * the server time must be obtained to sign the requests curl
     * Time is the variable used for requests
     */
    /* Real
    $Server = 'https://api.binance.com/api';
    $ws = 'wss://stream.binance.com:9443/ws';
    $stream = 'wss://stream.binance.com:9443/stream';
    $ApiKey = env('BINANCE_API_KEY', false); // the Api key provided by binance
    $ApiSecret = env('BINANCE_API_SECRET', false); // the Secret key provided by binance
     */
    /* Test */
    $Server = 'https://testnet.binance.vision/api';
    $ws = 'wss://testnet.binance.vision/ws';
    $stream = 'wss://testnet.binance.vision/stream';
    $ApiKey = env('BINANCE_TEST_API_KEY');
    $ApiSecret = env('BINANCE_TEST_API_SECRET');

    $ServerTimeUrl = $Server . '/v1/time';
    $ClassServerTime = new APIREST($ServerTimeUrl);
    $CallServerTime = $ClassServerTime->call(array());
    $DecodeCallTime = json_decode($CallServerTime);
    $Time = $DecodeCallTime->serverTime;
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
    //echo "$CallBalance";
    //dd(json_decode($CallBalance)->balances);
    $kn = 0;
    $this->line('Balance:');
    foreach (json_decode($CallBalance)->balances as $key => $crypto) {
      //dd($crypto);
      $total = $crypto->free * 1 + $crypto->locked * 1;
      //dd($total);
      if ($total > 0) {
        /*
        $price = 1;
        if ($crypto->asset != 'USDT') {
          $res = Http::get($Server . '/v3/ticker/price?symbol=' . $crypto->asset . 'EUR');
        } else {
          $res = Http::get($Server . '/v3/ticker/price?symbol=EURUSDT');
        $data = $res->json();
        dd($data);
        if ($crypto->asset != 'USDT') {
          $price = $data['price'] * 1;
        } else {
          $price = 1 / $data['price'];
        }
        $total_kn = $total * $price * $euro * (1 - 0.00075);
        $kn += $total_kn;
        $this->line($crypto->asset . ': < ' . $total_kn . ' kn(HNB) [' . $total . ' ' . $price . ']');
        }
        */
        $this->line($crypto->asset . ': ' . $total);
      }
    }
    $this->line($kn . ' kn(HNB)');
    /*
    $cryptos = $this->withProgressBar(json_decode($CallBalance)->balances, function ($crypto) use ($euro) {
      dd($crypto->asset);
    });
    */

    $OpenOrdersUrl = $Server . '/v3/openOrders?timestamp=' . $Time . '&signature=' . $Signature;
    $ClassOpenOrders = new APIREST($OpenOrdersUrl);
    $CallOpenOrders = $ClassOpenOrders->call(
      array('X-MBX-APIKEY:' . $ApiKey)
    );
    //echo "$CallOpenOrders";
    $this->newLine();
    $this->line('Orders:');
    foreach (json_decode($CallOpenOrders) as $key => $order) {
      $this->line(
        'date: ' . gmdate("Y-m-d H:i:s", $order->time / 1000)
          . ' pair: ' . $order->symbol
          . ' type: ' . $order->type
          . ' side: ' . $order->side
          . ' price: ' . $order->price * 1
          . ' amount: ' . $order->origQty * 1
        /*. ' flled: '
          . ' total: '
          . ' trigger condition: '*/
      );
    }
  }
}

/*
 * Example Usage:
 * require 'API.php';
 * $api = new APIREST($url);
 * $call = $api->call(array()); | array contains the headers of the request
 */
class APIREST
{
  private $url;
  /**
   * Constructor for the class,
   * you must send the url to initialize the class
   *
   * @return $url
   */
  public function __construct($url)
  {
    $this->url = $url;
  }
  /**
   * @param $httpheader array of headers
   * @return response
   */
  public function call($httpheader)
  {
    try {
      $curl = curl_init();
      if (FALSE === $curl)
        throw new \Exception('Failed to initialize');

      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $httpheader,
      ));
      $response = curl_exec($curl);
      if (FALSE === $response)
        throw new \Exception(curl_error($curl), curl_errno($curl));
      $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      if (200 != $http_status)
        throw new \Exception($response, $http_status);
      curl_close($curl);
    } catch (\Exception $e) {
      $response = $e->getCode() . $e->getMessage();
      echo $response;
    }
    return $response;
  }
}
