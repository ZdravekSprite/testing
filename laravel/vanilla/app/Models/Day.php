<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    'user_id',
    'created_at',
    'updated_at',
  ];

  protected $fillable = ['user_id', 'date', 'state', 'night', 'start', 'end'];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'date' => 'datetime:d.m.Y',
    'night' => 'datetime:H:i',
    'start' => 'datetime:H:i',
    'end' => 'datetime:H:i',
  ];

  /**
   * Get the user that owns the day.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  /**
   * Dan u tjednu.
   */
  public function dan()
  {
    $weekMap = [
      0 => 'Ne',
      1 => 'Po',
      2 => 'Ut',
      3 => 'Sr',
      4 => 'Če',
      5 => 'Pe',
      6 => 'Su',
    ];
    return $weekMap[$this->date->dayOfWeek];
  }

  public function stateDayBefore()
  {
    $dayBefore = Day::where('user_id', '=', $this->user_id)->where('date', '=', $this->date->addDays(-1)->format('Y-m-d'))->first();
    $stateDayBefore = $dayBefore ? $dayBefore->state : 0;
    return $stateDayBefore;
  }


  public function minWork()
  {
    if ($this->stateDayBefore() == 1) {
      $night = $this->night ? $this->night->format('H') * 60 + $this->night->format('i') : 0;
    } else {
      $night = 0;
    }
    if ($this->state == 1) {
      $startEnd = $this->start ? ($this->end ? $this->end->diffInMinutes($this->start) : 24 * 60 - $this->start->hour * 60 + $this->start->minute) : 0;
    } else {
      $startEnd = 0;
    }
    $day_minWork = $startEnd + $night;
    return $day_minWork;
  }

  public function minWorkNight()
  {
    if ($this->stateDayBefore() == 1) {
      $night = $this->night ? ($this->night->hour > 6 ? 360 : $this->night->hour * 60 + $this->night->minute) : 0;
    } else {
      $night = 0;
    }
    if ($this->state == 1) {
      $startMin = $this->start ? $this->start->hour * 60 + $this->start->minute : 0;
      $endMin = $this->end ? $this->end->hour * 60 + $this->end->minute : 0;
      if ($startMin > 0 && $endMin == 0) $endMin = 1440;
      // befor 6:00 (360 min)
      $start = $this->start ? ($startMin > 360 ? 0 : 360 - $startMin) : 0;
      // after 22:00 (1320 min)
      $end = $this->start ? ($startMin < 1320 ? ($endMin > 1320 ? $endMin - 1320 : 0) : $endMin - $startMin) : 0;
    } else {
      $start = 0;
      $end = 0;
    }
    /*
    if ($this->end && $this->end->hour * 60 + $this->end->minute > 1320) {
      dd($night, $start, $end, $this->night->format('H:i'), $this->start->format('H:i'), $this->end->format('H:i'));
    }
    */
    $day_minWorkNight = $night + $start + $end;
    return $day_minWorkNight;
  }
}
