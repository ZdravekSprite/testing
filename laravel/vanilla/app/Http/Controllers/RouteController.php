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
      $lookAtLng = 0;
      $lookAtLat = 0;
      $paths = '';
      $signs = '';
      $types = [];

      foreach ($routes as $route) {

        $speedLimit = null;
        $cooedinates = '';
        if (isset(json_decode($route->data)->path)) {
          foreach (json_decode($route->data)->path as $key => $value) {
            $cooedinates .= '
          ' . $value->location->coords->longitude . ',' . $value->location->coords->latitude . ',0';
          }
          $lookAtLng = json_decode($route->data)->path[0]->location->coords->longitude;
          $lookAtLat = json_decode($route->data)->path[0]->location->coords->latitude;
        } else {
          foreach (json_decode($route->data)->data as $key => $value) {
            $cooedinates .= '
          ' . $value->coords->longitude . ',' . $value->coords->latitude . ',0';
            if ($speedLimit != $value->speedLimit) {
              $types[] = $value->speedLimit ? 'b30-' . $value->speedLimit : 'c14';
              $signs .= '
    <Placemark>
      <name>Oznaka ' . $key . '</name>
      <description>' . $value->speedLimit . '</description>
      <styleUrl>#' . ($value->speedLimit ? 'b30-' . $value->speedLimit : 'c14') . '</styleUrl>
      <Point>
        <coordinates>' . $value->coords->longitude . ',' . $value->coords->latitude . ',0</coordinates>
      </Point>
    </Placemark>';
              $speedLimit = $value->speedLimit;
            }
          }
          $lookAtLng = json_decode($route->data)->data[0]->coords->longitude;
          $lookAtLat = json_decode($route->data)->data[0]->coords->latitude;
        }
        $paths .= '
    <Placemark>
      <name>Path ' . json_decode($route->data)->title . '</name>
      <description>' . json_decode($route->data)->route . '</description>
      <LookAt>
        <longitude>' . $lookAtLng . '</longitude>
        <latitude>' . $lookAtLat . '</latitude>
        <altitude>0</altitude>
        <heading>-50</heading>
        <tilt>60</tilt>
        <range>80</range>
        <altitudeMode>relativeToGround</altitudeMode>
      </LookAt>
      <styleUrl>#testExample</styleUrl>
      <LineString>
        <extrude>1</extrude>
        <tessellate>1</tessellate>
        <altitudeMode>absolute</altitudeMode>
        <coordinates>' . $cooedinates . '
        </coordinates>
      </LineString>
    </Placemark>
        ';
        if (isset(json_decode($route->data)->signs)) {
          foreach (json_decode($route->data)->signs as $key => $value) {
            $types[] = $value->type;
            $signs .= '
    <Placemark>
      <name>Oznaka ' . $key . '</name>
      <description>' . $value->type . '</description>
      <styleUrl>#' . $value->type . '</styleUrl>
      <Point>
        <coordinates>' . $value->coords->longitude . ',' . $value->coords->latitude . ',0</coordinates>
      </Point>
    </Placemark>';
          }
        }
      }
      //dd(array_unique($types));
      $styles = '';
      foreach (array_unique($types) as $type) {
        $gif = $type;
        if ($type == null) $gif = 'c14';
        if ($type == 40) $gif = 'b30-40';
        if (substr($type, 0, 4) == 'b31-') $gif = 'b30-' . substr($type, 4);
        $styles .= '
    <Style id="' . $type . '">
      <IconStyle>
        <scale>1</scale>
        <Icon>
          <href>' . url('/') . '/gif/' . $gif . '</href>
        </Icon>
      </IconStyle>
      <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
      <LabelStyle>
        <color>00ffffff</color>
      </LabelStyle>
    </Style>';
      };

      $kml_string = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
  <Document>
    <name>Paths.kml</name>
    <description>All path</description>
    <Style id="testExample">
      <IconStyle>
        <scale>1</scale>
          <Icon>
            <href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
          </Icon>
        <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
      </IconStyle>
      <LabelStyle>
        <color>00ffffff</color>
      </LabelStyle>
      <LineStyle>
        <color>ffff00aa</color>
        <width>2</width>
      </LineStyle>
      <PolyStyle>
        <color>7f00ff00</color>
      </PolyStyle>
    </Style>';
      $kml_string .= $styles . $paths . $signs . '
  </Document>
</kml>';
      return view('routes.index')->with(compact('routes', 'kml_string'));
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

  public function style_string($type) {
    $gif = $type;
    if ($type == null) $gif = 'c14';
    if ($type == 40) $gif = 'b30-40';
    if (substr($type, 0, 4) == 'b31-') $gif = 'b30-' . substr($type, 4);
    $string = '
    <Style id="' . $type . '">
      <IconStyle>
        <scale>1</scale>
        <Icon>
          <href>' . url('/') . '/gif/' . $gif . '</href>
        </Icon>
      </IconStyle>
      <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
      <LabelStyle>
        <color>00ffffff</color>
      </LabelStyle>
    </Style>';
    return $string;
  }
  public function sign_placemarker($key, $type, $lng, $lat) {
    $string = '
    <Placemark>
      <name>Oznaka ' . $key . '</name>
      <description>' . $type . '</description>
      <styleUrl>#' . $type . '</styleUrl>
      <Point>
        <coordinates>' . $lng . ',' . $lat . ',0</coordinates>
      </Point>
    </Placemark>';
    return $string;
  }
  public function path_placemarker($title, $route, $lookAtLng, $lookAtLat, $style, $coords) {
    $string = '
    <Placemark>
    <name>Path ' . $title . '</name>
    <description>' . $route . '</description>
    <LookAt>
      <longitude>' . $lookAtLng . '</longitude>
      <latitude>' . $lookAtLat . '</latitude>
      <altitude>0</altitude>
      <heading>-50</heading>
      <tilt>60</tilt>
      <range>80</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>
    <styleUrl>#' . $style . '</styleUrl>
    <LineString>
      <extrude>1</extrude>
      <tessellate>1</tessellate>
      <altitudeMode>absolute</altitudeMode>
      <coordinates>' . $coords . '
      </coordinates>
    </LineString>
  </Placemark>';
    return $string;
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
      $lookAtLng = 0;
      $lookAtLat = 0;
      $paths = '';
      $signs = '';
      $types = [];

      $speedLimit = null;
      $coordinates = [];
      if (isset(json_decode($route->data)->path)) {
        foreach (json_decode($route->data)->path as $key => $value) {
          $coordinates[] = [$value->location->coords->longitude, $value->location->coords->latitude , $value->location->coords->heading];
        }
      } else {
        foreach (json_decode($route->data)->data as $key => $value) {
          $coordinates[] = [$value->coords->longitude, $value->coords->latitude , $value->coords->heading];
          if ($speedLimit != $value->speedLimit) {
            $types[] = $value->speedLimit ? 'b30-' . $value->speedLimit : 'c14';
            $signs .= $this->sign_placemarker($key, ($value->speedLimit ? 'b30-' . $value->speedLimit : 'c14'), $value->coords->longitude, $value->coords->latitude);
            $speedLimit = $value->speedLimit;
          }
        }
      }
      $coords_string = '';
      $path_style = $coordinates[0][2]*1<180 ? 'test1' : 'test2';
      $lookAtLng = $coordinates[0][0];
      $lookAtLat = $coordinates[0][1];
      foreach ($coordinates as $key => $value) {
        $new_style = $value[2]*1<180 ? 'test1' : 'test2';
        if ($new_style != $path_style) {
          $coords_string .= '
          ' .$value[0] . ',' . $value[1] . ',0';
          $paths .= $this->path_placemarker(json_decode($route->data)->title, json_decode($route->data)->route, $lookAtLng, $lookAtLat, $path_style, $coords_string);
          $path_style = $new_style;
          $lookAtLng = $value[0];
          $lookAtLat = $value[1];
          $coords_string = '';
          }
        $coords_string .= '
        ' .$value[0] . ',' . $value[1] . ',0';
      }
      $paths .= $this->path_placemarker(json_decode($route->data)->title, json_decode($route->data)->route, $lookAtLng, $lookAtLat, $path_style, $coords_string);
      if (isset(json_decode($route->data)->signs)) {
        foreach (json_decode($route->data)->signs as $key => $value) {
          $types[] = $value->type;
          $signs .= $this->sign_placemarker($key, $value->type, $value->coords->longitude, $value->coords->latitude);
        }
      }
      
      //dd(array_unique($types));
      $styles = '';
      foreach (array_unique($types) as $type) {
        $styles .= $this->style_string($type);
      };

      $kml_string = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
  <Document>
    <name>' . json_decode($route->data)->title . '</name>
    <description>' . json_decode($route->data)->route . '</description>
    <Style id="test1">
      <IconStyle>
        <scale>1</scale>
          <Icon>
            <href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
          </Icon>
        <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
      </IconStyle>
      <LabelStyle>
        <color>00ffffff</color>
      </LabelStyle>
      <LineStyle>
        <color>ffff00aa</color>
        <width>2</width>
      </LineStyle>
      <PolyStyle>
        <color>7f00ff00</color>
      </PolyStyle>
    </Style>
    <Style id="test2">
      <IconStyle>
        <scale>1</scale>
          <Icon>
            <href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
          </Icon>
        <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
      </IconStyle>
      <LabelStyle>
        <color>00ffffff</color>
      </LabelStyle>
      <LineStyle>
        <color>aa00ffff</color>
        <width>2</width>
      </LineStyle>
      <PolyStyle>
        <color>7f00ff00</color>
      </PolyStyle>
    </Style>';
      $kml_string .= $styles . $paths . $signs . '
  </Document>
</kml>';
      return view('routes.show')->with(compact('route', 'kml_string'));
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
