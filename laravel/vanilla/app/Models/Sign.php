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
    $start_fill =  $this->svg_start_fill ? ' fill="'.$this->svg_start_fill.'"' : '';
    $start_transform = $this->svg_start_transform ? ' transform="'.$this->svg_start_transform.'"' : '';
    $start_g = $start_fill.$start_transform ? '<g'.$start_fill.$start_transform.'>
    '.$start.'
    </g>' : $start;
    $end = $this->svg_end ? Sign::where('name', '=', $this->svg_end)->first()->svg_all() : '';
    $end_fill = $this->svg_end_fill ? ' fill="'.$this->svg_end_fill.'"' : '';
    $end_transform = $this->svg_end_transform ? ' transform="'.$this->svg_end_transform.'"' : '';
    $end_g = $end_fill.$end_transform ? '<g'.$end_fill.$end_transform.'>
    '.$end.'
    </g>' : $end;
    return $start_g.$this->svg.$end_g;
  }
}
