<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class PlatnaLista extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Store user resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function data(Request $request)
  {
    //dd($request);
    $this->validate($request, [
      'bruto' => 'required',
      'prijevoz' => 'required',
      'odbitak' => 'required',
      'prirez' => 'required'
    ]);
    $bruto = $request->input('bruto');
    $prijevoz = $request->input('prijevoz');
    $odbitak = $request->input('odbitak');
    $prirez = $request->input('prirez') * 10;
    $user = User::find(Auth::id());
    //$user = Auth::user();
    //dd($user);
    if (null != $request->input('zaposlen')) $user->zaposlen = $request->input('zaposlen');
    $user->bruto = $bruto;
    $user->prijevoz = $prijevoz;
    $user->odbitak = $odbitak;
    $user->prirez = $prirez;
    $user->save();
    return redirect(route('dashboard'))->with('success', 'User Updated');
  }

  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function __invoke(Request $request)
  {
    $bruto = Auth::user()->bruto ?? 5300;
    $data['bruto'] = $bruto;
    $prijevoz = Auth::user()->prijevoz ?? 360;
    $data['prijevoz'] = $prijevoz;
    $odbitak = Auth::user()->odbitak ?? 4000;
    $data['odbitak'] = $odbitak;
    $prirez = Auth::user()->prirez ?? 180;
    $data['prirez'] = $prirez / 10;
    $prekovremeni = $request->input('prekovremeni') != null ? $request->input('prekovremeni') : 0;
    $data['prekovremeni'] = $prekovremeni;
    $data['prekovremeniOptions'] = [0, 8, 16, 24, 32];

    if ($request->input('month') == null) {
      $month['x'] = Carbon::now();
    } else {
      $month['x'] = Carbon::parse('01.' . $request->input('month'));
      //dd($month);
    }
    $month['-'] = Carbon::parse($month['x'])->subMonthsNoOverflow();
    $month['+'] = Carbon::parse($month['x'])->addMonthsNoOverflow();
    $from = CarbonImmutable::parse($month['x'])->firstOfMonth();
    $firstFrom = Auth::user()->zaposlen > $from ? Carbon::parse(Auth::user()->zaposlen) : $from;
    //dd($firstFrom);
    $to = Carbon::parse($month['x'])->endOfMonth();

    $daysColection = Day::whereBetween('date', [$from, $to])->where('user_id', '=', Auth::user()->id)->get();
    $holidaysColection = Holiday::whereBetween('date', [$from, $to])->get();

    $hoursNorm = 0;
    $firstHoursNorm = 0;
    $hoursNormHoli = 0;
    $firstHoursNormHoli = 0;

    $hoursNormSick = 0;
    $hoursNormGO = 0;
    $daysGO = 0;
    $hoursNormDopust = 0;
    $daysDopust = 0;
    $minWork = 0;
    $minWorkHoli = 0;
    $minWorkSunday = 0;
    for ($i = 0; $i < $from->daysInMonth; $i++) {
      $temp_date = $from->addDays($i)->format('d.m.Y');
      $dayOfWeek = $from->addDays($i)->dayOfWeek;
      switch ($dayOfWeek) {
        case 0:
          $def_h = 0;
          break;
        case 6:
          $def_h = Auth::id() == 2 ? 0 : 5;
          break;
        default:
          $def_h = Auth::id() == 2 ? 8 : 7;
          break;
      }
      //dd($hoursWork);
      $hoursNorm += $def_h;
      $firstHoursNorm += $firstFrom > $from->addDays($i) ? 0 : $def_h;
      if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $hoursNormHoli += $def_h;
        $firstHoursNormHoli += $firstFrom > $from->addDays($i) ? 0 : $def_h;
      }
      //if ($firstHoursNorm > 0) dd($hoursNorm,$hoursNormHoli,$firstHoursNorm,$firstHoursNormHoli);
      if ($daysColection->where('date', '=', $from->addDays($i))->where('sick', '=', true)->first() != null) {
        $hoursNormSick += $def_h;
      }
      if ($daysColection->where('date', '=', $from->addDays($i))->where('go', '=', true)->first() != null) {
        $hoursNormGO += $def_h;
        if ($def_h > 0) $daysGO++;
      }
      if ($daysColection->where('date', '=', $from->addDays($i))->where('dopust', '=', true)->first() != null) {
        $hoursNormDopust += $def_h;
        if ($def_h > 0) $daysDopust++;
      }
      if ($daysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_day = $daysColection->where('date', '=', $from->addDays($i))->first();
        $temp_minWork = $temp_day->duration->diffInMinutes($temp_day->start) + $temp_day->night_duration->format('H') * 60 + $temp_day->night_duration->format('i');
        //dd($temp_day, $temp_day->duration->diffInMinutes($temp_day->start), $temp_minWork);
        $minWork += $temp_minWork;
        if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
          $minWorkHoli += $temp_minWork;
        }
        if ($def_h == 0) {
          $minWorkSunday += $temp_minWork;
        }
      }
    }

    $perHour = round(($bruto / $hoursNorm), 2);
    //dd($minWork/60, $minWorkHoli/60, $hoursNorm, $hoursNormHoli);
    //dd($perHour);

    $data['III.godina'] = $month['x']->format('Y');
    $data['III.mjesec'] = $month['x']->format('m');
    $data['III.od'] = $from > $firstFrom ? $from->format('d') : $firstFrom->format('d');
    // treba dodat provjeru da li zaposlen do kraja mjeseca
    $data['III.do'] = $to->format('d');
    // 1.1. Za redoviti rad
    //dd($from, $firstFrom, $hoursNorm, $hoursNormHoli, $firstHoursNorm, $firstHoursNormHoli);
    $hoursWorkNorm = ($from > $firstFrom ? $hoursNorm - $hoursNormHoli : $firstHoursNorm - $firstHoursNormHoli) - $hoursNormSick - $hoursNormGO - $hoursNormDopust;
    $h1_1 = $minWork / 60 > $hoursWorkNorm ? $hoursWorkNorm : $minWork / 60;
    $data['1.1.h'] = number_format($h1_1, 2, ',', '.'); //'158,00';
    $data['1.1.kn'] = number_format($h1_1 * $perHour, 2, ',', '.'); //'4.867,98';
    // 1.4 Za prekovremeni rad
    $h1_4 = $prekovremeni;
    $overWork = $minWork / 60 - $hoursWorkNorm;

    $data['1.4.h'] = number_format($h1_4, 2, ',', '.') . ' (' . $overWork . ')'; //'24,00';
    $data['1.4.kn'] = number_format($h1_4 * $perHour * 1.5, 2, ',', '.'); //'1.109,16';
    // 1.x Za godišnji
    $h1_go = $hoursNormGO;

    $data['1.go.h'] = number_format($h1_go, 2, ',', '.') . ' (' . $daysGO . ')';
    $data['1.go.kn'] = number_format($h1_go * $perHour, 2, ',', '.');
    // 1.7a Praznici. Blagdani, izbori
    $data['1.7a.h'] = number_format($hoursNormHoli, 2, ',', '.'); //'14,00';
    $data['1.7a.kn'] = number_format($hoursNormHoli * $perHour, 2, ',', '.'); //'431,34';
    // 1.7c Plaćeni dopust
    $data['1.7c.h'] = number_format($hoursNormDopust, 2, ',', '.'); //'14,00';
    $data['1.7c.kn'] = number_format($hoursNormDopust * $perHour, 2, ',', '.'); //'431,34';
    // 1.7d Bolovanje do 42 dana
    $data['1.7d.h'] = number_format($hoursNormSick, 2, ',', '.'); //'0,00';
    $data['1.7d.kn'] = number_format($hoursNormSick * $perHour * 0.7588, 2, ',', '.'); //'0,00';
    // 1.7e Dodatak za rad nedjeljom
    $data['1.7e.h'] = number_format($minWorkSunday / 60, 2, ',', '.'); //'16,00';
    $data['1.7e.kn'] = number_format($minWorkSunday / 60 * $perHour * 0.35, 2, ',', '.'); //'172,54';
    // 1.7f Dodatak za rad na praznik
    $data['1.7f.h'] = number_format($minWorkHoli / 60, 2, ',', '.'); //'8,00';
    $data['1.7f.kn'] = number_format($minWorkHoli / 60 * $perHour * 0.5, 2, ',', '.'); //'123,24';
    // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
    $prijevoz = $from > $firstFrom ? $prijevoz : $prijevoz * $firstHoursNorm / $hoursNorm;
    $kn3 = $prijevoz;
    $data['3.kn'] = number_format($kn3, 2, ',', '.'); //'400,00';
    // 3.1. Prijevoz
    $data['3.1.kn'] = number_format($prijevoz, 2, ',', '.'); //'400,00';
    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn5 = ($h1_1 + $h1_4 * 1.5 + $hoursNormHoli + $hoursNormSick * 0.7588 + $hoursNormGO + $minWorkSunday / 60 * 0.35 + $minWorkHoli / 60 * 0.5) * $perHour;
    $data['4.kn'] = number_format($kn5 + $kn3, 2, ',', '.'); //'7.104,26';
    // 5. OSNOVICA ZA OBRAČUN DOPRINOSA
    $kn5 = round($kn5, 2);
    $data['5.kn'] = number_format($kn5, 2, ',', '.'); //'6.704,26';
    // 6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)
    $kn6_1 = round($kn5 * 0.15, 2);
    $data['6.1.kn'] = number_format($kn6_1, 2, ',', '.'); //'1.005,64';
    // 6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)
    $kn6_2 = round($kn5 * 0.05, 2);
    $data['6.2.kn'] = number_format($kn6_2, 2, ',', '.'); //'335,21';
    // 7. DOHODAK
    $kn7 = $kn5 - $kn6_1 - $kn6_2;
    $data['7.kn'] = number_format($kn7, 2, ',', '.'); //'5.363,41';
    // 8. OSOBNI ODBITAK 1.00 / 4000.00
    $kn8 = $kn7 > $odbitak ? $odbitak : $kn7;
    $data['8.kn'] = number_format($kn8, 2, ',', '.'); //'4.000,00';
    // 9. POREZNA OSNOVICA
    $kn9 = $kn7 - $kn8;
    $data['9.kn'] = number_format($kn9, 2, ',', '.'); //'1.363,41';
    // 10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK
    $kn10_20 = round($kn9 * 0.2, 2);
    $kn10_prirez = round($kn10_20 * $prirez / 1000, 2);
    $kn10 = $kn10_20 + $kn10_prirez;
    $data['10.kn'] = number_format($kn10, 2, ',', '.'); //'305,40';
    // 20.00% 1363.41
    $data['10.20.kn'] = number_format($kn10_20, 2, ',', '.'); //'272,68';
    // Prirez 12.00 %
    $data['10.prirez.kn'] = number_format($kn10_prirez, 2, ',', '.'); //'32,72';
    // 11. NETO PLAĆA
    $data['11.kn'] = number_format($kn7 - $kn10, 2, ',', '.'); //'5.058,01';
    // 12. NAKNADE UKUPNO
    $data['12.kn'] = number_format($kn3, 2, ',', '.'); //'400,00';
    // 13. NETO + NAKNADE
    $data['13.kn'] = number_format($kn7 - $kn10 + $kn3, 2, ',', '.'); //'5.458,01';

    return view('platna-lista')->with(compact('month', 'data'));
  }
}
