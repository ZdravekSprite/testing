<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
  use HasFactory;
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'id',
    'created_at',
    'updated_at',
  ];

  protected $fillable = [
    'symbol',
    'status',
    'baseAsset',
    'baseAssetPrecision',
    'quoteAsset',
    'quotePrecision',
    'quoteAssetPrecision',
    'icebergAllowed',
    'ocoAllowed',
    'isSpotTradingAllowed',
    'isMarginTradingAllowed'
  ];

}
