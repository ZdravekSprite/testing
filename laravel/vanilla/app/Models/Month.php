<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

class Month extends Model
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

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'sindikat' => 'boolean',
  ];

  /**
   * Get the user that owns the month.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the month slug.
   */
  public function slug()
  {
    $m = $this->month % 12 + 1;
    $y = ($this->month - $this->month % 12) / 12;
    return sprintf("%02d.%04d", $m, $y);
  }
  /**
   * Get the last month for some attributte.
   */
  public function last($att)
  {
    if (!Month::orderBy('month', 'desc')->where('user_id', '=', $this->user_id)->first()) return redirect(route('months.create'))->with('warning', 'Barem jedan mjesec treba napraviti');
    $month = Month::orderBy('month', 'desc')->where('user_id', '=', $this->user_id)->where('month', '<', $this->month)->where($att, '<>', null)->first();
    if (!$month) $month = $this;
    return $month->attributes[$att];
  }

  /**
   * Get the next month.
   */
  public function next()
  {
    $next = Month::orderBy('month', 'asc')->where('user_id', '=', $this->user_id)->where('month', '>', $this->month)->first();
    return $next ? $next->slug() : $this->slug();
  }

  /**
   * Get the prev month.
   */
  public function prev()
  {
    $prev = Month::orderBy('month', 'desc')->where('user_id', '=', $this->user_id)->where('month', '<', $this->month)->first();
    return $prev ? $prev->slug() : $this->slug();
  }

  /**
   * Get the first day of month.
   */
  public function from()
  {
    $firstDate = '01.' . $this->slug();
    $from = CarbonImmutable::createFromFormat('d.m.Y', $firstDate)->firstOfMonth();
    $firstFrom = $this->user->settings->zaposlen > $from ? Carbon::parse($this->user->settings->zaposlen) : $from;
    return $firstFrom;
  }

  /**
   * Get the last day of month.
   */
  public function to()
  {
    $firstDate = '01.' . $this->slug();
    $to = Carbon::createFromFormat('d.m.Y', $firstDate)->endOfMonth();
    return $to;
  }

  /**
   * Get the days of month.
   */
  public function days()
  {
    $firstDate = '01.' . $this->slug();
    $from = CarbonImmutable::createFromFormat('d.m.Y', $firstDate)->firstOfMonth();
    $to = Carbon::createFromFormat('d.m.Y', $firstDate)->endOfMonth();
    //dd($firstDate,$from,$to);
    $daysColection = Day::whereBetween('date', [$from, $to])->where('user_id', '=', $this->user_id)->get();
    $holidaysColection = Holiday::whereBetween('date', [$from, $to])->get();
    $datesArray = array();
    for ($i = 0; $i < $from->daysInMonth; $i++) {
      if ($daysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_date = $daysColection->where('date', '=', $from->addDays($i))->first();
      } else {
        $temp_date = new Day;
        $temp_date->date = $from->addDays($i);
        //dd($temp_date);
      }
      //$temp_date = $from->addDays($i);
      if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
        //dd($holidaysColection->where('date', '=', $from->addDays($i))->first());
        $temp_date->holiday = $holidaysColection->where('date', '=', $from->addDays($i))->first()->name;
      }
      $datesArray[] = $temp_date;
    }
    $days = $datesArray;

    return $days;
  }

  /**
   * Get the hours Norm of month.
   */
  public function hoursNorm()
  {
    $firstDate = '01.' . $this->slug();
    $from = CarbonImmutable::createFromFormat('d.m.Y', $firstDate)->firstOfMonth();
    $firstFrom = $this->user->zaposlen > $from ? Carbon::parse($this->user->zaposlen) : $from;
    $hoursNormAll = 0;
    $firstHoursNormAll = 0;
    $hoursNormHoli = 0;
    $firstHoursNormHoli = 0;

    $hoursNormGO = 0;
    $hoursNormDopust = 0;
    $hoursNormSick = 0;

    $minWork = 0;
    $minWorkNight = 0;
    $minWorkHoli = 0;
    $minWorkHoliNight = 0;
    $minWorkSunday = 0;
    $minWorkSundayNight = 0;

    //dd($this->days());
    $days_night = [];
    foreach ($this->days() as $d) {
      $day_minWork = $d->minWork();
      $minWork += $day_minWork;
      $day_minWorkNight = $d->minWorkNight();
      $minWorkNight += $day_minWorkNight;
      $days_night[] =
        ($d->night ? $d->night->format('H:i') : '0:00') . ' ' .
        ($d->start ? $d->start->format('H:i') : '0:00') . ' ' .
        ($d->end ? $d->end->format('H:i') : '0:00') . ' ' .
        $day_minWorkNight;

      $dayOfWeek = $d->date->dayOfWeek;
      $norm = User::where('id', '=', Auth::user()->id)->first()->hasAnyRole(env('FIRM1'));
      switch ($dayOfWeek) {
        case 0:
          $def_h = 0;
          $minWorkSunday += $day_minWork;
          $minWorkSundayNight += $day_minWorkNight;
          break;
        case 6:
          if ($norm) {
            $def_h = 5;
          } else {
            $def_h = 0;
          }
          break;
        default:
          if ($norm) {
            $def_h = 7;
          } else {
            $def_h = 8;
          }
          break;
      }
      $hoursNormAll += $def_h;
      //dd($firstFrom,$d->date);
      $firstHoursNormAll += $firstFrom > $d->date ? 0 : $def_h;

      if ($d->holiday && $d->state != 4) {
        $hoursNormHoli += $def_h;
        $firstHoursNormHoli += $firstFrom > $d->date ? 0 : $def_h;
        $minWorkHoli += $day_minWork;
        $minWorkHoliNight += $day_minWorkNight;
      }

      switch ($d->state) {
        case 2:
          $hoursNormGO += $def_h;
          break;
        case 3:
          $hoursNormDopust += $def_h;
          break;
        case 4:
          $hoursNormSick += $def_h;
          break;
        default:
          break;
      }
    }
    //dd($days_night);

    $hoursNormWork = ($from > $firstFrom ? $hoursNormAll - $hoursNormHoli : $firstHoursNormAll - $firstHoursNormHoli) - $hoursNormSick - $hoursNormGO - $hoursNormDopust;

    $hoursNorm = (object) [
      'All' => $hoursNormAll,
      'Holiday' => $hoursNormHoli,
      'firstAll' => $firstHoursNormAll,
      'firstHoliday' => $firstHoursNormHoli,
      'GO' => $hoursNormGO,
      'Dopust' => $hoursNormDopust,
      'Sick' => $hoursNormSick,
      'Work' => $hoursNormWork,
      'min' => $minWork,
      'minNight' => $minWorkNight,
      'minSunday' => $minWorkSunday,
      'minSundayNight' => $minWorkSundayNight,
      'minHoliday' => $minWorkHoli,
      'minHolidayNight' => $minWorkHoliNight,
    ];
    return $hoursNorm;
  }

  /**
   * Get the hours Norm of month.
   */
  public function data()
  {
    $firstDate = '01.' . $this->slug();
    $from = CarbonImmutable::createFromFormat('d.m.Y', $firstDate)->firstOfMonth();
    $firstFrom = $this->user->zaposlen > $from ? Carbon::parse($this->user->zaposlen) : $from;
    $hoursNormAll = 0;
    $hours575All = 0;
    $hours580All = 0;
    $firstHoursNormAll = 0;
    $firstHours575All = 0;
    $firstHours580All = 0;
    $hoursNormHoli = 0;
    $hours575Holi = 0;
    $hours580Holi = 0;
    $firstHoursNormHoli = 0;
    $firstHours575Holi = 0;
    $firstHours580Holi = 0;

    $hoursNormGO = 0;
    $hours575GO = 0;
    $hours580GO = 0;
    $hoursNormDopust = 0;
    $hours575Dopust = 0;
    $hours580Dopust = 0;
    $hoursNormSick = 0;
    $hours575Sick = 0;
    $hours580Sick = 0;

    $minWork = 0;
    $minWorkX = 0;
    $minWorkNight = 0;
    $minWorkHoli = 0;
    $minWorkHoliNight = 0;
    $minWorkSunday = 0;
    $minWorkSundayNight = 0;

    //dd($this->days());
    foreach ($this->days() as $d) {
      $day_minWork = $d->minWork();
      $day_minWorkX = $d->minWorkX();
      $minWork += $day_minWork;
      $minWorkX += $day_minWorkX;
      $day_minWorkNight = $d->minWorkNight();
      $minWorkNight += $day_minWorkNight;
      $dayOfWeek = $d->date->dayOfWeek;
      $norm = User::where('id', '=', Auth::user()->id)->first()->hasAnyRole(env('FIRM1'));
      switch ($dayOfWeek) {
        case 0:
          $def_h = 0;
          $def_575_h = 0;
          $def_580_h = 0;
          $minWorkSunday += $day_minWork;
          $minWorkSundayNight += $day_minWorkNight;
          break;
        case 6:
          if ($norm) {
            $def_h = 5;
          } else {
            $def_h = 0;
          }
          $def_575_h = 5;
          $def_580_h = 0;
          break;
        default:
          if ($norm) {
            $def_h = 7;
          } else {
            $def_h = 8;
          }
          $def_575_h = 7;
          $def_580_h = 8;
          break;
      }
      $hoursNormAll += $def_h;
      $hours575All += $def_575_h;
      $hours580All += $def_580_h;
      //dd($firstFrom,$d->date);
      if ($firstFrom <= $d->date) {
        $firstHoursNormAll += $def_h;
        $firstHours575All += $def_575_h;
        $firstHours580All += $def_580_h;
      }

      if ($d->holiday && $d->state != 4) {
        $hoursNormHoli += $def_h;
        $hours575Holi += $def_575_h;
        $hours580Holi += $def_580_h;

        if ($firstFrom <= $d->date) {
          $firstHoursNormHoli += $def_h;
          $firstHours575Holi += $def_575_h;
          $firstHours580Holi += $def_580_h;
        }

        $minWorkHoli += $day_minWork;
        $minWorkHoliNight += $day_minWorkNight;
      }

      switch ($d->state) {
        case 2:
          if (!$d->holiday) {
            $hoursNormGO += $def_h;
            $hours575GO += $def_575_h;
            $hours580GO += $def_580_h;
          }
          break;
        case 3:
          if (!$d->holiday) {
            $hoursNormDopust += $def_h;
            $hours575Dopust += $def_575_h;
            $hours580Dopust += $def_580_h;
          }
          break;
        case 4:
          $hoursNormSick += $def_h;
          $hours575Sick += $def_575_h;
          $hours580Sick += $def_580_h;
          break;
        default:
          break;
      }
    }

    $hoursNormWork = ($from > $firstFrom ? $hoursNormAll - $hoursNormHoli : $firstHoursNormAll - $firstHoursNormHoli) - $hoursNormSick - $hoursNormGO - $hoursNormDopust;
    $hours575Work = ($from > $firstFrom ? $hours575All - $hours575Holi : $firstHours575All - $firstHours575Holi) - $hours575Sick - $hours575GO - $hours575Dopust;
    $hours580Work = ($from > $firstFrom ? $hours580All - $hours580Holi : $firstHours580All - $firstHours580Holi) - $hours580Sick - $hours580GO - $hours580Dopust;

    $data = (object) [
      'All' => $hoursNormAll,
      'Holiday' => $hoursNormHoli,
      'firstAll' => $firstHoursNormAll,
      'firstHoliday' => $firstHoursNormHoli,
      'GO' => $hoursNormGO,
      'Dopust' => $hoursNormDopust,
      'Sick' => $hoursNormSick,
      'Work' => $hoursNormWork,
      'All_575' => $hours575All,
      'Holiday_575' => $hours575Holi,
      'firstAll_575' => $firstHours575All,
      'firstHoliday_575' => $firstHours575Holi,
      'GO_575' => $hours575GO,
      'Dopust_575' => $hours575Dopust,
      'Sick_575' => $hours575Sick,
      'Work_575' => $hours575Work,
      'All_580' => $hours580All,
      'Holiday_580' => $hours580Holi,
      'firstAll_580' => $firstHours580All,
      'firstHoliday_580' => $firstHours580Holi,
      'GO_580' => $hours580GO,
      'Dopust_580' => $hours580Dopust,
      'Sick_580' => $hours580Sick,
      'Work_580' => $hours580Work,
      'min' => $minWork,
      'minX' => $minWorkX,
      'minNight' => $minWorkNight,
      'minSunday' => $minWorkSunday,
      'minSundayNight' => $minWorkSundayNight,
      'minHoliday' => $minWorkHoli,
      'minHolidayNight' => $minWorkHoliNight,
    ];
    return $data;
  }
}
