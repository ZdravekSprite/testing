<?php

namespace App\Http\Controllers;

use App\Models\Sign;
use App\Http\Requests\StoreSignRequest;
use App\Http\Requests\UpdateSignRequest;
use Illuminate\Support\Facades\Auth;

class SignController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (Auth::id()) {
      $signs = Sign::orderBy('name')->paginate(25);  
    } else {
      $signs = Sign::orderBy('name')->where('svg_type', '!=', 'help')->where('svg_type', '!=', 'broj')->paginate(25);
    }
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
    $sign->a = $request->post('a');
    $sign->b1 = $request->post('b1');
    $sign->b2 = $request->post('b2');
    $sign->c = $request->post('c');
    $sign->svg_type = $request->post('svg_type');
    $sign->svg_start_fill = $request->post('svg_start_fill');
    $sign->svg_start_transform = $request->post('svg_start_transform');
    $sign->svg_start = $request->post('svg_start');
    $sign->svg = $request->post('svg');
    $sign->svg_end_fill = $request->post('svg_end_fill');
    $sign->svg_end_transform = $request->post('svg_end_transform');
    $sign->svg_end = $request->post('svg_end');
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
    //dd($sign->svg_all());
    return view('signs.show')->with(compact('sign'));
  }

  /**
   * Display the specified resource.
   *
   * @param  $sign
   * @return \Illuminate\Http\Response
   */
  public function gif($sign)
  {
    $svg = '';
    $sign = Sign::where('name', '=', $sign)->first();
    if (!$sign) {
      $svg = '<text text-anchor="middle" x="360" y="500" font-size="500" >?</text>';
    } else {
      $svg = $sign->svg_all();
    }
    $im = new \Imagick();
    $svg = '<?xml version="1.0" standalone="no"?>
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="720px" height="720px" viewBox="0 0 720 720">
    ' . $svg . '
    </svg>';
    //dd($svg);
    $im->setBackgroundColor(new \ImagickPixel('transparent'));
    $im->readImageBlob($svg);
    $im->resizeImage(100, 100, \Imagick::FILTER_LANCZOS, 1, true);
    $im->setImageFormat("gif");
    $type = 'image/gif';
    header("Content-Type: " . $type);
    return response($im->getImageBlob())->header('Content-Type', $type);
  }

  public function svg($sign)
  {
    $svg = '';
    $sign = Sign::where('name', '=', $sign)->first();
    if (!$sign) {
      $svg = '<text text-anchor="middle" x="360" y="500" font-size="500" >?</text>';
    } else {
      $svg = $sign->svg_all();
    }
    $svg = '<?xml version="1.0" standalone="no"?>
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="720px" height="720px" viewBox="0 0 720 720">
    ' . $svg . '
    </svg>';
    return response($svg);
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
    $sign->a = $request->post('a');
    $sign->b1 = $request->post('b1');
    $sign->b2 = $request->post('b2');
    $sign->c = $request->post('c');
    $sign->svg_type = $request->post('svg_type');
    $sign->svg_start_fill = $request->post('svg_start_fill');
    $sign->svg_start_transform = $request->post('svg_start_transform');
    $sign->svg_start = $request->post('svg_start');
    $sign->svg = $request->post('svg');
    $sign->svg_end_fill = $request->post('svg_end_fill');
    $sign->svg_end_transform = $request->post('svg_end_transform');
    $sign->svg_end = $request->post('svg_end');
    $sign->save();

    return redirect(route('signs.index'))->with('success', 'Sign ' . $sign->name . ' updated');
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
