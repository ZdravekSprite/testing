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
}
