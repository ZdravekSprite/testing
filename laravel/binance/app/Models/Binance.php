<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Binance extends Model
{
  use HasFactory;

  /**
   * Get the user that owns the binance.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the apiKey.
   */
  public function apiKey()
  {
    $apiKey = $this->BINANCE_API_KEY;
    if (!$apiKey) return redirect(route('binance.create'));
    return $apiKey;
  }

  /**
   * Get the apiKey.
   */
  public function apiSecret()
  {
    $apiSecret = $this->BINANCE_API_SECRET;
    if (!$apiSecret) return redirect(route('binance.create'));
    return $apiSecret;
  }

}
