<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Http\Requests\StoreDayRequest;
use App\Http\Requests\UpdateDayRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonImmutable;
use App\Models\Holiday;
use App\Models\Settings;
use Illuminate\Http\Request;

class DayController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $_month['x'] = Carbon::now();
    $_month['-'] = Carbon::parse($_month['x'])->subMonthsNoOverflow();
    $_month['+'] = Carbon::parse($_month['x'])->addMonthsNoOverflow();
    $days = Day::orderBy('date', 'desc')->where('user_id', '=', Auth::user()->id)->get();
    return view('days.index')->with(compact('_month', 'days'));
  }

  public function month($month = null)
  {
    if ($month == null) {
      $_month['x'] = Carbon::now();
    } else {
      $_month['x'] = Carbon::parse('01.' . $month);
    }
    $_month['-'] = Carbon::parse($_month['x'])->subMonthsNoOverflow();
    $_month['+'] = Carbon::parse($_month['x'])->addMonthsNoOverflow();
    $from = CarbonImmutable::parse($_month['x'])->firstOfMonth();
    $to = Carbon::parse($_month['x'])->endOfMonth();

    $daysColection = Day::whereBetween('date', [$from, $to])->where('user_id', '=', Auth::user()->id)->get();
    $holidaysColection = Holiday::whereBetween('date', [$from, $to])->get();

    $datesArray = array();
    for ($i = 0; $i < $from->daysInMonth; $i++) {
      if ($daysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_date = $daysColection->where('date', '=', $from->addDays($i))->first();
      } else {
        $temp_date = new Day;
        $temp_date->date = $from->addDays($i);
      }
      if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_date->holiday = $holidaysColection->where('date', '=', $from->addDays($i))->first()->name;
      }
      $datesArray[] = $temp_date;
    }
    $days = $datesArray;

    $settings = Settings::where('user_id', '=', Auth::user()->id)->first();

    if (!$settings) {
      $settings = new Settings();
      $settings->start1 = '06:00';
      $settings->end1 = '14:00';
      $settings->start2 = '14:00';
      $settings->end2 = '22:00';
      $settings->start3 = '22:00';
      $settings->end3 = '06:00';
    }

    return view('days.index')->with(compact('_month', 'days', 'settings'));
  }

  public function print($month = null)
  {
    if ($month == null) {
      $_month = Carbon::now();
    } else {
      $_month = Carbon::parse('01.' . $month);
    }
    $from = CarbonImmutable::parse($_month)->firstOfMonth();
    $to = Carbon::parse($_month)->endOfMonth();

    $daysColection = Day::whereBetween('date', [$from, $to])->where('user_id', '=', Auth::user()->id)->get();

    $datesArray = array();
    for ($i = 0; $i < $from->daysInMonth; $i++) {
      if ($daysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_date = $daysColection->where('date', '=', $from->addDays($i))->first();
      } else {
        $temp_date = new Day;
        $temp_date->date = $from->addDays($i);
      }
      $datesArray[] = $temp_date;
    }
    $days = $datesArray;
    return view('days.print')->with(compact('_month', 'days'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Request $request)
  {
    $day = new Day;
    //dd($request->input('date'));
    if (null != $request->input('date')) {
      $day->date = $request->input('date');
      if ($request->input('start')) {
        $day->start = $request->input('start');
        $day->state = 1;
      }
      if ($request->input('end')) {
        $day->end = $request->input('end');
        $day->state = 1;
      }
      if ($request->input('state')) {
        $day->state = $request->input('state');
      }
    }
    //dd($day);
    return view('days.create')->with(compact('day'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreDayRequest $request)
  {
    $this->validate($request, [
      'date' => 'required'
    ]);
    $day = new Day;
    $day->date = $request->input('date');
    $day->user_id = Auth::user()->id;
    $day->state = $request->input('state') ? $request->input('state') : 0;
    if ($request->input('state') == 1) {
      $day->start = $request->input('start');
      $day->end = $request->input('end');
      $old_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($request->input('date'))))->first();
      if ($old_day) return redirect(route('day.edit', ['date' => $day->date->format('d.m.Y')]))->with('new_day', $day)->with('warning', 'Day Exist');
      if ($day->start > $day->end) {
        $next_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', $day->date->addDays(1)->format('Y-m-d'))->first();
        if (!$next_day) {
          $next_day = new Day;
          $next_day->user_id = Auth::user()->id;
          $next_day->date = $day->date->addDays(1)->format('Y-m-d');
        }
        $next_day->night = $day->end->format('H:i');
        $day->end = "24:00";
        $next_day->save();
      }
    }
    $day->save();
    return redirect(route('day.show', ['date' => $day->date->format('d.m.Y')]))->with('success', 'Day Created');
  }

  /**
   * Display the specified resource.
   */
  //public function show(Day $day)
  public function show($date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->first();
    if (!$day) return redirect(route('days.index'))->with('warning', 'Wrong Day');
    if ($day->end && $day->end->format('H:i') == "00:00") {
      $next_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', $day->date->addDays(1)->format('Y-m-d'))->first();
      if ($next_day) {
        $day->end = $next_day->night->format('H:i');
      }
    }
    return view('days.show')->with('day', $day);
  }

  /**
   * Show the form for editing the specified resource.
   */
  //public function edit(Day $day)
  public function edit($date)
  {
    if (session('new_day')) {
      $day = session('new_day');
    } else {
      $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->first();
    }
    if (!$day) return redirect(route('day.create', ['date' => date('d.m.Y', strtotime($date))]))->with('warning', 'Day not exist');
    if ($day->end && $day->end->format('H:i') == "00:00") {
      $next_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', $day->date->addDays(1)->format('Y-m-d'))->first();
      if ($next_day) {
        $day->end = $next_day->night->format('H:i');
      }
    }
    return view('days.edit')->with('day', $day);
  }

  /**
   * Update the specified resource in storage.
   */
  //public function update(UpdateDayRequest $request, Day $day)
  public function update(Request $request, $date)
  {
    //dd($request);
    $day = Day::where('user_id', '=', Auth::user()->id)->firstOrNew(
      ['date' => date('Y-m-d', strtotime($date))],
      ['user_id' => Auth::user()->id]
    );
    $day->state = $request->input('state') ?? 0;
    if ($request->input('state') == 1) {
      $day->start = $request->input('start');
      $day->end = $request->input('end');
      $next_day = Day::where('user_id', '=', Auth::user()->id)->firstOrNew(
        ['date' => $day->date->addDays(1)->format('Y-m-d')],
        ['user_id' => Auth::user()->id]
      );
      //dd($day,$next_day);
      if ($day->start > $day->end) {
        $next_day->night = $day->end->format('H:i');
        $day->end = "24:00";
        //dd($day,$next_day);
        $next_day->save();
      } else {
        if ($next_day->night) {
          $next_day->night = "00:00";
          $next_day->save();
        }
      }
    }
    //dd($day);
    $day->save();
    return redirect(route('day.show', ['date' => $day->date->format('d.m.Y')]))->with('success', 'Day Updated');
  }

  public function sick($date)
  {
    //dd($date);
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (count($day) == 0) {
      $day = new Day;
      $day->date = date('Y-m-d', strtotime($date));
      $day->user_id = Auth::user()->id;
      //$day->sick = true;
    }
    $day->state = 4;
    $day->start = '00:00';
    $day->end = '00:00';
    $day->save();
    //return redirect(route('days.show', ['day' => $day->date->format('d.m.Y')]))->with('success', 'Day Updated');
    return redirect(route('month') . '/' . $day->date->format('m.Y'))->with('success', 'Day Updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  //public function destroy(Day $day)
  public function destroy($date)
  {
    /*
    $month = $day->date->format('m.Y');
    $day->delete();
    //return redirect(route('days.index'))->with('success', 'Day removed');
    return redirect(route('month') . '/' . $month)->with('success', 'Day removed');
    */
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->first();
    //dd($day->night);
    if ($day->night->format('H:i') == "00:00") {
      $day->delete();
    } else {
      $day->state = 0;
      $day->start = "00:00";
      $day->end = "00:00";
      $day->save();
    }
    $next_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', $day->date->addDays(1)->format('Y-m-d'))->first();
    if ($next_day) {
      $next_day->night = "00:00";
      $next_day->save();
    }
    return redirect(route('days.index'))->with('success', 'Day removed');
  }
}
