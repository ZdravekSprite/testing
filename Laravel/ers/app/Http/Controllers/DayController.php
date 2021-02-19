<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;

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
    //$days = [];
    //$days = Day::orderBy('date','desc')->where('user_id', auth()->user()->id);
    $days = Day::orderBy('day','desc')->get();
    //dd($days);
    return view('days.index')->with('days', $days);
    /*
Route::get('/days', function () {
    return view('days.index');
})->middleware(['auth'])->name('days');
*/
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
    //
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
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Day $day)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  public function destroy(Day $day)
  {
    //
  }
}
