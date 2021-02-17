<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Day::orderBy('day', 'DESC')->get();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $newDay = new Day;
    $newDay->day = $request->day["day"];
    $newDay->sick = isset($request->day["sick"]) ? $request->day["sick"] : false;
    $newDay->start = isset($request->day["start"]) ? $request->day["start"] : '6:00';
    $newDay->duration = isset($request->day["duration"]) ? $request->day["duration"] : '8:00';
    $newDay->night_duration = isset($request->day["night_duration"]) ? $request->day["night_duration"] : 0;
    $newDay->save();

    return $newDay;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  public function show(Day $day)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  public function edit(Day $day)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Day  $day
   * @param  str  $day
   * @return \Illuminate\Http\Response
   */
  //  public function update(Request $request, Day $day)
  public function update(Request $request, $day)
  {
    //return $day;
    //return $request;
    $existingDay = Day::where('day', '=', $day)->first(); //firstOrNew firstOrCreate
    //return $existingDay;
    if ($existingDay) {
      if (isset($request->day["sick"])) $existingDay->sick = $request->day["sick"];
      if (isset($request->day["start"])) $existingDay->start = $request->day["start"];
      if (isset($request->day["duration"])) $existingDay->duration = $request->day["duration"];
      if (isset($request->day["night_duration"])) $existingDay->night_duration = $request->day["night_duration"];
      $existingDay->save();
      return $existingDay;
    }

    return "Day not found.";
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Day  $day
   * @param  str  $day
   * @return \Illuminate\Http\Response
   */
  public function destroy($day)
  {
    $existingDay = Day::where('day', '=', $day)->first();
    if ($existingDay) {
      $existingDay->delete();
      return "Day successfully deleted.";
    }

    return "Day not found.";
  }
}
