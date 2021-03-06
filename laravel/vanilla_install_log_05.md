## Platna lista

```
php artisan serve
npm run hot
php artisan make:migration add_go_to_days_table --table=days
```
### database\migrations\2021_03_03_090909_add_go_to_days_table.php
```
  public function up()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->boolean('go')
        ->after('sick')
        ->default(false);
    });
  }
  public function down()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->dropColumn('go');
    });
  }
```
```
php artisan migrate
```
### resources\views\days\index.blade.php
```
                  <div class="w-full rounded-md relative {{$day->sick ? 'bg-red' : ($day->go ? 'bg-green' : 'bg-indigo')}}-{{$day->date->format('D') == 'Sun' ? '300' : '100'}}" style="min-height: 18px;" title={{$day->date->format('d.m.Y')}}>

                  <div class="w-full rounded-md relative bg-yellow-{{$day->date->format('D') == 'Sun' ? '300' : '100'}}" style="min-height: 18px;" title={{$day->date->format('d.m.Y')}}>
```
### resources\views\days\show.blade.php
```
          <p>{{ $day[0]->go ? 'bio sam' : 'nisam bio' }} na godišnjem</p>
```
### resources\views\days\create.blade.php
```
            <!-- GO -->
            <div class="mt-4">
              <x-label for="go" :value="__('Godišnji')" />
              <input id="go" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="go" />
              <p>Da li ste taj dan bili na godišnjem? Ako je godišnji onda bi ostale vrijednosti trebale biti 00:00</p>
              <p>Kako nisam još bio na GO ne znam kak se računa ali pretpostavljam da kao i za bolovanje, ali ako netko zna točno slobodno javi na <a href="mailto:zdravek.sprite@gmail.com">mail zdravek.sprite@gmail.com</a></p>
            </div>
```
### resources\views\days\edit.blade.php
```
            <!-- GO -->
            <div class="mt-4">
              <x-label for="go" :value="__('Godišnji')" />
              <input id="go" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="go" {{$day->go ? 'checked' : ''}} />
            </div>
```
### app\Http\Controllers\DayController.php
```
  public function create(Request $request)
  {
    $day = new Day;
    if (null != $request->input('date')) {
      $day->date = $request->input('date');
      if ($request->input('sick') == true) $day->sick = true;
      if ($request->input('go') == true) $day->go = true;
      if ($request->input('start') != null) $day->start = $request->input('start');
    }
    return view('days.create')->with(compact('day'));
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'date' => 'required'
    ]);
    $old_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($request->input('date'))))->get();
    $day = new Day;
    $day->date = $request->input('date');
    $day->user_id = Auth::user()->id;
    if (null != $request->input('sick')) $day->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day->go = $request->input('go') == 'on' ? true : false;
    if (null != $request->input('night_duration')) $day->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day->night_duration;
    $day->start = $request->input('start');
    $day->duration = $request->input('duration');
    if (count($old_day) > 0) return view('days.edit')->with(compact('old_day', 'day'));
    $day->save();
    return redirect(route('month').'/'.$day->date->format('m.Y'))->with('success', 'Day Updated');
  }

  public function update(Request $request, $date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (null != $request->input('sick')) $day[0]->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day[0]->go = $request->input('go') == 'on' ? true : false;
    $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
    $day[0]->start = $request->input('start');
    $day[0]->duration = $request->input('duration');
    $day[0]->save();
    return redirect(route('days.show', ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated');
  }
```
### app\Http\Controllers\PlatnaLista.php
```
    $hoursNormGO = 0;
    $daysGO = 0;

      if ($daysColection->where('date', '=', $from->addDays($i))->where('go', '=', true)->first() != null) {
        $hoursNormGO += $def_h;
        if ($def_h > 0 ) $daysGO++;
      }

    // 1.1. Za redoviti rad
    $hoursWorkNorm = $hoursNorm - $hoursNormHoli - $hoursNormSick - $hoursNormGO;
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

    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn5 = ($h1_1 + $h1_4 * 1.5 + $hoursNormHoli + $hoursNormSick * 0.7588 + $hoursNormGO + $minWorkSunday / 60 * 0.35 + $minWorkHoli / 60 * 0.5) * $perHour;
```
### resources\views\platna-lista.blade.php
```
              @if($data['1.go.h'] > 0)
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">GO (pretpostavljam da se ovak računa)</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.go.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.go.kn'] }}</td>
              </tr>
              @endif
```
```
git add .
git commit -am "add GO [laravel]"
php artisan make:migration add_zaposlen_to_users_table --table=users
```
### database\migrations\2021_03_05_065636_add_zaposlen_to_users_table.php
```
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->date('zaposlen')
        ->after('password')
        ->nullable();
    });
  }
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('zaposlen');
    });
  }
```
```
php artisan migrate
```
### resources\views\dashboard.blade.php
```
            <!-- zaposlen -->
            <div class="mt-4">
              <x-label for="zaposlen" :value="__('Zaposlen od')" />
              <input id="zaposlen" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="zaposlen" value="{{Auth::user()->zaposlen ? Auth::user()->zaposlen : old('zaposlen')}}" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Da bi se mogao točno izračunati prvi mjesec rada ako se nije zaposlilo prvog u mjesecu.
              </div>
            </div>
```
### app\Http\Controllers\PlatnaLista.php
```
    if (null != $request->input('zaposlen')) $user->zaposlen = $request->input('zaposlen');

    $from = CarbonImmutable::parse($month['x'])->firstOfMonth();
    $firstFrom = Auth::user()->zaposlen > $from ? Carbon::parse(Auth::user()->zaposlen) : $from;

    $hoursNorm = 0;
    $firstHoursNorm = 0;
    $hoursNormHoli = 0;
    $firstHoursNormHoli = 0;

      $hoursNorm += $def_h;
      $firstHoursNorm += $firstFrom > $from->addDays($i) ? 0 : $def_h;
      if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $hoursNormHoli += $def_h;
        $firstHoursNormHoli += $firstFrom > $from->addDays($i) ? 0 : $def_h;
      }

    $data['III.od'] = $from > $firstFrom ? $from->format('d') : $firstFrom->format('d');

    // 1.1. Za redoviti rad
    $hoursWorkNorm = ($from > $firstFrom ? $hoursNorm - $hoursNormHoli : $firstHoursNorm - $firstHoursNormHoli) - $hoursNormSick - $hoursNormGO;

    // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
    $prijevoz = $from > $firstFrom ? $prijevoz : $prijevoz * $firstHoursNorm / $hoursNorm;

```
```
git add .
git commit -am "dodan datum zaposljavanja [laravel]"
```
### resources\views\layouts\app.blade.php
```
      @if (session('status'))
      <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" /></svg>
        <p>{{ session('status') }}</p>
      </div>
      @endif

      @if (session('success'))
      <div class="bg-indigo-900 text-center py-4 lg:px-4">
        <div class="p-2 bg-indigo-800 items-center text-indigo-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert">
          <span class="flex rounded-full bg-indigo-500 uppercase px-2 py-1 text-xs font-bold mr-3">Success</span>
          <span class="font-semibold mr-2 text-left flex-auto">{{ session('success') }}</span>
          <svg class="fill-current opacity-75 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z" /></svg>
        </div>
      </div>
      @endif

      @if (session('warning'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Holy smokes!</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
          <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <title>Close</title>
            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
          </svg>
        </span>
      </div>
      @endif
```
```
git commit -am "dodani alerti [laravel]"
```
