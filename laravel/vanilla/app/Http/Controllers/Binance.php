<?php

namespace App\Http\Controllers;

use App\Models\Hnb;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class Binance extends Controller
{
  /**
   * Show the portfolio.
   *
   * @return \Illuminate\View\View
   */
  public function portfolio()
  {
    if (!Auth::user()) {
      return 'not auth';
    } else {
      if (!Auth::user()->BINANCE_API_KEY) {
        return 'no key';
      }
      if (!Auth::user()->BINANCE_API_KEY) {
        return 'no secret';
      }
      $apiKey = Auth::user()->BINANCE_API_KEY;
      $apiSecret = Auth::user()->BINANCE_API_SECRET;
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
      $eur_kn = str_replace(',', '.', $hnb_eur_kn->kupovni_tecaj);
      $total_kn = -0.8 * $eur_kn;
      //dd($total_kn);

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
      $balance = [];
      $total = 0;
      foreach ($getall as $coin) {
        $coin->total = $coin->free + $coin->locked + $coin->freeze + $coin->withdrawing + $coin->ipoing + $coin->ipoable + $coin->storage;
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
                dd('test',$coin,$data);
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
              $coin->price = max($coin->eur, $coin->busd, $coin->usdt);
              //dd($coin);
            }
          $balance = Arr::add($balance, $coin->coin, $coin);
          $total = $total + $coin->price;
        }
      }
      //dd($balance);

      return view('binance.portfolio')->with(compact('balance', 'total'));
    }
  }
}
