<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{
  use HasFactory;

  /**
   * Get the month slug.
   */
  public function svg_all()
  {
    $start = $this->svg_start ? Sign::where('name', '=', $this->svg_start)->first()->svg_all() : '';
    $end = $this->svg_end ? Sign::where('name', '=', $this->svg_end)->first()->svg_all() : '';
    return $start.$this->svg.$end;
  }
}
