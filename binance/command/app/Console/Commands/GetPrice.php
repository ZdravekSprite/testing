<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetPrice extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'binance:get-prices';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get prices from binance';

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
   * @return mixed
   */
  public function handle()
  {
    $this->line('test:');
    //$response = Http::get('https://api.binance.com/api/v3/exchangeInfo');
    $response = Http::get('https://api.hnb.hr/tecajn/v2?valuta=EUR');
    $data = $response->json();
    //dd($data[0]['kupovni_tecaj']);
    $euro = str_replace(',','.',$data[0]['kupovni_tecaj'])*1;
    $response = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=EURUSDT');
    $data = $response->json();
    $usdt = $data['price']*$euro;
    //dd($data['symbol']);
    //$cryptos = $this->withProgressBar($data['symbols'], function ($crypto) {
    $cryptos = $this->withProgressBar(['BTC', 'ETH', 'BNB'], function ($crypto) use ($usdt) {
      //dd($crypto);
      //$response = Http::get('https://api.binance.com/api/v3/trades?symbol='.$crypto.'USDT&limit=10');
      $response = Http::get('https://api.binance.com/api/v3/ticker/price?symbol='.$crypto.'USDT');
      $data = $response->json();
      $this->newLine();
      //dd($data);
      $this->line($crypto.': <'.($data['price']*$usdt*(1-0.00075)).'kn(HNB)');
      //usleep(100000);
    });
/*
  https://api.hnb.hr/tecajn/v1
    ?datum=2014-03-02
    ?valuta=EUR
  https://api.hnb.hr/tecajn/v2
    ?datum-primjene=2019-03-02
    ?valuta=EUR
  
  $response = Http::get('https://api.hnb.hr/tecajn/v2?valuta=EUR');
  $data = $response->json();
  dd($data);

  //https://api.binance.com/api/v3/exchangeInfo
  $response = Http::get('https://api.binance.com/api/v3/trades?symbol=ETHUSDT&limit=10');
  $data = $response->json();
  dd($data);

    $cryptos = ['BTC', 'ETH', 'BNB', 'USDT'];
    $bar = $this->output->createProgressBar(count($cryptos));
    $bar->start();
    foreach ($cryptos as $crypto) {
      $this->line($crypto);

      $bar->advance();
    }
    $bar->finish();
*/
    return 0;
  }
}
