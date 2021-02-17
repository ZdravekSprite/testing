<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
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

  protected $casts = [
    //'day' => 'datetime:d.m.Y',
    'sick' => 'boolean',
    'start' => 'datetime:H:i',
    'duration' => 'datetime:H:i',
    'night_duration' => 'datetime:H:i',
  ];
}
