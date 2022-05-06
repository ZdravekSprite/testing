<?php

namespace App\Http\Controllers;

use App\Models\Sign;
use App\Http\Requests\StoreSignRequest;
use App\Http\Requests\UpdateSignRequest;

class SignController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $signs = Sign::all();
    return view('signs.index')->with(compact('signs'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $sign = new Sign;
    //dd($sign);
    return view('signs.create')->with(compact('sign'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreSignRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreSignRequest $request)
  {
    $sign = new Sign();
    $sign->name = $request->post('name');
    $sign->description = $request->post('description');
    $sign->svg = $request->post('svg');
    $sign->save();

    return redirect(route('signs.index'))->with('success', 'Sign created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Sign  $sign
   * @return \Illuminate\Http\Response
   */
  public function show(Sign $sign)
  {
    $im = new Imagick();
    // Create a new image instance
    $svg = '<?xml version="1.0" standalone="no"?>
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="720px" height="720px" viewBox="0 0 720 720">
    '.$sign->svg.'
    </svg>';
    $im->readImageBlob($svg);
    $im->setImageFormat("gif");
    $type = 'image/gif';
    header("Content-Type: ".$type);
    return response($im->getImageBlob())->header('Content-Type', $type);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Sign  $sign
   * @return \Illuminate\Http\Response
   */
  public function edit(Sign $sign)
  {
    return view('signs.edit')->with(compact('sign'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateSignRequest  $request
   * @param  \App\Models\Sign  $sign
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateSignRequest $request, Sign $sign)
  {
    $sign->name = $request->post('name');
    $sign->description = $request->post('description');
    $sign->svg = $request->post('svg');
    $sign->save();

    return redirect(route('signs.index'))->with('success', 'Sign updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Sign  $sign
   * @return \Illuminate\Http\Response
   */
  public function destroy(Sign $sign)
  {
    $sign->delete();
    return redirect(route('signs.index'))->with('success', 'Sign removed');
  }
}
