<?php

namespace Database\Seeders;

use App\Models\Symbol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class SymbolSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Symbol::truncate();
    $exchangeInfo = json_decode(Http::get('https://api.binance.com/api/v3/exchangeInfo'));
    $symbols = $exchangeInfo->symbols;
    foreach ($symbols as $key => $value) {
      Symbol::create([
        'symbol' => $value->symbol,
        'status' => $value->status,
        'baseAsset' => $value->baseAsset,
        'baseAssetPrecision' => $value->baseAssetPrecision,
        'quoteAsset' => $value->quoteAsset,
        'quotePrecision' => $value->quotePrecision,
        'quoteAssetPrecision' => $value->quoteAssetPrecision,
        'icebergAllowed' => $value->icebergAllowed,
        'ocoAllowed' => $value->ocoAllowed,
        'isSpotTradingAllowed' => $value->isSpotTradingAllowed,
        'isMarginTradingAllowed' => $value->isMarginTradingAllowed
      ]);
    }
  }
}
