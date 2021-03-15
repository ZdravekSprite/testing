<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class DayController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $_month['x'] = Carbon::now();
    $_month['-'] = Carbon::parse($_month['x'])->subMonthsNoOverflow();
    $_month['+'] = Carbon::parse($_month['x'])->addMonthsNoOverflow();
    $days = Day::orderBy('date', 'desc')->where('user_id', '=', Auth::user()->id)->get();
    return view('days.index')->with(compact('_month', 'days'));
  }
  /**
   * Display a listing of the resource.
   *
   * @param  $month
   * @return \Illuminate\Http\Response
   */
  public function month($month = null)
  {
    //dd($month);
    if ($month == null) {
      $_month['x'] = Carbon::now();
    } else {
      $_month['x'] = Carbon::parse('01.' . $month);
      //dd($month);
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
    //dd($datesArray, $days);
    //dd($month, $from, $to);

    return view('days.index')->with(compact('_month', 'days'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  //public function create()
  public function create(Request $request)
  {
    $day = new Day;
    //dd($request->input('date'));
    if (null != $request->input('date')) {
      $day->date = $request->input('date');
      if ($request->input('sick') == true) $day->sick = true;
      if ($request->input('go') == true) $day->go = true;
      if ($request->input('start') != null) $day->start = $request->input('start');
      if ($request->input('duration') != null) $day->duration = $request->input('duration');
    }
    //dd($day);
    return view('days.create')->with(compact('day'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'date' => 'required'
    ]);
    //dd($request);
    $old_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($request->input('date'))))->get();
    $day = new Day;
    $day->date = $request->input('date');
    $day->user_id = Auth::user()->id;
    if (null != $request->input('sick')) $day->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day->go = $request->input('go') == 'on' ? true : false;
    if (null != $request->input('night_duration')) $day->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day->night_duration;
    $day->start = $request->input('start');
    $day->duration = $request->input('duration');
    //dd($old_day);
    //dd($day);
    if (count($old_day) > 0) return view('days.edit')->with(compact('old_day', 'day'));
    $day->save();
    //return redirect(route('days.show', ['date' => $day->date->format('d.m.Y')]))->with('success', 'Day Updated');
    //return redirect(route('days.show', ['day' => $day]))->with('success', 'Day Updated');
    return redirect(route('month').'/'.$day->date->format('m.Y'))->with('success', 'Day Updated');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  //public function show(Day $day)
  public function show($date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (count($day) == 0) return redirect(route('month'));
    //dd($day);
    return view('days.show')->with('day', $day[0]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  //public function edit(Day $day)
  public function edit($date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->first();
    //dd($day);
    return view('days.edit')->with('day', $day);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  //public function update(Request $request, Day $day)
  public function update(Request $request, $date)
  {
    //dd($request);
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (null != $request->input('sick')) $day[0]->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day[0]->go = $request->input('go') == 'on' ? true : false;
    $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
    $day[0]->start = $request->input('start');
    $day[0]->duration = $request->input('duration');
    $day[0]->save();
    return redirect(route('days.show', ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated');
  }

  public function sick($date)
  {
    //dd($date);
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (count($day) == 0)  {
      $day = new Day;
      $day->date = date('Y-m-d', strtotime($date));
      $day->user_id = Auth::user()->id;
      //$day->sick = true;
    }
    $day->sick = !$day->sick;
    $day->night_duration = '00:00';
    $day->start = '00:00';
    $day->duration = '00:00';
    $day->save();
    //return redirect(route('days.show', ['day' => $day->date->format('d.m.Y')]))->with('success', 'Day Updated');
    return redirect(route('month').'/'.$day->date->format('m.Y'))->with('success', 'Day Updated');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  public function destroy(Day $day)
  {
    $month = $day->date->format('m.Y');
    $day->delete();
    //return redirect(route('days.index'))->with('success', 'Day removed');
    return redirect(route('month').'/'.$month)->with('success', 'Day removed');
  }
}
