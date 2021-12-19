<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Month;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MonthController extends Controller
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
    $months = Month::orderBy('month', 'desc')->where('user_id', '=', Auth::user()->id)->get();
    $month = Month::orderBy('month', 'desc')->where('user_id', '=', Auth::user()->id)->first();
    if (!$month) {
      return redirect(route('months.create'))->with('warning', 'Treba napraviti bar jedan mjesec');
    }
    $hoursNorm = $month->hoursNorm();
    $bruto = $month->bruto ?? $month->last('bruto');
    $perHour = round(($bruto / 100 / $hoursNorm->All), 2);
    $data['perHour'] = $perHour;
    $days = $month->days();
    $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
    if (!$settings) {
      $settings = new Settings();
      $settings->start1 = '06:00';
      $settings->end1 = '14:00';
      $settings->start2 = '14:00';
      $settings->end2 = '22:00';
      $settings->start3 = '22:00';
      $settings->end3 = '06:00';
    }

    return view('months.index')->with(compact('months', 'month', 'data', 'days', 'settings'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $month = new Month;
    $last_month = Month::orderBy('month', 'desc')->where('user_id', '=', Auth::user()->id)->first();
    $month->user_id = Auth::user()->id;
    /*
    if (!$last_month) {
      $bruto = $month->bruto ?? 530000;
      $prijevoz = $month->prijevoz ?? 36000;
      $prehrana = $month->prehrana ?? 0;
      $minuli = $month->minuli ?? 0;
      $odbitak = $month->odbitak ?? 400000;
      $prirez = $month->prirez ?? 1800;
      $sindikat = $month->sindikat;
      $month->kredit = $month->kredit ?? 0;
    } else {
      $bruto = $month->bruto ?? $last_month->last('bruto') ?? 530000;
      $prijevoz = $month->prijevoz ?? $last_month->last('prijevoz') ?? 36000;
      $prehrana = $month->prehrana ?? $last_month->last('prehrana') ?? 0;
      $minuli = $month->minuli ?? $last_month->last('minuli') ?? 0;
      $odbitak = $month->odbitak ?? $last_month->last('odbitak') ?? 400000;
      $prirez = $month->prirez ?? $last_month->last('prirez') ?? 1800;
      $sindikat = $month->sindikat ?? $last_month->last('sindikat') ?? false;
      $kredit = $month->kredit ?? $last_month->last('kredit') ?? 0;
    }
    $month->bruto = $bruto;
    $month->prijevoz = $prijevoz;
    $month->prehrana = $prehrana;
    $month->minuli = $minuli;
    $month->odbitak = $odbitak;
    $month->prirez = $prirez;
    $month->sindikat = $sindikat;
    $month->kredit = $kredit;
    */

    $month->bruto = $last_month ? $last_month->last('bruto') : 530000;
    $month->prijevoz = $last_month ? $last_month->last('prijevoz') : 36000;
    $month->prehrana = $last_month ? $last_month->last('prehrana') : 0;
    $month->minuli = $last_month ? $last_month->last('minuli') : 0;
    $month->odbitak = $last_month ? $last_month->last('odbitak') : 400000;
    $month->prirez = $last_month ? $last_month->last('prirez') : 1800;
    $month->sindikat = $last_month ? $last_month->last('sindikat') : false;
    $month->kredit = $last_month ? $last_month->last('kredit') : 0;

    //dd($month);
    return view('months.create')->with(compact('month'));
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
      'month' => 'required',
      'year' => 'required'
    ]);
    $month = new Month;
    $month->month = $request->input('month') - 1 + $request->input('year') * 12;
    $month->user_id = Auth::user()->id;
    $month->bruto = $request->input('bruto') ? $request->input('bruto') * 100 : null;
    $month->prijevoz = $request->input('prijevoz') ? $request->input('prijevoz') * 100 : null;
    $month->prehrana = $request->input('prehrana') ? $request->input('prehrana') * 100 : null;
    $month->minuli = $request->input('minuli') ? $request->input('minuli') * 10 : null;
    $month->odbitak = $request->input('odbitak') ? $request->input('odbitak') * 100 : null;
    $month->prirez = $request->input('prirez') ? $request->input('prirez') * 100 : null;
    if ($request->input('sindikat') == true) $month->sindikat = true;
    //$month->sindikat = $request->input('sindikat') ? $request->input('sindikat') : null;
    $month->kredit = $request->input('kredit') ? $request->input('kredit') : null;
    $old_month = Month::where('user_id', '=', Auth::user()->id)->where('month', '=', $month->month)->first();
    if ($old_month) return redirect(route('months.edit', ['month' => $month->slug()]))->with('new_month', $month)->with('warning', 'Day already exist');
    //dd($request,$month,$old_month);
    $month->save();
    return redirect(route('months.show', ['month' => $month->slug()]))->with('success', 'Month Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Month  $month
   * @return \Illuminate\Http\Response
   */
  //public function show(Month $month)
  public function show(String $month)
  {
    $unslug = explode(".", $month)[0] - 1 + explode(".", $month)[1] * 12;
    $month = Month::where('user_id', '=', Auth::user()->id)->where('month', '=', $unslug)->first();
    if (!$month) {
      return redirect(route('months.index'))->with('warning', 'Nema tog mjeseca');
    }
    $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
    if (!$settings) {
      $settings = new Settings();
      $settings->start1 = '06:00';
      $settings->end1 = '14:00';
      $settings->start2 = '14:00';
      $settings->end2 = '22:00';
      $settings->start3 = '22:00';
      $settings->end3 = '06:00';
    }
    $days = $month->days();

    if (User::where('id', '=', Auth::user()->id)->first()->hasAnyRole(env('FIRM1'))) {
      $firm = 'firm1';
      $data  = $this->lista_data1($month);
    } elseif (User::where('id', '=', Auth::user()->id)->first()->hasAnyRole(env('FIRM2'))) {
      $firm = 'firm2';
      $data  = $this->lista_data2($month);
    } else {
      $firm = 'firm0';
      $data  = $this->lista_data0($month);
    }

    $data['-'] = route('months.show', ['month' => $month->prev()]);
    $data['+'] = route('months.show', ['month' => $month->next()]);
    //dd($month,$days,$data);
    return view('months.show')->with(compact('month', 'days', 'data', 'settings', 'firm'));
  }

  public function platna_lista(Request $request)
  {

    if ($request->input('month') == null) {
      $m['x'] = Carbon::now();
    } else {
      $m['x'] = Carbon::parse('01.' . $request->input('month'));
    }
    $data['month'] = $m['x']->format('Y') * 12 + $m['x']->format('m') - 1;
    $month = Month::orderBy('month', 'desc')->where('month', '<=', $data['month'])->where('user_id', '=', Auth::user()->id)->first();
    if (!$month) {
      return redirect(route('months.create'))->with('warning', 'Treba napraviti bar jedan mjesec');
    }
    //dd($m, $month);
    $settings = Settings::where('user_id', '=', Auth::user()->id)->first();
    if (!$settings) {
      $settings = new Settings();
      $settings->start1 = '06:00';
      $settings->end1 = '14:00';
      $settings->start2 = '14:00';
      $settings->end2 = '22:00';
      $settings->start3 = '22:00';
      $settings->end3 = '06:00';
    }
    /*
    if (!User::where('id', '=', Auth::user()->id)->first()->hasAnyRole('panpek')) {
      $data  = $this->lista_data1($month);
    } else {
      $data  = $this->lista_data($month);
    }
    */
    if (User::where('id', '=', Auth::user()->id)->first()->hasAnyRole(env('FIRM1'))) {
      $firm = 'firm1';
      $data  = $this->lista_data1($month);
    } elseif (User::where('id', '=', Auth::user()->id)->first()->hasAnyRole(env('FIRM2'))) {
      $firm = 'firm2';
      $data  = $this->lista_data2($month);
    } else {
      $firm = 'firm0';
      $data  = $this->lista_data0($month);
    }

    $data['-'] = route('lista', ['month' => $month->prev()]);
    $data['+'] = route('lista', ['month' => $month->next()]);
    //dd($m, $month, $data);
    return view('months.platna-lista')->with(compact('month', 'data', 'firm'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Month  $month
   * @return \Illuminate\Http\Response
   */
  //public function edit(Month $month)
  public function edit($month)
  {
    if (session('new_month')) {
      $month = session('new_month');
    } else {
      $unslug = explode(".", $month)[0] - 1 + explode(".", $month)[1] * 12;
      $month = Month::where('user_id', '=', Auth::user()->id)->where('month', '=', $unslug)->first();
    }
    $month->bruto = $month->bruto ?? $month->last('bruto');
    $month->prijevoz = $month->prijevoz ?? $month->last('prijevoz');
    $month->prehrana = $month->prehrana ?? $month->last('prehrana');
    $month->minuli = $month->minuli ?? $month->last('minuli');
    $month->odbitak = $month->odbitak ?? $month->last('odbitak');
    $month->prirez = $month->prirez ?? $month->last('prirez');
    $month->sindikat = $month->sindikat ?? $month->last('sindikat');
    $month->kredit = $month->kredit ?? $month->last('kredit');
    //dd($month);
    return view('months.edit')->with(compact('month'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Month  $month
   * @return \Illuminate\Http\Response
   */
  //public function update(Request $request, Month $month)
  public function update(Request $request, $month)
  {
    $this->validate($request, [
      'month' => 'required',
    ]);
    $month = Month::where('user_id', '=', Auth::user()->id)->where('month', '=', $request->input('month'))->first();
    $month->bruto = $request->input('bruto') ? $request->input('bruto') * 100 : $month->bruto;
    $month->prijevoz = $request->input('prijevoz') ? $request->input('prijevoz') * 100 : $month->prijevoz;
    $month->prehrana = $request->input('prehrana') ? $request->input('prehrana') * 100 : $month->prehrana;
    $month->minuli = $request->input('minuli') ? $request->input('minuli') * 10 : $month->minuli;
    $month->odbitak = $request->input('odbitak') ? $request->input('odbitak') * 100 : $month->odbitak;
    $month->prirez = $request->input('prirez') ? $request->input('prirez') * 100 : $month->prirez;
    $month->sindikat = $request->input('sindikat') ? $request->input('sindikat') : $month->sindikat;
    $month->kredit = $request->input('kredit') ? $request->input('kredit') : $month->kredit;

    $month->prekovremeni = $request->input('prekovremeni') ?? $month->prekovremeni;
    $month->bolovanje = $request->input('bolovanje') * 100 ?? $month->bolvanje;
    $month->nocni = $request->input('nocni') * 10 ?? $month->nocni;
    $month->nagrada = $request->input('nagrada') ? $request->input('nagrada') * 100 : $month->nagrada;
    $month->regres = $request->input('regres') ? $request->input('regres') * 100 : $month->regres;
    $month->bozicnica = $request->input('bozicnica') ? $request->input('bozicnica') * 100 : $month->bozicnica;
    $month->prigodna = $request->input('prigodna') ? $request->input('prigodna') * 100 : $month->prigodna;
    $month->stimulacija = $request->input('stimulacija') ? $request->input('stimulacija') * 100 : $month->stimulacija;
    $month->stari = $request->input('starih') ? ($request->input('starih') * 60 + $request->input('starim')) : $month->stari;
    if ($request->input('sindikat') == true) $month->sindikat = true;
    $month->kredit = $request->input('kredit') * 100 ?? $month->kredit;
    $month->save();
    return redirect(route('months.show', ['month' => $month->slug()]))->with('success', 'Month Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Month  $month
   * @return \Illuminate\Http\Response
   */
  //public function destroy(Month $month)
  public function destroy($month)
  {
    $unslug = explode(".", $month)[0] - 1 + explode(".", $month)[1] * 12;
    $month = Month::where('month', '=', $unslug)->first();
    $month->delete();
    return redirect(route('months.index'))->with('success', 'Month removed');
  }

  public function lista_data0(Month $month)
  {
    $data['III.godina'] = explode(".", $month->slug())[1];
    $data['III.mjesec'] = explode(".", $month->slug())[0];

    $from = $month->from();
    $to = $month->to();
    $data['III.od'] = $from->format('d');
    $data['III.do'] = $to->format('d');

    $hoursNorm = $month->hoursNorm();
    $bruto = $month->bruto ?? $month->last('bruto');
    $month->bruto = $bruto;
    $data['bruto'] = $bruto;
    $perHour = round(($bruto / 100 / $hoursNorm->All), 2);
    $data['perHour'] = $perHour;
    $hoursWorkNorm = $hoursNorm->Work;
    $prijevoz = $month->prijevoz ?? $month->last('prijevoz');
    $month->prijevoz = $prijevoz;
    $data['prijevoz'] = $prijevoz;
    $odbitak = $month->odbitak ?? $month->last('odbitak');
    $month->odbitak = $odbitak;
    $data['odbitak'] = $odbitak;
    $prirez = $month->prirez ?? $month->last('prirez');
    $month->prirez = $prirez;
    $data['prirez'] = $prirez;
    //dd($hoursNorm, $bruto, $perHour);

    // 1.1. Za redoviti rad
    $h1_1 = $hoursNorm->min / 60 > $hoursWorkNorm ? $hoursWorkNorm : $hoursNorm->min / 60;
    $data['1.1.h'] = number_format($h1_1, 2, ',', '.');
    $kn1_1 = round($h1_1 * $perHour, 2);
    $data['1.1.kn'] = number_format($kn1_1, 2, ',', '.');

    // 1.4 Za prekovremeni rad
    $h1_4 = $month->prekovremeni;
    $data['prekovremeni'] = $month->prekovremeni;
    $overWork = $hoursNorm->min / 60 - $hoursWorkNorm;

    $data['1.4.h'] = number_format($h1_4, 2, ',', '.') . ' (' . number_format($overWork, 2, ',', '.') . ')';
    $kn1_4 = round($h1_4 * $perHour * 1.5, 2);
    $kn1_4x = $overWork > 0 ? round($overWork * $perHour * 1.5, 2) : 0;
    $data['1.4.kn'] = number_format($kn1_4, 2, ',', '.') . ($kn1_4x ? ' (' . number_format($kn1_4x, 2, ',', '.') . ')' : '');

    // 1.7a Praznici. Blagdani, izbori
    $data['1.7a.h'] = number_format($hoursNorm->Holiday, 2, ',', '.');
    $kn1_7a = round($hoursNorm->Holiday * $perHour, 2);
    $data['1.7a.kn'] = number_format($kn1_7a, 2, ',', '.');

    // 1.7b Godišnji odmor
    $data['1.7b.h'] = number_format($hoursNorm->GO, 2, ',', '.');
    $kn1_7b = round($hoursNorm->GO * $perHour, 2);
    $data['1.7b.kn'] = number_format($kn1_7b, 2, ',', '.');

    // 1.7c Plaćeni dopust
    $data['1.7c.h'] = number_format($hoursNorm->Dopust, 2, ',', '.');
    $kn1_7c = round($hoursNorm->Dopust * $perHour, 2);
    $data['1.7c.kn'] = number_format($kn1_7c, 2, ',', '.');

    // 1.7d Bolovanje do 42 dana
    $data['1.7d.h'] = number_format($hoursNorm->Sick, 2, ',', '.');
    //$kn1_7d = round($hoursNorm->Sick * $perHour * 0.7588, 2);
    if ($hoursNorm->Sick && !$month->bolovanje) {
      $ms = Month::where('user_id', '=', Auth::user()->id)->where('month', '>=', $month->month - 6)->where('month', '<', $month->month)->get();
      //dd($ms);
      if (count($ms)) {
        $mjeseci = [];
        foreach ($ms as $key => $value) {
          $mHoursNorm = $value->hoursNorm();
          $mBruto = $value->bruto ?? $value->last('bruto');
          $mPerHour = round(($mBruto / 100 / $mHoursNorm->All), 2);
          $mjeseci[$key] = $mPerHour;
        }
        //dd(array_sum($mjeseci) / count($mjeseci) * 0.7);
        $kn1_7d = round(array_sum($mjeseci) / count($mjeseci) * 0.7, 2) * $hoursNorm->Sick;
        $text17 = '(' . number_format($kn1_7d / $hoursNorm->Sick / $perHour * 100, 2, ',', '.') . '%)*';
        $data['1.7d.t'] = 'izračunato na osnovu prosjeka zadnjih 6 mjeseci';
        $data['1.7d.kn'] = number_format($kn1_7d, 2, ',', '.') . $text17;
      } else {
        $kn1_7d = round($perHour * 0.7, 2) * $hoursNorm->Sick;
        $data['1.7d.kn'] = number_format($kn1_7d, 2, ',', '.') . '(70%)**';
        $data['1.7d.t'] = 'izračunato na osnovu prosjeka trenutnog mjeseca';
      }
    } else {
      $kn1_7d = $month->bolovanje / 100;
      $text17 = $hoursNorm->Sick ? '(' . number_format($kn1_7d / $hoursNorm->Sick / $perHour * 100, 2, ',', '.') . '%)' : '';
      $data['1.7d.t'] = '';
      $data['1.7d.kn'] = number_format($kn1_7d, 2, ',', '.') . $text17;
    }
    // 1.7e Dodatak za rad nedjeljom
    $data['1.7e.h'] = number_format($hoursNorm->minSunday / 60, 2, ',', '.');
    $kn1_7e = round($hoursNorm->minSunday / 60 * $perHour * 0.35, 2);
    $data['1.7e.kn'] = number_format($kn1_7e, 2, ',', '.');

    // 1.7f Dodatak za rad na praznik
    $data['1.7f.h'] = number_format($hoursNorm->minHoliday / 60, 2, ',', '.');
    $kn1_7f = round($hoursNorm->minHoliday / 60 * $perHour * 0.5, 2);
    $data['1.7f.kn'] = number_format($kn1_7f, 2, ',', '.');

    // 1.7g Dodatak za noćni rad
    $h1_7g = $month->nocni / 10;
    $nightWork = $hoursNorm->minNight;

    $data['1.7g.h'] = number_format($h1_7g, 2, ',', '.') . ' (' . number_format($nightWork, 0, ',', '.') . 'min)';
    $kn1_7g = round($h1_7g * $perHour * 0.3, 2);
    $data['1.7g.kn'] = number_format($kn1_7g, 2, ',', '.');

    // 1.7p Nagrada za radne rezultate
    $kn1_7p = round($month->nagrada / 100, 2);
    $data['1.7p.kn'] = number_format($kn1_7p, 2, ',', '.');

    $kn1 = $kn1_1 + $kn1_4 + $kn1_7a + $kn1_7b + $kn1_7c + $kn1_7d + $kn1_7e + $kn1_7f + $kn1_7g + $kn1_7p;
    $kn1x = $kn1_1 + $kn1_4x + $kn1_7a + $kn1_7b + $kn1_7c + $kn1_7d + $kn1_7e + $kn1_7f + $kn1_7g + $kn1_7p;
    $razlika = $kn1_4x;

    // 2. OSTALI OBLICI
    $kn2 = round($month->stimulacija / 100, 2);
    $data['2.kn'] = number_format($kn2, 2, ',', '.');
    // 2.8. Stimulacija bruto
    $data['2.8.kn'] = number_format($month->stimulacija / 100, 2, ',', '.');
    $extra_prekovremeni = $month->stimulacija / 100 / $perHour / 1.5;
    $data['2.8.kn'] = number_format($month->stimulacija / 100, 2, ',', '.');
    $data['extra'] = floor($extra_prekovremeni) . 'h ' . (floor($extra_prekovremeni * 60) % 60) . 'm ' . floor($extra_prekovremeni * 3600) % 60 . 's';

    // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
    $prijevoz = $month->prijevoz / 100 ?? 360;
    $prijevoz = ($hoursNorm->GO + $hoursNorm->Sick) > 14 ? $prijevoz * ($hoursNorm->All - $hoursNorm->GO - $hoursNorm->Sick) / $hoursNorm->All : $prijevoz;
    //dd($hoursNorm);
    $prijevoz = $hoursNorm->firstAll > $hoursNorm->All ? $prijevoz : $prijevoz * $hoursNorm->firstAll / $hoursNorm->All;
    $regres = $month->regres / 100 ?? 0;
    $kn3 = round($prijevoz + $regres, 2);
    $data['3.kn'] = number_format($kn3, 2, ',', '.');
    // 3.1. Prijevoz
    $data['3.1.kn'] = number_format($prijevoz, 2, ',', '.');
    // 3.7. Regres za godišnji odmor
    $data['3.7.kn'] = number_format($regres, 2, ',', '.');

    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn4 = $kn1 + $kn2 + $kn3;
    $kn4x = $kn1x + $kn3;
    $data['4.kn'] = number_format($kn4, 2, ',', '.') . ($razlika ? ' (' . number_format($kn4x, 2, ',', '.') . ')' : '');

    // 5. OSNOVICA ZA OBRAČUN DOPRINOSA
    $kn5 = $kn1 - $kn1_7p + $kn2;
    $kn5x = $kn1x - $kn1_7p;
    $data['5.kn'] = number_format($kn5, 2, ',', '.') . ($razlika ? ' (' . number_format($kn5x, 2, ',', '.') . ')' : '');

    // 6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)
    $kn6_1 = round($kn5 * 0.15, 2);
    $kn6_1x = round($kn5x * 0.15, 2);
    $data['6.1.kn'] = number_format($kn6_1, 2, ',', '.') . ($razlika ? ' (' . number_format($kn6_1x, 2, ',', '.') . ')' : '');
    // 6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)
    $kn6_2 = round($kn5 * 0.05, 2);
    $kn6_2x = round($kn5x * 0.05, 2);
    $data['6.2.kn'] = number_format($kn6_2, 2, ',', '.') . ($razlika ? ' (' . number_format($kn6_2x, 2, ',', '.') . ')' : '');

    // 7. DOHODAK
    $kn7 = $kn5 - $kn6_1 - $kn6_2;
    $kn7x = $kn5x - $kn6_1x - $kn6_2x;
    $data['7.kn'] = number_format($kn7, 2, ',', '.') . ($razlika ? ' (' . number_format($kn7x, 2, ',', '.') . ')' : '');

    // 8. OSOBNI ODBITAK 1.00 / 4000.00
    $kn8 = $kn7 * 100 > $odbitak ? $odbitak / 100 : $kn7;
    $data['8.kn'] = number_format($kn8, 2, ',', '.');

    // 9. POREZNA OSNOVICA
    $kn9 = $kn7 - $kn8;
    $kn9x = $kn7x - $kn8;
    $data['9.kn'] = number_format($kn9, 2, ',', '.') . ($razlika ? ' (' . number_format($kn9x, 2, ',', '.') . ')' : '');

    // 10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK
    $kn10_20 = round($kn9 * 0.2, 2);
    $kn10_20x = round($kn9x * 0.2, 2);
    $kn10_prirez = round($kn10_20 * $prirez / 10000, 2);
    $kn10_prirezx = round($kn10_20x * $prirez / 10000, 2);
    $kn10 = $kn10_20 + $kn10_prirez;
    $kn10x = $kn10_20x + $kn10_prirezx;
    $data['10.kn'] = number_format($kn10, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10x, 2, ',', '.') . ')' : '');
    // 20.00%
    $data['10.20.kn'] = number_format($kn10_20, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_20x, 2, ',', '.') . ')' : '');
    // Prirez
    $data['10.prirez.kn'] = number_format($kn10_prirez, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_prirezx, 2, ',', '.') . ')' : '');

    // 11. NETO PLAĆA
    $kn11 = $kn7 - $kn10;
    $kn11x = $kn7x - $kn10x;
    $data['11.kn'] = number_format($kn11, 2, ',', '.') . ($razlika ? ' (' . number_format($kn11x, 2, ',', '.') . ')' : '');

    // 12. NAKNADE UKUPNO
    $kn12 = $kn3 + $kn1_7p;
    $data['12.kn'] = number_format($kn12, 2, ',', '.');

    // 13. NETO + NAKNADE
    $kn13 = $kn11 + $kn12;
    $kn13x = $kn11x + $kn12;
    $data['13.kn'] = number_format($kn13, 2, ',', '.') . ($razlika ? ' (' . number_format($kn13x, 2, ',', '.') . ')' : '');

    // 14. OBUSTAVE UKUPNO
    $sindikat = $month->sindikat ? round($kn5 * 0.01, 2) : 0;
    $sindikatx = $month->sindikat ? round($kn5x * 0.01, 2) : 0;
    $kredit = $month->kredit / 100 ?? 0;
    $kn14 = $sindikat + $kredit;
    $kn14x = $sindikatx + $kredit;
    $data['14.kn'] = number_format($kn14, 2, ',', '.') . ($razlika ? ' (' . number_format($kn14x, 2, ',', '.') . ')' : '');

    // 15. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA REDOVAN RAČUN
    $kn15 = $kn13 - $sindikat - $kredit;
    $kn15x = $kn13x - $sindikatx - $kredit;
    $data['15.kn'] = number_format($kn15, 2, ',', '.') . ($razlika ? ' (' . number_format($kn15x, 2, ',', '.') . ')' : '');

    // 17.5. vrsta i iznos obustave
    $data['17_5a.kn'] = number_format($sindikat, 2, ',', '.') . ($razlika ? ' (' . number_format($sindikatx, 2, ',', '.') . ')' : '');
    $data['17_5b.kn'] = number_format($kredit, 2, ',', '.');

    return $data;
  }

  public function lista_data1(Month $month)
  {
    $data['III.godina'] = explode(".", $month->slug())[1];
    $data['III.mjesec'] = explode(".", $month->slug())[0];

    $from = $month->from();
    $to = $month->to();
    $data['III.od'] = $from->format('d');
    $data['III.do'] = $to->format('d');

    $hoursNorm = $month->hoursNorm();
    $bruto = $month->bruto ?? $month->last('bruto');
    $month->bruto = $bruto;
    $data['bruto'] = $bruto;
    $perHour = round(($bruto / 100 / $hoursNorm->All), 2);
    $data['perHour'] = $perHour;
    $hoursWorkNorm = $hoursNorm->Work;
    $prijevoz = $month->prijevoz ?? $month->last('prijevoz');
    $month->prijevoz = $prijevoz;
    $data['prijevoz'] = $prijevoz;
    $odbitak = $month->odbitak ?? $month->last('odbitak');
    $month->odbitak = $odbitak;
    $data['odbitak'] = $odbitak;
    $prirez = $month->prirez ?? $month->last('prirez');
    $month->prirez = $prirez;
    $data['prirez'] = $prirez;
    //dd($hoursNorm, $bruto, $perHour);

    // 1.1. Za redoviti rad
    $h1_1 = $hoursNorm->min / 60 > $hoursWorkNorm ? $hoursWorkNorm : $hoursNorm->min / 60;
    $data['1.1.h'] = number_format($h1_1, 2, ',', '.');
    $kn1_1 = round($h1_1 * $perHour, 2);
    $data['1.1.kn'] = number_format($kn1_1, 2, ',', '.');

    // 1.4 Za prekovremeni rad
    $h1_4 = $month->prekovremeni;
    $data['prekovremeni'] = $month->prekovremeni;
    $overWork = $hoursNorm->min / 60 - $hoursWorkNorm;

    $data['1.4.h'] = number_format($h1_4, 2, ',', '.') . ' (' . number_format($overWork, 2, ',', '.') . ')';
    $kn1_4 = round($h1_4 * $perHour * 1.5, 2);
    $kn1_4x = $overWork > 0 ? round($overWork * $perHour * 1.5, 2) : 0;
    $data['1.4.kn'] = number_format($kn1_4, 2, ',', '.') . ($kn1_4x ? ' (' . number_format($kn1_4x, 2, ',', '.') . ')' : '');

    // 1.7a Praznici. Blagdani, izbori
    $data['1.7a.h'] = number_format($hoursNorm->Holiday, 2, ',', '.');
    $kn1_7a = round($hoursNorm->Holiday * $perHour, 2);
    $data['1.7a.kn'] = number_format($kn1_7a, 2, ',', '.');

    // 1.7b Godišnji odmor
    $data['1.7b.h'] = number_format($hoursNorm->GO, 2, ',', '.');
    $kn1_7b = round($hoursNorm->GO * $perHour, 2);
    $data['1.7b.kn'] = number_format($kn1_7b, 2, ',', '.');

    // 1.7c Plaćeni dopust
    $data['1.7c.h'] = number_format($hoursNorm->Dopust, 2, ',', '.');
    $kn1_7c = round($hoursNorm->Dopust * $perHour, 2);
    $data['1.7c.kn'] = number_format($kn1_7c, 2, ',', '.');

    // 1.7d Bolovanje do 42 dana
    $data['1.7d.h'] = number_format($hoursNorm->Sick, 2, ',', '.');
    //$kn1_7d = round($hoursNorm->Sick * $perHour * 0.7588, 2);
    if ($hoursNorm->Sick && !$month->bolovanje) {
      $ms = Month::where('user_id', '=', Auth::user()->id)->where('month', '>=', $month->month - 6)->where('month', '<', $month->month)->get();
      //dd($ms);
      if (count($ms)) {
        $mjeseci = [];
        foreach ($ms as $key => $value) {
          $mHoursNorm = $value->hoursNorm();
          $mBruto = $value->bruto ?? $value->last('bruto');
          $mPerHour = round(($mBruto / 100 / $mHoursNorm->All), 2);
          $mjeseci[$key] = $mPerHour;
        }
        //dd(array_sum($mjeseci) / count($mjeseci) * 0.7);
        $kn1_7d = round(array_sum($mjeseci) / count($mjeseci), 2) * 0.7 * $hoursNorm->Sick;
        $text17 = '(' . number_format($kn1_7d / $hoursNorm->Sick / $perHour * 100, 2, ',', '.') . '%)*';
        $data['1.7d.t'] = 'izračunato na osnovu prosjeka zadnjih 6 mjeseci';
        $data['1.7d.kn'] = number_format($kn1_7d, 2, ',', '.') . $text17;
      } else {
        $kn1_7d = round($perHour * 0.7, 2) * $hoursNorm->Sick;
        $data['1.7d.kn'] = number_format($kn1_7d, 2, ',', '.') . '(70%)**';
        $data['1.7d.t'] = 'izračunato na osnovu prosjeka trenutnog mjeseca';
      }
    } else {
      $kn1_7d = $month->bolovanje / 100;
      $text17 = $hoursNorm->Sick ? '(' . number_format($kn1_7d / $hoursNorm->Sick / $perHour * 100, 2, ',', '.') . '%)' : '';
      $data['1.7d.t'] = '';
      $data['1.7d.kn'] = number_format($kn1_7d, 2, ',', '.') . $text17;
    }
    // 1.7e Dodatak za rad nedjeljom
    $data['1.7e.h'] = number_format($hoursNorm->minSunday / 60, 2, ',', '.');
    $kn1_7e = round($hoursNorm->minSunday / 60 * $perHour * 0.35, 2);
    $data['1.7e.kn'] = number_format($kn1_7e, 2, ',', '.');

    // 1.7f Dodatak za rad na praznik
    $data['1.7f.h'] = number_format($hoursNorm->minHoliday / 60, 2, ',', '.');
    $kn1_7f = round($hoursNorm->minHoliday / 60 * $perHour * 0.5, 2);
    $data['1.7f.kn'] = number_format($kn1_7f, 2, ',', '.');

    // 1.7g Dodatak za noćni rad
    $h1_7g = $month->nocni / 10;
    $nightWork = $hoursNorm->minNight;

    $data['1.7g.h'] = number_format($h1_7g, 2, ',', '.') . ' (' . number_format($nightWork, 0, ',', '.') . 'min)';
    $kn1_7g = round($h1_7g * $perHour * 0.3, 2);
    $data['1.7g.kn'] = number_format($kn1_7g, 2, ',', '.');

    // 1.7p Nagrada za radne rezultate
    $kn1_7p = round($month->nagrada / 100, 2);
    $data['1.7p.kn'] = number_format($kn1_7p, 2, ',', '.');

    $kn1 = $kn1_1 + $kn1_4 + $kn1_7a + $kn1_7b + $kn1_7c + $kn1_7d + $kn1_7e + $kn1_7f + $kn1_7g + $kn1_7p;
    $kn1x = $kn1_1 + $kn1_4x + $kn1_7a + $kn1_7b + $kn1_7c + $kn1_7d + $kn1_7e + $kn1_7f + $kn1_7g + $kn1_7p;
    $razlika = $kn1_4x;

    // 2. OSTALI OBLICI
    $kn2 = round($month->stimulacija / 100, 2);
    $data['2.kn'] = number_format($kn2, 2, ',', '.');
    // 2.8. Stimulacija bruto
    $data['2.8.kn'] = number_format($month->stimulacija / 100, 2, ',', '.');
    $extra_prekovremeni = $month->stimulacija / 100 / $perHour / 1.5;
    $data['2.8.kn'] = number_format($month->stimulacija / 100, 2, ',', '.');
    $data['extra'] = floor($extra_prekovremeni) . 'h ' . (floor($extra_prekovremeni * 60) % 60) . 'm ' . floor($extra_prekovremeni * 3600) % 60 . 's';

    // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
    $prijevoz = $month->prijevoz / 100 ?? 360;
    $prijevoz = ($hoursNorm->GO + $hoursNorm->Sick) > 14 ? $prijevoz * ($hoursNorm->All - $hoursNorm->GO - $hoursNorm->Sick) / $hoursNorm->All : $prijevoz;
    //dd($hoursNorm);
    $prijevoz = $hoursNorm->firstAll > $hoursNorm->All ? $prijevoz : $prijevoz * $hoursNorm->firstAll / $hoursNorm->All;
    $regres = $month->regres / 100 ?? 0;
    $bozicnica = $month->bozicnica / 100 ?? 0;
    $prehrana = $month->prehrana / 100 ?? 0;
    $kn3 = round($prijevoz + $regres + $bozicnica + $prehrana, 2);
    $data['3.kn'] = number_format($kn3, 2, ',', '.');
    // 3.1. Prijevoz
    $data['3.1.kn'] = number_format($prijevoz, 2, ',', '.');
    // 3.2. Bozicnica
    $data['3.2.kn'] = number_format($bozicnica, 2, ',', '.');
    // 3.7. Regres za godišnji odmor
    $data['3.7.kn'] = number_format($regres, 2, ',', '.');
    // 3.10. Topli obrok
    $data['3.10.kn'] = number_format($prehrana, 2, ',', '.');

    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn4 = $kn1 + $kn2 + $kn3;
    $kn4x = $kn1x + $kn3;
    $data['4.kn'] = number_format($kn4, 2, ',', '.') . ($razlika ? ' (' . number_format($kn4x, 2, ',', '.') . ')' : '');

    // 5. OSNOVICA ZA OBRAČUN DOPRINOSA
    $kn5 = $kn1 - $kn1_7p + $kn2;
    $kn5x = $kn1x - $kn1_7p;
    $data['5.kn'] = number_format($kn5, 2, ',', '.') . ($razlika ? ' (' . number_format($kn5x, 2, ',', '.') . ')' : '');

    // 6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)
    $kn6_1 = round($kn5 * 0.15, 2);
    $kn6_1x = round($kn5x * 0.15, 2);
    $data['6.1.kn'] = number_format($kn6_1, 2, ',', '.') . ($razlika ? ' (' . number_format($kn6_1x, 2, ',', '.') . ')' : '');
    // 6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)
    $kn6_2 = round($kn5 * 0.05, 2);
    $kn6_2x = round($kn5x * 0.05, 2);
    $data['6.2.kn'] = number_format($kn6_2, 2, ',', '.') . ($razlika ? ' (' . number_format($kn6_2x, 2, ',', '.') . ')' : '');

    // 7. DOHODAK
    $kn7 = $kn5 - $kn6_1 - $kn6_2;
    $kn7x = $kn5x - $kn6_1x - $kn6_2x;
    $data['7.kn'] = number_format($kn7, 2, ',', '.') . ($razlika ? ' (' . number_format($kn7x, 2, ',', '.') . ')' : '');

    // 8. OSOBNI ODBITAK 1.00 / 4000.00
    $kn8 = $kn7 * 100 > $odbitak ? $odbitak / 100 : $kn7;
    $kn8x = $kn7x * 100 > $odbitak ? $odbitak / 100 : $kn7x;
    $data['8.kn'] = number_format($kn8, 2, ',', '.') . ($razlika ? ' (' . number_format($kn8x, 2, ',', '.') . ')' : '');

    // 9. POREZNA OSNOVICA
    $kn9 = $kn7 - $kn8;
    $kn9x = $kn7x - $kn8x;
    $data['9.kn'] = number_format($kn9, 2, ',', '.') . ($razlika ? ' (' . number_format($kn9x, 2, ',', '.') . ')' : '');

    // 10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK
    $kn10_20 = round($kn9 * 0.2, 2);
    $kn10_20x = round($kn9x * 0.2, 2);
    $kn10_prirez = round($kn10_20 * $prirez / 10000, 2);
    $kn10_prirezx = round($kn10_20x * $prirez / 10000, 2);
    $kn10 = $kn10_20 + $kn10_prirez;
    $kn10x = $kn10_20x + $kn10_prirezx;
    $data['10.kn'] = number_format($kn10, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10x, 2, ',', '.') . ')' : '');
    // 20.00%
    $data['10.20.kn'] = number_format($kn10_20, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_20x, 2, ',', '.') . ')' : '');
    // Prirez
    $data['10.prirez.kn'] = number_format($kn10_prirez, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_prirezx, 2, ',', '.') . ')' : '');

    // 10.1. KOREKCIJA POREZA I PRIREZA
    $kn10_1_20 = $kn9 * 0.2 - $kn10_20;
    $kn10_1_20x = $kn9x * 0.2 - $kn10_20x;
    $kn10_1_prirez = $kn10_20 * $prirez / 10000 - $kn10_prirez;
    $kn10_1_prirezx = $kn10_20x * $prirez / 10000 - $kn10_prirezx;
    $kn10_1 = ($kn10_1_20 > 0) ? round($kn10_1_20 + $kn10_1_prirez, 2) : 0;
    $kn10_1x = ($kn10_1_20x > 0) ? round($kn10_1_20x + $kn10_1_prirezx, 2) : 0;
    $data['10.1.kn'] = number_format($kn10_1, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_1x, 2, ',', '.') . ')' : '');
    // 20.00%
    $data['10.1.20.kn'] = number_format($kn10_1 - round($kn10_1_prirez, 2), 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_1_20x, 2, ',', '.') . ')' : '');
    // Prirez
    $data['10.1.prirez.kn'] = number_format($kn10_1_prirez, 2, ',', '.') . ($razlika ? ' (' . number_format($kn10_1_prirezx, 2, ',', '.') . ')' : '');

    // 11. NETO PLAĆA
    $kn11 = $kn7 - $kn10 - $kn10_1;
    $kn11x = $kn7x - $kn10x;
    $data['11.kn'] = number_format($kn11, 2, ',', '.') . ($razlika ? ' (' . number_format($kn11x, 2, ',', '.') . ')' : '');

    // 12. NAKNADE UKUPNO
    $kn12 = $kn3 + $kn1_7p;
    $data['12.kn'] = number_format($kn12, 2, ',', '.');

    // 13. NETO + NAKNADE
    $kn13 = $kn11 + $kn12;
    $kn13x = $kn11x + $kn12;
    $data['13.kn'] = number_format($kn13, 2, ',', '.') . ($razlika ? ' (' . number_format($kn13x, 2, ',', '.') . ')' : '');

    // 14. OBUSTAVE UKUPNO
    $sindikat = $month->sindikat ? round($kn5 * 0.01, 2) : 0;
    $sindikatx = $month->sindikat ? round($kn5x * 0.01, 2) : 0;
    $kredit = $month->kredit / 100 ?? 0;
    $kn14 = $sindikat + $kredit;
    $kn14x = $sindikatx + $kredit;
    $data['14.kn'] = number_format($kn14, 2, ',', '.') . ($razlika ? ' (' . number_format($kn14x, 2, ',', '.') . ')' : '');

    // 15. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA REDOVAN RAČUN
    $kn15 = $kn13 - $sindikat - $kredit;
    $kn15x = $kn13x - $sindikatx - $kredit;
    $data['15.kn'] = number_format($kn15, 2, ',', '.') . ($razlika ? ' (' . number_format($kn15x, 2, ',', '.') . ')' : '');

    // 17.5. vrsta i iznos obustave
    $data['17_5a.kn'] = number_format($sindikat, 2, ',', '.') . ($razlika ? ' (' . number_format($sindikatx, 2, ',', '.') . ')' : '');
    $data['17_5b.kn'] = number_format($kredit, 2, ',', '.');

    return $data;
  }

  public function lista_data2(Month $month)
  {
    //dd($month->hoursNorm());
    $data['III.godina'] = explode(".", $month->slug())[1];
    $data['III.mjesec'] = explode(".", $month->slug())[0];

    $from = $month->from();
    $to = $month->to();
    $data['III.od'] = $from->format('d');
    $data['III.do'] = $to->format('d');

    $hoursNorm = $month->hoursNorm();
    $bruto = $month->bruto ?? $month->last('bruto');
    $month->bruto = $bruto;
    $data['bruto'] = $bruto;
    $perHour = $bruto / 100 / $hoursNorm->All;
    $data['perHour'] = $perHour;
    $hoursWorkNorm = $hoursNorm->All;
    $prijevoz = $month->prijevoz ?? $month->last('prijevoz');
    $month->prijevoz = $prijevoz;
    $data['prijevoz'] = $prijevoz;
    $prehrana = $month->prehrana ?? $month->last('prehrana');
    $month->prehrana = $prehrana;
    $data['prehrana'] = $prehrana;
    $nagrada = $month->nagrada ?? $month->last('nagrada');
    $month->nagrada = $nagrada;
    $data['nagrada'] = $nagrada;
    $prigodna = $month->prigodna ?? 0;
    $month->prigodna = $prigodna;
    $data['prigodna'] = $prigodna;
    $odbitak = $month->odbitak ?? $month->last('odbitak');
    $month->odbitak = $odbitak;
    $data['odbitak'] = $odbitak;
    $prirez = $month->prirez ?? $month->last('prirez');
    $month->prirez = $prirez;
    $data['prirez'] = $prirez;
    $minuli = $month->minuli ?? $month->last('minuli');
    $month->minuli = $minuli;
    $data['minuli'] = $minuli;
    //dd($hoursNorm, $bruto, $perHour);

    // 1.3 sati redovitog rada u dane državnog praznika/ blagdana
    $h1_3 = round(($hoursNorm->minHoliday - $hoursNorm->minHolidayNight) / 60, 2);
    $data['1.3.h'] = number_format($h1_3, 1, ',', '.');
    $kn1_3 = round($h1_3 * $perHour * 1.5, 2);
    $data['1.3.kn'] = number_format($kn1_3, 2, ',', '.');

    // 1.7 sati redovnog rada nedeljom
    $h1_7 = ($hoursNorm->minSunday - $hoursNorm->minSundayNight) / 60;
    $data['1.7.h'] = number_format($h1_7, 1, ',', '.');
    $kn1_7 = round($h1_7 * $perHour * 1.3, 2);
    $data['1.7.kn'] = number_format($kn1_7, 2, ',', '.');

    // 1.8 sati redovnog rada nedeljom + noć
    $h1_8 = $hoursNorm->minSundayNight / 60;
    $data['1.8.h'] = number_format($h1_8, 1, ',', '.');
    $kn1_8 = round($h1_8 * $perHour * 1.65, 2);
    $data['1.8.kn'] = number_format($kn1_8, 2, ',', '.');

    // 1.2. sati redovnog rada noću
    $h1_2 = ($hoursNorm->minNight / 60) - $h1_8;
    $data['1.2.h'] = number_format($h1_2, 1, ',', '.');
    $kn1_2 = round($h1_2 * $perHour * 1.35, 2);
    $data['1.2.kn'] = number_format($kn1_2, 2, ',', '.');

    // 1.1. sati redovnog rada
    $h1_1 = $hoursNorm->min / 60 > $hoursWorkNorm ? $hoursWorkNorm - $h1_2 - $h1_3 - $h1_7 - $h1_8 : ($hoursNorm->min - $hoursNorm->minNight - $hoursNorm->minHoliday - $hoursNorm->minSunday - $hoursNorm->minSundayNight) / 60;
    $data['1.1.h'] = number_format($h1_1, 1, ',', '.');
    $kn1_1 = round($h1_1 * $perHour, 2);
    $data['1.1.kn'] = number_format($kn1_1, 2, ',', '.');

    $h1 = $h1_1 + $h1_2 + $h1_3 + $h1_7 + $h1_8;
    $kn1 = $kn1_1 + $kn1_2 + $kn1_3 + $kn1_7 + $kn1_8;

    $overWork = $hoursNorm->min / 60 - $hoursWorkNorm;
    $data['1.4.h'] = number_format($overWork, 2, ',', '.');

    // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
    $kn3 = round($kn1 * $minuli / 1000, 2);
    $data['3.h'] = number_format($minuli / 10, 1, ',', '.') . '%';
    $data['3.kn'] = number_format($kn3, 2, ',', '.');

    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn4 = $kn1 + $kn3;
    $data['4.h'] = number_format($h1, 1, ',', '.');;
    $data['4.kn'] = number_format($kn4, 2, ',', '.');;

    // 5. OSNOVICA ZA OBRAČUN DOPRINOSA
    $kn5 = $kn4;
    $data['5.kn'] = number_format($kn5, 2, ',', '.');;

    // 6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)
    $data['6.1.h'] = '15%';
    $kn6_1 = round($kn5 * 0.15, 2);
    $data['6.1.kn'] = number_format($kn6_1, 2, ',', '.');
    // 6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)
    $data['6.2.h'] = '5%';
    $kn6_2 = round($kn5 * 0.05, 2);
    $data['6.2.kn'] = number_format($kn6_2, 2, ',', '.');

    $data['6.h'] = '20%';
    $kn6 = $kn6_1 + $kn6_2;
    $data['6.kn'] = number_format($kn6, 2, ',', '.');

    // 7. DOHODAK
    $kn7 = $kn5 - $kn6;
    $data['7.kn'] = number_format($kn7, 2, ',', '.');

    // 8. OSOBNI ODBITAK 1.00 / 4000.00
    $kn8 = $kn7 * 100 > $odbitak ? $odbitak / 100 : $kn7;
    $data['8.kn'] = number_format($kn8, 2, ',', '.');

    // 9. POREZNA OSNOVICA
    $kn9 = $kn7 - $kn8;
    $data['9.kn'] = number_format($kn9, 2, ',', '.');

    // 10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK
    $kn10_20 = round($kn9 * 0.2, 2);
    $kn10_prirez = round($kn10_20 * $prirez / 10000, 2);
    $kn10 = $kn10_20 + $kn10_prirez;
    $data['10.kn'] = number_format($kn10, 2, ',', '.');
    // 20.00%
    $data['10.20.kn'] = number_format($kn10_20, 2, ',', '.');
    // Prirez
    $data['10.prirez.kn'] = number_format($kn10_prirez, 2, ',', '.');

    // 11. NETO PLAĆA
    $kn11 = $kn7 - $kn10;
    $data['11.kn'] = number_format($kn11, 2, ',', '.');

    // 12. NAKNADE UKUPNO
    $kn12 = ($prijevoz + $nagrada + $prehrana + $prigodna) / 100;
    $data['12.a.kn'] = number_format($prijevoz / 100, 2, ',', '.');
    $data['12.b.kn'] = number_format($nagrada / 100, 2, ',', '.');
    $data['12.c.kn'] = number_format($prehrana / 100, 2, ',', '.');
    $data['12.d.kn'] = number_format($prigodna / 100, 2, ',', '.');

    // 15. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA REDOVAN RAČUN
    $data['15.kn'] = number_format($kn11 + $kn12, 2, ',', '.');

    //dd($data);
    return $data;
  }

  public function print($month = null)
  {
    //dd($month);
    if ($month == null) {
      $_month = Carbon::now();
    } else {
      $_month = Carbon::parse('01.' . $month);
    }
    //dd($_month);
    $unslug = $_month->format('Y') * 12 + $_month->format('m') - 1;
    $month = Month::where('user_id', '=', Auth::user()->id)->where('month', '=', $unslug)->first();
    if (!$month) {
      $month = new Month;
      $month->month = $unslug;
      $month->user_id = Auth::user()->id;
    }
    $days = $month->days();
    //dd($month);
    return view('months.print')->with(compact('month', 'days'));
  }
}
