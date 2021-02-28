## Platna lista

da bi izracunao placu, osim sata rada, trebam definirati:
[] koliki je bruto
```
php artisan make:migration add_bruto_to_users_table --table=users
php artisan migrate
```
### database\migrations\2021_02_28_091056_add_bruto_to_users_table.php
```
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->mediumInteger('bruto')
        ->after('password')
        ->nullable(5300);
    });
  }
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('bruto');
    });
  }
```
```
git add .
git commit -am "add bruto [laravel]"
```
[x] koliki je prijevoz
[x] koliki je prirez
[x] koliki je osnovni odbitak

### resources\views\dashboard.blade.php
```
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('bruto') }}">
            @csrf
            @method('PUT')
            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <input id="bruto" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="bruto" value="{{Auth::user()->bruto ? Auth::user()->bruto : old('bruto')?? 5300}}" min="4250" step="50" />
            </div>
            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Spremi') }}
              </x-button>
            </div>
          </form>
```
```
php artisan make:controller PlatnaLista --invokable
```
### routes\web.php
```
use App\Http\Controllers\PlatnaLista;
Route::get('/lista', PlatnaLista::class)->name('lista');
Route::put('/lista', [PlatnaLista::class, 'bruto'])->name('bruto');
```
### resources\views\layouts\navigation.blade.php
```
        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('lista')" :active="request()->routeIs('lista')">
            {{ __('Platna lista') }}
          </x-nav-link>
        </div>
      </div>
  <!-- Responsive Navigation Menu -->
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('lista')" :active="request()->routeIs('lista')">
        {{ __('Platna lista') }}
      </x-responsive-nav-link>
    </div>
```
### app\Http\Controllers\PlatnaLista.php
```
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
   * Store bruto resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bruto(Request $request)
  {
    $this->validate($request, [
      'bruto' => 'required'
    ]);
    $bruto = $request->input('bruto');
    $user = User::find(Auth::id());
    //$user = Auth::user();
    //dd($user);
    $user->bruto = $bruto;
    $user->save();
    //dd($request);
    return redirect(route('dashboard'))->with('success', 'Bruto Updated');
  }

  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function __invoke(Request $request)
  {
    $bruto = $request->input('bruto') != null ? $request->input('bruto') : Auth::user()->bruto?? 5300;
    $data['bruto'] = $bruto;
    $prijevoz = $request->input('prijevoz') != null ? $request->input('prijevoz') : 400;
    $data['prijevoz'] = $prijevoz;
    $data['prijevozOptions'] = [360,400,600];
    $odbitak = $request->input('odbitak') != null ? $request->input('odbitak') : 4000;
    $data['odbitak'] = $odbitak;
    $data['odbitakOptions'] = [4000,5750,8250,11750];
    $prirez = $request->input('prirez') != null ? $request->input('prirez') : 12;
    $data['prirez'] = $prirez;
    $data['prirezOptions'] = [0,1,2,3,4,5,6,7,7.5,8,9,10,12,18];
    $prekovremeni = $request->input('prekovremeni') != null ? $request->input('prekovremeni') : 0;
    $data['prekovremeni'] = $prekovremeni;
    $data['prekovremeniOptions'] = [0,8,16,24,32];

    if ($request->input('month') == null) {
      $month['x'] = Carbon::now();
    } else {
      $month['x'] = Carbon::parse('01.' . $request->input('month'));
      //dd($month);
    }
    $month['-'] = Carbon::parse($month['x'])->subMonthsNoOverflow();
    $month['+'] = Carbon::parse($month['x'])->addMonthsNoOverflow();
    $from = CarbonImmutable::parse($month['x'])->firstOfMonth();
    $to = Carbon::parse($month['x'])->endOfMonth();

    $daysColection = Day::whereBetween('date', [$from, $to])->where('user_id', '=', Auth::user()->id)->get();
    $holidaysColection = Holiday::whereBetween('date', [$from, $to])->get();

    $hoursNorm = 0;
    $hoursNormHoli = 0;
    $hoursNormSick = 0;
    $hoursWork = Carbon::create(0);
    $hoursWorkHoli = Carbon::create(0);
    $hoursWorkSunday = Carbon::create(0);
    for ($i = 0; $i < $from->daysInMonth; $i++) {
      $temp_date = $from->addDays($i)->format('d.m.Y');
      $dayOfWeek = $from->addDays($i)->dayOfWeek;
      switch ($dayOfWeek) {
        case 0:
          $def_h = 0;
          break;
        case 6:
          $def_h = 5;
          break;
        default:
          $def_h = 7;
          break;
      }
      //dd($hoursWork);
      $hoursNorm += $def_h;
      if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $hoursNormHoli += $def_h;
      }
      //dd($hoursNorm,$hoursNormHoli);
      if ($daysColection->where('date', '=', $from->addDays($i))->where('sick', '=', true)->first() != null) {
        $hoursNormSick += $def_h;
      }
      if ($daysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_day = $daysColection->where('date', '=', $from->addDays($i))->first();
        //dd($temp_day->duration);
        $hoursWork->addMinutes($temp_day->night_duration->format('i'));
        $hoursWork->addHours($temp_day->night_duration->format('H'));
        $hoursWork->addMinutes($temp_day->duration->format('i'));
        $hoursWork->addHours($temp_day->duration->format('H'));
        if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
          $hoursWorkHoli->addMinutes($temp_day->night_duration->format('i'));
          $hoursWorkHoli->addHours($temp_day->night_duration->format('H'));
          $hoursWorkHoli->addMinutes($temp_day->duration->format('i'));
          $hoursWorkHoli->addHours($temp_day->duration->format('H'));
        }
        if ($def_h == 0) {
          $hoursWorkSunday->addMinutes($temp_day->night_duration->format('i'));
          $hoursWorkSunday->addHours($temp_day->night_duration->format('H'));
          $hoursWorkSunday->addMinutes($temp_day->duration->format('i'));
          $hoursWorkSunday->addHours($temp_day->duration->format('H'));
        }
      }
    }

    $minWork = $hoursWork->format('d') * 1440 - 1440 + $hoursWork->format('H') * 60 + $hoursWork->format('i');
    $minWorkHoli = $hoursWorkHoli->format('d') * 1440 - 1440 + $hoursWorkHoli->format('H') * 60 + $hoursWorkHoli->format('i');
    $minWorkSunday = $hoursWorkSunday->format('d') * 1440 - 1440 + $hoursWorkSunday->format('H') * 60 + $hoursWorkSunday->format('i');
    $perHour = round(($bruto / $hoursNorm), 2);
    //dd($minWork/60, $minWorkHoli/60, $hoursNorm, $hoursNormHoli);
    //dd($perHour);

    $data['III.godina'] = $month['x']->format('Y');
    $data['III.mjesec'] = $month['x']->format('m');
    // treba dodat provjeru da li zaposlen od pocetka mjeseca
    $data['III.od'] = $from->format('d');
    // treba dodat provjeru da li zaposlen do kraja mjeseca
    $data['III.do'] = $to->format('d');
    // 1.1. Za redoviti rad
    $h1_1 = $minWork / 60 > $hoursNorm - $hoursNormHoli - $hoursNormSick ? $hoursNorm - $hoursNormHoli - $hoursNormSick : $minWork / 60;
    $data['1.1.h'] = number_format($h1_1, 2, ',', '.'); //'158,00';
    $data['1.1.kn'] = number_format($h1_1 * $perHour, 2, ',', '.'); //'4.867,98';
    // 1.4 Za prekovremeni rad
    $h1_4 = $prekovremeni;
    $overWork = $minWork/60 - $hoursNorm + $hoursNormHoli;

    $data['1.4.h'] = number_format($h1_4, 2, ',', '.').' ('.$overWork.')'; //'24,00';
    $data['1.4.kn'] = number_format($h1_4 * $perHour * 1.5, 2, ',', '.'); //'1.109,16';
    // 1.7a Praznici. Blagdani, izbori
    $data['1.7a.h'] = number_format($hoursNormHoli, 2, ',', '.'); //'14,00';
    $data['1.7a.kn'] = number_format($hoursNormHoli * $perHour, 2, ',', '.'); //'431,34';
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
    $kn3 = $prijevoz;
    $data['3.kn'] = number_format($kn3, 2, ',', '.'); //'400,00';
    // 3.1. Prijevoz
    $data['3.1.kn'] = number_format($prijevoz, 2, ',', '.'); //'400,00';
    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn5 = ($h1_1 + $h1_4 * 1.5 + $hoursNormHoli + $hoursNormSick * 0.7588 + $minWorkSunday / 60 * 0.35 + $minWorkHoli / 60 * 0.5) * $perHour;
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
    $kn10_prirez = round($kn10_20 * $prirez / 100, 2);
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
```
### resources\views\platna-lista.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Platna lista') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="flex justify-center">
            <label class="block">
              <span class="text-gray-700">Bruto:</span>
              <input type="text" class="form-input py-1 mt-1 block w-full" placeholder="{{$data['bruto']}}"  disabled>
            </label>
            <label class="block">
              <span class="text-gray-700">Prijevoz:</span>
              <select class="form-select py-1 block w-full mt-1" name="myprijevoz" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach ($data['prijevozOptions'] as $key => $value)
                <option value="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prijevoz' => $value, 'odbitak' => $data['odbitak'], 'prirez' => $data['prirez'], 'prekovremeni' => $data['prekovremeni']]) }}" @if ($value==old('myprijevoz', $data['prijevoz'])) selected="selected" @endif>{{ $value }}</option>
                @endforeach
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Odbitak:</span>
              <select class="form-select py-1 block w-full mt-1" name="myodbitak" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach ($data['odbitakOptions'] as $key => $value)
                <option value="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prijevoz' => $data['prijevoz'], 'odbitak' => $value, 'prirez' => $data['prirez'], 'prekovremeni' => $data['prekovremeni']]) }}" @if ($value==old('myodbitak', $data['odbitak'])) selected="selected" @endif>{{ $value }}</option>
                @endforeach
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Prirez:</span>
              <select class="form-select py-1 block w-full mt-1" name="myprirez" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach ($data['prirezOptions'] as $key => $value)
                <option value="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prijevoz' => $data['prijevoz'], 'odbitak' => $data['odbitak'], 'prirez' => $value, 'prekovremeni' => $data['prekovremeni']]) }}" @if ($value==old('myprirez', $data['prirez'])) selected="selected" @endif>{{ $value }}</option>
                @endforeach
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Prekovremeni:</span>
              <select class="form-select py-1 block w-full mt-1" name="myprekovremeni" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach ($data['prekovremeniOptions'] as $key => $value)
                <option value="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prijevoz' => $data['prijevoz'], 'odbitak' => $data['odbitak'], 'prirez' => $data['prirez'], 'prekovremeni' => $value]) }}" @if ($value==old('myprekovremeni', $data['prekovremeni'])) selected="selected" @endif>{{ $value }}</option>
                @endforeach
              </select>
            </label>
          </div>
          <div class="flex justify-center">
            <a href="{{ route('lista', ['month' => $month['-']->format('m.Y')]) }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
              </svg>
            </a>
            <a class="mx-auto" href="{{ route('lista', [$month['x']->format('m.Y')]) }}">
              Platna lista za {{$month['x']->format('m.Y')}}!
            </a>
            <a href="{{ route('lista', [$month['+']->format('m.Y')]) }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
              </svg>
            </a>
          </div>
          <table class="table-fixed">
            <thead>
              <tr>
                <th class="w-1/2 text-left"><b>OBRAČUN ISPLAĆENE PLAĆE</b></th>
                <th class="w-1/2 text-right" colspan="3"><b>Obrazac IP1</b></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="border p-2">
                  <ul>
                    <li><b>I. PODACI O POSLODAVCU</b></li>
                    <li>1. Tvrtka/ Ime i prezime: ____</li>
                    <li>2. Sjedište / Adresa: ____</li>
                    <li>3. Osobni identifikacijski broj: ____</li>
                    <li>4. IBAN broj računa ____ kod ____</li>
                  </ul>
                </td>
                <td class="border p-2" colspan="3">
                  <ul>
                    <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
                    <li>
                      1. Ime i prezime: <b>{{ Auth::user()->name }}</b>
                    </li>
                    <li>2. Adresa: ____</li>
                    <li>3. Osobni identifikacijski broj: ____</li>
                    <li>4. IBAN broj računa ____ kod ____</li>
                    <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
                  </ul>
                </td>
              </tr>
              <tr>
                <td class="border p-2" colspan="4"><b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {{ $data['III.godina'] }}, MJESEC
                  {{ $data['III.mjesec'] }} DANI U MJESECU OD {{ $data['III.od'] }} DO {{ $data['III.do'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2"><b>1. OPIS PLAĆE</b></td>
                <td class="w-1/8 border p-2 text-center"><b>SATI</b></td>
                <td class="w-1/8 border p-2 text-right"><b>IZNOS</b></td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.1. Za redoviti rad</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.1.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.1.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.4 Za prekovremeni rad</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.4.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.4.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7a Praznici. Blagdani, izbori</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7a.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7a.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7d Bolovanje do 42 dana</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7d.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7d.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7e Dodatak za rad nedjeljom</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7e.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7e.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7f Dodatak za rad na praznik</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7f.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7f.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS PO TOJ OSNOVI (SATI PRIPRAVNOSTI)</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"></td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['3.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">3.1. Prijevoz</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['3.1.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['4.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2"><b>5. OSNOVICA ZA OBRAČUN DOPRINOSA</b></td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"><b>{{ $data['5.kn'] }}<b></td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">6. VRSTE I IZNOSI DOPRINOSA ZA OBVEZNA OSIGURANJA KOJA SE OBUSTAVLJAJU IZ PLAĆ</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"></td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['6.1.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['6.2.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2"><b>7. DOHODAK</b></td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"><b>{{ $data['7.kn'] }}</b></td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">8. OSOBNI ODBITAK 1.00 / {{ number_format($data['odbitak'], 2, '.', '') }}</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['8.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">9. POREZNA OSNOVICA</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['9.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['10.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">20.00% {{ $data['9.kn'] }}</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['10.20.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-12" colspan="2">Prirez {{ number_format($data['prirez'], 2, '.', ',') }} %</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['10.prirez.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2"><b>11. NETO PLAĆA</b></td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"><b>{{ $data['11.kn'] }}</b></td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">12. NAKNADE UKUPNO</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['12.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2"><b>13. NETO + NAKNADE</b></td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"><b>{{ $data['13.kn'] }}</b></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
```
git add .
git commit -am "platna lista v0.2 [laravel]"
```