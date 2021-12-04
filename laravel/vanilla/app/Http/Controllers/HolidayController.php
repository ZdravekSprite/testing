<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $holidays = Holiday::orderBy('date', 'desc')->get();
    return view('holidays.index')->with('holidays', $holidays);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $holiday = new Holiday;
    //dd($holiday);
    return view('holidays.create')->with(compact('holiday'));
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
      'date' => 'required|unique:holidays',
      'name' => 'required|string|min:3|max:255'
    ]);
    $holiday = new Holiday;
    $holiday->date = $request->input('date');
    $holiday->name = $request->input('name');
    $holiday->save();
    return redirect(route('holidays.index'))->with('success', 'Holiday Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Holiday  $holiday
   * @return \Illuminate\Http\Response
   */
  public function show(Holiday $holiday)
  {
    return view('holidays.show')->with(compact('holiday'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Holiday  $holiday
   * @return \Illuminate\Http\Response
   */
  public function edit(Holiday $holiday)
  {
    return view('holidays.edit')->with(compact('holiday'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Holiday  $holiday
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Holiday $holiday)
  {
    $this->validate($request, [
      'date' => 'required|unique:holidays',
      'name' => 'required|string|min:3|max:255'
    ]);
    $holiday->date = $request->input('date');
    $holiday->name = $request->input('name');
    $holiday->save();
    return redirect(route('holidays.index'))->with('success', 'Holiday Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Holiday  $holiday
   * @return \Illuminate\Http\Response
   */
  public function destroy(Holiday $holiday)
  {
    $holiday->delete();
    return redirect(route('holidays.index'))->with('success', 'Holiday removed');
  }
}
