<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Http\Requests\StoreSettingsRequest;
use App\Http\Requests\UpdateSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreSettingsRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Settings $settings)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Settings $settings)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Settings $settings)
  {
    $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
    if (!$settings) {
      $settings = new Settings();
      $settings->user_id = Auth::user()->id;
    }
    $settings->start1 = $request->input('start1') ?? $settings->start1;
    $settings->end1 = $request->input('end1') ?? $settings->end1;
    $settings->start2 = $request->input('start2') ?? $settings->start2;
    $settings->end2 = $request->input('end2') ?? $settings->end2;
    $settings->start3 = $request->input('start3') ?? $settings->start3;
    $settings->end3 = $request->input('end3') ?? $settings->end3;
    $settings->zaposlen = $request->input('zaposlen') ?? $settings->zaposlen;
    $settings->BINANCE_API_KEY = $request->input('bkey') ?? $settings->BINANCE_API_KEY;
    $settings->BINANCE_API_SECRET = $request->input('bsecret') ?? $settings->BINANCE_API_SECRET;
    //dd($settings);
    $settings->save();
    return redirect(route('dashboard'))->with('success', 'Settings Updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Settings $settings)
  {
    //
  }
}
