<?php

namespace App\Http\Controllers;

use App\Models\Hnb;
use App\Models\Symbol;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Binance extends Controller
{
  protected $base = 'https://api.binance.com';
  protected $api1 = 'https://api1.binance.com';
  protected $api2 = 'https://api2.binance.com';
  protected $api3 = 'https://api3.binance.com';

  protected $getSystemStatus = '/sapi/v1/system/status';

  /**
   * Show the portfolio.
   *
   * @return \Illuminate\View\View
   */
  public function portfolio()
  {
    if (!Auth::user()) {
      return redirect(route('home'))->with('warning', 'not auth');
    } else {
      if (!isset(Auth::user()->settings->BINANCE_API_KEY)) {
        return redirect(route('home'))->with('warning', 'no key');
      }
      if (!isset(Auth::user()->settings->BINANCE_API_SECRET)) {
        return redirect(route('home'))->with('warning', 'no secret');
      }
      $apiKey = Auth::user()->settings->BINANCE_API_KEY;
      $apiSecret = Auth::user()->settings->BINANCE_API_SECRET;
      $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
      $serverTime = $time->serverTime;
      $timeStamp = 'timestamp=' . $serverTime;
      $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
      $getall = json_decode(Http::withHeaders([
        'X-MBX-APIKEY' => $apiKey
      ])->get('https://api.binance.com/sapi/v1/capital/config/getall', [
        'timestamp' => $serverTime,
        'signature' => $signature
      ]));

      $date = gmdate("Y-m-d", $serverTime / 1000);
      $hnb_eur_kn = Hnb::where('datum_primjene', '=', $date)->where('valuta', '=', 'EUR')->first();

      if (!$hnb_eur_kn) {
        
        try
        {
          $response = Http::get('https://api.hnb.hr/tecajn/v2?datum-primjene=' . $date);
          $day = $response->json();
          foreach ($day as $key => $valuta) {
            $hnb = new Hnb;
            $hnb->broj_tecajnice = $valuta['broj_tecajnice'];
            $hnb->datum_primjene = $valuta['datum_primjene'];
            $hnb->drzava = $valuta['drzava'];
            $hnb->drzava_iso = $valuta['drzava_iso'];
            $hnb->sifra_valute = $valuta['sifra_valute'];
            $hnb->valuta = $valuta['valuta'];
            $hnb->jedinica = $valuta['jedinica'];
            $hnb->kupovni_tecaj = $valuta['kupovni_tecaj'];
            $hnb->srednji_tecaj = $valuta['srednji_tecaj'];
            $hnb->prodajni_tecaj = $valuta['prodajni_tecaj'];
            $hnb->save();
          }
          $hnb_eur_kn = Hnb::where('datum_primjene', '=', $date)->where('valuta', '=', 'EUR')->first();
        }
        catch(Exception $e)
        {
          logger()->error('Goutte client error ' . $e->getMessage());
          //dd($e->getMessage());
          $hnb_eur_kn = Hnb::orderBy('datum_primjene', 'desc')->where('valuta', '=', 'EUR')->first();
          //dd($hnb_eur_kn);
      }
        //dd($response->json());
      }
      $eur_kn = str_replace(',', '.', $hnb_eur_kn->kupovni_tecaj) / 1.01;
      $total_kn = -0.8 * $eur_kn;
      //dd($total_kn);
      //dd('test portfolio');

      $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=EURBUSD');
      $data = $res->json();
      $busd_kn = $eur_kn / $data['price'];
      $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BUSDUSDT');
      $data = $res->json();
      $busd_usdt = $data['price'];
      $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=EURUSDT');
      $data = $res->json();
      $usdt_kn = (1 - 0.0075) * $eur_kn / $data['price'];
      $busdt_kn =  $busd_kn / $busd_usdt;
      //dd($total_kn, $usdt_kn, $busd_usdt, $busd_kn, $busdt_kn);

      //dd($getall);
      /*
      $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
      $serverTime = $time->serverTime;
      $timeStamp = 'timestamp=' . $serverTime;
      $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
      $lendingList = json_decode(Http::withHeaders([
        'X-MBX-APIKEY' => $apiKey
      ])->get('https://api.binance.com/sapi/v1/lending/daily/product/list', [
        'timestamp' => $serverTime,
        'signature' => $signature
      ]));
      dd($lendingList);
      */
      /* */
      $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
      $serverTime = $time->serverTime;
      $timeStamp = 'timestamp=' . $serverTime;
      $signature = hash_hmac('SHA256', $timeStamp, $apiSecret);
      $lendingAccount = json_decode(Http::withHeaders([
        'X-MBX-APIKEY' => $apiKey
      ])->get('https://api.binance.com/sapi/v1/lending/union/account', [
        'timestamp' => $serverTime,
        'signature' => $signature
      ]));
      //dd($lendingAccount->positionAmountVos);
      /* */

      $balance = [];
      $total = 0;
      foreach ($getall as $coin) {
        $coin->lending = 0;
        foreach ($lendingAccount->positionAmountVos as $lending) {
          if ($lending->asset == $coin->coin) {
            $coin->lending = $lending->amount;
          }
        }
        $coin->total = $coin->free + $coin->locked + $coin->freeze + $coin->withdrawing + $coin->ipoing + $coin->ipoable + $coin->storage + $coin->lending;
        if ($coin->total > 0) {
          $coin->price = 0;
          switch ($coin->coin) {
            case 'EUR':
              $coin->eur = $coin->total * $eur_kn;
              $coin->price = $coin->eur;
              break;
            case 'BUSD':
              $coin->busd = $coin->total * $busd_kn;
              $coin->price = $coin->busd;
              break;
            case 'USDT':
              $coin->usdt = $coin->total * $usdt_kn;
              $coin->price = $coin->total * $busdt_kn;
              break;
            default:
              $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=' . $coin->coin . 'EUR');
              $data = $res->json();
              if (isset($data['price'])) {
                $coin->eur = (1 - 0.0075) * $coin->total * $eur_kn * $data['price'];
              } else {
                //dd('test',$coin,$data);
                $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=EUR' . $coin->coin);
                $data = $res->json();
                if (isset($data['price'])) $coin->eur = (1 - 0.0075) * $coin->total * $eur_kn / $data['price'];
              }
              $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=' . $coin->coin . 'BUSD');
              $data = $res->json();
              if (isset($data['price'])) {
                $coin->busd = $coin->total * $busd_kn * $data['price'];
              } else {
                $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BUSD' . $coin->coin);
                $data = $res->json();
                if (isset($data['price'])) $coin->busd = $coin->total * $busd_kn / $data['price'];
              }
              $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=' . $coin->coin . 'USDT');
              $data = $res->json();
              if (isset($data['price'])) {
                $coin->usdt =  (1 - 0.0075) * $coin->total * $usdt_kn * $data['price'];
              } else {
                $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=USDT' . $coin->coin);
                $data = $res->json();
                if (isset($data['price'])) $coin->usdt =  (1 - 0.0075) * $coin->total * $usdt_kn / $data['price'];
              }
              if (!isset($coin->busd)) {
                //dd($coin);
                $coin->busd = 0;
              }
              $coin->price = $coin->busd; //max($coin->eur, $coin->busd, $coin->usdt);
              $coin->openOrders = (new Binance)->openOrders($coin->coin . "BUSD");
              //$coin->allOrders = (new Binance)->allOrders($coin->coin);
              //dd($coin);
          }
          $balance = Arr::add($balance, $coin->coin, $coin);
          $total = $total + $coin->price;
        }
      }
      $binanceSocket = 'wss://stream.binance.com:9443/stream?streams=';
      //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=adabusd@kline_1m/bnbbusd@kline_1m/ethbusd@kline_1m/maticbusd@kline_1m/btcbusd@kline_1m/eurbusd@kline_1m/solbusd@kline_1m/mboxbusd@kline_1m/lunabusd@kline_1m/fttbusd@kline_1m");
      foreach ($balance as $coin) {
        if ($coin->coin == 'BUSD') {
          $coin->ATH = null;
        } elseif ($coin->coin == 'DAI') {
          $binanceSocket .= 'busd'.Str::lower($coin->coin).'@kline_1m/';
          $coin->ATH = null;
        } elseif ($coin->coin == 'SANTOS') {
          $binanceSocket .= Str::lower($coin->coin).'usdt@kline_1m/';
          $kline = json_decode(Http::get('https://api.binance.com/api/v3/klines?symbol=' . $coin->coin . 'USDT&interval=1d'));
          $coin->ATH = is_object($kline) ? null : max(array_column($kline, 2))*1;
        } else {
          $binanceSocket .= Str::lower($coin->coin).'busd@kline_1m/';
          if ($coin->coin == 'EUR') {
            $coin->ATH = null;
          } else {
            $kline = json_decode(Http::get('https://api.binance.com/api/v3/klines?symbol=' . $coin->coin . 'BUSD&interval=1d'));
            //dd(max(array_column($kline, 2)));
            $coin->ATH = is_object($kline) ? null : max(array_column($kline, 2))*1;
          }
        }
      }
      $binanceSocket = Str::replaceLast('/', '', $binanceSocket);

      //dd($balance,$binanceSocket,$total,$eur_kn,$busd_kn);

      return view('binance.portfolio')->with(compact('balance', 'binanceSocket', 'total', 'eur_kn', 'busd_kn', 'usdt_kn'));
    }
  }

  /**
   */
  public function openOrders($symbol = 'BNBBUSD')
  {
    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = array(
      "symbol" => $symbol,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $getArray = $queryArray + $signatureArray;
    $openOrders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get('https://api.binance.com/api/v3/openOrders', $getArray));

    return $openOrders;
  }

  /**
   */
  public function allOrders($symbol = 'BNBBUSD')
  {

    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = array(
      "symbol" => $symbol,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $getArray = $queryArray + $signatureArray;
    $allOrders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get('https://api.binance.com/api/v3/allOrders', $getArray));

    return $allOrders;
  }

  /**
   * Show the orders size.
   *
   * @return \Illuminate\View\View
   */
  public function orders()
  {
    $coins = [['ETH', 5, 2], ['BTC', 6, 2], ['BNB', 4, 2], ['ADA', 2, 4], ['MATIC', 1, 5]];
    $simbols = [];
    foreach ($coins as $coin) {
      $res = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=' . $coin[0] . 'BUSD');
      $data = $res->json();
      $coin_data = [];
      $price = $data['price'];
      $coin_data = Arr::add($coin_data, 'price', $price);
      $busd10 = 10 / $price;
      $pow1 = pow(10, $coin[1]);
      $pow2 = pow(10, $coin[2]);
      $up = [];
      $busd10up = [];
      $down = [];
      $busd10down = [];

      $up[0] = floor($busd10 * $pow1) / $pow1;
      $busd10up[0] = ceil(1 / $up[0] * 10 * $pow2) / $pow2;
      $down[0] = ceil($busd10 * $pow1) / $pow1;
      $busd10down[0] = ceil(1 / $down[0] * 10 * $pow2) / $pow2;

      for ($i = 0; $i < 10; $i++) {
        $up[$i + 1] = $up[$i] - 1 / $pow1;
        $busd10up[$i + 1] = ceil(1 / $up[$i + 1] * 10 * $pow2) / $pow2;
        $down[$i + 1] = $down[$i] + 1 / $pow1;
        $busd10down[$i + 1] = ceil(1 / $down[$i + 1] * 10 * $pow2) / $pow2;
        # code...
      }

      $coin_data = Arr::add($coin_data, 'up', $up);
      $coin_data = Arr::add($coin_data, 'busd10up', $busd10up);
      $coin_data = Arr::add($coin_data, 'down', $down);
      $coin_data = Arr::add($coin_data, 'busd10down', $busd10down);
      $simbols = Arr::add($simbols, $coin[0], $coin_data);
    }
    //dd($simbols);
    return view('binance.orders')->with(compact('simbols', 'coins'));
  }

  /**
   * Show the chart.
   *
   * @return \Illuminate\View\View
   */
  public function chart($coin = 'BNB')
  {
    //dd(Symbol::where('symbol', '=', $coin.'BUSD')->first()->tickSize);
    $busd = Symbol::where('symbol', '=', $coin . 'BUSD')->first();
    $btc = Symbol::where('symbol', '=', $coin . 'BTC')->first();
    $eth = Symbol::where('symbol', '=', $coin . 'ETH')->first();

    $precision = [
      'BUSD' => $busd ? $busd->tickSize + 1 : 0,
      'BTC' => $btc ? $btc->tickSize + 1 : 0,
      'ETH' => $eth ? $eth->tickSize + 1 : 0
    ];
    return view('binance.chart')->with(compact('coin', 'precision'));
  }

  /**
   * Show the chart.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard($symbol = 'MATICBUSD')
  {
    $symbol = 'MATICBUSD';
    $base = 'MATIC';
    $dec1 = 1;
    $quote = 'BUSD';
    $dec2 = 5;

    return view('binance.dashboard')->with(compact('symbol', 'base', 'dec1', 'quote', 'dec2'));
  }

  /**
   * Test New Order (TRADE)
   * Response:
   * 
   * {}
   * POST /api/v3/order/test (HMAC SHA256)
   * 
   * Test new order creation and signature/recvWindow long. Creates and validates a new order but does not send it into the matching engine.
   * 
   * Weight: 1
   * 
   * Parameters:
   * 
   * Same as POST /api/v3/order
   * 
   * Data Source: Memory
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function testNewOrder(Request $request)
  {
    $symbol = $request->input('symbol');
    $side =  $request->input('side');
    $type = $request->input('type');

    $quantity = $request->input('quantity');
    $quoteOrderQty = $request->input('quoteOrderQty');
    $price = $request->input('price');
    //dd($symbol, $side, $type, $quantity, $quoteOrderQty, $price);
    $apiKey = Auth::user()->BINANCE_API_KEY;
    $apiSecret = Auth::user()->BINANCE_API_SECRET;
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = array(
      "symbol" => $symbol,
      "side" => $side,
      "type" => $type,
      "quantity" => $quantity,
      "quoteOrderQty" => $quoteOrderQty,
      "price" => $price,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac('SHA256', http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $getArray = $queryArray + $signatureArray;
    $testNewOrder = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get('https://api.binance.com/api/v3/order/test', $getArray));
    dd($testNewOrder);
  }
  /*
   * Dust Transfer (USER_DATA)
   * Response:
   * {
   *     "totalServiceCharge":"0.02102542",
   *     "totalTransfered":"1.05127099",
   *     "transferResult":[
   *         {
   *             "amount":"0.03000000",
   *             "fromAsset":"ETH",
   *             "operateTime":1563368549307,
   *             "serviceChargeAmount":"0.00500000",
   *             "tranId":2970932918,
   *             "transferedAmount":"0.25000000"
   *         },
   *         {
   *             "amount":"0.09000000",
   *             "fromAsset":"LTC",
   *             "operateTime":1563368549404,
   *             "serviceChargeAmount":"0.01548000",
   *             "tranId":2970932918,
   *             "transferedAmount":"0.77400000"
   *         },
   *         {
   *             "amount":"248.61878453",
   *             "fromAsset":"TRX",
   *             "operateTime":1563368549489,
   *             "serviceChargeAmount":"0.00054542",
   *             "tranId":2970932918,
   *             "transferedAmount":"0.02727099"
   *         }
   *     ]
   * }

   * POST /sapi/v1/asset/dust (HMAC SHA256)
   * Convert dust assets to BNB.
   * Weight(UID): 10
   * Parameters:
   * Name	Type	Mandatory	Description
   * asset	ARRAY	YES	The asset being converted. For example: asset=BTC&asset=USDT
   * recvWindow	LONG	NO	
   * timestamp	LONG	YES
   */
  public function dustTransfer(Request $request)
  {
    //dd($request);
    $assets = $request->input('assets');
    //dd($assets);
    $url = 'https://api.binance.com/sapi/v1/asset/dust';
    /*
    $array = array(
      "asset=".$assets[0]."&asset=".$assets[1]
    );
    //dd($array);
    $curl = new HttpCurl();
    $dustTransfer = $curl->post($url, $array, true);
    dd($dustTransfer);
    return $dustTransfer;
    */
    $flat_array = '';
    foreach ($assets as $key => $value) {
      $flat_array .= 'asset='.$value.'&';
    }
    //dd($flat_array);
    $ch = curl_init();

    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
    );

    curl_setopt_array($ch, $defaults);

    $apiKey = Auth::user()->settings->BINANCE_API_KEY;
    $apiSecret = Auth::user()->settings->BINANCE_API_SECRET;
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-MBX-APIKEY: ' . $apiKey,
    ));
    $time = json_decode(Http::get('https://api.binance.com/api/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = $flat_array."timestamp=".$serverTime;
    $signature = hash_hmac('SHA256', $queryArray, $apiSecret);
    $query = $queryArray."&signature=".$signature;

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    //dd($queryArray,$query);
    $server_output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($server_output, true);
    return $json;
  }
}
