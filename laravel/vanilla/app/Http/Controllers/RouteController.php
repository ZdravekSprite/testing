<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $routes = Route::all();
    if ($request->wantsJson()) {
      // I'm from API
      return $routes;
    } else {
      // I'm from HTTP
      return view('routes.index')->with('routes', $routes);
    }

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $route = new Route;
    //dd($route);
    return view('routes.create')->with(compact('route'));

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreRouteRequest  $request
   * @return \Illuminate\Http\Response
   */
  //public function store(StoreRouteRequest $request)
  public function store(Request $request)
  {
    /*
    $this->validate($request, [
      'data' => ['required', 'array'],
      'data.*.timestamp' => ['required', 'int'],
      'data.*.coords' => ['required', 'array'],
      'data.*.coords.accuracy' => ['required', 'string'],
      'data.*.coords.altitude' => ['required', 'string'],
      'data.*.coords.altitudeAccuracy' => ['required', 'string'],
      'data.*.coords.heading' => ['required', 'string'],
      'data.*.coords.latitude' => ['required', 'string'],
      'data.*.coords.longitude' => ['required', 'string'],
      'data.*.coords.speed' => ['required', 'string'],
  ]);
  */

    $this->validate($request, [
      'data' => ['required', 'json'],
    ]);
/*
    $data = json_decode($request->post('data'), true, JSON_THROW_ON_ERROR); // Needs to be decoded

    // validate $data is correct
    Validator::make($data, [
      'timestamp' => ['required', 'int'],
      'coords' => ['required', 'array'],
      'coords.*.accuracy' => ['required', 'string'],
      'coords.*.altitude' => ['required', 'string'],
      'coords.*.altitudeAccuracy' => ['required', 'string'],
      'coords.*.heading' => ['required', 'string'],
      'coords.*.latitude' => ['required', 'string'],
      'coords.*.longitude' => ['required', 'string'],
      'coords.*.speed' => ['required', 'string'],
    ])->validate();
*/
    $route = new Route();
    //$route->uuid = Str::uuid()->toString();
    $route->data = $request->post('data'); // No need to decode as it's already an array
    $route->save();

    if ($request->wantsJson()) {
      // I'm from API
      return $route;
    } else {
      // I'm from HTTP
      return redirect(route('routes.index'))->with('success', 'Route Created');
    }

    //return Redirect::to("/route/{$route->uuid}")->with('success', 'Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Route  $route
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, Route $route)
  {
    if ($request->wantsJson()) {
      // I'm from API
      return $route;
    } else {
      // I'm from HTTP
      return view('routes.show')->with(compact('route'));
    }
  }
  /*
  public function show($uuid)
  {
    $paste = Route::where('uuid', $uuid)->first();
    return response()->json($paste->data);
  }
  */
  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Route  $route
   * @return \Illuminate\Http\Response
   */
  public function edit(Route $route)
  {
    return view('routes.edit')->with(compact('route'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateRouteRequest  $request
   * @param  \App\Models\Route  $route
   * @return \Illuminate\Http\Response
   */
  //public function update(UpdateRouteRequest $request, Route $route)
  public function update(Request $request, Route $route)
  {
    $this->validate($request, [
      'data' => ['required', 'json'],
    ]);
    $route->data = $request->post('data');
    $route->save();

    if ($request->wantsJson()) {
      // I'm from API
      return $route;
    } else {
      // I'm from HTTP
      return redirect(route('routes.index'))->with('success', 'Route Updated');
    }

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Route  $route
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, Route $route)
  {
    if ($request->wantsJson()) {
      // I'm from API
      return $route->delete();
    } else {
      // I'm from HTTP
      $route->delete();
      return redirect(route('routes.index'))->with('success', 'Route removed');
    }
  }
}
