<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $holidays = Holiday::orderBy('date', 'desc')->get();
    if ($request->wantsJson()) {
      // I'm from API
      return $holidays;
    } else {
      // I'm from HTTP
      return view('holidays.index')->with('holidays', $holidays);
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $holiday = new Holiday;
    //dd($holiday);
    return view('holidays.create')->with(compact('holiday'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreHolidayRequest $request)
  {
    $this->validate($request, [
      'date' => 'required|unique:holidays',
      'name' => 'required|string|min:3|max:255'
    ]);
    $holiday = new Holiday;
    $holiday->date = $request->input('date');
    $holiday->name = $request->input('name');
    $holiday->save();
    if ($request->wantsJson()) {
      // I'm from API
      return $holiday;
    } else {
      // I'm from HTTP
      return redirect(route('holidays.index'))->with('success', 'Holiday Created');
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request, Holiday $holiday)
  {
    if ($request->wantsJson()) {
      // I'm from API
      return $holiday;
    } else {
      // I'm from HTTP
      return view('holidays.show')->with(compact('holiday'));
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Holiday $holiday)
  {
    return view('holidays.edit')->with(compact('holiday'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateHolidayRequest $request, Holiday $holiday)
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
   */
  public function destroy(Request $request, Holiday $holiday)
  {
    if ($request->wantsJson()) {
      // I'm from API
      return $holiday->delete();
    } else {
      // I'm from HTTP
      $holiday->delete();
      return redirect(route('holidays.index'))->with('success', 'Holiday removed');
    }
  }
}
