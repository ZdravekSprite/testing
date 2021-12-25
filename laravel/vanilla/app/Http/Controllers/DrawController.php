<?php

namespace App\Http\Controllers;

use App\Models\Draw;
use Illuminate\Http\Request;

class DrawController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $draw = new Draw;
    //dd($draw);
    return view('lotto.draws.create')->with(compact('draw'));
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
    $draw = new Draw;
    $draw->date = $request->input('date');
    $draw->no01 = $request->input('no01');
    $draw->no02 = $request->input('no02');
    $draw->no03 = $request->input('no03');
    $draw->no04 = $request->input('no04');
    $draw->no05 = $request->input('no05');
    $draw->bo01 = $request->input('bo01');
    $draw->bo02 = $request->input('bo02');
    $draw->save();
    return redirect(route('lotto.draws.index'))->with('success', 'Day Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Draw  $draw
   * @return \Illuminate\Http\Response
   */
  public function show(Draw $draw)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Draw  $draw
   * @return \Illuminate\Http\Response
   */
  public function edit(Draw $draw)
  {
    return view('lotto.draws.edit')->with('draw', $draw);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Draw  $draw
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Draw $draw)
  {
    $draw->no01 = $request->input('no01');
    $draw->no02 = $request->input('no02');
    $draw->no03 = $request->input('no03');
    $draw->no04 = $request->input('no04');
    $draw->no05 = $request->input('no05');
    $draw->bo01 = $request->input('bo01');
    $draw->bo02 = $request->input('bo02');
    $draw->save();
    return redirect(route('eurojackpot'))->with('success', 'Draw Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Draw  $draw
   * @return \Illuminate\Http\Response
   */
  public function destroy(Draw $draw)
  {
    //
  }
}
