<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    $days = Day::orderBy('date','desc')->where('user_id', '=', Auth::user()->id)->get();
    return view('days.index')->with('days', $days);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('days.create'); 
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
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
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d',strtotime($date)))->get();
    //dd($day);
    return view('days.show')->with('day', $day); 
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
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d',strtotime($date)))->get();
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
  $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d',strtotime($date)))->get();
  if (null != $request->input('sick')) $day[0]->sick = $day[0]->sick;
  $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
  $day[0]->start = $request->input('start');
  $day[0]->duration = $request->input('duration');
  $day[0]->save();
  return redirect(route('days.show' , ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated'); 
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  public function destroy(Day $day)
  {
    $day->delete();
    return redirect(route('days.index'))->with('success', 'Day removed');
  }
}
